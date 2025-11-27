@extends('layouts.app')

@section('title', 'Input Penggajian')

@section('content')
<div class="space-y-6" x-data="payrollForm()">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('payrolls.index') }}" class="p-2 rounded-lg hover:bg-gray-100 text-gray-600 transition-colors">
                <i class="ph ph-arrow-left text-xl"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Input Penggajian</h1>
                <p class="text-sm text-gray-500 mt-1">Hitung dan catat gaji karyawan</p>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center gap-3">
            <i class="ph ph-x-circle text-xl"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <form action="{{ route('payrolls.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @csrf
        
        <!-- Left Column: Selection & Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Employee Selection -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">1. Pilih Karyawan</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Karyawan</label>
                        <select name="employee_id" x-model="selectedEmployeeId" @change="fetchUnpaidWork()" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">Pilih Karyawan...</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div x-show="selectedEmployee">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Gaji</label>
                        <div class="px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-700 font-medium capitalize" x-text="salaryTypeLabel"></div>
                    </div>
                </div>
            </div>

            <!-- Unpaid Work List -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6" x-show="selectedEmployeeId">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800">2. Riwayat Produksi Belum Dibayar</h3>
                    <span class="text-xs font-medium px-2.5 py-1 bg-yellow-100 text-yellow-700 rounded-full" x-text="unpaidWorks.length + ' Transaksi'"></span>
                </div>

                <div class="overflow-x-auto border border-gray-200 rounded-lg mb-4">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left w-10">
                                    <input type="checkbox" @change="toggleAll($event)" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Beban Kerja</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <template x-for="work in unpaidWorks" :key="work.id">
                                <tr class="hover:bg-gray-50" :class="isSelected(work.id) ? 'bg-blue-50' : ''">
                                    <td class="px-4 py-3">
                                        <input type="checkbox" :value="work.id" x-model="selectedWorkIds" @change="updateCalculation()" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900" x-text="formatDate(work.production.production_date)"></td>
                                    <td class="px-4 py-3 text-sm font-bold text-primary-600" x-text="formatNumber(work.workload) + ' KG'"></td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                            Belum Dibayar
                                        </span>
                                    </td>
                                </tr>
                            </template>
                            <tr x-show="unpaidWorks.length === 0">
                                <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                    Tidak ada riwayat produksi yang belum dibayar.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Hidden inputs for submission -->
                <template x-for="id in selectedWorkIds" :key="id">
                    <input type="hidden" name="production_worker_ids[]" :value="id">
                </template>
            </div>
        </div>

        <!-- Right Column: Calculation & Action -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">3. Rincian Gaji</h3>
                
                <div class="space-y-4">
                    <!-- Date Range -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Periode Gaji</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="date" name="start_date" x-model="startDate" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <input type="date" name="end_date" x-model="endDate" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                    </div>

                    <!-- Base Salary Info -->
                    <!-- Base Salary Info -->
                    <div class="p-3 bg-blue-50 rounded-lg border border-blue-100" x-data="{ isEditingRate: false }">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs text-blue-600 font-medium">Gaji Pokok (Rate)</span>
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-blue-600 font-bold" x-text="baseSalaryLabel"></span>
                                <button type="button" @click="isEditingRate = !isEditingRate" class="text-blue-600 hover:text-blue-800 transition-colors" title="Edit Rate">
                                    <i class="ph" :class="isEditingRate ? 'ph-check' : 'ph-pencil-simple'"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Display Mode -->
                        <div x-show="!isEditingRate" class="text-lg font-bold text-blue-800" x-text="formatCurrency(customBaseSalary)"></div>
                        
                        <!-- Edit Mode -->
                        <div x-show="isEditingRate" class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-medium text-sm">Rp</span>
                            <input type="number" x-model="customBaseSalary" @input="calculateSalary()" class="w-full pl-8 pr-3 py-1.5 text-sm border border-blue-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-blue-900 font-bold">
                        </div>
                    </div>

                    <!-- Multiplier Input (Total KG or Days) -->
                    <div class="pt-4 border-t border-gray-100">
                        <label class="block text-sm font-bold text-gray-800 mb-2" x-text="multiplierLabel"></label>
                        <div class="relative">
                            <input type="number" step="0.01" x-model="multiplierValue" @input="calculateSalary()" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-lg font-bold text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium" x-text="multiplierUnit"></span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Total akumulasi dari item yang dipilih.</p>
                    </div>

                    <!-- Calculation Result -->
                    <div class="space-y-3 pt-4 border-t border-gray-100">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Estimasi Gaji Pokok</span>
                            <span class="text-sm font-bold text-gray-900" x-text="formatCurrency(estimatedSalary)"></span>
                        </div>
                    </div>

                    <!-- Bonuses -->
                    <div class="pt-4 border-t border-gray-100">
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-xs font-semibold text-green-600 uppercase tracking-wider">Bonus / Tunjangan</label>
                            <button type="button" @click="addBonus()" class="text-xs text-green-600 hover:text-green-800 font-medium flex items-center gap-1">
                                <i class="ph ph-plus-circle"></i> Tambah
                            </button>
                        </div>
                        <template x-for="(bonus, index) in bonuses" :key="index">
                            <div class="flex gap-2 mb-2">
                                <input type="text" x-model="bonus.description" placeholder="Keterangan" class="flex-1 px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                                <input type="number" x-model="bonus.amount" @input="calculateFinalSalary()" placeholder="Jumlah" class="w-24 px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-right">
                                <button type="button" @click="removeBonus(index)" class="text-red-500 hover:text-red-700 px-1">
                                    <i class="ph ph-trash"></i>
                                </button>
                            </div>
                        </template>
                        <div class="flex justify-between items-center mt-1" x-show="bonuses.length > 0">
                            <span class="text-xs text-gray-500">Total Bonus</span>
                            <span class="text-xs font-bold text-green-600" x-text="formatCurrency(totalBonus)"></span>
                        </div>
                    </div>

                    <!-- Deductions -->
                    <div class="pt-4 border-t border-gray-100">
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-xs font-semibold text-red-600 uppercase tracking-wider">Potongan</label>
                            <button type="button" @click="addDeduction()" class="text-xs text-red-600 hover:text-red-800 font-medium flex items-center gap-1">
                                <i class="ph ph-plus-circle"></i> Tambah
                            </button>
                        </div>
                        <template x-for="(deduction, index) in deductions" :key="index">
                            <div class="flex gap-2 mb-2">
                                <input type="text" x-model="deduction.description" placeholder="Keterangan" class="flex-1 px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                                <input type="number" x-model="deduction.amount" @input="calculateFinalSalary()" placeholder="Jumlah" class="w-24 px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-right">
                                <button type="button" @click="removeDeduction(index)" class="text-red-500 hover:text-red-700 px-1">
                                    <i class="ph ph-trash"></i>
                                </button>
                            </div>
                        </template>
                        <div class="flex justify-between items-center mt-1" x-show="deductions.length > 0">
                            <span class="text-xs text-gray-500">Total Potongan</span>
                            <span class="text-xs font-bold text-red-600" x-text="formatCurrency(totalDeduction)"></span>
                        </div>
                    </div>

                    <!-- Hidden Inputs for Bonuses and Deductions -->
                    <template x-for="(bonus, index) in bonuses" :key="'b'+index">
                        <div>
                            <input type="hidden" :name="'bonuses['+index+'][description]'" :value="bonus.description">
                            <input type="hidden" :name="'bonuses['+index+'][amount]'" :value="bonus.amount">
                        </div>
                    </template>
                    <template x-for="(deduction, index) in deductions" :key="'d'+index">
                        <div>
                            <input type="hidden" :name="'deductions['+index+'][description]'" :value="deduction.description">
                            <input type="hidden" :name="'deductions['+index+'][amount]'" :value="deduction.amount">
                        </div>
                    </template>

                    <!-- Final Input -->
                    <div class="pt-4 border-t border-gray-100">
                        <label class="block text-sm font-bold text-gray-800 mb-2">Total Gaji Dibayarkan</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                            <input type="number" name="total_salary" x-model="finalSalary" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl text-lg font-bold text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="0">
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="pt-4 border-t border-gray-100">
                        <label class="block text-sm font-bold text-gray-800 mb-2">Catatan (Opsional)</label>
                        <textarea name="notes" rows="2" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="Contoh: Pembayaran via Transfer"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" :disabled="selectedWorkIds.length === 0" class="w-full py-3 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all shadow-lg shadow-primary-600/30 font-bold flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed mt-4">
                        <i class="ph ph-check-circle text-xl"></i>
                        <span>Simpan Penggajian</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function payrollForm() {
        return {
            selectedEmployeeId: '',
            selectedEmployee: null,
            unpaidWorks: [],
            selectedWorkIds: [],
            multiplierValue: 0,
            customBaseSalary: 0,
            estimatedSalary: 0,
            finalSalary: 0,
            startDate: new Date().toISOString().split('T')[0],
            endDate: new Date().toISOString().split('T')[0],

            bonuses: [],
            deductions: [],

            async fetchUnpaidWork() {
                if (!this.selectedEmployeeId) {
                    this.resetForm();
                    return;
                }

                try {
                    const response = await fetch(`/payrolls/get-unpaid-work/${this.selectedEmployeeId}`);
                    const data = await response.json();

                    this.selectedEmployee = data.employee;
                    this.unpaidWorks = data.unpaid_works;
                    
                    // Set default base salary
                    this.customBaseSalary = this.baseSalaryValue;

                    // Auto-select all by default
                    this.selectedWorkIds = this.unpaidWorks.map(w => w.id);
                    this.updateCalculation();

                } catch (error) {
                    console.error('Error fetching unpaid work:', error);
                    alert('Gagal mengambil data produksi.');
                }
            },

            resetForm() {
                this.selectedEmployee = null;
                this.unpaidWorks = [];
                this.selectedWorkIds = [];
                this.multiplierValue = 0;
                this.customBaseSalary = 0;
                this.estimatedSalary = 0;
                this.finalSalary = 0;
                this.bonuses = [];
                this.deductions = [];
            },

            toggleAll(event) {
                if (event.target.checked) {
                    this.selectedWorkIds = this.unpaidWorks.map(w => w.id);
                } else {
                    this.selectedWorkIds = [];
                }
                this.updateCalculation();
            },

            isSelected(id) {
                return this.selectedWorkIds.some(selectedId => selectedId == id);
            },

            get baseSalaryValue() {
                if (!this.selectedEmployee) return 0;
                
                // Strict check based on salary_type
                // If type is 'per_kg', use base_salary_per_kg
                // If type is 'per_day' (or anything else), use base_salary_per_day
                
                if (this.selectedEmployee.salary_type === 'per_kg') {
                    return parseFloat(this.selectedEmployee.base_salary_per_kg) || 0;
                } else {
                    return parseFloat(this.selectedEmployee.base_salary_per_day) || 0;
                }
            },

            updateCalculation() {
                if (!this.selectedEmployee) return;

                // Ensure loose comparison or consistent types because checkbox values might be strings
                const selectedWorks = this.unpaidWorks.filter(w => 
                    this.selectedWorkIds.some(id => id == w.id)
                );
                
                // Update Date Range
                if (selectedWorks.length > 0) {
                    const dates = selectedWorks.map(w => new Date(w.production.production_date));
                    const minDate = new Date(Math.min.apply(null, dates));
                    const maxDate = new Date(Math.max.apply(null, dates));
                    
                    this.startDate = minDate.toISOString().split('T')[0];
                    this.endDate = maxDate.toISOString().split('T')[0];
                }

                // Calculate Multiplier based on Type
                if (this.selectedEmployee.salary_type === 'per_kg') {
                    // For KG: Sum of workload
                    this.multiplierValue = selectedWorks.reduce((sum, w) => sum + parseFloat(w.workload), 0).toFixed(2);
                } else {
                    // For Daily: Count of UNIQUE production dates
                    const uniqueDates = new Set(selectedWorks.map(w => w.production.production_date));
                    this.multiplierValue = uniqueDates.size;
                }

                this.calculateSalary();
            },

            calculateSalary() {
                if (!this.selectedEmployee) return;

                const baseSalary = parseFloat(this.customBaseSalary) || 0;
                const multiplier = parseFloat(this.multiplierValue) || 0;
                
                console.log('Calculating Salary:', { baseSalary, multiplier }); // Debug log

                this.estimatedSalary = baseSalary * multiplier;
                this.calculateFinalSalary();
            },

            addBonus() {
                this.bonuses.push({ description: '', amount: 0 });
            },

            removeBonus(index) {
                this.bonuses.splice(index, 1);
                this.calculateFinalSalary();
            },

            addDeduction() {
                this.deductions.push({ description: '', amount: 0 });
            },

            removeDeduction(index) {
                this.deductions.splice(index, 1);
                this.calculateFinalSalary();
            },

            get totalBonus() {
                return this.bonuses.reduce((sum, b) => sum + (parseFloat(b.amount) || 0), 0);
            },

            get totalDeduction() {
                return this.deductions.reduce((sum, d) => sum + (parseFloat(d.amount) || 0), 0);
            },

            calculateFinalSalary() {
                this.finalSalary = this.estimatedSalary + this.totalBonus - this.totalDeduction;
            },


            get salaryTypeLabel() {
                if (!this.selectedEmployee) return '-';
                return this.selectedEmployee.salary_type === 'per_kg' ? 'Per Kilogram' : 'Harian';
            },

            get baseSalaryLabel() {
                if (!this.selectedEmployee) return '-';
                return this.selectedEmployee.salary_type === 'per_kg' ? 'Per KG' : 'Per Hari';
            },

            get multiplierLabel() {
                if (!this.selectedEmployee) return 'Total';
                return this.selectedEmployee.salary_type === 'per_kg' ? 'Total Berat (KG)' : 'Total Hari Kerja';
            },

            get multiplierUnit() {
                if (!this.selectedEmployee) return '';
                return this.selectedEmployee.salary_type === 'per_kg' ? 'KG' : 'Hari';
            },

            formatNumber(num) {
                return new Intl.NumberFormat('id-ID', { maximumFractionDigits: 2 }).format(num);
            },

            formatCurrency(num) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(num);
            },

            formatDate(dateString) {
                const date = new Date(dateString);
                return date.toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' });
            }
        }
    }
</script>
@endsection
