<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ReportController;

use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Authentication Routes
Auth::routes();

// API Routes for AJAX
Route::get('/api/buyers/search', function(Illuminate\Http\Request $request) {
    $query = $request->get('q', '');
    $buyers = App\Models\Buyer::where('name', 'like', "%{$query}%")
        ->orderBy('name')
        ->limit(10)
        ->get(['id', 'name', 'phone', 'address']);
    return response()->json($buyers);
})->middleware('auth');

// Protected Routes (Require Authentication)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Employees
    Route::prefix('employees')->name('employees.')->group(function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('index');
        Route::get('/create', [EmployeeController::class, 'create'])->name('create');
        Route::post('/', [EmployeeController::class, 'store'])->name('store');
        Route::get('/{employee}', [EmployeeController::class, 'show'])->name('show');
        Route::get('/{employee}/edit', [EmployeeController::class, 'edit'])->name('edit');
        Route::put('/{employee}', [EmployeeController::class, 'update'])->name('update');
        Route::delete('/{employee}', [EmployeeController::class, 'destroy'])->name('destroy');
    });

    // Items (Produk Kue)
    Route::prefix('items')->name('items.')->group(function () {
        Route::get('/', [ItemController::class, 'index'])->name('index');
        Route::get('/create', [ItemController::class, 'create'])->name('create');
        Route::post('/', [ItemController::class, 'store'])->name('store');
        Route::get('/{item}', [ItemController::class, 'show'])->name('show');
        Route::get('/{item}/edit', [ItemController::class, 'edit'])->name('edit');
        Route::put('/{item}', [ItemController::class, 'update'])->name('update');
        Route::delete('/{item}', [ItemController::class, 'destroy'])->name('destroy');
    });

    // Vendors (Supplier)
    Route::prefix('vendors')->name('vendors.')->group(function () {
        Route::get('/', [VendorController::class, 'index'])->name('index');
        Route::get('/create', [VendorController::class, 'create'])->name('create');
        Route::post('/', [VendorController::class, 'store'])->name('store');
        Route::get('/{vendor}', [VendorController::class, 'show'])->name('show');
        Route::get('/{vendor}/edit', [VendorController::class, 'edit'])->name('edit');
        Route::put('/{vendor}', [VendorController::class, 'update'])->name('update');
        Route::delete('/{vendor}', [VendorController::class, 'destroy'])->name('destroy');
    });

    // Productions
    Route::prefix('productions')->name('productions.')->group(function () {
        Route::get('/', [ProductionController::class, 'index'])->name('index');
        Route::get('/worker-summary', [ProductionController::class, 'workerSummary'])->name('worker_summary');
        Route::get('/create', [ProductionController::class, 'create'])->name('create');
        Route::post('/', [ProductionController::class, 'store'])->name('store');
        Route::get('/{production}', [ProductionController::class, 'show'])->name('show');
        Route::delete('/{production}', [ProductionController::class, 'destroy'])->name('destroy');
    });

    // Payrolls (Penggajian)
    Route::prefix('payrolls')->name('payrolls.')->group(function () {
        Route::get('/', [PayrollController::class, 'index'])->name('index');
        Route::get('/create', [PayrollController::class, 'create'])->name('create');
        Route::post('/', [PayrollController::class, 'store'])->name('store');
        Route::get('/get-unpaid-work/{employeeId}', [PayrollController::class, 'getUnpaidWork'])->name('get_unpaid_work');
        Route::get('/{id}/preview', [PayrollController::class, 'preview'])->name('preview');
        Route::get('/{payroll}', [PayrollController::class, 'show'])->name('show');
        Route::get('/{payroll}/print', [PayrollController::class, 'print'])->name('print');
    });

    // Purchases
    Route::prefix('purchases')->name('purchases.')->group(function () {
        Route::get('/', [PurchaseController::class, 'index'])->name('index');
        Route::get('/export', [PurchaseController::class, 'export'])->name('export');
        Route::post('/bulk-action', [PurchaseController::class, 'bulkAction'])->name('bulk_action');
        Route::get('/create', [PurchaseController::class, 'create'])->name('create');
        Route::post('/', [PurchaseController::class, 'store'])->name('store');
        Route::get('/{purchase}', [PurchaseController::class, 'show'])->name('show');
        Route::get('/{purchase}/edit', [PurchaseController::class, 'edit'])->name('edit');
        Route::put('/{purchase}', [PurchaseController::class, 'update'])->name('update');
        Route::delete('/{purchase}', [PurchaseController::class, 'destroy'])->name('destroy');
        Route::get('/{purchase}/print', [PurchaseController::class, 'print'])->name('print');
    });

    // Sales
    Route::prefix('sales')->name('sales.')->group(function () {
        Route::get('/', [SaleController::class, 'index'])->name('index');
        Route::get('/export', [SaleController::class, 'export'])->name('export');
        Route::post('/bulk-action', [SaleController::class, 'bulkAction'])->name('bulk_action');
        Route::get('/create', [SaleController::class, 'create'])->name('create');
        Route::post('/', [SaleController::class, 'store'])->name('store');
        Route::get('/{sale}', [SaleController::class, 'show'])->name('show');
        Route::get('/{sale}/edit', [SaleController::class, 'edit'])->name('edit');
        Route::put('/{sale}', [SaleController::class, 'update'])->name('update');
        Route::delete('/{sale}', [SaleController::class, 'destroy'])->name('destroy');
        Route::get('/{sale}/print', [SaleController::class, 'print'])->name('print');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/production', [ReportController::class, 'production'])->name('production');
        Route::get('/production/export', [ReportController::class, 'exportProduction'])->name('production.export');
        Route::get('/payroll', [ReportController::class, 'payroll'])->name('payroll');
        Route::get('/payroll/export', [ReportController::class, 'exportPayroll'])->name('payroll.export');
        Route::get('/purchase', [ReportController::class, 'purchase'])->name('purchase');
        Route::get('/purchase/export', [ReportController::class, 'exportPurchase'])->name('purchase.export');
        Route::get('/export', [ReportController::class, 'export'])->name('export');
    });

    // Additional utility routes
    Route::get('/profile', function () {
        return view('auth.profile');
    })->name('profile');

    Route::get('/settings', function () {
        return view('auth.settings');
    })->name('settings');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
