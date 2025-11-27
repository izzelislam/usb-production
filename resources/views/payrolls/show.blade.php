@extends('layouts.app')

@section('title', 'Detail Penggajian')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('payrolls.index') }}" class="p-2 rounded-lg hover:bg-gray-100 text-gray-600 transition-colors">
                <i class="ph ph-arrow-left text-xl"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Detail Penggajian #{{ $payroll->id }}</h1>
                <p class="text-sm text-gray-500 mt-1">Dibuat pada {{ $payroll->created_at->format('d M Y H:i') }}</p>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('payrolls.print', $payroll) }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2.5 bg-gray-800 text-white rounded-xl hover:bg-gray-900 transition-all shadow-lg shadow-gray-800/30 font-medium gap-2">
                <i class="ph ph-printer text-lg"></i>
                <span>Cetak Slip Gaji</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Employee Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Informasi Karyawan</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase">Nama</label>
                        <div class="text-base font-semibold text-gray-900">{{ $payroll->employee->name }}</div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase">Tipe Gaji</label>
                        <div class="text-base font-semibold text-gray-900 capitalize">{{ str_replace('_', ' ', $payroll->salary_type) }}</div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase">Periode</label>
                        <div class="text-base font-semibold text-gray-900">
                            {{ \Carbon\Carbon::parse($payroll->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($payroll->end_date)->format('d M Y') }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase">Status</label>
                        @if($payroll->status == 'paid')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Sudah Dibayar
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Belum Dibayar
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Production Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Rincian Produksi</h3>
                <div class="overflow-x-auto border border-gray-200 rounded-lg">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Beban Kerja</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($payroll->details as $detail)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $detail->productionWorker->production->production_date ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                        {{ number_format($detail->workload, 2) }} {{ $payroll->salary_type == 'per_kg' ? 'KG' : 'Hari' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900 text-right">
                                        Rp {{ number_format($detail->salary_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 border-t border-gray-200">
                            <tr>
                                <td class="px-4 py-3 text-sm font-bold text-gray-700">Total</td>
                                <td class="px-4 py-3 text-sm font-bold text-gray-900">
                                    {{ number_format($payroll->total_workload, 2) }} {{ $payroll->salary_type == 'per_kg' ? 'KG' : 'Hari' }}
                                </td>
                                <td class="px-4 py-3 text-sm font-bold text-gray-900 text-right">
                                    Rp {{ number_format($payroll->details->sum('salary_amount'), 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column: Summary -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Ringkasan Gaji</h3>
                
                <div class="space-y-3">
                    <!-- Base Salary -->
                    <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Gaji Pokok</span>
                        <span class="text-sm font-bold text-gray-900">Rp {{ number_format($payroll->details->sum('salary_amount'), 0, ',', '.') }}</span>
                    </div>

                    <!-- Bonuses -->
                    @if($payroll->bonuses->count() > 0)
                        <div class="py-2">
                            <span class="text-xs font-semibold text-green-600 uppercase tracking-wider mb-2 block">Bonus / Tunjangan</span>
                            @foreach($payroll->bonuses as $bonus)
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm text-gray-600">{{ $bonus->description }}</span>
                                    <span class="text-sm font-medium text-green-600">+ Rp {{ number_format($bonus->amount, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Deductions -->
                    @if($payroll->deductions->count() > 0)
                        <div class="py-2 border-t border-gray-100">
                            <span class="text-xs font-semibold text-red-600 uppercase tracking-wider mb-2 block">Potongan</span>
                            @foreach($payroll->deductions as $deduction)
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm text-gray-600">{{ $deduction->description }}</span>
                                    <span class="text-sm font-medium text-red-600">- Rp {{ number_format($deduction->amount, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Final Total -->
                    <div class="pt-4 border-t-2 border-gray-100 mt-2">
                        <div class="flex justify-between items-center">
                            <span class="text-base font-bold text-gray-800">Total Diterima</span>
                            <span class="text-xl font-bold text-primary-600">Rp {{ number_format($payroll->final_salary, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                @if($payroll->notes)
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Catatan</label>
                        <p class="text-sm text-gray-600 italic">"{{ $payroll->notes }}"</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection