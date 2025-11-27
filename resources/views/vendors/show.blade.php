@extends('layouts.app')

@section('title', 'Detail Supplier')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('vendors.index') }}" class="p-2 rounded-lg hover:bg-gray-100 text-gray-600 transition-colors">
            <i class="ph ph-arrow-left text-xl"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Supplier</h1>
            <p class="text-sm text-gray-500 mt-1">Informasi lengkap supplier</p>
        </div>
    </div>

    <!-- Detail Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Header -->
        <div class="p-6 sm:p-8 border-b border-gray-100 bg-gray-50/50">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center text-2xl font-bold">
                        {{ strtoupper(substr($vendor->name, 0, 2)) }}
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">{{ $vendor->name }}</h2>
                        <p class="text-sm text-gray-500 mt-1">ID: {{ $vendor->id }}</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('vendors.edit', $vendor) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium shadow-sm">
                        <i class="ph ph-pencil-simple text-lg"></i>
                        Edit
                    </a>
                    <button type="button" onclick="showDeleteModal('{{ route('vendors.destroy', $vendor) }}', '{{ $vendor->name }}')" class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 text-red-600 border border-red-100 rounded-lg hover:bg-red-100 transition-colors font-medium">
                        <i class="ph ph-trash text-lg"></i>
                        Hapus
                    </button>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6 sm:p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Informasi Kontak -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Informasi Kontak</h3>
                    <div class="space-y-4">
                        <div class="flex gap-3">
                            <div class="w-10 h-10 rounded-lg bg-gray-50 flex items-center justify-center flex-shrink-0">
                                <i class="ph ph-phone text-xl text-gray-500"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-0.5">Nomor Telepon</p>
                                <p class="font-medium text-gray-900">{{ $vendor->phone ?: '-' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex gap-3">
                            <div class="w-10 h-10 rounded-lg bg-gray-50 flex items-center justify-center flex-shrink-0">
                                <i class="ph ph-map-pin text-xl text-gray-500"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-0.5">Alamat</p>
                                <p class="font-medium text-gray-900">{{ $vendor->address ?: '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistik / Info Tambahan (Placeholder) -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Informasi Sistem</h3>
                    <div class="space-y-4">
                        <div class="flex gap-3">
                            <div class="w-10 h-10 rounded-lg bg-gray-50 flex items-center justify-center flex-shrink-0">
                                <i class="ph ph-clock text-xl text-gray-500"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-0.5">Dibuat Pada</p>
                                <p class="font-medium text-gray-900">{{ $vendor->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex gap-3">
                            <div class="w-10 h-10 rounded-lg bg-gray-50 flex items-center justify-center flex-shrink-0">
                                <i class="ph ph-pencil-simple-slash text-xl text-gray-500"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-0.5">Terakhir Diupdate</p>
                                <p class="font-medium text-gray-900">{{ $vendor->updated_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
