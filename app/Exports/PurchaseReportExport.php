<?php

namespace App\Exports;

use App\Models\Purchase;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;

class PurchaseReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Purchase::with('vendor');

        if ($this->request->has('start_date') && $this->request->has('end_date')) {
            $query->whereBetween('date', [
                $this->request->start_date,
                $this->request->end_date
            ]);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Nomor Nota',
            'Tanggal',
            'Supplier',
            'Total (Rp)',
            'Status',
            'Catatan',
        ];
    }

    public function map($purchase): array
    {
        return [
            $purchase->invoice_number,
            $purchase->date,
            $purchase->vendor->name ?? 'Unknown',
            $purchase->grand_total,
            $purchase->status,
            $purchase->notes,
        ];
    }
}
