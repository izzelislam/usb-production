<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the dashboard page.
     */
    public function index(Request $request)
    {
        $today = now()->format('Y-m-d');
        $startOfMonth = now()->startOfMonth()->format('Y-m-d');
        $endOfMonth = now()->endOfMonth()->format('Y-m-d');

        // Handle AJAX request for chart filtering
        if ($request->ajax()) {
            $startDate = $request->input('start_date', now()->subDays(6)->format('Y-m-d'));
            $endDate = $request->input('end_date', now()->format('Y-m-d'));
            
            $dates = collect();
            $current = \Carbon\Carbon::parse($startDate);
            $end = \Carbon\Carbon::parse($endDate);

            while ($current <= $end) {
                $dates->push($current->format('Y-m-d'));
                $current->addDay();
            }

            $chartData = [
                'dates' => $dates->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))->toArray(),
                'sales' => [],
                'purchases' => [],
                'production' => []
            ];

            foreach ($dates as $date) {
                $chartData['sales'][] = \App\Models\Sale::whereDate('date', $date)->sum('grand_total');
                $chartData['purchases'][] = \App\Models\Purchase::whereDate('date', $date)->sum('grand_total');
                $chartData['production'][] = \App\Models\Production::whereDate('production_date', $date)->sum('total_kue');
            }

            return response()->json($chartData);
        }

        // 1. Stats Cards
        $totalProductionToday = \App\Models\Production::whereDate('production_date', $today)->sum('total_kue');
        
        // Calculate percentage change for production (vs yesterday)
        $yesterdayProduction = \App\Models\Production::whereDate('production_date', now()->subDay()->format('Y-m-d'))->sum('total_kue');
        $productionPercentage = $yesterdayProduction > 0 ? (($totalProductionToday - $yesterdayProduction) / $yesterdayProduction) * 100 : 0;

        // Employees Present Today
        $employeesPresent = \App\Models\ProductionWorker::whereHas('production', function($q) use ($today) {
            $q->whereDate('production_date', $today);
        })->distinct('employee_id')->count();
        $totalEmployees = \App\Models\Employee::count();

        // Monthly Sales
        $monthlySales = \App\Models\Sale::whereBetween('date', [$startOfMonth, $endOfMonth])->sum('grand_total');
        
        // Monthly Purchases
        $monthlyPurchases = \App\Models\Purchase::whereBetween('date', [$startOfMonth, $endOfMonth])->sum('grand_total');

        $stats = [
            'total_production_today' => $totalProductionToday,
            'production_percentage' => $productionPercentage,
            'employees_present' => $employeesPresent . ' / ' . $totalEmployees,
            'attendance_percentage' => $totalEmployees > 0 ? ($employeesPresent / $totalEmployees) * 100 : 0,
            'monthly_sales' => $monthlySales,
            'monthly_purchases' => $monthlyPurchases,
        ];

        // 2. Chart Data (Last 7 Days)
        $dates = collect();
        for ($i = 6; $i >= 0; $i--) {
            $dates->push(now()->subDays($i)->format('Y-m-d'));
        }

        $chartData = [
            'dates' => $dates->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))->toArray(),
            'sales' => [],
            'purchases' => [],
            'production' => []
        ];

        foreach ($dates as $date) {
            $chartData['sales'][] = \App\Models\Sale::whereDate('date', $date)->sum('grand_total');
            $chartData['purchases'][] = \App\Models\Purchase::whereDate('date', $date)->sum('grand_total');
            $chartData['production'][] = \App\Models\Production::whereDate('production_date', $date)->sum('total_kue');
        }

        // 3. Recent Activities
        $activities = collect();

        // Recent Sales
        $recentSales = \App\Models\Sale::with('buyer')->latest('date')->take(3)->get()->map(function($item) {
            return [
                'type' => 'sale',
                'title' => 'Penjualan Baru',
                'description' => 'Invoice #' . $item->invoice_number . ' - ' . ($item->buyer->name ?? 'Umum'),
                'time' => $item->created_at,
                'icon' => 'ph-shopping-cart',
                'color' => 'green'
            ];
        });

        // Recent Productions
        $recentProductions = \App\Models\Production::latest('production_date')->take(3)->get()->map(function($item) {
            return [
                'type' => 'production',
                'title' => 'Produksi Selesai',
                'description' => $item->total_kue . ' pcs Kue',
                'time' => $item->created_at,
                'icon' => 'ph-cake',
                'color' => 'blue'
            ];
        });

        // Recent Purchases
        $recentPurchases = \App\Models\Purchase::with('vendor')->latest('date')->take(3)->get()->map(function($item) {
            return [
                'type' => 'purchase',
                'title' => 'Pembelian Bahan',
                'description' => 'Dari ' . ($item->vendor->name ?? 'Supplier'),
                'time' => $item->created_at,
                'icon' => 'ph-bag',
                'color' => 'orange'
            ];
        });

        $recentActivities = $activities->concat($recentSales)
            ->concat($recentProductions)
            ->concat($recentPurchases)
            ->sortByDesc('time')
            ->take(5)
            ->values();

        return view('dashboard.index', compact('stats', 'chartData', 'recentActivities'));
    }
}