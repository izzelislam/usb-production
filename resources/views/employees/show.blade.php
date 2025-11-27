@extends('layouts.app')

@section('title', 'Detail Karyawan')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('employees.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <i class="ph ph-arrow-left text-xl text-gray-600"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Detail Karyawan</h1>
                <p class="text-sm text-gray-500 mt-1">Informasi lengkap karyawan</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('employees.edit', $employee) }}" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors font-medium">
                <i class="ph ph-pencil-simple mr-2"></i>Edit
            </a>
        </div>
    </div>

    <!-- Employee Info Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-primary-600 to-primary-700 px-8 py-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-white text-primary-600 flex items-center justify-center font-bold text-2xl">
                    {{ strtoupper(substr($employee->name, 0, 2)) }}
                </div>
                <div class="text-white">
                    <h2 class="text-2xl font-bold">{{ $employee->name }}</h2>
                    <p class="text-primary-100 text-sm">ID Karyawan: #{{ $employee->id }}</p>
                </div>
            </div>
        </div>

        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Contact Info -->
            <div class="space-y-4">
                <h3 class="font-semibold text-gray-800 flex items-center gap-2 pb-2 border-b border-gray-200">
                    <i class="ph ph-address-book text-primary-600"></i>
                    Informasi Kontak
                </h3>
                
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-medium">Nomor Telepon</p>
                        <p class="text-gray-800 font-medium">{{ $employee->phone ?: '-' }}</p>
                    </div>
                    
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-medium">Alamat</p>
                        <p class="text-gray-800">{{ $employee->address ?: '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Employment Info -->
            <div class="space-y-4">
                <h3 class="font-semibold text-gray-800 flex items-center gap-2 pb-2 border-b border-gray-200">
                    <i class="ph ph-briefcase text-primary-600"></i>
                    Informasi Pekerjaan
                </h3>
                
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-medium">Status</p>
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium {{ $employee->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $employee->is_active ? 'bg-green-600' : 'bg-red-600' }}"></span>
                            {{ $employee->is_active ? 'Aktif' : 'Non-Aktif' }}
                        </span>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 uppercase font-medium">Tipe Gaji</p>
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium {{ $employee->salary_type === 'per_kg' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                            <i class="ph {{ $employee->salary_type === 'per_kg' ? 'ph-scales' : 'ph-calendar-blank' }}"></i>
                            {{ $employee->salary_type === 'per_kg' ? 'Per Kilogram' : 'Per Hari' }}
                        </span>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 uppercase font-medium">Gaji Dasar</p>
                        <p class="text-2xl font-bold text-gray-800">
                            @if($employee->salary_type === 'per_kg')
                                Rp {{ number_format($employee->base_salary_per_kg, 0, ',', '.') }}
                                <span class="text-sm font-normal text-gray-500">/kg</span>
                            @else
                                Rp {{ number_format($employee->base_salary_per_day, 0, ',', '.') }}
                                <span class="text-sm font-normal text-gray-500">/hari</span>
                            @endif
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 uppercase font-medium">Bergabung Sejak</p>
                        <p class="text-gray-800">{{ $employee->created_at->format('d F Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Card -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-blue-100 rounded-lg text-blue-600">
                    <i class="ph ph-clipboard-text text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Produksi</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $employee->productionWorkers->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-green-100 rounded-lg text-green-600">
                    <i class="ph ph-currency-dollar text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Payroll</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $employee->payrolls->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-purple-100 rounded-lg text-purple-600">
                    <i class="ph ph-clock text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Terakhir Update</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $employee->updated_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-semibold text-gray-800 mb-4">Aksi Lainnya</h3>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('employees.edit', $employee) }}" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors font-medium">
                <i class="ph ph-pencil-simple mr-2"></i>Edit Karyawan
            </a>
            <button type="button" onclick="showDeleteModal('{{ route('employees.destroy', $employee) }}', '{{ $employee->name }}')" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                <i class="ph ph-trash mr-2"></i>Hapus Karyawan
            </button>
        </div>
    </div>
</div>
@endsection
