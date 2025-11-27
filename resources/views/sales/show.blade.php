@extends('layouts.app')

@section('title', 'Detail Penjualan')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Page Header -->
    <div class="flex items-center gap-4 print:hidden">
        <a href="{{ route('sales.index') }}" class="p-2 rounded-lg hover:bg-gray-100 text-gray-600 transition-colors">
            <i class="ph ph-arrow-left text-xl"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Penjualan</h1>
            <p class="text-sm text-gray-500 mt-1">Informasi lengkap transaksi penjualan</p>
        </div>
    </div>

    <!-- Invoice Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden print:shadow-none print:border-none">
        <!-- Header -->
        <div class="p-8 border-b border-gray-100 bg-gray-50/50 flex flex-col md:flex-row justify-between gap-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-1">INVOICE</h2>
                <p class="text-primary-600 font-mono font-medium">{{ $sale->invoice_number }}</p>
                
                <div class="mt-4 text-sm text-gray-600">
                    <p class="font-semibold text-gray-800">Diterbitkan Oleh:</p>
                    <p>USB Cake Production</p>
                    <p>Jl. Contoh No. 123, Jakarta</p>
                </div>
            </div>
            
            <div class="text-right">
                <div class="mb-4">
                    <p class="text-sm text-gray-500">Tanggal Transaksi</p>
                    <p class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($sale->date)->format('d F Y') }}</p>
                </div>
                
                @if($sale->buyer)
                <div>
                    <p class="text-sm text-gray-500 mb-1">Pembeli</p>
                    <h3 class="font-bold text-gray-800 text-lg">{{ $sale->buyer->name }}</h3>
                    @if($sale->buyer->address)
                        <p class="text-sm text-gray-600">{{ $sale->buyer->address }}</p>
                    @endif
                    @if($sale->buyer->phone)
                        <p class="text-sm text-gray-600">{{ $sale->buyer->phone }}</p>
                    @endif
                </div>
                @endif
            </div>
        </div>

        <!-- Items Table -->
        <div class="p-8">
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-gray-100">
                        <th class="py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                        <th class="py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Produk</th>
                        <th class="py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga Satuan</th>
                        <th class="py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Qty</th>
                        <th class="py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($sale->items as $index => $item)
                    <tr>
                        <td class="py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                        <td class="py-4">
                            <p class="font-medium text-gray-800">{{ $item->item->name }}</p>
                            <p class="text-xs text-gray-500">{{ $item->item->unit }}</p>
                        </td>
                        <td class="py-4 text-right text-sm text-gray-600">
                            Rp {{ number_format($item->price, 0, ',', '.') }}
                        </td>
                        <td class="py-4 text-right text-sm text-gray-600">
                            {{ $item->qty }}
                        </td>
                        <td class="py-4 text-right font-medium text-gray-800">
                            Rp {{ number_format($item->total, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="pt-6 text-right text-sm font-medium text-gray-600">Grand Total</td>
                        <td class="pt-6 text-right text-xl font-bold text-primary-600">
                            Rp {{ number_format($sale->grand_total, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Footer Actions -->
        <div class="bg-gray-50 px-8 py-6 border-t border-gray-100 flex justify-end gap-3 print:hidden">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                <i class="ph ph-printer text-lg"></i>
                Cetak Invoice
            </button>
            <button type="button" onclick="showDeleteModal('{{ route('sales.destroy', $sale) }}', '{{ $sale->invoice_number }}')" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium shadow-lg shadow-red-600/30">
                <i class="ph ph-trash text-lg"></i>
                Hapus Transaksi
            </button>
        </div>
    </div>
</div>
@endsection
