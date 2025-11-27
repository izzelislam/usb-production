<?php

namespace App\Exports;

use App\Models\Payroll;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;

class PayrollReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Payroll::with('employee');

        if ($this->request->has('start_date') && $this->request->has('end_date')) {
            $query->whereBetween('created_at', [
                $this->request->start_date,
                $this->request->end_date
            ]);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID Transaksi',
            'Nama Karyawan',
            'Tanggal Pembayaran',
            'Periode Gaji',
            'Total Gaji (Rp)',
            'Status',
        ];
    }

    public function map($payroll): array
    {
        return [
            $payroll->id,
            $payroll->employee->name ?? 'Unknown',
            $payroll->created_at->format('Y-m-d'),
            $payroll->month . ' ' . $payroll->year,
            $payroll->final_salary,
            $payroll->status,
        ];
    }
}
