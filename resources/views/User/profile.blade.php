<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Lab Rawa</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body { background-color: #DAE4EB; font-family: 'Inter', sans-serif; overflow-x: hidden; }
        
        /* Sidebar Styling */
        .sidebar { background-color: #345E6F; min-height: 100vh; color: white; padding: 20px 0 20px 10px; width: 240px; position: fixed; z-index: 100; }
        .sidebar-logo-text { font-size: 10px; line-height: 1.3; color: #E2E8F0; margin-top: 10px; text-align: center; }
        .nav-link { color: #CBD5E0; font-size: 14px; padding: 10px 15px; margin-bottom: 5px; border-radius: 8px 0 0 8px; transition: 0.3s; margin-left: 10px; }
        .nav-link:hover { background-color: rgba(255,255,255,0.1); color: white; }
        
        /* Active Link Styling */
        .nav-link.active { background-color: #DAE4EB; color: #345E6F; font-weight: bold; position: relative; }
        .nav-link.active::after { content: ''; position: absolute; right: 0; top: 0; bottom: 0; width: 10px; background-color: #DAE4EB; margin-right: -10px; }

        .main-wrapper { margin-left: 240px; padding: 0; }

        /* Topbar */
        .topbar-card { background: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; position: relative; }

        /* Content */
        .profile-container { padding: 30px; }

        /* Cards */
        .profile-card { background: white; border-radius: 10px; padding: 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); margin-bottom: 20px; }
        .card-title-custom { color: #333; font-weight: bold; font-size: 18px; margin-bottom: 25px; }

        /* Profile Header */
        .profile-header { display: flex; align-items: flex-start; gap: 20px; margin-bottom: 30px; }
        .profile-avatar { width: 100px; height: 100px; border-radius: 10px; border: 2px solid #E53E3E; }

        /* Form Styling */
        .form-label { font-weight: 600; color: #333; margin-bottom: 8px; font-size: 14px; }
        .form-control { border: 1px solid #333; border-radius: 6px; padding: 10px 12px; font-size: 14px; }
        .form-control:focus { border-color: #345E6F; box-shadow: 0 0 0 0.2rem rgba(52, 94, 111, 0.25); }
        .form-control:disabled { background-color: #f0f0f0; }

        .btn-custom { background-color: #345E6F; color: white; border: none; padding: 10px 30px; border-radius: 6px; font-weight: 600; }
        .btn-custom:hover { background-color: #2b4a5a; color: white; }
        .btn-custom-secondary { background-color: #CBD5E0; color: #345E6F; border: none; padding: 10px 30px; border-radius: 6px; font-weight: 600; }
        .btn-custom-secondary:hover { background-color: #b2c3cf; color: #345E6F; }

        /* Alert */
        .alert-custom { border-radius: 6px; border: none; font-size: 14px; }

        .btn-keluar { border: none; background: transparent; color: white; display: flex; align-items: center; padding-left: 20px; }
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
        <li class="nav-item"><a href="{{ route('kepala.permintaan') }}" class="nav-link"><i class="bi bi-file-earmark-text-fill me-2"></i> Permintaan Layanan</a></li>
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
    <!-- Topbar -->
    <div class="topbar-card shadow-sm">
        <h3 class="fw-bold m-0" style="color: #333; font-family: serif;">Profil Saya</h3>
        
        <!-- Dropdown Profil -->
        <div class="dropdown d-flex align-items-center" style="gap: 15px; position: relative;">
            <div>
                <p class="m-0 fw-bold" style="font-size: 14px; color: #333;">{{ Auth::user()->nama }}</p>
                <small class="text-muted" style="font-size: 12px;">Kepala Lab</small>
            </div>
            <button class="dropdown-toggle" id="dropdownProfil" data-bs-toggle="dropdown" aria-expanded="false" style="background: none; border: none; padding: 0; cursor: pointer;">
                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->nama }}&background=E53E3E&color=fff" class="rounded-circle border border-2 border-danger" width="40">
            </button>
            
            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownProfil" style="border: 2px solid #333; border-radius: 8px; min-width: 200px; z-index: 1050; position: absolute;">
                <li><a class="dropdown-item fw-bold text-secondary" href="{{ route('user.profile') }}" style="font-size: 14px;"><i class="bi bi-person-fill me-2"></i> Profil Saya</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item fw-bold text-secondary" href="{{ route('user.change-password') }}" style="font-size: 14px;"><i class="bi bi-shield-lock-fill me-2"></i> Ganti Password</a></li>
            </ul>
        </div>
    </div>

    <div class="profile-container">
        
        <!-- Success Alert -->
        @if(session('success'))
        <div class="alert alert-success alert-custom" role="alert">
            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
        </div>
        @endif

        <!-- Error Alert -->
        @if($errors->any())
        <div class="alert alert-danger alert-custom" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i> Terjadi kesalahan:
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Profile Card -->
        <div class="profile-card">
            <h5 class="card-title-custom"><i class="bi bi-person-circle me-2"></i>Informasi Profil</h5>
            
            <div class="profile-header">
                <img src="https://ui-avatars.com/api/?name={{ $user->nama }}&background=E53E3E&color=fff&size=100" 
                     class="profile-avatar" alt="{{ $user->nama }}">
                <div>
                    <h6 class="mb-1" style="color: #333; font-weight: bold;">{{ $user->nama }}</h6>
                    <p class="mb-0" style="color: #666; font-size: 13px;">{{ $user->email }}</p>
                    <p class="mb-0 mt-2">
                        <span class="badge bg-secondary" style="font-size: 12px;">
                            {{ $user->role ? $user->role->nama_role : 'User' }}
                        </span>
                    </p>
                </div>
            </div>

            <hr>

            <!-- Form Edit Profil -->
            <form action="{{ route('user.update-profile') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                               name="nama" value="{{ old('nama', $user->nama) }}" required>
                        @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="form-label">Bergabung Sejak</label>
                        <input type="text" class="form-control" 
                               value="{{ $user->created_at ? $user->created_at->format('d-m-Y H:i') : '-' }}" disabled>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-custom">
                        <i class="bi bi-save me-2"></i>Simpan Perubahan
                    </button>
                    <a href="{{ route('kepalalab.dashboard') }}" class="btn btn-custom-secondary">
                        <i class="bi bi-x-circle me-2"></i>Batal
                    </a>
                </div>
            </form>
        </div>

        <!-- Security Card -->
        <div class="profile-card">
            <h5 class="card-title-custom"><i class="bi bi-shield-lock me-2"></i>Keamanan</h5>
            
            <p style="color: #666; font-size: 14px; margin-bottom: 15px;">
                Untuk menjaga keamanan akun Anda, silakan ubah password secara berkala.
            </p>

            <a href="{{ route('user.change-password') }}" class="btn btn-custom">
                <i class="bi bi-key me-2"></i>Ubah Password
            </a>
        </div>

    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
