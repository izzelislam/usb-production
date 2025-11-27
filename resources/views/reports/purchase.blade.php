@extends('layouts.app')

@section('title', 'Laporan Pembelian')

@section('content')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Laporan Pembelian</h1>
        
        <div class="flex items-center gap-2">
            <!-- Date Filter Form -->
            <form action="{{ route('reports.purchase') }}" method="GET" id="filterForm" class="flex items-center gap-2">
                <div class="flex items-center gap-2 bg-white p-1 rounded-lg border border-gray-200 shadow-sm">
                    <div class="pl-3 text-gray-500">
                        <i class="ph ph-calendar-blank text-lg"></i>
                    </div>
                    <input type="text" name="daterange" id="reportDateRange" class="text-sm border-none focus:ring-0 text-gray-700 font-medium w-56 py-1.5" placeholder="Pilih Tanggal">
                    <input type="hidden" name="start_date" id="start_date" value="{{ $startDate }}">
                    <input type="hidden" name="end_date" id="end_date" value="{{ $endDate }}">
                </div>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors text-sm font-medium">
                    Filter
                </button>
            </form>

            <!-- Export Buttons -->
            <div class="flex items-center gap-2 ml-2 border-l pl-4 border-gray-300">
                <a href="{{ route('reports.purchase.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                    <i class="ph ph-microsoft-excel-logo text-lg"></i>
                    Excel
                </a>
                <button onclick="window.print()" class="flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                    <i class="ph ph-file-pdf text-lg"></i>
                    PDF
                </button>
            </div>
        </div>
    </div>

    <!-- Report Content -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden print:shadow-none print:border-none">
        <div class="p-6 border-b border-gray-100 print:hidden">
            <h2 class="text-lg font-semibold text-gray-800">Ringkasan Bulanan</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-500 font-semibold">
                        <th class="px-6 py-4">Bulan</th>
                        <th class="px-6 py-4 text-right">Total Pembelian (Rp)</th>
                        <th class="px-6 py-4 text-center">Jumlah Transaksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($monthlyPurchases as $data)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-800 font-medium">{{ $data['month'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 text-right">Rp {{ number_format($data['total_amount'], 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 text-center">{{ $data['total_transactions'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="ph ph-shopping-cart text-3xl mb-2 text-gray-400"></i>
                                    <p>Tidak ada data pembelian pada periode ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if(count($monthlyPurchases) > 0)
                <tfoot class="bg-gray-50 font-semibold text-gray-800">
                    <tr>
                        <td class="px-6 py-4">Total</td>
                        <td class="px-6 py-4 text-right">Rp {{ number_format($monthlyPurchases->sum('total_amount'), 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">{{ $monthlyPurchases->sum('total_transactions') }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    <script>
        $(function() {
            var start = moment('{{ $startDate }}');
            var end = moment('{{ $endDate }}');

            function cb(start, end) {
                $('#reportDateRange').val(start.format('D MMM YYYY') + ' - ' + end.format('D MMM YYYY'));
                $('#start_date').val(start.format('YYYY-MM-DD'));
                $('#end_date').val(end.format('YYYY-MM-DD'));
            }

            $('#reportDateRange').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                   'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
                   'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                   'Tahun Ini': [moment().startOf('year'), moment().endOf('year')],
                   '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()]
                },
                locale: {
                    format: 'D MMM YYYY',
                    separator: ' - ',
                    applyLabel: 'Terapkan',
                    cancelLabel: 'Batal',
                    fromLabel: 'Dari',
                    toLabel: 'Sampai',
                    customRangeLabel: 'Kustom',
                    weekLabel: 'M',
                    daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                    monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                    firstDay: 1
                }
            }, cb);

            cb(start, end);
            
            // Auto submit on apply
            $('#reportDateRange').on('apply.daterangepicker', function(ev, picker) {
                $('#filterForm').submit();
            });
        });
    </script>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .bg-white, .bg-white * {
                visibility: visible;
            }
            .bg-white {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                border: none;
                box-shadow: none;
            }
            /* Hide buttons and filters in print */
            button, form, a {
                display: none !important;
            }
            /* Show title */
            h1 {
                visibility: visible;
                position: absolute;
                top: -50px;
                left: 0;
            }
        }
    </style>
@endsection
