@extends('layouts.app')

@section('title', 'Edit Karyawan')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('employees.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
            <i class="ph ph-arrow-left text-xl text-gray-600"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Karyawan</h1>
            <p class="text-sm text-gray-500 mt-1">Perbarui data karyawan</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        <form action="{{ route('employees.update', $employee) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Name & Phone Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $employee->name) }}" required
                        @class([
                            'w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500',
                            'border-red-500' => $errors->has('name'),
                            'border-gray-300' => !$errors->has('name')
                        ])
                        placeholder="Masukkan nama lengkap">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Nomor Telepon
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <i class="ph ph-phone text-lg"></i>
                        </span>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $employee->phone) }}"
                            @class([
                                'w-full pl-10 pr-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500',
                                'border-red-500' => $errors->has('phone'),
                                'border-gray-300' => !$errors->has('phone')
                            ])
                            placeholder="08xx-xxxx-xxxx">
                    </div>
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Address -->
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                    Alamat
                </label>
                <textarea id="address" name="address" rows="3"
                    @class([
                        'w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500',
                        'border-red-500' => $errors->has('address'),
                        'border-gray-300' => !$errors->has('address')
                    ])
                    placeholder="Masukkan alamat lengkap">{{ old('address', $employee->address) }}</textarea>
                @error('address')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Salary Type -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Tipe Gaji <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-2 gap-4">
                    <label @class([
                        'relative flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all hover:border-primary-500 has-checked:border-primary-600 has-checked:bg-primary-50',
                        'border-red-500' => $errors->has('salary_type'),
                        'border-gray-200' => !$errors->has('salary_type')
                    ])>
                        <input type="radio" name="salary_type" value="per_kg" {{ old('salary_type', $employee->salary_type) === 'per_kg' ? 'checked' : '' }} class="sr-only peer" required onchange="toggleSalaryFields()">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                <i class="ph ph-scales text-xl"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">Per Kilogram</p>
                                <p class="text-xs text-gray-500">Gaji berdasarkan produksi</p>
                            </div>
                        </div>
                    </label>

                    <label @class([
                        'relative flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all hover:border-primary-500 has-checked:border-primary-600 has-checked:bg-primary-50',
                        'border-red-500' => $errors->has('salary_type'),
                        'border-gray-200' => !$errors->has('salary_type')
                    ])>
                        <input type="radio" name="salary_type" value="per_day" {{ old('salary_type', $employee->salary_type) === 'per_day' ? 'checked' : '' }} class="sr-only peer" onchange="toggleSalaryFields()">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                                <i class="ph ph-calendar-blank text-xl"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">Per Hari</p>
                                <p class="text-xs text-gray-500">Gaji harian tetap</p>
                            </div>
                        </div>
                    </label>
                </div>
                @error('salary_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Salary Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Salary Per KG -->
                <div id="salary-per-kg-field">
                    <label for="base_salary_per_kg" class="block text-sm font-medium text-gray-700 mb-2">
                        Gaji Per Kilogram
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 text-sm">Rp</span>
                        <input type="number" id="base_salary_per_kg" name="base_salary_per_kg" value="{{ old('base_salary_per_kg', $employee->base_salary_per_kg) }}" step="0.01" min="0"
                            @class([
                                'w-full pl-10 pr-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500',
                                'border-red-500' => $errors->has('base_salary_per_kg'),
                                'border-gray-300' => !$errors->has('base_salary_per_kg')
                            ])
                            placeholder="0">
                    </div>
                    @error('base_salary_per_kg')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Salary Per Day -->
                <div id="salary-per-day-field">
                    <label for="base_salary_per_day" class="block text-sm font-medium text-gray-700 mb-2">
                        Gaji Per Hari
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 text-sm">Rp</span>
                        <input type="number" id="base_salary_per_day" name="base_salary_per_day" value="{{ old('base_salary_per_day', $employee->base_salary_per_day) }}" step="0.01" min="0"
                            @class([
                                'w-full pl-10 pr-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500',
                                'border-red-500' => $errors->has('base_salary_per_day'),
                                'border-gray-300' => !$errors->has('base_salary_per_day')
                            ])
                            placeholder="0">
                    </div>
                    @error('base_salary_per_day')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Status <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="is_active" value="1" {{ old('is_active', $employee->is_active) == '1' ? 'checked' : '' }} class="w-4 h-4 text-primary-600 focus:ring-primary-500" required>
                        <span class="text-sm text-gray-700 font-medium">Aktif</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="is_active" value="0" {{ old('is_active', $employee->is_active) == '0' ? 'checked' : '' }} class="w-4 h-4 text-primary-600 focus:ring-primary-500">
                        <span class="text-sm text-gray-700 font-medium">Non-Aktif</span>
                    </label>
                </div>
                @error('is_active')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3 pt-6 border-t border-gray-100">
                <button type="submit" class="flex-1 sm:flex-none px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-lg shadow-primary-600/30 font-medium">
                    <i class="ph ph-check-circle mr-2"></i>
                    Perbarui Karyawan
                </button>
                <a href="{{ route('employees.index') }}" class="flex-1 sm:flex-none px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function toggleSalaryFields() {
    const salaryType = document.querySelector('input[name="salary_type"]:checked').value;
    const perKgField = document.getElementById('salary-per-kg-field');
    const perDayField = document.getElementById('salary-per-day-field');
    const perKgInput = document.getElementById('base_salary_per_kg');
    const perDayInput = document.getElementById('base_salary_per_day');

    if (salaryType === 'per_kg') {
        perKgField.style.opacity = '1';
        perDayField.style.opacity = '0.5';
        perKgInput.required = true;
        perDayInput.required = false;
    } else {
        perKgField.style.opacity = '0.5';
        perDayField.style.opacity = '1';
        perKgInput.required = false;
        perDayInput.required = true;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', toggleSalaryFields);
</script>
@endsection
