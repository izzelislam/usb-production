<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Pembelian</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #1f2937;
            line-height: 1.5;
            margin: 0;
            padding: 40px;
            background-color: #f3f4f6;
        }
        .page-break {
            page-break-after: always;
        }
        .slip-container {
            max-width: 210mm;
            margin: 0 auto 40px auto;
            background-color: #fff;
            padding: 40px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            position: relative;
            min-height: 200mm;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .company-info h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            color: #111827;
            letter-spacing: -0.5px;
        }
        .company-info p {
            margin: 4px 0 0;
            color: #6b7280;
            font-size: 13px;
        }
        .slip-title {
            text-align: right;
        }
        .slip-title h2 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .slip-title p {
            margin: 4px 0 0;
            color: #6b7280;
            font-size: 13px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 30px;
        }
        .info-column h3 {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: #9ca3af;
            margin: 0 0 10px 0;
            letter-spacing: 0.5px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            border-bottom: 1px dashed #f3f4f6;
            padding-bottom: 4px;
        }
        .info-label {
            font-weight: 500;
            color: #6b7280;
        }
        .info-value {
            font-weight: 600;
            color: #111827;
        }
        .table-section {
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            text-align: left;
            padding: 12px 16px;
            background-color: #f9fafb;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: #6b7280;
            border-bottom: 1px solid #e5e7eb;
        }
        td {
            padding: 12px 16px;
            border-bottom: 1px solid #f3f4f6;
            color: #374151;
        }
        .text-right {
            text-align: right;
        }
        .amount-col {
            width: 150px;
            font-family: 'Courier New', Courier, monospace;
            font-weight: 600;
        }
        .total-section {
            background-color: #f9fafb;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
            break-inside: avoid;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .total-row.final {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #e5e7eb;
            margin-bottom: 0;
        }
        .total-label {
            font-size: 13px;
            font-weight: 600;
            color: #4b5563;
        }
        .total-value {
            font-size: 14px;
            font-weight: 700;
            color: #111827;
        }
        .final .total-label {
            font-size: 16px;
            color: #111827;
        }
        .final .total-value {
            font-size: 20px;
            color: #059669;
        }
        .terbilang {
            margin-top: 10px;
            font-style: italic;
            color: #6b7280;
            font-size: 11px;
            text-align: right;
        }
        .footer {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
            padding-top: 20px;
            break-inside: avoid;
        }
        .signature-box {
            text-align: center;
            width: 200px;
        }
        .signature-line {
            margin-top: 80px;
            border-top: 1px solid #d1d5db;
            padding-top: 8px;
            font-weight: 600;
            color: #111827;
        }
        .signature-title {
            font-size: 11px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        @media print {
            body {
                background-color: #fff;
                padding: 20px; /* Maintain some padding around the page */
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .slip-container {
                box-shadow: none;
                margin: 0 0 20px 0; /* Add margin bottom for multiple items */
                padding: 40px; /* Keep the internal padding */
                border: none;
                border-radius: 0;
                min-height: auto;
                page-break-after: always;
            }
            .slip-container:last-child {
                page-break-after: auto;
            }
        }
    </style>
</head>
<body>
    @foreach($purchases as $purchase)
    <div class="slip-container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <h1>USB CAKE</h1>
                <p>Jl. Contoh No. 123, Kota Bandung, Jawa Barat</p>
                <p>Telp: (022) 1234567 | Email: purchasing@usbcake.com</p>
            </div>
            <div class="slip-title">
                <h2>NOTA PEMBELIAN</h2>
                <p>#{{ $purchase->invoice_number }}</p>
            </div>
        </div>

        <!-- Info Grid -->
        <div class="info-grid">
            <div class="info-column">
                <h3>Data Supplier</h3>
                <div class="info-row">
                    <span class="info-label">Nama</span>
                    <span class="info-value">{{ $purchase->vendor->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Alamat</span>
                    <span class="info-value">{{ $purchase->vendor->address ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Telepon</span>
                    <span class="info-value">{{ $purchase->vendor->phone ?? '-' }}</span>
                </div>
            </div>
            <div class="info-column">
                <h3>Detail Transaksi</h3>
                <div class="info-row">
                    <span class="info-label">Tanggal Transaksi</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($purchase->date)->format('d M Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal Cetak</span>
                    <span class="info-value">{{ now()->format('d M Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Items -->
        <div class="table-section">
            <table>
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th class="text-right">Harga Satuan</th>
                        <th class="text-right">Qty</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchase->items as $item)
                        <tr>
                            <td>
                                <strong>{{ $item->item->name }}</strong>
                            </td>
                            <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($item->qty, 0, ',', '.') }}</td>
                            <td class="text-right amount-col">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Summary -->
        <div class="total-section">
            <div class="total-row final">
                <span class="total-label">TOTAL PEMBAYARAN</span>
                <span class="total-value">Rp {{ number_format($purchase->grand_total, 0, ',', '.') }}</span>
            </div>
            <div class="terbilang">
                # {{ ucwords(\NumberFormatter::create('id', \NumberFormatter::SPELLOUT)->format($purchase->grand_total)) }} Rupiah #
            </div>
        </div>

        <!-- Signatures -->
        <div class="footer">
            <div class="signature-box">
                <div class="signature-title">Supplier</div>
                <div class="signature-line">{{ $purchase->vendor->name }}</div>
            </div>
            <div class="signature-box">
                <div class="signature-title">Penerima</div>
                <div class="signature-line">Admin Gudang</div>
            </div>
        </div>
    </div>
    @endforeach
</body>
</html>
