<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Production;
use App\Models\Purchase;
use App\Models\Employee;
use App\Models\ProductionWorker;
use Illuminate\Support\Facades\DB;

class ProductionController extends Controller
{
    /**
     * Display a listing of productions.
     */
    public function index(Request $request)
    {
        $query = Production::with(['purchases', 'productionWorkers.employee']);

        // Date filter
        if ($request->filled('date_from')) {
            $query->where('production_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('production_date', '<=', $request->date_to);
        }

        $productions = $query->orderBy('production_date', 'desc')->paginate(10);

        return view('productions.index', compact('productions'));
    }

    /**
     * Display a summary of productions per worker.
     */
    public function workerSummary(Request $request)
    {
        $query = ProductionWorker::with(['employee', 'production']);

        // Date filter
        if ($request->filled('date_from')) {
            $query->whereHas('production', function ($q) use ($request) {
                $q->where('production_date', '>=', $request->date_from);
            });
        }
        if ($request->filled('date_to')) {
            $query->whereHas('production', function ($q) use ($request) {
                $q->where('production_date', '<=', $request->date_to);
            });
        }

        // Employee filter
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        $workerProductions = $query->join('productions', 'production_workers.production_id', '=', 'productions.id')
            ->select('production_workers.*')
            ->orderBy('productions.production_date', 'desc')
            ->paginate(15);

        $employees = Employee::orderBy('name')->get();

        return view('productions.worker_summary', compact('workerProductions', 'employees'));
    }

    /**
     * Show the form for creating a new production.
     */
    public function create()
    {
        // Get unpurchased purchases (is_produced = false)
        $purchases = Purchase::with(['vendor', 'items.item'])
            ->where('is_produced', false)
            ->orderBy('date', 'desc')
            ->get();

        // Get active employees
        $employees = Employee::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('productions.create', compact('purchases', 'employees'));
    }

    /**
     * Store a newly created production in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'production_date' => 'required|date',
            'purchase_ids' => 'required|array|min:1',
            'purchase_ids.*' => 'exists:purchases,id',
            'employee_ids' => 'required|array|min:1',
            'employee_ids.*' => 'exists:employees,id',
            'workloads' => 'required|array',
            'workloads.*' => 'numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Calculate total_kue from selected purchases
            $selectedPurchases = Purchase::with('items')->whereIn('id', $request->purchase_ids)->get();
            $total_kue = 0;
            $grand_total = 0;

            foreach ($selectedPurchases as $purchase) {
                foreach ($purchase->items as $item) {
                    $total_kue += $item->qty;
                }
                $grand_total += $purchase->grand_total;
            }

            // Create production
            $production = Production::create([
                'production_date' => $request->production_date,
                'total_kue' => $total_kue,
                'grand_total' => $grand_total,
                'notes' => $request->notes,
            ]);

            // Attach purchases to production
            $production->purchases()->attach($request->purchase_ids);

            // Mark purchases as produced
            Purchase::whereIn('id', $request->purchase_ids)->update(['is_produced' => true]);

            // Create production workers with workload
            foreach ($request->employee_ids as $employee_id) {
                ProductionWorker::create([
                    'production_id' => $production->id,
                    'employee_id' => $employee_id,
                    'is_present' => true,
                    'workload' => $request->workloads[$employee_id] ?? 0,
                ]);
            }

            DB::commit();

            return redirect()->route('productions.index')->with('success', 'Produksi berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified production.
     */
    public function show(Production $production)
    {
        $production->load(['purchases.vendor', 'purchases.items.item', 'productionWorkers.employee']);

        return view('productions.show', compact('production'));
    }

    /**
     * Remove the specified production from storage.
     */
    public function destroy(Production $production)
    {
        DB::beginTransaction();
        try {
            // Get purchase IDs before deleting
            $purchaseIds = $production->purchases->pluck('id')->toArray();

            // Mark purchases as not produced
            Purchase::whereIn('id', $purchaseIds)->update(['is_produced' => false]);

            // Delete production (cascade will delete production_workers and production_purchases)
            $production->delete();

            DB::commit();

            return redirect()->route('productions.index')->with('success', 'Produksi berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}