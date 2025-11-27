<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Vendor;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\PurchasesExport;
use Maatwebsite\Excel\Facades\Excel;

class PurchaseController extends Controller
{
    /**
     * Display a listing of purchases.
     */
    public function index(Request $request)
    {
        $query = Purchase::with('vendor');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('vendor', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
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

        $purchases = $query->orderBy('date', 'desc')->paginate(10);

        return view('purchases.index', compact('purchases'));
    }

    /**
     * Show the form for creating a new purchase.
     */
    public function create()
    {
        $vendors = Vendor::orderBy('name')->get();
        $items = Item::orderBy('name')->get();
        
        // Generate Invoice Number
        $lastPurchase = Purchase::latest()->first();
        $nextId = $lastPurchase ? $lastPurchase->id + 1 : 1;
        $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        return view('purchases.create', compact('vendors', 'items', 'invoiceNumber'));
    }

    /**
     * Store a newly created purchase in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Calculate Grand Total
            $grandTotal = 0;
            foreach ($request->items as $item) {
                $grandTotal += $item['qty'] * $item['price'];
            }

            // Create Purchase
            $purchase = Purchase::create([
                'invoice_number' => $request->invoice_number, // Assuming generated in frontend or controller
                'vendor_id' => $request->vendor_id,
                'date' => $request->date,
                'grand_total' => $grandTotal,
            ]);

            // Create Purchase Items
            foreach ($request->items as $itemData) {
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'item_id' => $itemData['item_id'],
                    'qty' => $itemData['qty'],
                    'price' => $itemData['price'],
                    'total' => $itemData['qty'] * $itemData['price'],
                ]);
            }

            DB::commit();

            return redirect()->route('purchases.index')->with('success', 'Pembelian berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified purchase.
     */
    public function show(Purchase $purchase)
    {
        $purchase->load(['vendor', 'items.item']);
        return view('purchases.show', compact('purchase'));
    }

    /**
     * Remove the specified purchase from storage.
     */
    public function destroy(Purchase $purchase)
    {
        $purchase->delete();
        return redirect()->route('purchases.index')->with('success', 'Pembelian berhasil dihapus.');
    }

    /**
     * Handle bulk actions.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:purchases,id',
            'action' => 'required|string|in:download_nota',
        ]);

        if ($request->action === 'download_nota') {
            $purchases = Purchase::with(['vendor', 'items.item'])
                ->whereIn('id', $request->ids)
                ->orderBy('date', 'desc')
                ->get();
                
            return view('purchases.print', compact('purchases'));
        }

        return back()->with('error', 'Aksi tidak valid.');
    }

    /**
     * Print the specified purchase.
     */
    public function print(Purchase $purchase)
    {
        $purchase->load(['vendor', 'items.item']);
        $purchases = collect([$purchase]);
        return view('purchases.print', compact('purchases'));
    }

    /**
     * Export purchases to Excel.
     */
    public function export(Request $request)
    {
        return Excel::download(new PurchasesExport($request), 'laporan-pembelian-' . date('Ymd-His') . '.xlsx');
    }
}