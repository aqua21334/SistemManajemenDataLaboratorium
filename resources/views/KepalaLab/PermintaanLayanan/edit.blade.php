<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Laporan - Lab Rawa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #DAE4EB; font-family: 'Inter', sans-serif; overflow-x: hidden; }
        .sidebar { background-color: #345E6F; min-height: 100vh; color: white; padding: 20px 0 20px 10px; width: 240px; position: fixed; z-index: 100; }
        .sidebar-logo-text { font-size: 10px; line-height: 1.3; color: #E2E8F0; margin-top: 10px; text-align: center; }
        .nav-link { color: #CBD5E0; font-size: 14px; padding: 10px 15px; margin-bottom: 5px; border-radius: 8px 0 0 8px; transition: 0.3s; margin-left: 10px; }
        .nav-link:hover { background-color: rgba(255,255,255,0.1); color: white; }
        .nav-link.active { background-color: #DAE4EB; color: #345E6F; font-weight: bold; position: relative; }
        .nav-link.active::after { content: ''; position: absolute; right: 0; top: 0; bottom: 0; width: 10px; background-color: #DAE4EB; margin-right: -10px; }
        .main-wrapper { margin-left: 240px; padding: 0; }
        .topbar-card { background: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;}
        .dashboard-container { padding: 0 25px 25px 25px; }
        .form-card { background: white; border-radius: 12px; padding: 30px 40px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .form-title { color: #345E6F; font-weight: bold; font-size: 18px; margin-bottom: 30px; }
        .custom-label { color: #345E6F; font-weight: bold; font-size: 15px; margin-bottom: 8px; display: block; }
        .custom-input { border: 2px solid #333; border-radius: 6px; padding: 10px 15px; width: 100%; outline: none; font-size: 14px; background-color: white; }
        .custom-input[readonly] { background-color: #f8f9fa; }
        .checkbox-container { display: flex; align-items: center; gap: 10px; margin-top: 10px; }
        .custom-checkbox { width: 22px; height: 22px; border: 2px solid #333; border-radius: 4px; cursor: pointer; }
        .checkbox-label { color: #345E6F; font-weight: bold; font-size: 15px; cursor: pointer; }
        .btn-custom { border: 2px solid #333; border-radius: 8px; font-weight: bold; font-size: 14px; padding: 8px 35px; text-decoration: none; cursor: pointer; display: inline-block; transition: 0.2s; }
        .btn-kembali { background-color: white; color: #333; }
        .btn-kembali:hover { background-color: #f8f9fa; }
        .btn-edit-action { background-color: #B2C3CF; color: #333; }
        .btn-edit-action:hover { background-color: #9cb1c0; }
        .btn-keluar { border: none; background: transparent; color: white; display: flex; align-items: center; padding-left: 20px; }
        .info-box { background: #eef4f8; border: 1px solid #c9d7e3; border-radius: 10px; padding: 14px 16px; color: #345E6F; font-size: 14px; margin-bottom: 20px; }
    </style>
</head>
<body>
<div class="sidebar shadow">
    <div class="text-center mb-4 px-3" style="padding-right: 15px;">
        <img src="{{ asset('images/logo-btr.jpg') }}" width="60" class="rounded-circle border border-2 border-white">
        <p class="sidebar-logo-text fw-semibold">Sistem Manajemen Data<br>Laboratorium Balai Teknik Rawa</p>
    </div>
    <ul class="nav flex-column">
        <li class="nav-item"><a href="{{ route('kepalalab.dashboard') }}" class="nav-link"><i class="bi bi-grid-fill me-2"></i> Dashboard</a></li>
        <li class="nav-item"><a href="{{ route('kepala.pegawai') }}" class="nav-link"><i class="bi bi-people-fill me-2"></i> Pegawai</a></li>
        <li class="nav-item"><a href="{{ route('kepala.peralatan') }}" class="nav-link"><i class="bi bi-tools me-2"></i> Monitoring Peralatan</a></li>
        <li class="nav-item"><a href="{{ route('kepala.sop') }}" class="nav-link"><i class="bi bi-file-earmark-check-fill me-2"></i> SOP</a></li>
        <li class="nav-item"><a href="{{ route('kepala.permintaan') }}" class="nav-link active"><i class="bi bi-file-earmark-text-fill me-2"></i> Permintaan Layanan</a></li>
        <li class="nav-item"><a href="{{ route('kepala.riwayat') }}" class="nav-link"><i class="bi bi-file-earmark-bar-graph-fill me-2"></i> Riwayat Penelitian</a></li>
    </ul>
    <div style="position: absolute; bottom: 30px; left: 0; width: 100%;">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-keluar fw-bold">
                <i class="bi bi-box-arrow-left fs-4 me-2"></i> Keluar
            </button>
        </form>
    </div>
</div>

<div class="main-wrapper">
    <div class="topbar-card shadow-sm">
        <h3 class="fw-bold m-0" style="color: #333; font-family: serif;">Selamat Datang</h3>
        <div class="dropdown d-flex align-items-center" style="gap: 15px; position: relative;">
            <div>
                <p class="m-0 fw-bold" style="font-size: 14px; color: #333;">{{ Auth::user()->nama ?? 'Weka Athaya' }}</p>
                <small class="text-muted" style="font-size: 12px;">Kepala Lab</small>
            </div>
            <button class="dropdown-toggle" id="dropdownProfil" data-bs-toggle="dropdown" aria-expanded="false" style="background: none; border: none; padding: 0; cursor: pointer;">
                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->nama ?? 'WA' }}&background=E53E3E&color=fff" class="rounded-circle border border-2 border-danger" width="40">
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownProfil" style="border: 2px solid #333; border-radius: 8px; min-width: 200px; z-index: 1050; position: absolute;">
                <li><a class="dropdown-item fw-bold text-secondary" href="{{ route('user.profile') }}" style="font-size: 14px;"><i class="bi bi-person-fill me-2"></i> Profil Saya</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item fw-bold text-secondary" href="{{ route('user.change-password') }}" style="font-size: 14px;"><i class="bi bi-shield-lock-fill me-2"></i> Ganti Password</a></li>
            </ul>
        </div>
    </div>

    <div class="dashboard-container">
        <div class="form-card">
            <h4 class="form-title">Edit Data Laporan</h4>

            @if($permintaan->status !== 'diverifikasi')
                <div class="info-box">
                    Permintaan ini sudah berstatus <strong>{{ ucfirst($permintaan->status) }}</strong>.
                    Hanya permintaan dengan status <strong>diverifikasi</strong> yang bisa ditandai selesai.
                </div>
            @endif

            <form action="{{ route('kepala.permintaan.update', $permintaan->id_permintaan) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="custom-label">Id Permintaan</label>
                            <input type="text" class="custom-input" value="{{ $permintaan->id_permintaan }}" readonly>
                        </div>
                        <div class="mb-4">
                            <label class="custom-label">Jenis Permintaan</label>
                            <input type="text" class="custom-input" value="{{ $permintaan->jenis_permintaan }}" readonly>
                        </div>
                        <div class="mb-4">
                            <label class="custom-label">Status Saat Ini</label>
                            <input type="text" class="custom-input" value="{{ ucfirst(str_replace('_', ' ', $permintaan->status)) }}" readonly>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="custom-label">Id User</label>
                            <input type="text" class="custom-input" value="{{ $permintaan->id_user }}" readonly>
                        </div>
                        <div class="mb-4">
                            <label class="custom-label">Tanggal Permintaan</label>
                            <input type="date" class="custom-input" value="{{ $permintaan->tanggal_permintaan }}" readonly>
                        </div>
                        <div class="mb-4">
                            <label class="custom-label">Status Verifikasi</label>
                            <div class="checkbox-container">
                                <input type="checkbox" name="status" id="statusCheckbox" class="custom-checkbox" value="selesai" {{ $permintaan->status === 'selesai' ? 'checked' : '' }} {{ $permintaan->status !== 'diverifikasi' ? 'disabled' : '' }}>
                                <label for="statusCheckbox" class="checkbox-label">Disetujui dan ditandai selesai oleh Kepala Laboratorium</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-4">
                    <a href="{{ route('kepala.permintaan') }}" class="btn-custom btn-kembali">Kembali</a>
                    <button type="submit" class="btn-custom btn-edit-action" {{ $permintaan->status !== 'diverifikasi' ? 'disabled' : '' }}>Edit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
