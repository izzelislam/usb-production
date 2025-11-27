@extends('layouts.app')

@section('title', 'Input Pembelian')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('purchases.index') }}" class="p-2 rounded-lg hover:bg-gray-100 text-gray-600 transition-colors">
            <i class="ph ph-arrow-left text-xl"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Input Pembelian</h1>
            <p class="text-sm text-gray-500 mt-1">Catat transaksi pembelian bahan baku</p>
        </div>
    </div>

    <form action="{{ route('purchases.store') }}" method="POST" id="purchaseForm" class="space-y-6">
        @csrf

        <!-- Header Info Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-100 pb-4 mb-6">Informasi Transaksi</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Invoice Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor NOTA</label>
                    <input type="text" name="invoice_number" value="{{ $invoiceNumber }}" readonly 
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-gray-500 cursor-not-allowed">
                </div>

                <!-- Date -->
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Transaksi <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="date" id="date" value="{{ old('date', date('Y-m-d\TH:i')) }}" required 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    @error('date')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Vendor -->
                <div>
                    <label for="vendor_id" class="block text-sm font-medium text-gray-700 mb-2">Supplier <span class="text-red-500">*</span></label>
                    <select name="vendor_id" id="vendor_id" required 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Pilih Supplier</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                {{ $vendor->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('vendor_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Items Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Daftar Barang</h3>
                <button type="button" onclick="addItemRow()" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-50 text-primary-600 rounded-lg hover:bg-primary-100 transition-colors font-medium text-sm">
                    <i class="ph ph-plus text-lg"></i>
                    Tambah Barang
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full" id="itemsTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-1/3">Produk</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-24">Qty</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-24">Satuan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-40">Harga Satuan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-40">Subtotal</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-16">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100" id="itemsTableBody">
                        <!-- Rows will be added here by JS -->
                    </tbody>
                    <tfoot class="bg-gray-50 font-semibold text-gray-800">
                        <tr>
                            <td colspan="4" class="px-4 py-3 text-right">Grand Total:</td>
                            <td class="px-4 py-3">
                                <span id="grandTotalDisplay">Rp 0</span>
                                <input type="hidden" name="grand_total" id="grandTotalInput" value="0">
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @error('items')
                <p class="mt-2 text-sm text-red-500 text-center">{{ $message }}</p>
            @enderror
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('purchases.index') }}" class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-lg shadow-primary-600/30 font-medium flex items-center gap-2">
                <i class="ph ph-check-circle text-lg"></i>
                Simpan Transaksi
            </button>
        </div>
    </form>
</div>

<script>
    // Pass PHP data to JS
    const products = @json($items);
    let rowCount = 0;

    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
    }

    function addItemRow(productId = null) {
        const tbody = document.getElementById('itemsTableBody');
        const rowId = rowCount++;
        
        const tr = document.createElement('tr');
        tr.id = `row-${rowId}`;
        tr.className = 'hover:bg-gray-50 transition-colors';
        
        // Pre-select product if productId is provided
        let productOptions = `<option value="">Pilih Produk</option>`;
        products.forEach(p => {
            const selected = (productId && p.id == productId) ? 'selected' : '';
            productOptions += `<option value="${p.id}" ${selected}>${p.name}</option>`;
        });

        // Default values
        let defaultUnit = '';
        let defaultPrice = '';
        let defaultQty = 0; // Default qty 0 as requested

        if (productId) {
            const product = products.find(p => p.id == productId);
            if (product) {
                defaultUnit = product.unit;
                defaultPrice = product.default_price || 0;
            }
        }
        
        tr.innerHTML = `
            <td class="px-4 py-3">
                <select name="items[${rowId}][item_id]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" onchange="updateProductDetails(${rowId}, this.value)" required>
                    ${productOptions}
                </select>
            </td>
            <td class="px-4 py-3">
                <input type="number" name="items[${rowId}][qty]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" min="0" step="0.01" value="${defaultQty}" oninput="calculateRowTotal(${rowId})">
            </td>
            <td class="px-4 py-3">
                <input type="text" id="unit-${rowId}" value="${defaultUnit}" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-500 text-sm cursor-not-allowed" readonly>
            </td>
            <td class="px-4 py-3">
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 text-xs">Rp</span>
                    <input type="number" name="items[${rowId}][price]" id="price-${rowId}" value="${defaultPrice}" class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" min="0" oninput="calculateRowTotal(${rowId})" required>
                </div>
            </td>
            <td class="px-4 py-3">
                <span id="subtotal-${rowId}" class="font-medium text-gray-800 text-sm">Rp 0</span>
            </td>
            <td class="px-4 py-3 text-center">
                <button type="button" onclick="removeRow(${rowId})" class="text-red-500 hover:text-red-700 p-1 rounded-md hover:bg-red-50 transition-colors">
                    <i class="ph ph-trash text-lg"></i>
                </button>
            </td>
        `;
        
        tbody.appendChild(tr);
        
        // Calculate initial total if product is pre-selected
        if (productId) {
            calculateRowTotal(rowId);
        }
    }

    function updateProductDetails(rowId, productId) {
        const product = products.find(p => p.id == productId);
        if (product) {
            document.getElementById(`unit-${rowId}`).value = product.unit;
            document.getElementById(`price-${rowId}`).value = product.default_price || 0;
            calculateRowTotal(rowId);
        } else {
            document.getElementById(`unit-${rowId}`).value = '';
            document.getElementById(`price-${rowId}`).value = '';
            calculateRowTotal(rowId);
        }
    }

    function calculateRowTotal(rowId) {
        const qtyInput = document.querySelector(`input[name="items[${rowId}][qty]"]`);
        const priceInput = document.querySelector(`input[name="items[${rowId}][price]"]`);
        const subtotalDisplay = document.getElementById(`subtotal-${rowId}`);
        
        const qty = parseFloat(qtyInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;
        const subtotal = qty * price;
        
        subtotalDisplay.textContent = formatCurrency(subtotal);
        
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let total = 0;
        const rows = document.querySelectorAll('#itemsTableBody tr');
        
        rows.forEach(row => {
            const rowId = row.id.split('-')[1];
            const qty = parseFloat(document.querySelector(`input[name="items[${rowId}][qty]"]`).value) || 0;
            const price = parseFloat(document.querySelector(`input[name="items[${rowId}][price]"]`).value) || 0;
            total += qty * price;
        });
        
        document.getElementById('grandTotalDisplay').textContent = formatCurrency(total);
        document.getElementById('grandTotalInput').value = total;
    }

    function removeRow(rowId) {
        const row = document.getElementById(`row-${rowId}`);
        row.remove();
        calculateGrandTotal();
    }

    // Add initial rows for all products
    document.addEventListener('DOMContentLoaded', () => {
        if (products.length > 0) {
            products.forEach(product => {
                addItemRow(product.id);
            });
        } else {
            addItemRow(); // Fallback if no products
        }
    });
</script>
@endsection
