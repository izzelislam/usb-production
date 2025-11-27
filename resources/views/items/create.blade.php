@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('items.index') }}" class="p-2 rounded-lg hover:bg-gray-100 text-gray-600 transition-colors">
            <i class="ph ph-arrow-left text-xl"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tambah Produk</h1>
            <p class="text-sm text-gray-500 mt-1">Input data produk baru</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8">
        <form action="{{ route('items.store') }}" method="POST" class="space-y-8">
            @csrf

            <!-- Informasi Dasar -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-100 pb-2">Informasi Dasar</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Produk <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <i class="ph ph-package text-lg"></i>
                            </span>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('name') border-red-500 @enderror"
                                placeholder="Nama Produk">
                        </div>
                        @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Satuan -->
                    <div>
                        <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">
                            Satuan <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <i class="ph ph-ruler text-lg"></i>
                            </span>
                            <input type="text" name="unit" id="unit" value="{{ old('unit', 'pcs') }}" required 
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('unit') border-red-500 @enderror"
                                placeholder="pcs, kg, box, dll">
                        </div>
                        @error('unit')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Harga Default -->
                    <div class="md:col-span-2">
                        <label for="default_price" class="block text-sm font-medium text-gray-700 mb-2">
                            Harga Default
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <span class="text-sm font-bold">Rp</span>
                            </span>
                            <input type="number" name="default_price" id="default_price" value="{{ old('default_price') }}" 
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('default_price') border-red-500 @enderror"
                                placeholder="0">
                        </div>
                        @error('default_price')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100">
                <a href="{{ route('items.index') }}" class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-lg shadow-primary-600/30 font-medium flex items-center gap-2">
                    <i class="ph ph-check-circle text-lg"></i>
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
