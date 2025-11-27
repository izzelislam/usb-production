@extends('layouts.app')

@section('title', 'Produksi Karyawan')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Produksi Karyawan</h1>
            <p class="text-sm text-gray-500 mt-1">Riwayat produksi per karyawan</p>
        </div>
    </div>

    <!-- Filter & Search Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="GET" action="{{ route('productions.worker_summary') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <!-- Employee Select -->
                <div class="md:col-span-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Karyawan</label>
                    <select name="employee_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Semua Karyawan</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Start Date -->
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>

                <!-- End Date -->
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>

                <!-- Buttons -->
                <div class="md:col-span-2 flex gap-2 items-end">
                    <button type="submit" class="px-4 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors font-medium flex-1" title="Filter">
                        <i class="ph ph-funnel text-lg"></i>
                    </button>
                    <a href="{{ route('productions.worker_summary') }}" class="px-4 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium flex-1 text-center" title="Reset">
                        <i class="ph ph-x text-lg"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Karyawan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal Produksi</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Beban Kerja</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Produksi</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($workerProductions as $index => $workerProduction)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $workerProductions->firstItem() + $index }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center text-primary-600">
                                        <i class="ph ph-user"></i>
                                    </div>
                                    {{ $workerProduction->employee->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ \Carbon\Carbon::parse($workerProduction->production->production_date)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-primary-600">{{ number_format($workerProduction->workload, 2) }} KG</td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                dari total {{ number_format($workerProduction->production->total_kue, 2) }} KG
                            </td>
                            <td class="px-6 py-4 text-sm text-center">
                                <a href="{{ route('productions.show', $workerProduction->production_id) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors text-xs font-medium">
                                    <i class="ph ph-eye text-base mr-1"></i>
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <i class="ph ph-users text-5xl mb-3 block mx-auto text-gray-300"></i>
                                <p class="text-lg font-medium">Tidak ada data produksi karyawan</p>
                                <p class="text-sm mt-1">Coba ubah filter pencarian</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($workerProductions->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $workerProductions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
