@extends('layouts.app')

@section('title', 'Data Karyawan')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Data Karyawan</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data karyawan produksi dan penggajian</p>
        </div>
        <a href="{{ route('employees.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-lg shadow-primary-600/30 font-medium">
            <i class="ph ph-plus-circle text-lg"></i>
            Tambah Karyawan
        </a>
    </div>

    <!-- Filter & Search Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="GET" action="{{ route('employees.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Karyawan</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <i class="ph ph-magnifying-glass text-lg"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama, telepon, atau alamat..." class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                </select>
            </div>

            <!-- Salary Type Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Gaji</label>
                <select name="salary_type" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white">
                    <option value="">Semua Tipe</option>
                    <option value="per_kg" {{ request('salary_type') === 'per_kg' ? 'selected' : '' }}>Per Kg</option>
                    <option value="per_day" {{ request('salary_type') === 'per_day' ? 'selected' : '' }}>Per Hari</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="md:col-span-4 flex gap-3">
                <button type="submit" class="px-5 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors font-medium">
                    <i class="ph ph-funnel mr-2"></i>Terapkan Filter
                </button>
                <a href="{{ route('employees.index') }}" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
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
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Karyawan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kontak</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tipe Gaji</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Gaji</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($employees as $employee)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center font-semibold">
                                    {{ strtoupper(substr($employee->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $employee->name }}</p>
                                    <p class="text-xs text-gray-500">ID: {{ $employee->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                @if($employee->phone)
                                <p class="text-gray-800 flex items-center gap-1">
                                    <i class="ph ph-phone text-gray-400"></i>
                                    {{ $employee->phone }}
                                </p>
                                @endif
                                @if($employee->address)
                                <p class="text-gray-500 text-xs mt-1">{{ Str::limit($employee->address, 30) }}</p>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium {{ $employee->salary_type === 'per_kg' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                <i class="ph {{ $employee->salary_type === 'per_kg' ? 'ph-scales' : 'ph-calendar-blank' }}"></i>
                                {{ $employee->salary_type === 'per_kg' ? 'Per Kg' : 'Per Hari' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-800">
                                @if($employee->salary_type === 'per_kg')
                                    Rp {{ number_format($employee->base_salary_per_kg, 0, ',', '.') }}/kg
                                @else
                                    Rp {{ number_format($employee->base_salary_per_day, 0, ',', '.') }}/hari
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium {{ $employee->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $employee->is_active ? 'bg-green-600' : 'bg-red-600' }}"></span>
                                {{ $employee->is_active ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('employees.show', $employee) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors font-medium" title="Lihat Detail">
                                    <i class="ph ph-eye text-lg"></i>
                                    <span>Detail</span>
                                </a>
                                <a href="{{ route('employees.edit', $employee) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm text-primary-600 hover:bg-primary-50 rounded-lg transition-colors font-medium" title="Edit">
                                    <i class="ph ph-pencil-simple text-lg"></i>
                                    <span>Edit</span>
                                </a>
                                <button type="button" onclick="showDeleteModal('{{ route('employees.destroy', $employee) }}', '{{ $employee->name }}')" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors font-medium" title="Hapus">
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
                                <i class="ph ph-users text-6xl text-gray-300"></i>
                                <p class="text-gray-500 font-medium">Tidak ada data karyawan</p>
                                <a href="{{ route('employees.create') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                                    Tambah karyawan pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($employees->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $employees->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
