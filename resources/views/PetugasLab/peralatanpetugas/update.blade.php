<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Peralatan - Lab Rawa</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body { background-color: #BBD0DE; font-family: 'Inter', sans-serif; overflow-x: hidden; }
        
        /* Sidebar Styling */
        .sidebar { background-color: #345E6F; min-height: 100vh; color: white; padding: 20px 0 20px 10px; width: 240px; position: fixed; z-index: 100; }
        .sidebar-logo-text { font-size: 10px; line-height: 1.3; color: #E2E8F0; margin-top: 10px; text-align: center; }
        .nav-link { color: #CBD5E0; font-size: 14px; padding: 10px 15px; margin-bottom: 5px; border-radius: 8px 0 0 8px; transition: 0.3s; margin-left: 10px; }
        .nav-link:hover { background-color: rgba(255,255,255,0.1); color: white; }
        
        /* Active Sidebar Item (Menyambung ke konten) */
        .nav-link.active { background-color: #BBD0DE; color: #345E6F; font-weight: bold; position: relative; }
        .nav-link.active::after { content: ''; position: absolute; right: 0; top: 0; bottom: 0; width: 10px; background-color: #BBD0DE; margin-right: -10px; }

        .main-wrapper { margin-left: 240px; padding: 0; }

        /* Topbar */
        .topbar-card { background: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;}

        /* Content Container */
        .dashboard-container { padding: 0 30px 30px 30px; }

        /* Form Card Styling */
        .form-card { background: white; border-radius: 8px; padding: 30px 40px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .form-title { color: #345E6F; font-weight: bold; font-size: 18px; margin-bottom: 30px; }

        /* Form Inputs */
        .custom-label { color: #345E6F; font-weight: bold; font-size: 15px; margin-bottom: 8px; display: block; }
        .custom-input { border: 2px solid #000; border-radius: 6px; padding: 10px 15px; width: 100%; outline: none; font-size: 14px; background-color: white; }
        .custom-input:focus { border-color: #345E6F; }

        /* Input Date Custom Wrapper (Ikon di luar input) */
        .date-input-wrapper { display: flex; align-items: center; gap: 15px; }
        .calendar-icon { font-size: 30px; color: #000; line-height: 1; }

        /* Dropdown Select (Tebal) */
        .custom-select-form { border: 2px solid #000; border-radius: 6px; padding: 10px 15px; width: 100%; outline: none; font-size: 14px; background-color: white; cursor: pointer; }

        /* Buttons */
        .btn-custom { border: 2px solid #000; border-radius: 6px; font-weight: bold; font-size: 14px; padding: 8px 40px; text-decoration: none; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; transition: 0.2s; }
        .btn-kembali { background-color: white; color: #000; }
        .btn-kembali:hover { background-color: #f0f0f0; }
        .btn-edit-action { background-color: #BBD0DE; color: #000; }
        .btn-edit-action:hover { background-color: #a4bccd; }
        
        .btn-keluar { border: none; background: transparent; color: white; display: flex; align-items: center; padding-left: 20px; }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar shadow">
    <div class="text-center mb-4 px-3" style="padding-right: 15px;">
        <img src="{{ asset('images/logo-btr.jpg') }}" width="60" class="rounded-circle border border-2 border-white">
        <p class="sidebar-logo-text fw-semibold">Sistem Manajemen Data<br>Laboratorium Balai Teknik Rawa</p>
    </div>
    <ul class="nav flex-column">
        <!-- Menu Petugas Lab -->
        <li class="nav-item"><a href="{{ route('petugas.dashboard') }}" class="nav-link"><i class="bi bi-grid-fill me-2"></i> Dashboard</a></li>
        <li class="nav-item"><a href="{{ route('petugas.absensi.index') }}" class="nav-link"><i class="bi bi-fingerprint me-2"></i> Absensi</a></li>
        <li class="nav-item"><a href="{{ route('petugas.laporanpetugas.index') }}" class="nav-link"><i class="bi bi-chat-square-text-fill me-2"></i> Laporan</a></li>
        <li class="nav-item"><a href="{{ route('petugas.peralatan.index') }}" class="nav-link active"><i class="bi bi-tools me-2"></i> Peralatan</a></li>
        <li class="nav-item"><a href="{{ route('petugas.sop.index') }}" class="nav-link"><i class="bi bi-file-earmark-check-fill me-2"></i> Daftar SOP</a></li>
        <li class="nav-item"><a href="{{ route('petugas.riwayat.index') }}" class="nav-link"><i class="bi bi-exclamation-square-fill me-2"></i> Riwayat Laporan</a></li>
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

<!-- Main Content -->
<div class="main-wrapper">
    <!-- Topbar -->
    <div class="topbar-card shadow-sm">
        <h3 class="fw-bold m-0" style="color: #333; font-family: serif;">Selamat Datang</h3>
        
        <!-- Area Profil -->
        <div class="dropdown">
            <div class="d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                <div class="text-end me-3">
                    <p class="m-0 fw-bold" style="font-size: 14px; color: #333;">{{ Auth::user()->nama ?? 'Weka Athaya' }}</p>
                    <small class="text-muted">Petugas Lab</small>
                </div>
                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->nama ?? 'WA' }}&background=E53E3E&color=fff" class="rounded-circle border border-2 border-danger" width="45">
            </div>
            
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-2 mt-2" style="min-width: 200px; border-color:#333;">
                <li><a class="dropdown-item py-2 fw-bold text-secondary" href="{{ route('user.profile') }}"><i class="bi bi-person-circle me-2"></i> Profil Saya</a></li>
                <li><a class="dropdown-item py-2 fw-bold text-secondary" href="{{ route('user.change-password') }}"><i class="bi bi-shield-lock me-2"></i> Ganti Kata Sandi</a></li>
            </ul>
        </div>
    </div>

    <!-- Container Form -->
    <div class="dashboard-container">
        <div class="form-card">
            <h4 class="form-title">Edit Data Peralatan</h4>

            <form action="{{ route('petugas.peralatan.update', $peralatan->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row g-4 mb-4">
                    <!-- Kolom Kiri -->
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="custom-label">Kode BMN</label>
                            <input type="text" name="kode_bmn" class="custom-input" value="{{ old('kode_bmn', $peralatan->kode_bmn) }}" required>
                        </div>
                        <div>
                            <label class="custom-label">Nama Peralatan</label>
                            <input type="text" name="nama_peralatan" class="custom-input" value="{{ old('nama_peralatan', $peralatan->nama_peralatan) }}" required>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="custom-label">Tanggal Kalibrasi</label>
                            <div class="date-input-wrapper">
                                <input type="date" name="tanggal_kalibrasi" class="custom-input" value="{{ old('tanggal_kalibrasi', $peralatan->tanggal_kalibrasi) }}" required>
                                <!-- Icon Kalender Besar -->
                                <i class="bi bi-calendar-event calendar-icon"></i>
                            </div>
                        </div>
                        <div>
                            <label class="custom-label">Status Kalibrasi</label>
                            <select name="status" class="custom-select-form" required>
                                <option value="" disabled>Pilih Status...</option>
                                <option value="terkalibrasi" @selected(in_array(old('status', $peralatan->status), ['terkalibrasi', 'sudah dikalibrasi']))>Sudah Kalibrasi</option>
                                <option value="belum dikalibrasi" @selected(old('status', $peralatan->status) === 'belum dikalibrasi')>Belum Kalibrasi</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi (Kanan Bawah) -->
                <div class="d-flex justify-content-end gap-3 mt-5">
                    <a href="{{ route('petugas.peralatan.index') }}" class="btn-custom btn-kembali">Kembali</a>
                    <button type="submit" class="btn-custom btn-edit-action">Edit</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>