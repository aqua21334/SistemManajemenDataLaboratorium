<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data PNBP - Lab Rawa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body { background-color: #DAE4EB; font-family: 'Inter', sans-serif; }
        
        /* Sidebar */
        .sidebar { background-color: #345E6F; min-height: 100vh; color: white; padding: 20px 10px; width: 240px; position: fixed; z-index: 100; }
        .sidebar-logo-text { font-size: 10px; line-height: 1.3; color: #E2E8F0; margin-top: 10px; }
        .nav-link { color: #CBD5E0; font-size: 14px; padding: 8px 15px; margin-bottom: 5px; border-radius: 8px; }
        .nav-link:hover, .nav-link.active { background-color: rgba(255,255,255,0.1); color: white; }
        .main-wrapper { margin-left: 240px; padding: 25px; }

        /* Topbar */
        .topbar-card { background: white; padding: 15px 30px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; border-radius: 0 0 10px 10px; }

        /* Content Container */
        .edit-container { background-color: white; border-radius: 10px; padding: 30px; border: 1px solid #dee2e6; }
        
        .form-title { color: #345E6F; font-weight: bold; font-size: 18px; margin-bottom: 25px; }
        
        .form-group-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-group-row.full {
            grid-template-columns: 1fr;
        }

        .form-label { 
            font-weight: bold; 
            color: #345E6F; 
            font-size: 14px;
            margin-bottom: 8px;
        }

        .form-control {
            border: 2px solid #333;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #345E6F;
            box-shadow: 0 0 0 0.2rem rgba(52, 94, 111, 0.25);
        }

        .form-control:disabled, .form-control[readonly] {
            background-color: #f5f5f5;
            color: #666;
        }

        /* Button Area */
        .button-area {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
        }

        .btn-custom {
            border: 2px solid #333;
            border-radius: 8px;
            padding: 10px 25px;
            font-weight: bold;
            font-size: 14px;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-kembali {
            background: white;
            color: #333;
        }

        .btn-kembali:hover {
            background: #f5f5f5;
        }

        .btn-simpan {
            background: #1F4557;
            color: white;
            border-color: #1F4557;
        }

        .btn-simpan:hover {
            background: #152b37;
        }

        .badge-status {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-lunas { background-color: #28a745; color: white; }
        .status-belum-lunas { background-color: #ffc107; color: #333; }
        .status-belum-dibayar { background-color: #dc3545; color: white; }

        /* Info Box */
        .info-box {
            background-color: #f0f7ff;
            border-left: 4px solid #0d6efd;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 13px;
            color: #0c5de4;
        }
    </style>
</head>
<body>

<div class="sidebar shadow">
    <div class="text-center mb-5 px-3">
        <img src="{{ asset('images/logo-btr.jpg') }}" width="60" class="rounded-circle border border-2 border-white">
        <p class="sidebar-logo-text fw-semibold">Sistem Manajemen Data<br>Laboratorium Balai Teknik Rawa</p>
    </div>
    <ul class="nav flex-column px-2">
        <li class="nav-item"><a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="bi bi-grid-fill me-2"></i> Dashboard</a></li>
        <li class="nav-item"><a href="{{ route('admin.pegawai') }}" class="nav-link"><i class="bi bi-people-fill me-2"></i> Pegawai</a></li>
        <li class="nav-item"><a href="{{ route('admin.peralatan.index') }}" class="nav-link"><i class="bi bi-tools me-2"></i> Peralatan</a></li>
        <li class="nav-item"><a href="{{ route('admin.sop.index') }}" class="nav-link"><i class="bi bi-file-earmark-check-fill me-2"></i> Daftar SOP</a></li>
        <li class="nav-item"><a href="{{ route('admin.permintaan.index') }}" class="nav-link"><i class="bi bi-file-earmark-text-fill me-2"></i> Laporan</a></li>
        <li class="nav-item"><a href="{{ route('admin.riwayat-penelitian.index') }}" class="nav-link"><i class="bi bi-file-earmark-bar-graph-fill me-2"></i> Riwayat Penelitian</a></li>
        <li class="nav-item"><a href="{{ route('admin.pnbp.index') }}" class="nav-link active"><i class="bi bi-cash-stack me-2"></i> PNBP</a></li>
        <li class="nav-item"><a href="{{ route('admin.riwayat-absensi.index') }}" class="nav-link"><i class="bi bi-person-badge-fill me-2"></i> Riwayat Absensi</a></li>
    </ul>
    <div style="position: absolute; bottom: 30px; left: 25px;">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="border-0 bg-transparent text-white d-flex align-items-center">
                <i class="bi bi-box-arrow-left fs-4 me-2"></i> <span class="fw-bold">Keluar</span>
            </button>
        </form>
    </div>
</div>

<div class="main-wrapper">
    <!-- Topbar -->
    <div class="topbar-card shadow-sm">
        <h3 class="fw-light m-0">Selamat Datang</h3>
        <div class="d-flex align-items-center">
            <div class="text-end me-3">
                <p class="m-0 fw-bold" style="font-size: 14px;">{{ Auth::user()->nama }}</p>
                <small class="text-muted">Admin</small>
            </div>
            <img src="https://ui-avatars.com/api/?name={{ Auth::user()->nama }}&background=E53E3E&color=fff" class="rounded-circle border border-2 border-danger" width="45">
        </div>
    </div>

    <!-- Edit Form -->
    <div class="edit-container shadow-sm">
        <h5 class="form-title"><i class="bi bi-pencil-square me-2"></i>Edit Data PNBP</h5>

        <div class="info-box">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Info:</strong> Hanya status pembayaran yang dapat diubah. Data lainnya bersifat read-only.
        </div>

        <form action="{{ route('admin.pnbp.update', $pnbp->id_pnbp) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Row 1: Id Permintaan & Id PNBP -->
            <div class="form-group-row">
                <div>
                    <label class="form-label">Id Permintaan</label>
                    <input type="text" class="form-control" value="{{ $pnbp->id_permintaan }}" readonly>
                </div>
                <div>
                    <label class="form-label">Id PNBP</label>
                    <input type="text" class="form-control" value="{{ $pnbp->id_pnbp }}" readonly>
                </div>
            </div>

            <!-- Row 2: Jenis Permintaan & Pemohon -->
            <div class="form-group-row">
                <div>
                    <label class="form-label">Jenis Permintaan</label>
                    <input type="text" class="form-control" value="{{ $pnbp->permintaanLayanan->jenis_permintaan ?? '-' }}" readonly>
                </div>
                <div>
                    <label class="form-label">Pemohon</label>
                    <input type="text" class="form-control" value="{{ $pnbp->permintaanLayanan->pemohon ?? ($pnbp->permintaanLayanan->user->nama ?? '-') }}" readonly>
                </div>
            </div>

            <!-- Row 3: Total Biaya & Jumlah Bayar -->
            <div class="form-group-row">
                <div>
                    <label class="form-label">Total Biaya</label>
                    <input type="text" class="form-control" value="Rp {{ number_format($pnbp->total_biaya, 0, ',', '.') }}" readonly>
                </div>
                <div>
                    <label class="form-label">Jumlah Bayar</label>
                    <input type="text" class="form-control" value="Rp {{ number_format($pnbp->jumlah_bayar, 0, ',', '.') }}" readonly>
                </div>
            </div>

            <!-- Row 4: Sisa Tagihan & Tanggal Bayar -->
            <div class="form-group-row">
                <div>
                    <label class="form-label">Sisa Tagihan</label>
                    <input type="text" class="form-control" value="Rp {{ number_format($pnbp->sisa_tagihan, 0, ',', '.') }}" readonly>
                </div>
                <div>
                    <label class="form-label">Tanggal Bayar</label>
                    <input type="text" class="form-control" value="{{ $pnbp->tanggal_bayar ? \Carbon\Carbon::parse($pnbp->tanggal_bayar)->format('d/m/Y') : '-' }}" readonly>
                </div>
            </div>

            <!-- Row 5: Status Pembayaran (EDITABLE) -->
            <div class="form-group-row full">
                <div>
                    <label class="form-label">Status Pembayaran <span style="color: #dc3545;">*</span></label>
                    <select class="form-control" name="status_pembayaran" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="Belum Dibayar" {{ $pnbp->status_pembayaran === 'Belum Dibayar' ? 'selected' : '' }}>
                            Belum Dibayar
                        </option>
                        <option value="Belum Lunas" {{ $pnbp->status_pembayaran === 'Belum Lunas' ? 'selected' : '' }}>
                            Belum Lunas
                        </option>
                        <option value="Lunas" {{ $pnbp->status_pembayaran === 'Lunas' ? 'selected' : '' }}>
                            Lunas
                        </option>
                    </select>
                </div>
            </div>

            <!-- Buttons -->
            <div class="button-area">
                <a href="{{ route('admin.pnbp.index') }}" class="btn-custom btn-kembali">Kembali</a>
                <button type="submit" class="btn-custom btn-simpan">Simpan Perubahan</button>
            </div>
        </form>
    </div>

</div>

</body>
</html>
