<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice PNBP - Lab Rawa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: 'Arial', sans-serif;
        }
        .invoice-container {
            background: white;
            padding: 40px;
            margin: 20px auto;
            max-width: 800px;
            border: 2px solid #333;
            border-radius: 10px;
        }
        .invoice-header {
            text-align: center;
            border-bottom: 2px solid #345E6F;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .invoice-header h2 {
            color: #345E6F;
            margin: 0;
            font-weight: bold;
        }
        .invoice-header p {
            color: #666;
            margin: 5px 0;
            font-size: 14px;
        }
        .invoice-details {
            margin-bottom: 30px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #ddd;
        }
        .detail-label {
            font-weight: bold;
            color: #345E6F;
            width: 40%;
        }
        .detail-value {
            text-align: right;
            width: 60%;
        }
        .invoice-table {
            width: 100%;
            margin: 30px 0;
        }
        .invoice-table th {
            background-color: #345E6F;
            color: white;
            padding: 12px;
            text-align: center;
            font-weight: bold;
        }
        .invoice-table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        .total-section {
            background-color: #f9f9f9;
            padding: 20px;
            border: 2px solid #345E6F;
            border-radius: 5px;
            margin: 30px 0;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 16px;
            font-weight: bold;
        }
        .total-amount {
            color: #E53E3E;
            font-size: 18px;
        }
        .badge-status {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
        }
        .status-lunas {
            background-color: #28a745;
            color: white;
        }
        .status-belum {
            background-color: #ffc107;
            color: #333;
        }
        .status-belum-lunas {
            background-color: #ffc107;
            color: #333;
        }
        .footer-section {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
            color: #666;
            font-size: 12px;
        }
        .print-btn {
            text-align: center;
            margin: 20px 0;
        }
        .print-btn button {
            background-color: #345E6F;
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        .print-btn button:hover {
            background-color: #1F4557;
        }
        @media print {
            .print-btn {
                display: none;
            }
            body {
                background-color: white;
            }
        }
    </style>
</head>
<body>

<div class="invoice-container">
    <!-- Header -->
    <div class="invoice-header">
        <h2>INVOICE PNBP</h2>
        <p>Laboratorium Balai Teknik Rawa</p>
        <p>Nomor Invoice: <strong>#{{ $pnbp->id_pnbp }}</strong></p>
    </div>

    <!-- Detail Permintaan -->
    <div class="invoice-details">
        <h5 style="color: #345E6F; font-weight: bold; margin-bottom: 15px;">Informasi Permintaan</h5>
        
        <div class="detail-row">
            <div class="detail-label">ID Permintaan</div>
            <div class="detail-value">{{ $pnbp->id_permintaan }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Jenis Permintaan</div>
            <div class="detail-value">{{ $pnbp->permintaanLayanan->jenis_permintaan ?? '-' }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Pemohon</div>
            <div class="detail-value">{{ $pnbp->permintaanLayanan->pemohon ?? $pnbp->permintaanLayanan->user->nama ?? '-' }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">No. HP</div>
            <div class="detail-value">{{ $pnbp->permintaanLayanan->no_hp ?? '-' }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Tanggal Permintaan</div>
            <div class="detail-value">{{ \Carbon\Carbon::parse($pnbp->permintaanLayanan->tanggal_permintaan)->format('d/m/Y') }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Tanggal Invoice</div>
            <div class="detail-value">{{ \Carbon\Carbon::parse($pnbp->created_at)->format('d/m/Y H:i') }}</div>
        </div>
    </div>

    <!-- Tabel Detail Tagihan -->
    <table class="invoice-table">
        <thead>
            <tr>
                <th>Deskripsi Layanan</th>
                <th>Harga</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: left;">{{ $pnbp->permintaanLayanan->jenis_permintaan ?? 'Layanan' }}</td>
                <td>Rp {{ number_format($pnbp->total_biaya, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Total Section -->
    <div class="total-section">
        <div class="total-row">
            <span>Total Biaya:</span>
            <span class="total-amount">Rp {{ number_format($pnbp->total_biaya, 0, ',', '.') }}</span>
        </div>

        <div class="total-row">
            <span>Jumlah Bayar:</span>
            <span>Rp {{ number_format($pnbp->jumlah_bayar, 0, ',', '.') }}</span>
        </div>

        <div class="total-row">
            <span>Sisa Tagihan:</span>
            <span class="total-amount">Rp {{ number_format($pnbp->sisa_tagihan, 0, ',', '.') }}</span>
        </div>

        <div class="total-row" style="margin-top: 15px; border-top: 1px solid #ddd; padding-top: 15px;">
            <span>Status Pembayaran:</span>
            <span>
                @if($pnbp->status_pembayaran === 'Lunas')
                    <span class="badge-status status-lunas">✓ LUNAS</span>
                @elseif($pnbp->status_pembayaran === 'Belum Lunas')
                    <span class="badge-status status-belum-lunas">⏳ BELUM LUNAS</span>
                @else
                    <span class="badge-status status-belum">⚠ BELUM DIBAYAR</span>
                @endif
            </span>
        </div>
    </div>

    <!-- Catatan -->
    <div style="background-color: #f0f0f0; padding: 15px; border-radius: 5px; margin: 20px 0;">
        <p style="margin: 0; color: #666; font-size: 13px;">
            <strong>Catatan:</strong> Mohon lakukan pembayaran sebelum tanggal yang telah ditentukan. Kirimkan bukti pembayaran ke sistem untuk memverifikasi pembayaran Anda.
        </p>
    </div>

    <!-- Footer -->
    <div class="footer-section">
        <p>Terima kasih telah menggunakan layanan kami</p>
        <p>Invoice ini otomatis dibuat oleh Sistem Manajemen Data Laboratorium</p>
        <p>© 2026 Laboratorium Balai Teknik Rawa. All Rights Reserved.</p>
    </div>
</div>

<!-- Print Button -->
<div class="print-btn">
    <button onclick="window.print()">🖨️ Cetak Invoice</button>
    <a href="{{ route('admin.pnbp.index') }}" style="margin-left: 10px; background-color: #6c757d; padding: 10px 30px; color: white; text-decoration: none; border-radius: 5px; display: inline-block;">← Kembali</a>
</div>

</body>
</html>
