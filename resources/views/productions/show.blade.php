@extends('layouts.app')

@section('title', 'Detail Produksi')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('productions.index') }}" class="p-2 rounded-lg hover:bg-gray-100 text-gray-600 transition-colors">
            <i class="ph ph-arrow-left text-xl"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Produksi</h1>
            <p class="text-sm text-gray-500 mt-1">Informasi lengkap produksi barang</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Production Info Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-100 pb-4 mb-6">Informasi Produksi</h3>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Tanggal Produksi:</span>
                    <span class="text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($production->production_date)->format('d/m/Y') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total Barang:</span>
                    <span class="text-sm font-semibold text-gray-900">{{ number_format($production->total_kue, 2) }} KG</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Grand Total:</span>
                    <span class="text-sm font-bold text-primary-600">Rp {{ number_format($production->grand_total, 0, ',', '.') }}</span>
                </div>
                <div class="pt-4 border-t border-gray-100">
                    <p class="text-sm text-gray-600 mb-2">Catatan:</p>
                    <p class="text-sm text-gray-900">{{ $production->notes ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Workers Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-100 pb-4 mb-6">Pembagian Pekerja</h3>
            
            <div class="space-y-3">
                @foreach($production->productionWorkers as $worker)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                                <i class="ph ph-user text-primary-600"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ $worker->employee->name }}</span>
                        </div>
                        <span class="text-sm font-bold text-primary-600">{{ number_format($worker->workload, 2) }} KG</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Purchases List Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-100 pb-4 mb-6">Daftar Pembelian yang Diproduksi</h3>
        
        <div class="space-y-4">
            @foreach($production->purchases as $purchase)
                <div class="border border-gray-200 rounded-lg p-5">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h4 class="font-semibold text-gray-900">{{ $purchase->vendor->name }}</h4>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="ph ph-calendar-blank"></i>
                                {{ \Carbon\Carbon::parse($purchase->date)->format('d/m/Y') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-primary-600">Rp {{ number_format($purchase->grand_total, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Nama Item</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Jumlah</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Harga</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($purchase->items as $item)
                                    <tr>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $item->item->name }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $item->qty }} KG</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td class="px-4 py-2 text-sm font-medium text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex items-center justify-between">
        <a href="{{ route('productions.index') }}" class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
            <i class="ph ph-arrow-left mr-2"></i>
            Kembali
        </a>
        
        <form action="{{ route('productions.destroy', $production) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produksi ini? Pembelian terkait akan ditandai belum diproduksi.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-6 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium flex items-center gap-2">
                <i class="ph ph-trash text-lg"></i>
                Hapus Produksi
            </button>
        </form>
    </div>
</div>
@endsection
