<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Peralatan - Lab Rawa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body { background-color: #B2C3CF; font-family: 'Inter', sans-serif; }
        
        /* Sidebar Styling */
        .sidebar { background-color: #345E6F; min-height: 100vh; color: white; padding: 20px 10px; width: 240px; position: fixed; }
        .sidebar-logo-text { font-size: 10px; line-height: 1.3; color: #E2E8F0; margin-top: 10px; }
        .nav-link { color: #CBD5E0; font-size: 14px; padding: 8px 15px; margin-bottom: 5px; border-radius: 8px; }
        .nav-link:hover, .nav-link.active { background-color: rgba(255,255,255,0.1); color: white; }
        .main-wrapper { margin-left: 240px; padding: 25px; }

        /* Topbar White Box */
        .topbar-card { background: white; padding: 15px 30px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; }

        /* Form Container Styling */
        .dashboard-container { background-color: #DAE4EB; border-radius: 10px; padding: 25px; }
        .form-container { background-color: white; border-radius: 15px; padding: 40px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }

        .form-group-custom label { color: #345E6F; font-weight: bold; margin-bottom: 8px; display: block; }
        .form-control-custom { border: 2px solid #333; border-radius: 10px; padding: 10px 15px; width: 100%; outline: none; transition: 0.3s; }
        .form-control-custom:focus { border-color: #345E6F; box-shadow: 0 0 0 0.25 rgba(52, 94, 111, 0.25); }

        /* Buttons */
        .btn-form { border: 2px solid #333; border-radius: 10px; padding: 10px 40px; font-weight: bold; transition: 0.3s; text-decoration: none; display: inline-block; }
        .btn-kembali { background: white; color: black; }
        .btn-update-action { background: #345E6F; color: white; border: none; padding: 12px 40px; }
        .btn-kembali:hover { background: #f8f9fa; }
    </style>
</head>
<body>

<div class="sidebar shadow">
    <div class="text-center mb-4 px-3" style="padding-right: 15px;">
        <img src="{{ asset('images/logo-btr.jpg') }}" width="60" class="rounded-circle border border-2 border-white">
        <p class="sidebar-logo-text fw-semibold">Sistem Manajemen Data<br>Laboratorium Balai Teknik Rawa</p>
    </div>
    <ul class="nav flex-column px-2">
        <li class="nav-item"><a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="bi bi-grid-fill me-2"></i> Dashboard</a></li>
        <li class="nav-item"><a href="{{ route('admin.pegawai') }}" class="nav-link"><i class="bi bi-people-fill me-2"></i> Pegawai</a></li>
        <li class="nav-item"><a href="{{ route('admin.peralatan.index') }}" class="nav-link active"><i class="bi bi-tools me-2"></i> Peralatan</a></li>
        <li class="nav-item"><a href="{{ route('admin.sop.index') }}" class="nav-link"><i class="bi bi-file-earmark-check-fill me-2"></i> Daftar SOP</a></li>
        <li class="nav-item"><a href="{{ route('admin.permintaan.index') }}" class="nav-link"><i class="bi bi-file-earmark-text-fill me-2"></i> Laporan</a></li>
        <li class="nav-item"><a href="{{ route('admin.riwayat-penelitian.index') }}" class="nav-link"><i class="bi bi-file-earmark-bar-graph-fill me-2"></i> Riwayat Penelitian</a></li>
       <li class="nav-item"><a href="{{ route('admin.pnbp.index') }}" class="nav-link"><i class="bi bi-cash-stack me-2"></i> PNBP</a></li>
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

    <div class="dashboard-container shadow-sm">
        <div class="form-container">
            <h4 class="fw-bold mb-5" style="color: #345E6F;">Edit Data Peralatan</h4>

            <form action="{{ route('admin.peralatan.update', $peralatan->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-5">
                    <div class="col-md-6">
                        <div class="form-group-custom mb-4">
                            <label>Kode BMN</label>
                            <input type="text" class="form-control-custom shadow-sm" name="kode_bmn" value="{{ $peralatan->kode_bmn }}" required>
                        </div>
                        <div class="form-group-custom mb-4">
                            <label>Nama Peralatan</label>
                            <input type="text" class="form-control-custom shadow-sm" name="nama_peralatan" value="{{ $peralatan->nama_peralatan }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-custom mb-4">
                            <label>Tanggal Peralatan Masuk</label>
                            <input type="date" class="form-control-custom shadow-sm" name="tanggal_masuk" value="{{ $peralatan->tanggal_masuk }}" required>
                        </div>
                        <div class="form-group-custom mb-4">
                            <label>Tanggal Penyelesaian Kalibrasi</label>
                            <input type="date" class="form-control-custom shadow-sm" name="tanggal_kalibrasi" value="{{ $peralatan->tanggal_kalibrasi }}" required>
                        </div>
                        <div class="form-group-custom mb-4">
                            <label>Status Kalibrasi</label>
                            <select class="form-control-custom shadow-sm" name="status" required>
                                <option value="belum dikalibrasi" {{ $peralatan->status == 'belum dikalibrasi' ? 'selected' : '' }}>Belum Dikalibrasi</option>
                                <option value="terkalibrasi" {{ in_array($peralatan->status, ['terkalibrasi', 'sudah dikalibrasi']) ? 'selected' : '' }}>Terkalibrasi</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-5">
                    <a href="{{ route('admin.peralatan.index') }}" class="btn-form btn-kembali">Kembali</a>
                    <button type="submit" class="btn-form btn-update-action">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>


