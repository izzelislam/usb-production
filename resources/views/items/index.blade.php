@extends('layouts.app')

@section('title', 'Data Produk')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Data Produk</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data produk dan bahan baku</p>
        </div>
        <a href="{{ route('items.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-lg shadow-primary-600/30 font-medium">
            <i class="ph ph-plus-circle text-lg"></i>
            Tambah Produk
        </a>
    </div>

    <!-- Filter & Search Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="GET" action="{{ route('items.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="md:col-span-3">
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Produk</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <i class="ph ph-magnifying-glass text-lg"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama produk..." class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 items-end">
                <button type="submit" class="px-5 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors font-medium w-full">
                    <i class="ph ph-funnel mr-2"></i>Filter
                </button>
                <a href="{{ route('items.index') }}" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium w-full text-center">
                    <i class="ph ph-x mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-16">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Produk</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Satuan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga Default</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($items as $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-500 text-sm">
                            {{ ($items->currentPage() - 1) * $items->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-800">{{ $item->name }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $item->unit }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-600">
                                Rp {{ number_format($item->default_price, 0, ',', '.') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('items.show', $item) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors font-medium" title="Lihat Detail">
                                    <i class="ph ph-eye text-lg"></i>
                                    <span>Detail</span>
                                </a>
                                <a href="{{ route('items.edit', $item) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm text-primary-600 hover:bg-primary-50 rounded-lg transition-colors font-medium" title="Edit">
                                    <i class="ph ph-pencil-simple text-lg"></i>
                                    <span>Edit</span>
                                </a>
                                <button type="button" onclick="showDeleteModal('{{ route('items.destroy', $item) }}', '{{ $item->name }}')" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors font-medium" title="Hapus">
                                    <i class="ph ph-trash text-lg"></i>
                                    <span>Hapus</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <i class="ph ph-package text-6xl text-gray-300"></i>
                                <p class="text-gray-500 font-medium">Tidak ada data produk</p>
                                <a href="{{ route('items.create') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                                    Tambah produk pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($items->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $items->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
