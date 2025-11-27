<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji - {{ $payroll->employee->name }}</title>
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
            background-color: #f3f4f6; /* Light gray background for preview */
        }
        .slip-container {
            max-width: 210mm; /* A4 width */
            margin: 0 auto;
            background-color: #fff;
            padding: 40px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-radius: 8px;
            position: relative;
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
            color: #059669; /* Green color for final amount */
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
                margin: 0;
                padding: 40px; /* Keep the internal padding */
                border: none;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>
    <div class="slip-container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <h1>USB CAKE</h1>
                <p>Jl. Contoh No. 123, Kota Bandung, Jawa Barat</p>
                <p>Telp: (022) 1234567 | Email: hr@usbcake.com</p>
            </div>
            <div class="slip-title">
                <h2>SLIP GAJI</h2>
                <p>#PAY-{{ str_pad($payroll->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>

        <!-- Info Grid -->
        <div class="info-grid">
            <div class="info-column">
                <h3>Data Karyawan</h3>
                <div class="info-row">
                    <span class="info-label">Nama</span>
                    <span class="info-value">{{ $payroll->employee->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">ID Karyawan</span>
                    <span class="info-value">EMP-{{ str_pad($payroll->employee->id, 4, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Jabatan</span>
                    <span class="info-value">Staff Produksi</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tipe Gaji</span>
                    <span class="info-value">{{ $payroll->salary_type == 'per_kg' ? 'Borongan (Per KG)' : 'Harian' }}</span>
                </div>
            </div>
            <div class="info-column">
                <h3>Periode Pembayaran</h3>
                <div class="info-row">
                    <span class="info-label">Dari Tanggal</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($payroll->start_date)->format('d M Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Sampai Tanggal</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($payroll->end_date)->format('d M Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal Cetak</span>
                    <span class="info-value">{{ now()->format('d M Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="info-value" style="color: {{ $payroll->status == 'paid' ? '#059669' : '#d97706' }}">{{ $payroll->status == 'paid' ? 'LUNAS' : 'BELUM DIBAYAR' }}</span>
                </div>
            </div>
        </div>

        <!-- Earnings -->
        <div class="table-section">
            <table>
                <thead>
                    <tr>
                        <th>Keterangan Penerimaan</th>
                        <th class="text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <strong>Gaji Pokok</strong><br>
                            <span style="font-size: 11px; color: #6b7280;">
                                {{ number_format($payroll->total_workload, 2) }} {{ $payroll->salary_type == 'per_kg' ? 'KG' : 'Hari' }} x Rp {{ number_format($payroll->base_salary, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="text-right amount-col">Rp {{ number_format($payroll->details->sum('salary_amount'), 0, ',', '.') }}</td>
                    </tr>
                    @foreach($payroll->bonuses as $bonus)
                        <tr>
                            <td>
                                <strong>Bonus</strong><br>
                                <span style="font-size: 11px; color: #6b7280;">{{ $bonus->description }}</span>
                            </td>
                            <td class="text-right amount-col">Rp {{ number_format($bonus->amount, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Deductions -->
        @if($payroll->deductions->count() > 0)
            <div class="table-section">
                <table>
                    <thead>
                        <tr>
                            <th style="color: #dc2626;">Keterangan Potongan</th>
                            <th class="text-right" style="color: #dc2626;">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payroll->deductions as $deduction)
                            <tr>
                                <td>{{ $deduction->description }}</td>
                                <td class="text-right amount-col" style="color: #dc2626;">(Rp {{ number_format($deduction->amount, 0, ',', '.') }})</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Summary -->
        <div class="total-section">
            <div class="total-row">
                <span class="total-label">Total Penerimaan</span>
                <span class="total-value">Rp {{ number_format($payroll->details->sum('salary_amount') + $payroll->bonuses->sum('amount'), 0, ',', '.') }}</span>
            </div>
            @if($payroll->deductions->count() > 0)
                <div class="total-row">
                    <span class="total-label">Total Potongan</span>
                    <span class="total-value" style="color: #dc2626;">(Rp {{ number_format($payroll->deductions->sum('amount'), 0, ',', '.') }})</span>
                </div>
            @endif
            <div class="total-row final">
                <span class="total-label">TOTAL DITERIMA (TAKE HOME PAY)</span>
                <span class="total-value">Rp {{ number_format($payroll->final_salary, 0, ',', '.') }}</span>
            </div>
            <div class="terbilang">
                # {{ ucwords(\NumberFormatter::create('id', \NumberFormatter::SPELLOUT)->format($payroll->final_salary)) }} Rupiah #
            </div>
        </div>

        <!-- Signatures -->
        <div class="footer">
            <div class="signature-box">
                <div class="signature-title">Penerima</div>
                <div class="signature-line">{{ $payroll->employee->name }}</div>
            </div>
            <div class="signature-box">
                <div class="signature-title">Disetujui Oleh</div>
                <div class="signature-line">Manager Keuangan</div>
            </div>
        </div>
    </div>
</body>
</html>