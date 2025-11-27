<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\ProductionWorker;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    /**
     * Display a listing of payrolls.
     */
    public function index(Request $request)
    {
        $query = Payroll::with('employee')->orderBy('created_at', 'desc');

        // Search by Employee Name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('employee', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // Filter by Date Range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by Month and Year
        if ($request->filled('month')) {
            $query->whereMonth('created_at', $request->month);
        }
        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }

        $payrolls = $query->paginate(10)->withQueryString();
        return view('payrolls.index', compact('payrolls'));
    }

    /**
     * Show the form for creating a new payroll.
     */
    public function create()
    {
        $employees = Employee::where('is_active', true)->orderBy('name')->get();
        return view('payrolls.create', compact('employees'));
    }

    /**
     * Get unpaid work for a specific employee.
     */
    public function getUnpaidWork(Request $request, $employeeId)
    {
        $employee = Employee::findOrFail($employeeId);
        
        $unpaidWorks = ProductionWorker::with('production')
            ->where('employee_id', $employeeId)
            ->where('is_paid', false)
            ->whereHas('production') // Ensure production exists
            ->get()
            ->sortByDesc('production.production_date');

        $totalWorkload = $unpaidWorks->sum('workload');
        
        // Calculate estimated salary
        $estimatedSalary = 0;
        if ($employee->salary_type === 'kg') {
            $estimatedSalary = $totalWorkload * $employee->base_salary_per_kg;
        } else {
            // If daily, we might count unique production days or just use base_salary_per_day * days present
            // For now, let's assume 1 production record = 1 day of work if daily
            $daysWorked = $unpaidWorks->count();
            $estimatedSalary = $daysWorked * $employee->base_salary_per_day;
        }

        return response()->json([
            'employee' => $employee,
            'unpaid_works' => $unpaidWorks->values(), // Reset keys for JSON
            'total_workload' => $totalWorkload,
            'estimated_salary' => $estimatedSalary,
        ]);
    }

    /**
     * Store a newly created payroll in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'total_salary' => 'required|numeric|min:0',
            'production_worker_ids' => 'required|array',
            'production_worker_ids.*' => 'exists:production_workers,id',
            'notes' => 'nullable|string',
            'bonuses' => 'nullable|array',
            'bonuses.*.description' => 'required_with:bonuses|string',
            'bonuses.*.amount' => 'required_with:bonuses|numeric|min:0',
            'deductions' => 'nullable|array',
            'deductions.*.description' => 'required_with:deductions|string',
            'deductions.*.amount' => 'required_with:deductions|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $employee = Employee::findOrFail($request->employee_id);
            
            // Calculate totals from selected works
            $selectedWorks = ProductionWorker::with('production')->whereIn('id', $request->production_worker_ids)->get();
            $totalWorkload = $selectedWorks->sum('workload');
            
            // Calculate days present (unique dates)
            $uniqueDates = $selectedWorks->pluck('production.production_date')->unique();
            $daysPresent = $uniqueDates->count();

            // 1. Create Payroll Record
            $payroll = Payroll::create([
                'employee_id' => $employee->id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'salary_type' => $employee->salary_type,
                'days_present' => $daysPresent,
                'total_workload' => $totalWorkload,
                'base_salary' => $employee->salary_type == 'per_kg' ? $employee->base_salary_per_kg : $employee->base_salary_per_day,
                'total_salary' => $request->total_salary, // This is the final amount paid
                'final_salary' => $request->total_salary, 
                'status' => 'paid', // Always paid upon creation
                'notes' => $request->notes,
            ]);

            // 2. Create Payroll Details (Link to Production Workers)
            // For daily workers, we need to be careful not to double count salary if multiple records exist for same day.
            // However, the total salary is passed from frontend which already did the unique calculation.
            // Here we just link the records. The individual 'salary_amount' for detail might be tricky for daily.
            // Let's just store 0 or pro-rated? Or maybe just the full rate on the first record of the day?
            // Simpler: Store 0 for individual details for daily, or just rate/count. 
            // Actually, for reporting, it's better if the sum of details equals total base salary.
            // So for daily: find records for each day. Distribute the daily rate among them? Or just assign to one?
            // Let's assign the daily rate to the first record of that date found, and 0 to others.
            
            $processedDates = [];

            foreach ($selectedWorks as $work) {
                $date = $work->production->production_date;
                $rate = $employee->salary_type == 'per_kg' ? $employee->base_salary_per_kg : $employee->base_salary_per_day;
                
                $amount = 0;
                if ($employee->salary_type == 'per_kg') {
                    $amount = $work->workload * $rate;
                } else {
                    // For daily, only charge for the first record of that date
                    if (!in_array($date, $processedDates)) {
                        $amount = $rate;
                        $processedDates[] = $date;
                    }
                }

                \App\Models\PayrollDetail::create([
                    'payroll_id' => $payroll->id,
                    'production_worker_id' => $work->id,
                    'workload' => $work->workload,
                    'salary_amount' => $amount,
                ]);
            }

            // 3. Create Bonuses
            if ($request->has('bonuses')) {
                foreach ($request->bonuses as $bonus) {
                    if ($bonus['amount'] > 0) {
                        \App\Models\PayrollBonus::create([
                            'payroll_id' => $payroll->id,
                            'type' => 'bonus', // Default type
                            'description' => $bonus['description'],
                            'amount' => $bonus['amount'],
                        ]);
                    }
                }
            }

            // 4. Create Deductions
            if ($request->has('deductions')) {
                foreach ($request->deductions as $deduction) {
                    if ($deduction['amount'] > 0) {
                        \App\Models\PayrollDeduction::create([
                            'payroll_id' => $payroll->id,
                            'type' => 'deduction', // Default type
                            'description' => $deduction['description'],
                            'amount' => $deduction['amount'],
                        ]);
                    }
                }
            }

            // 5. Mark production workers as paid
            ProductionWorker::whereIn('id', $request->production_worker_ids)->update(['is_paid' => true]);

            DB::commit();

            return redirect()->route('payrolls.index')->with('success', 'Penggajian berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified payroll.
     */
    /**
     * Display the specified payroll.
     */
    public function show(Payroll $payroll)
    {
        $payroll->load(['employee', 'details.productionWorker.production', 'bonuses', 'deductions']);
        return view('payrolls.show', compact('payroll'));
    }

    /**
     * Print the specified payroll slip.
     */
    public function print(Payroll $payroll)
    {
        $payroll->load(['employee', 'details.productionWorker.production', 'bonuses', 'deductions']);
        return view('payrolls.print', compact('payroll'));
    }
}