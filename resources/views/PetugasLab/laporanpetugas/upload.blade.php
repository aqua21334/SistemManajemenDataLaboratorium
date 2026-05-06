<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Hasil - Lab Rawa</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body { background-color: #BBD0DE; font-family: 'Inter', sans-serif; overflow-x: hidden; }
        
        /* Sidebar Styling */
        .sidebar { background-color: #345E6F; min-height: 100vh; color: white; padding: 20px 0 20px 10px; width: 240px; position: fixed; z-index: 100; }
        .sidebar-logo-text { font-size: 10px; line-height: 1.3; color: #E2E8F0; margin-top: 10px; text-align: center; }
        
        /* General Nav Link */
        .nav-link { color: #CBD5E0; font-size: 14px; padding: 10px 15px; margin-bottom: 5px; border-radius: 8px 0 0 8px; transition: 0.3s; margin-left: 10px; }
        .nav-link:hover { background-color: rgba(255,255,255,0.1); color: white; }
        
        /* Active Submenu Wrapper */
        .active-menu-wrapper { background-color: #BBD0DE; margin-left: 10px; border-radius: 8px 0 0 8px; position: relative; margin-bottom: 5px;}
        /* Seamless connection to main body */
        .active-menu-wrapper::after { content: ''; position: absolute; right: 0; top: 0; bottom: 0; width: 10px; background-color: #BBD0DE; margin-right: -10px; }
        .active-parent { color: #345E6F !important; font-weight: bold; padding-bottom: 5px; margin-left: 0; border-radius: 0; }
        .active-parent:hover { background-color: transparent; color: #345E6F; }
        .submenu { padding-left: 40px; padding-bottom: 12px; }
        .submenu a { color: #345E6F; font-size: 13px; text-decoration: none; font-weight: bold; }

        .main-wrapper { margin-left: 240px; padding: 0; }

        /* Topbar */
        .topbar-card { background: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;}

        /* Content Container */
        .dashboard-container { padding: 0 30px 30px 30px; }

        /* Form Card */
        .form-card { background: white; border-radius: 8px; padding: 30px 40px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .form-title { color: #345E6F; font-weight: bold; font-size: 16px; margin-bottom: 25px; }

        /* Form Elements */
        .custom-label { color: #345E6F; font-weight: bold; font-size: 15px; margin-bottom: 8px; display: block; }
        .custom-input { width: 100%; border: 2px solid #222; border-radius: 6px; padding: 10px 15px; outline: none; font-size: 14px; }
        .custom-input:focus { border-color: #345E6F; }

        /* Custom Input Group (Upload Button + Text Field) */
        .btn-unggah { background-color: #2A4B5C; color: white; border: 2px solid #222; border-right: none; border-radius: 6px 0 0 6px; padding: 10px 25px; font-weight: normal; font-size: 14px; cursor: pointer; }
        .btn-unggah:hover { background-color: #1f3744; color: white;}
        .custom-input-group { border: 2px solid #222; border-radius: 0 6px 6px 0; font-size: 14px; padding: 10px 15px; box-shadow: none; }
        .custom-input-group:focus { border-color: #222; box-shadow: none; }

        /* Upload Submit Button */
        .btn-upload { background-color: #2A4B5C; color: white; border: 2px solid #222; border-radius: 6px; padding: 8px 35px; font-weight: normal; font-size: 14px; transition: 0.2s; }
        .btn-upload:hover { background-color: #1f3744; color: white; }
        
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
        <li class="nav-item"><a href="{{ route('petugas.laporanpetugas.index') }}" class="nav-link active"><i class="bi bi-chat-square-text-fill me-2"></i> Laporan</a></li>
        <li class="nav-item"><a href="{{ route('petugas.peralatan.index') }}" class="nav-link"><i class="bi bi-tools me-2"></i> Peralatan</a></li>
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

    <!-- Form Container -->
    <div class="dashboard-container">
        <div class="form-card">
            <h5 class="form-title">Upload Hasil</h5>

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error:</strong>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('petugas.laporanpetugas.upload', $permintaan->id_permintaan) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-4 mb-4">
                    <!-- Kolom Kanan -->
                    <div class="col-md-6">
                        <div>
                            <label class="custom-label">Hasil Penelitian</label>
                            
                            <!-- Input Upload File Custom -->
                            <div class="input-group">
                                <!-- Tombol yang berfungsi sebagai pemicu (label) file input -->
                                <label class="input-group-text btn-unggah m-0" for="fileUpload">Unggah</label>
                                <!-- File input yang disembunyikan -->
                                <input type="file" id="fileUpload" name="hasil_penelitian" style="display: none;" onchange="updateFileName(this)">
                                <!-- Text input dummy untuk menampilkan nama file -->
                                <input type="text" id="fileNameDisplay" class="form-control custom-input-group" placeholder="" readonly>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="text-end mt-5">
                    <button type="submit" class="btn btn-upload">Upload</button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Fungsi sederhana untuk memunculkan nama file saat file dipilih
    function updateFileName(input) {
        const fileNameDisplay = document.getElementById('fileNameDisplay');
        if (input.files && input.files.length > 0) {
            fileNameDisplay.value = input.files[0].name;
        } else {
            fileNameDisplay.value = "";
        }
    }
</script>
</body>
</html>