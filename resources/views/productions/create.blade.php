@extends('layouts.app')

@section('title', 'Input Produksi')

@section('content')
<div class="space-y-6" x-data="productionForm()">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('productions.index') }}" class="p-2 rounded-lg hover:bg-gray-100 text-gray-600 transition-colors">
                <i class="ph ph-arrow-left text-xl"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Input Produksi</h1>
                <p class="text-sm text-gray-500 mt-1">Catat transaksi produksi barang</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="bg-primary-50 text-primary-700 px-4 py-2 rounded-lg text-sm font-medium">
                <i class="ph ph-calendar-blank mr-2"></i>
                {{ date('d F Y') }}
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center gap-3">
            <i class="ph ph-x-circle text-xl"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            <div class="flex items-start gap-3">
                <i class="ph ph-warning text-xl mt-0.5"></i>
                <div>
                    <p class="font-medium mb-2">Terdapat beberapa kesalahan:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('productions.store') }}" method="POST" id="productionForm" class="h-[calc(100vh-12rem)]">
        @csrf
        
        <div class="grid grid-cols-12 gap-6 h-full">
            <!-- Left Column - Purchase Selection (5 cols) -->
            <div class="col-span-12 lg:col-span-5 flex flex-col h-full">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col h-full overflow-hidden">
                    <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="text-lg font-bold text-gray-800">1. Pilih Pembelian</h3>
                            <span class="text-xs font-medium px-2.5 py-1 bg-blue-100 text-blue-700 rounded-full" x-text="selectedCount + ' Dipilih'"></span>
                        </div>
                        <p class="text-sm text-gray-500">Pilih bahan baku yang akan diolah</p>
                    </div>
                    
                    <div class="flex-1 overflow-y-auto p-4 space-y-3 custom-scroll" style="scrollbar-width: thin;">
                        <template x-for="purchase in purchases" :key="purchase.id">
                            <div class="purchase-card group relative border-2 rounded-xl p-4 cursor-pointer transition-all hover:shadow-md bg-white" 
                                 :class="isSelected(purchase.id) ? 'border-blue-500 bg-blue-50' : 'border-gray-100 hover:border-primary-200'"
                                 @click="togglePurchase(purchase.id)">
                                
                                <!-- Hidden Checkbox for Form Submission -->
                                <input type="checkbox" name="purchase_ids[]" :value="purchase.id" :checked="isSelected(purchase.id)" class="hidden">
                                
                                <!-- Selection Indicator -->
                                <div class="absolute top-4 right-4 w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors"
                                     :class="isSelected(purchase.id) ? 'bg-blue-500 border-blue-500' : 'border-gray-300'">
                                    <i class="ph ph-check text-white text-sm transition-transform" 
                                       :class="isSelected(purchase.id) ? 'scale-100' : 'scale-0'"></i>
                                </div>

                                <div class="pr-8">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="font-bold text-gray-800 text-lg" x-text="purchase.vendor.name"></span>
                                        <span class="text-xs text-gray-400">•</span>
                                        <span class="text-xs text-gray-500" x-text="formatDate(purchase.date)"></span>
                                    </div>
                                    
                                    <div class="bg-gray-50 rounded-lg p-3 mb-3">
                                        <div class="flex flex-wrap gap-2">
                                            <template x-for="item in purchase.items" :key="item.id">
                                                <div class="flex items-center gap-2 bg-white px-2 py-1 rounded border border-gray-200 shadow-sm">
                                                    <span class="text-sm font-medium text-gray-700" x-text="item.item.name"></span>
                                                    <span class="text-xs font-bold text-primary-600 bg-primary-50 px-1.5 py-0.5 rounded" x-text="item.qty + ' KG'"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    <div class="flex justify-between items-center">
                                        <div class="text-xs text-gray-500">Total Berat: <span class="font-bold text-gray-700" x-text="calculatePurchaseWeight(purchase) + ' KG'"></span></div>
                                        <div class="text-sm font-bold text-gray-900" x-text="formatCurrency(purchase.grand_total)"></div>
                                    </div>
                                </div>
                            </div>
                        </template>
                        
                        <div x-show="purchases.length === 0" class="h-full flex flex-col items-center justify-center text-gray-400 p-8">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                <i class="ph ph-package text-3xl text-gray-300"></i>
                            </div>
                            <p class="text-center font-medium">Tidak ada pembelian tersedia</p>
                            <p class="text-sm text-center mt-1">Semua pembelian telah diproses</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Middle Column - Workers (4 cols) -->
            <div class="col-span-12 lg:col-span-4 flex flex-col h-full">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col h-full overflow-hidden">
                    <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="text-lg font-bold text-gray-800">2. Pembagian Kerja</h3>
                            <span class="text-xs font-medium px-2.5 py-1 bg-green-100 text-green-700 rounded-full" x-text="activeWorkerCount + ' Aktif'"></span>
                        </div>
                        <p class="text-sm text-gray-500">Distribusi beban kerja otomatis</p>
                    </div>

                    <div class="flex-1 overflow-y-auto p-4 space-y-2 custom-scroll" style="scrollbar-width: thin;">
                        <template x-for="employee in employees" :key="employee.id">
                            <div class="worker-item group flex items-center justify-between p-3 rounded-lg border transition-all bg-white"
                                 :class="isActiveWorker(employee.id) ? 'border-gray-200 hover:border-primary-300' : 'opacity-60 bg-gray-50 border-dashed border-gray-200'">
                                <div class="flex items-center gap-3">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" 
                                               name="employee_ids[]" 
                                               :value="employee.id" 
                                               :id="'employee_' + employee.id"
                                               :checked="isActiveWorker(employee.id)"
                                               @change="toggleWorker(employee.id)"
                                               class="peer w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500 cursor-pointer transition-all">
                                    </div>
                                    <label :for="'employee_' + employee.id" class="text-sm font-medium text-gray-700 cursor-pointer select-none group-hover:text-primary-700" x-text="employee.name"></label>
                                </div>
                                
                                <div class="flex items-center gap-2">
                                    <div class="text-right">
                                        <span class="block text-[10px] text-gray-400 uppercase tracking-wider font-semibold">Beban</span>
                                        <span class="text-sm font-bold text-primary-600" x-text="formatNumber(getWorkload(employee.id))"></span>
                                        <span class="text-xs text-gray-500">KG</span>
                                    </div>
                                    <input type="hidden" :name="'workloads[' + employee.id + ']'" :value="getWorkload(employee.id)">
                                </div>
                            </div>
                        </template>

                        <div x-show="employees.length === 0" class="text-center py-8 text-gray-500">
                            <i class="ph ph-users text-3xl mb-2 block text-gray-300"></i>
                            <p class="text-sm">Tidak ada karyawan aktif</p>
                        </div>
                    </div>
                    
                    <div class="p-4 bg-yellow-50 border-t border-yellow-100">
                        <div class="flex gap-2 text-yellow-800 text-xs">
                            <i class="ph ph-info text-base"></i>
                            <p>Beban kerja akan dibagi rata secara otomatis ke karyawan yang dipilih.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Summary & Actions (3 cols) -->
            <div class="col-span-12 lg:col-span-3 flex flex-col h-full">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col h-full">
                    <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-lg font-bold text-gray-800">3. Ringkasan</h3>
                    </div>

                    <div class="p-5 flex-1 overflow-y-auto custom-scroll">
                        <!-- Date Input -->
                        <div class="mb-6">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Tanggal Produksi</label>
                            <input type="date" name="production_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" value="{{ old('production_date', date('Y-m-d')) }}" required>
                        </div>

                        <!-- Summary Stats -->
                        <div class="space-y-4 mb-6">
                            <div class="bg-primary-50 rounded-lg p-4 border border-primary-100">
                                <p class="text-xs text-primary-600 font-medium mb-1">Total Barang</p>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-2xl font-bold text-primary-700" x-text="formatNumber(totalKue)"></span>
                                    <span class="text-sm text-primary-600 font-medium">KG</span>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <p class="text-xs text-gray-500 font-medium mb-1">Estimasi Nilai</p>
                                <span class="text-xl font-bold text-gray-800" x-text="formatCurrency(grandTotal)"></span>
                            </div>
                        </div>

                        <!-- Item Breakdown -->
                        <div x-show="Object.keys(itemAggregates).length > 0">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Rincian Barang</label>
                            <div class="space-y-2">
                                <template x-for="(qty, name) in itemAggregates" :key="name">
                                    <div class="flex justify-between items-center p-2 bg-gray-50 rounded border border-gray-100">
                                        <span class="text-sm text-gray-700 font-medium" x-text="name"></span>
                                        <span class="text-sm font-bold text-primary-600" x-text="formatNumber(qty) + ' KG'"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                        
                        <!-- Notes -->
                        <div class="mt-6">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Catatan</label>
                            <textarea name="notes" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 resize-none" rows="3" placeholder="Tambahkan catatan...">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="p-5 border-t border-gray-100 bg-gray-50">
                        <button type="submit" class="w-full py-3 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all shadow-lg shadow-primary-600/30 font-bold flex items-center justify-center gap-2 group">
                            <span>Simpan Produksi</span>
                            <i class="ph ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function productionForm() {
        return {
            purchases: @json($purchases),
            employees: @json($employees),
            selectedPurchaseIds: [],
            activeWorkerIds: [],

            init() {
                // Initialize active workers (all active by default)
                this.activeWorkerIds = this.employees.map(e => e.id);
            },

            togglePurchase(id) {
                if (this.selectedPurchaseIds.includes(id)) {
                    this.selectedPurchaseIds = this.selectedPurchaseIds.filter(pid => pid !== id);
                } else {
                    this.selectedPurchaseIds.push(id);
                }
            },

            isSelected(id) {
                return this.selectedPurchaseIds.includes(id);
            },

            toggleWorker(id) {
                if (this.activeWorkerIds.includes(id)) {
                    this.activeWorkerIds = this.activeWorkerIds.filter(wid => wid !== id);
                } else {
                    this.activeWorkerIds.push(id);
                }
            },

            isActiveWorker(id) {
                return this.activeWorkerIds.includes(id);
            },

            get selectedCount() {
                return this.selectedPurchaseIds.length;
            },

            get activeWorkerCount() {
                return this.activeWorkerIds.length;
            },

            get totalKue() {
                let total = 0;
                this.purchases.forEach(p => {
                    if (this.selectedPurchaseIds.includes(p.id)) {
                        p.items.forEach(item => {
                            total += parseFloat(item.qty);
                        });
                    }
                });
                return total;
            },

            get grandTotal() {
                let total = 0;
                this.purchases.forEach(p => {
                    if (this.selectedPurchaseIds.includes(p.id)) {
                        total += parseFloat(p.grand_total);
                    }
                });
                return total;
            },

            get itemAggregates() {
                let aggregates = {};
                this.purchases.forEach(p => {
                    if (this.selectedPurchaseIds.includes(p.id)) {
                        p.items.forEach(item => {
                            const qty = parseFloat(item.qty);
                            if (aggregates[item.item.name]) {
                                aggregates[item.item.name] += qty;
                            } else {
                                aggregates[item.item.name] = qty;
                            }
                        });
                    }
                });
                return aggregates;
            },

            getWorkload(employeeId) {
                if (!this.isActiveWorker(employeeId) || this.activeWorkerCount === 0) {
                    return 0;
                }
                return this.totalKue / this.activeWorkerCount;
            },

            calculatePurchaseWeight(purchase) {
                return purchase.items.reduce((sum, item) => sum + parseFloat(item.qty), 0);
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
