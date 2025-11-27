@extends('layouts.app')

@section('title', 'Data Pembelian')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Data Pembelian</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data pembelian bahan baku</p>
        </div>
        <a href="{{ route('purchases.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-lg shadow-primary-600/30 font-medium">
            <i class="ph ph-plus-circle text-lg"></i>
            Input Pembelian
        </a>
    </div>

    <!-- Filter & Search Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="GET" action="{{ route('purchases.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <!-- Search -->
                <div class="md:col-span-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <i class="ph ph-magnifying-glass text-lg"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Invoice / Supplier..." class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>

                <!-- Start Date -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>

                <!-- End Date -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                
                <!-- Month Filter -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                    <input type="month" name="month" value="{{ request('month') }}" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>

                <!-- Buttons -->
                <div class="md:col-span-2 flex gap-2 items-end">
                    <button type="submit" class="px-4 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors font-medium w-full" title="Filter">
                        <i class="ph ph-funnel text-lg"></i>
                    </button>
                    <a href="{{ route('purchases.index') }}" class="px-4 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium w-full text-center" title="Reset">
                        <i class="ph ph-x text-lg"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Bulk Action Form -->
    <form action="{{ route('purchases.bulk_action') }}" method="POST" target="_blank">
        @csrf
        
        <!-- Table Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Bulk Action Toolbar -->
            <div class="p-4 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
                <span class="text-sm font-medium text-gray-600">Aksi Massal:</span>
                <select name="action" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" required>
                    <option value="">Pilih Aksi</option>
                    <option value="download_nota">Download Nota</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium shadow-sm">
                    Terapkan
                </button>
                <div class="flex-1"></div>
                <a href="{{ route('purchases.export', request()->all()) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium shadow-sm flex items-center gap-2">
                    <i class="ph ph-microsoft-excel-logo text-lg"></i>
                    Export Excel
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left w-10">
                                <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 w-4 h-4 cursor-pointer">
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Invoice</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Supplier</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($purchases as $purchase)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <input type="checkbox" name="ids[]" value="{{ $purchase->id }}" class="purchase-checkbox rounded border-gray-300 text-primary-600 focus:ring-primary-500 w-4 h-4 cursor-pointer">
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-mono font-medium text-primary-600">{{ $purchase->invoice_number }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($purchase->date)->format('d M Y H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-800">{{ $purchase->vendor->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($purchase->is_produced)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Sudah Diproduksi
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Belum Diproduksi
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-800">
                                    Rp {{ number_format($purchase->grand_total, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" onclick="openPrintModal('{{ route('purchases.print', $purchase) }}')" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors font-medium" title="Cetak Nota">
                                        <i class="ph ph-printer text-lg"></i>
                                        <span>Cetak</span>
                                    </button>
                                    <a href="{{ route('purchases.show', $purchase) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors font-medium" title="Lihat Detail">
                                        <i class="ph ph-eye text-lg"></i>
                                        <span>Detail</span>
                                    </a>
                                    <button type="button" onclick="showDeleteModal('{{ route('purchases.destroy', $purchase) }}', '{{ $purchase->invoice_number }}')" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors font-medium" title="Hapus">
                                        <i class="ph ph-trash text-lg"></i>
                                        <span>Hapus</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <i class="ph ph-shopping-cart text-6xl text-gray-300"></i>
                                    <p class="text-gray-500 font-medium">Tidak ada data pembelian</p>
                                    <a href="{{ route('purchases.create') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                                        Input pembelian baru
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($purchases->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $purchases->links() }}
            </div>
            @endif
        </div>
    </form>

    <!-- Print Modal -->
    <div x-data="{ show: false, url: '' }"
         @open-print-modal.window="show = true; url = $event.detail.url"
         x-show="show"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        
        <!-- Backdrop -->
        <div x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
             @click="show = false"></div>

        <!-- Modal Panel -->
        <div x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl mx-auto mt-10">
            
            <!-- Header -->
            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-semibold leading-6 text-gray-900">Preview Nota Pembelian</h3>
                <button @click="show = false" class="text-gray-400 hover:text-gray-500">
                    <i class="ph ph-x text-xl"></i>
                </button>
            </div>

            <!-- Content -->
            <div class="bg-gray-50 p-4 h-[600px]">
                <iframe :src="url" class="w-full h-full rounded border border-gray-200 bg-white"></iframe>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-gray-100">
                <button type="button" 
                        @click="document.querySelector('iframe').contentWindow.print()"
                        class="inline-flex w-full justify-center rounded-md bg-gray-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-800 sm:ml-3 sm:w-auto gap-2 items-center">
                    <i class="ph ph-printer text-lg"></i> Cetak Sekarang
                </button>
                <button type="button" 
                        @click="show = false" 
                        class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        function openPrintModal(url) {
            window.dispatchEvent(new CustomEvent('open-print-modal', { detail: { url: url } }));
        }

        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.purchase-checkbox');

            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    checkboxes.forEach(cb => cb.checked = this.checked);
                });
            }
        });
    </script>
</div>
@endsection
