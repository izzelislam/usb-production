<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Item;
use App\Models\Buyer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\SalesExport;
use Maatwebsite\Excel\Facades\Excel;

class SaleController extends Controller
{
    /**
     * Display a listing of sales.
     */
    public function index(Request $request)
    {
        $query = Sale::with('buyer');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('invoice_number', 'like', "%{$search}%");
        }

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        if ($request->filled('month') && !$request->filled('start_date') && !$request->filled('end_date')) {
            $query->where('date', 'like', $request->month . '%');
        }

        $sales = $query->orderBy('date', 'desc')->paginate(10);

        return view('sales.index', compact('sales'));
    }

    /**
     * Show the form for creating a new sale.
     */
    public function create()
    {
        $items = Item::orderBy('name')->get();
        
        // Generate Invoice Number
        $lastSale = Sale::latest()->first();
        $nextId = $lastSale ? $lastSale->id + 1 : 1;
        $invoiceNumber = 'INV-SALE-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        return view('sales.create', compact('items', 'invoiceNumber'));
    }

    /**
     * Store a newly created sale in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'buyer_name' => 'required|string|max:255',
            'buyer_address' => 'nullable|string',
            'buyer_phone' => 'nullable|string|max:20',
            'date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Find or create buyer
            $buyer = Buyer::firstOrCreate(
                ['name' => $request->buyer_name],
                [
                    'address' => $request->buyer_address,
                    'phone' => $request->buyer_phone,
                ]
            );

            // Calculate Grand Total
            $grandTotal = 0;
            foreach ($request->items as $item) {
                $grandTotal += $item['qty'] * $item['price'];
            }

            // Create Sale
            $sale = Sale::create([
                'invoice_number' => $request->invoice_number,
                'buyer_id' => $buyer->id,
                'date' => $request->date,
                'grand_total' => $grandTotal,
            ]);

            // Create Sale Items
            foreach ($request->items as $itemData) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'item_id' => $itemData['item_id'],
                    'qty' => $itemData['qty'],
                    'price' => $itemData['price'],
                    'total' => $itemData['qty'] * $itemData['price'],
                ]);
            }

            DB::commit();

            return redirect()->route('sales.index')->with('success', 'Penjualan berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified sale.
     */
    public function show(Sale $sale)
    {
        $sale->load(['buyer', 'items.item']);
        return view('sales.show', compact('sale'));
    }

    /**
     * Remove the specified sale from storage.
     */
    public function destroy(Sale $sale)
    {
        $sale->delete();
        return redirect()->route('sales.index')->with('success', 'Penjualan berhasil dihapus.');
    }

    /**
     * Handle bulk actions.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:sales,id',
            'action' => 'required|string|in:download_nota',
        ]);

        if ($request->action === 'download_nota') {
            $sales = Sale::with(['buyer', 'items.item'])
                ->whereIn('id', $request->ids)
                ->orderBy('date', 'desc')
                ->get();
                
            return view('sales.print', compact('sales'));
        }

        return back()->with('error', 'Aksi tidak valid.');
    }

    /**
     * Print the specified sale.
     */
    public function print(Sale $sale)
    {
        $sale->load(['buyer', 'items.item']);
        $sales = collect([$sale]);
        return view('sales.print', compact('sales'));
    }

    /**
     * Export sales to Excel.
     */
    public function export(Request $request)
    {
        return Excel::download(new SalesExport($request), 'laporan-penjualan-' . date('Ymd-His') . '.xlsx');
    }
}
