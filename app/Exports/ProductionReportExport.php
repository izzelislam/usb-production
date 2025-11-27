<?php

namespace App\Exports;

use App\Models\Production;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;

class ProductionReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Production::query();

        if ($this->request->has('start_date') && $this->request->has('end_date')) {
            $query->whereBetween('production_date', [
                $this->request->start_date,
                $this->request->end_date
            ]);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal Produksi',
            'Total Kue (Pcs)',
            'Total Biaya (Rp)',
            'Catatan',
            'Dibuat Pada',
        ];
    }

    public function map($production): array
    {
        return [
            $production->production_date,
            $production->total_kue,
            $production->grand_total,
            $production->notes,
            $production->created_at->format('d/m/Y H:i'),
        ];
    }
}
