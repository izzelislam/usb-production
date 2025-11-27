<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Illuminate\Http\Request;

class SalesExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Sale::query()->with(['items.item']);

        // Apply filters from request
        if ($this->request->filled('search')) {
            $search = $this->request->search;
            $query->where('invoice_number', 'like', "%{$search}%");
        }

        if ($this->request->filled('start_date')) {
            $query->whereDate('date', '>=', $this->request->start_date);
        }

        if ($this->request->filled('end_date')) {
            $query->whereDate('date', '<=', $this->request->end_date);
        }

        if ($this->request->filled('month') && !$this->request->filled('start_date') && !$this->request->filled('end_date')) {
            $query->where('date', 'like', $this->request->month . '%');
        }

        $sales = $query->orderBy('date', 'desc')->get();

        $data = collect();
        
        foreach ($sales as $sale) {
            foreach ($sale->items as $index => $item) {
                $row = collect();
                
                // Kode Nota and Tanggal only on first row
                if ($index === 0) {
                    $row->push($sale->invoice_number);
                    $row->push(\Carbon\Carbon::parse($sale->date)->format('d/m/Y H:i'));
                } else {
                    $row->push('');
                    $row->push('');
                }
                
                // Jumlah Barang columns
                $row->push($item->qty); // at
                $row->push($item->qty); // TRN
                $row->push($item->qty); // usb
                
                // Harga columns
                $row->push($item->price); // at
                $row->push($item->price); // TRN
                $row->push($item->price); // usb
                
                // Sub Total
                $row->push($item->total);
                
                $data->push($row);
            }
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            ['Kode Nota', 'Tanggal', 'Jumlah Barang', '', '', 'Harga', '', '', 'Sub Total'],
            ['', '', 'at', 'TRN', 'usb', 'at', 'TRN', 'usb', ''],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            2 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Merge headers
                $sheet->mergeCells('A1:A2'); // Kode Nota
                $sheet->mergeCells('B1:B2'); // Tanggal
                $sheet->mergeCells('C1:E1'); // Jumlah Barang
                $sheet->mergeCells('F1:H1'); // Harga
                $sheet->mergeCells('I1:I2'); // Sub Total
                
                // Center align headers
                $sheet->getStyle('A1:I2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A1:I2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                
                // Add borders to headers
                $sheet->getStyle('A1:I2')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);
                
                $highestRow = $sheet->getHighestRow();
                
                // Merge cells for Kode Nota and Tanggal when they are the same
                $currentRow = 3;
                while ($currentRow <= $highestRow) {
                    $kodeNota = $sheet->getCell('A' . $currentRow)->getValue();
                    
                    if (!empty($kodeNota)) {
                        $startRow = $currentRow;
                        $nextRow = $currentRow + 1;
                        
                        // Find all rows with the same sale (empty Kode Nota)
                        while ($nextRow <= $highestRow && empty($sheet->getCell('A' . $nextRow)->getValue())) {
                            $nextRow++;
                        }
                        
                        $endRow = $nextRow - 1;
                        
                        // Merge Kode Nota and Tanggal if there are multiple items
                        if ($endRow > $startRow) {
                            $sheet->mergeCells('A' . $startRow . ':A' . $endRow);
                            $sheet->mergeCells('B' . $startRow . ':B' . $endRow);
                            $sheet->getStyle('A' . $startRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                            $sheet->getStyle('B' . $startRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                        }
                        
                        $currentRow = $nextRow;
                    } else {
                        $currentRow++;
                    }
                }
                
                // Add borders to data
                if ($highestRow > 2) {
                    $sheet->getStyle('A3:I' . $highestRow)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                    ]);
                }
                
                // Add Total row
                $totalRow = $highestRow + 1;
                $sheet->setCellValue('H' . $totalRow, 'Total:');
                $sheet->getStyle('H' . $totalRow)->getFont()->setBold(true);
                $sheet->getStyle('H' . $totalRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                
                if ($highestRow > 2) {
                    $sheet->setCellValue('I' . $totalRow, '=SUM(I3:I' . $highestRow . ')');
                } else {
                    $sheet->setCellValue('I' . $totalRow, 0);
                }
                $sheet->getStyle('I' . $totalRow)->getFont()->setBold(true);
                
                // Auto-size columns
                foreach (range('A', 'I') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
