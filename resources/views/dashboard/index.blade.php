@extends('layouts.app')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard Overview')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!-- Grid Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Card 1: Production -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Produksi Hari Ini</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($stats['total_production_today'], 0, ',', '.') }} KG</h3>
                </div>
                <div class="p-2 bg-primary-50 rounded-lg text-primary-600">
                    <i class="ph ph-cake text-xl"></i>
                </div>
            </div>
            <span class="text-sm {{ $stats['production_percentage'] >= 0 ? 'text-green-500' : 'text-red-500' }} font-medium flex items-center mt-4">
                <i class="ph {{ $stats['production_percentage'] >= 0 ? 'ph-trend-up' : 'ph-trend-down' }} mr-1"></i> 
                {{ number_format(abs($stats['production_percentage']), 1) }}% dari kemarin
            </span>
        </div>

        <!-- Card 2: Attendance -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Karyawan Hadir</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['employees_present'] }}</h3>
                </div>
                <div class="p-2 bg-purple-50 rounded-lg text-purple-600">
                    <i class="ph ph-users text-xl"></i>
                </div>
            </div>
            <span class="text-sm text-green-500 font-medium flex items-center mt-4">
                <i class="ph ph-check-circle mr-1"></i> {{ number_format($stats['attendance_percentage'], 0) }}% kehadiran
            </span>
        </div>

        <!-- Card 3: Sales -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Penjualan Bulan Ini</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">Rp {{ number_format($stats['monthly_sales'] / 1000000, 1, ',', '.') }} jt</h3>
                </div>
                <div class="p-2 bg-green-50 rounded-lg text-green-600">
                    <i class="ph ph-currency-dollar text-xl"></i>
                </div>
            </div>
            <span class="text-sm text-gray-400 font-medium flex items-center mt-4">
                Update: Realtime
            </span>
        </div>

        <!-- Card 4: Purchases -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Pembelian Bulan Ini</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">Rp {{ number_format($stats['monthly_purchases'] / 1000000, 1, ',', '.') }} jt</h3>
                </div>
                <div class="p-2 bg-orange-50 rounded-lg text-orange-600">
                    <i class="ph ph-shopping-cart text-xl"></i>
                </div>
            </div>
            <span class="text-sm text-gray-400 font-medium mt-4 block">Update: Realtime</span>
        </div>
    </div>

    <!-- Charts Filter Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h2 class="text-lg font-bold text-gray-800">Analisis Grafik</h2>
        <div class="flex items-center gap-2 bg-white p-1 rounded-lg border border-gray-200 shadow-sm">
            <div class="pl-3 text-gray-500">
                <i class="ph ph-calendar-blank text-lg"></i>
            </div>
            <input type="text" id="chartDateRange" class="text-sm border-none focus:ring-0 text-gray-700 font-medium w-56 py-1.5" placeholder="Pilih Tanggal">
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Financial Chart -->
        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Tren Keuangan</h3>
            <div id="financialChart" style="min-height: 300px;"></div>
        </div>

        <!-- Production Chart -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Tren Produksi</h3>
            <div id="productionChart" style="min-height: 300px;"></div>
        </div>
    </div>

    <!-- Recent Activities & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Activities -->
        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Aktivitas Terbaru</h3>
            <div class="space-y-4">
                @forelse($recentActivities as $activity)
                    <div class="flex items-start gap-3">
                        <div class="p-2 bg-{{ $activity['color'] }}-50 rounded-lg text-{{ $activity['color'] }}-600">
                            <i class="ph {{ $activity['icon'] }} text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">{{ $activity['title'] }}</p>
                            <p class="text-xs text-gray-500">{{ $activity['description'] }}</p>
                        </div>
                        <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($activity['time'])->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 text-center py-4">Belum ada aktivitas.</p>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Aksi Cepat</h3>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('productions.create') }}" class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg hover:from-blue-100 hover:to-indigo-100 transition-colors group text-center">
                    <i class="ph ph-plus-circle text-2xl text-blue-600 mb-2 block mx-auto"></i>
                    <p class="text-sm font-medium text-gray-800 group-hover:text-blue-700">Input Produksi</p>
                </a>

                <a href="{{ route('purchases.create') }}" class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg hover:from-green-100 hover:to-emerald-100 transition-colors group text-center">
                    <i class="ph ph-receipt text-2xl text-green-600 mb-2 block mx-auto"></i>
                    <p class="text-sm font-medium text-gray-800 group-hover:text-green-700">Input Nota</p>
                </a>

                <a href="{{ route('payrolls.create') }}" class="p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg hover:from-purple-100 hover:to-pink-100 transition-colors group text-center">
                    <i class="ph ph-wallet text-2xl text-purple-600 mb-2 block mx-auto"></i>
                    <p class="text-sm font-medium text-gray-800 group-hover:text-purple-700">Input Gaji</p>
                </a>

                <a href="{{ route('sales.create') }}" class="p-4 bg-gradient-to-r from-orange-50 to-yellow-50 rounded-lg hover:from-orange-100 hover:to-yellow-100 transition-colors group text-center">
                    <i class="ph ph-shopping-cart text-2xl text-orange-600 mb-2 block mx-auto"></i>
                    <p class="text-sm font-medium text-gray-800 group-hover:text-orange-700">Input Jual</p>
                </a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize DateRangePicker
            var start = moment().subtract(6, 'days');
            var end = moment();

            function cb(start, end) {
                $('#chartDateRange').val(start.format('D MMM YYYY') + ' - ' + end.format('D MMM YYYY'));
                updateCharts(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
            }

            $('#chartDateRange').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                   'Hari Ini': [moment(), moment()],
                   'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                   '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
                   '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
                   'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
                   'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
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

            // Initial call
            cb(start, end);

            // Financial Chart (Sales vs Purchases)
            var financialOptions = {
                series: [{
                    name: 'Penjualan',
                    data: @json($chartData['sales'])
                }, {
                    name: 'Pembelian',
                    data: @json($chartData['purchases'])
                }],
                chart: {
                    type: 'area',
                    height: 350,
                    toolbar: { show: false },
                    fontFamily: 'Inter, sans-serif'
                },
                colors: ['#059669', '#ea580c'], // Green, Orange
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 2 },
                xaxis: {
                    categories: @json($chartData['dates']),
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: {
                        formatter: function (value) {
                            return 'Rp ' + (value / 1000000).toFixed(1) + ' jt';
                        }
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.2,
                        stops: [0, 90, 100]
                    }
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return "Rp " + new Intl.NumberFormat('id-ID').format(val);
                        }
                    }
                },
                grid: {
                    borderColor: '#f1f5f9',
                    strokeDashArray: 4,
                }
            };

            var financialChart = new ApexCharts(document.querySelector("#financialChart"), financialOptions);
            financialChart.render();

            // Production Chart
            var productionOptions = {
                series: [{
                    name: 'Total Produksi (Pcs)',
                    data: @json($chartData['production'])
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: { show: false },
                    fontFamily: 'Inter, sans-serif'
                },
                colors: ['#2563eb'], // Blue
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        columnWidth: '50%',
                    }
                },
                dataLabels: { enabled: false },
                xaxis: {
                    categories: @json($chartData['dates']),
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val + " pcs";
                        }
                    }
                },
                grid: {
                    borderColor: '#f1f5f9',
                    strokeDashArray: 4,
                }
            };

            var productionChart = new ApexCharts(document.querySelector("#productionChart"), productionOptions);
            productionChart.render();

            // Function to update charts via AJAX
            function updateCharts(startDate, endDate) {
                fetch(`{{ route('dashboard') }}?start_date=${startDate}&end_date=${endDate}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Update Financial Chart
                    financialChart.updateOptions({
                        xaxis: {
                            categories: data.dates
                        }
                    });
                    financialChart.updateSeries([{
                        name: 'Penjualan',
                        data: data.sales
                    }, {
                        name: 'Pembelian',
                        data: data.purchases
                    }]);

                    // Update Production Chart
                    productionChart.updateOptions({
                        xaxis: {
                            categories: data.dates
                        }
                    });
                    productionChart.updateSeries([{
                        name: 'Total Produksi (Pcs)',
                        data: data.production
                    }]);
                });
            }
        });
    </script>
@endsection