<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password - Lab Rawa</title>
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
        .profile-card { background: white; border-radius: 10px; padding: 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); margin-bottom: 20px; max-width: 600px; }
        .card-title-custom { color: #333; font-weight: bold; font-size: 18px; margin-bottom: 25px; }

        /* Form Styling */
        .form-label { font-weight: 600; color: #333; margin-bottom: 8px; font-size: 14px; }
        .form-control { border: 1px solid #333; border-radius: 6px; padding: 10px 12px; font-size: 14px; }
        .form-control:focus { border-color: #345E6F; box-shadow: 0 0 0 0.2rem rgba(52, 94, 111, 0.25); }

        .btn-custom { background-color: #345E6F; color: white; border: none; padding: 10px 30px; border-radius: 6px; font-weight: 600; }
        .btn-custom:hover { background-color: #2b4a5a; color: white; }
        .btn-custom-secondary { background-color: #CBD5E0; color: #345E6F; border: none; padding: 10px 30px; border-radius: 6px; font-weight: 600; }
        .btn-custom-secondary:hover { background-color: #b2c3cf; color: #345E6F; }

        /* Password Strength */
        .password-info { background-color: #EAF4F4; border: 1px solid #333; border-radius: 6px; padding: 15px; margin-top: 15px; font-size: 13px; }
        .password-info ul { margin-bottom: 0; padding-left: 20px; }
        .password-info li { margin-bottom: 5px; }

        /* Alert */
        .alert-custom { border-radius: 6px; border: none; font-size: 14px; }

        .btn-keluar { border: none; background: transparent; color: white; display: flex; align-items: center; padding-left: 20px; }
    </style>
</head>
<body>

@php
    $user = $user ?? Auth::user()->fresh(['personil', 'role']);
    $userRoleId = $user->id_role;
    $isPetugasLab = $userRoleId == 3;
    $isKepalaLab = $userRoleId == 2;
    $userPhoto = $user?->personil?->foto;
    $userAvatar = $userPhoto && file_exists(public_path('images/pegawai/' . $userPhoto))
        ? asset('images/pegawai/' . $userPhoto)
        : 'https://ui-avatars.com/api/?name=' . urlencode($user->nama ?? 'WA') . '&background=E53E3E&color=fff';
@endphp

<div class="sidebar shadow">
    <div class="text-center mb-4 px-3" style="padding-right: 15px;">
        <img src="{{ asset('images/logo-btr.jpg') }}" width="60" class="rounded-circle border border-2 border-white">
        <p class="sidebar-logo-text fw-semibold">Sistem Manajemen Data<br>Laboratorium Balai Teknik Rawa</p>
    </div>
    <ul class="nav flex-column">
        @if ($isPetugasLab)
            <li class="nav-item"><a href="{{ route('petugas.dashboard') }}" class="nav-link"><i class="bi bi-grid-fill me-2"></i> Dashboard</a></li>
            <li class="nav-item"><a href="{{ route('petugas.absensi.index') }}" class="nav-link"><i class="bi bi-fingerprint me-2"></i> Absensi</a></li>
            <li class="nav-item"><a href="{{ route('petugas.laporanpetugas.index') }}" class="nav-link"><i class="bi bi-chat-square-text-fill me-2"></i> Laporan</a></li>
            <li class="nav-item"><a href="{{ route('petugas.peralatan.index') }}" class="nav-link"><i class="bi bi-tools me-2"></i> Peralatan</a></li>
            <li class="nav-item"><a href="{{ route('petugas.sop.index') }}" class="nav-link"><i class="bi bi-file-earmark-check-fill me-2"></i> Daftar SOP</a></li>
            <li class="nav-item"><a href="{{ route('petugas.riwayat.index') }}" class="nav-link"><i class="bi bi-exclamation-square-fill me-2"></i> Riwayat Laporan</a></li>
        @else
            <li class="nav-item"><a href="{{ route('kepalalab.dashboard') }}" class="nav-link"><i class="bi bi-grid-fill me-2"></i> Dashboard</a></li>
            <li class="nav-item"><a href="{{ route('kepala.pegawai') }}" class="nav-link"><i class="bi bi-people-fill me-2"></i> Pegawai</a></li>
            <li class="nav-item"><a href="{{ route('kepala.peralatan') }}" class="nav-link"><i class="bi bi-tools me-2"></i> Monitoring Peralatan</a></li>
            <li class="nav-item"><a href="{{ route('kepala.sop') }}" class="nav-link"><i class="bi bi-file-earmark-check-fill me-2"></i> SOP</a></li>
            <li class="nav-item"><a href="{{ route('kepala.permintaan') }}" class="nav-link"><i class="bi bi-file-earmark-text-fill me-2"></i> Permintaan Layanan</a></li>
            <li class="nav-item"><a href="{{ route('kepala.riwayat') }}" class="nav-link"><i class="bi bi-file-earmark-bar-graph-fill me-2"></i> Riwayat Penelitian</a></li>
        @endif
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
        <h3 class="fw-bold m-0" style="color: #333; font-family: serif;">Ganti Password</h3>
        
        <!-- Dropdown Profil -->
        <div class="dropdown d-flex align-items-center" style="gap: 15px; position: relative;">
            <div>
                <p class="m-0 fw-bold" style="font-size: 14px; color: #333;">{{ $user->nama }}</p>
                <small class="text-muted" style="font-size: 12px;">{{ $isPetugasLab ? 'Petugas Lab' : ($isKepalaLab ? 'Kepala Lab' : 'User') }}</small>
            </div>
            <button class="dropdown-toggle" id="dropdownProfil" data-bs-toggle="dropdown" aria-expanded="false" style="background: none; border: none; padding: 0; cursor: pointer;">
                <img src="{{ $userAvatar }}" class="rounded-circle border border-2 border-danger" width="40">
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

        <!-- Change Password Card -->
        <div class="profile-card">
            <h5 class="card-title-custom"><i class="bi bi-key me-2"></i>Ubah Password</h5>
            
            <p style="color: #666; font-size: 14px; margin-bottom: 20px;">
                Masukkan password saat ini dan password baru yang ingin Anda gunakan.
            </p>

            <!-- Form Ganti Password -->
            <form action="{{ route('user.update-password') }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Current Password -->
                <div class="mb-3">
                    <label class="form-label">Password Saat Ini</label>
                    <div class="input-group">
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                               id="currentPassword" name="current_password" placeholder="Masukkan password saat ini" required>
                        <button class="btn btn-outline-secondary" type="button" id="toggleCurrent" style="border: 1px solid #333;">
                            <i class="bi bi-eye"></i>
                        </button>
                        @error('current_password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- New Password -->
                <div class="mb-3">
                    <label class="form-label">Password Baru</label>
                    <div class="input-group">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="newPassword" name="password" placeholder="Masukkan password baru (minimal 6 karakter)" required>
                        <button class="btn btn-outline-secondary" type="button" id="toggleNew" style="border: 1px solid #333;">
                            <i class="bi bi-eye"></i>
                        </button>
                        @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <div class="input-group">
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                               id="confirmPassword" name="password_confirmation" placeholder="Ulangi password baru" required>
                        <button class="btn btn-outline-secondary" type="button" id="toggleConfirm" style="border: 1px solid #333;">
                            <i class="bi bi-eye"></i>
                        </button>
                        @error('password_confirmation')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Password Requirements -->
                <div class="password-info">
                    <strong style="color: #333;">Persyaratan Password:</strong>
                    <ul>
                        <li>Minimal 6 karakter</li>
                        <li>Gunakan kombinasi huruf besar, huruf kecil, angka, dan simbol untuk keamanan maksimal</li>
                        <li>Jangan gunakan password yang mudah ditebak seperti tanggal lahir atau nama</li>
                    </ul>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-custom">
                        <i class="bi bi-check-circle me-2"></i>Ubah Password
                    </button>
                    <a href="{{ route('user.profile') }}" class="btn btn-custom-secondary">
                        <i class="bi bi-x-circle me-2"></i>Batal
                    </a>
                </div>
            </form>
        </div>

    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Toggle password visibility
    document.getElementById('toggleCurrent').addEventListener('click', function() {
        const input = document.getElementById('currentPassword');
        const icon = this.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.add('bi-eye');
            icon.classList.remove('bi-eye-slash');
        }
    });

    document.getElementById('toggleNew').addEventListener('click', function() {
        const input = document.getElementById('newPassword');
        const icon = this.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.add('bi-eye');
            icon.classList.remove('bi-eye-slash');
        }
    });

    document.getElementById('toggleConfirm').addEventListener('click', function() {
        const input = document.getElementById('confirmPassword');
        const icon = this.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.add('bi-eye');
            icon.classList.remove('bi-eye-slash');
        }
    });
</script>
</body>
</html>
