<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Production;
use App\Models\Payroll;
use App\Models\Purchase;
use App\Exports\ProductionReportExport;
use App\Exports\PayrollReportExport;
use App\Exports\PurchaseReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display production report.
     */
    public function production(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Monthly Data (Chart/Table)
        $monthlyData = Production::whereBetween('production_date', [$startDate, $endDate])
            ->selectRaw('DATE_FORMAT(production_date, "%Y-%m") as month, SUM(total_kue) as total_produced, COUNT(*) as total_items')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => Carbon::createFromFormat('Y-m', $item->month)->translatedFormat('F Y'),
                    'total_produced' => $item->total_produced,
                    'total_items' => $item->total_items,
                    'active_workers' => '-' // Placeholder
                ];
            });

        // Item Stats
        // Note: This assumes we have a way to link production to items. 
        // If not directly available, we might need to adjust. 
        // For now, we'll keep it simple or use mock if relationship is complex.
        // Let's use a simple count for now.
        $itemStats = []; 

        return view('reports.production', compact('monthlyData', 'itemStats', 'startDate', 'endDate'));
    }

    public function exportProduction(Request $request)
    {
        return Excel::download(new ProductionReportExport($request), 'laporan-produksi-' . date('Ymd-His') . '.xlsx');
    }

    /**
     * Display payroll report.
     */
    public function payroll(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $monthlyPayroll = Payroll::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(final_salary) as total_salary, COUNT(DISTINCT employee_id) as total_employees, AVG(final_salary) as average_salary')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => Carbon::createFromFormat('Y-m', $item->month)->translatedFormat('F Y'),
                    'total_salary' => $item->total_salary,
                    'total_employees' => $item->total_employees,
                    'average_salary' => $item->average_salary
                ];
            });

        $departmentBreakdown = []; // Placeholder

        return view('reports.payroll', compact('monthlyPayroll', 'departmentBreakdown', 'startDate', 'endDate'));
    }

    public function exportPayroll(Request $request)
    {
        return Excel::download(new PayrollReportExport($request), 'laporan-gaji-' . date('Ymd-His') . '.xlsx');
    }

    /**
     * Display purchase report.
     */
    public function purchase(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $monthlyPurchases = Purchase::whereBetween('date', [$startDate, $endDate])
            ->selectRaw('DATE_FORMAT(date, "%Y-%m") as month, SUM(grand_total) as total_amount, COUNT(*) as total_transactions')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => Carbon::createFromFormat('Y-m', $item->month)->translatedFormat('F Y'),
                    'total_amount' => $item->total_amount,
                    'total_transactions' => $item->total_transactions,
                    'top_vendor' => '-'
                ];
            });

        $categoryBreakdown = [];
        $topVendors = [];

        return view('reports.purchase', compact('monthlyPurchases', 'categoryBreakdown', 'topVendors', 'startDate', 'endDate'));
    }

    public function exportPurchase(Request $request)
    {
        return Excel::download(new PurchaseReportExport($request), 'laporan-pembelian-' . date('Ymd-His') . '.xlsx');
    }

    /**
     * Display export data page.
     */
    public function export()
    {
        // Available export options
        $exportOptions = [
            [
                'name' => 'Laporan Produksi',
                'description' => 'Export data produksi harian/mingguan/bulanan',
                'formats' => ['Excel', 'PDF'],
                'icon' => 'ph-factory',
                'route' => 'reports.production'
            ],
            [
                'name' => 'Laporan Penggajian',
                'description' => 'Export data gaji dan bonus karyawan',
                'formats' => ['Excel', 'PDF'],
                'icon' => 'ph-wallet',
                'route' => 'reports.payroll'
            ],
            [
                'name' => 'Laporan Pembelian',
                'description' => 'Export data pembelian dan supplier',
                'formats' => ['Excel', 'PDF'],
                'icon' => 'ph-shopping-cart',
                'route' => 'reports.purchase'
            ],
        ];

        return view('reports.export', compact('exportOptions'));
    }
}