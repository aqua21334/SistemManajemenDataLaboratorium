<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pegawai - Lab Rawa</title>
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
        
        /* Active Sidebar Item */
        .nav-link.active { background-color: #DAE4EB; color: #345E6F; font-weight: bold; position: relative; }
        .nav-link.active::after { content: ''; position: absolute; right: 0; top: 0; bottom: 0; width: 10px; background-color: #DAE4EB; margin-right: -10px; }

        .main-wrapper { margin-left: 240px; padding: 0; }

        /* Topbar */
        .topbar-card { background: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;}

        /* Content Container */
        .dashboard-container { padding: 0 25px 25px 25px; }

        /* Toolbar Card */
        .toolbar-card { background: white; border-radius: 10px; padding: 20px 25px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
        .toolbar-title { color: #345E6F; font-weight: bold; font-size: 16px; margin-bottom: 15px; }

        /* Search & Filter Elements */
        .search-wrapper { display: flex; align-items: center; border: 1px solid #333; border-radius: 8px; overflow: hidden; height: 35px; width: 350px; }
        .search-icon-box { background-color: #2A4B5C; color: white; padding: 0 15px; height: 100%; display: flex; align-items: center; border-right: 1px solid #333; }
        .search-input { border: none; outline: none; padding: 0 15px; width: 100%; font-size: 14px; }

        .custom-select { border: 1px solid #333; border-radius: 8px; padding: 0 15px; height: 35px; font-weight: bold; color: #888; font-size: 14px; width: auto; outline: none; background-color: white;}
        
        /* Buttons */
        .btn-custom { border: 2px solid #333; border-radius: 8px; font-weight: bold; font-size: 14px; height: 35px; padding: 0 35px; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; color: #333; cursor: pointer; }
        .btn-print { background-color: white; }

        /* Table Card */
        .table-card { background: white; border: 2px solid #333; border-radius: 10px; overflow: hidden; }
        .table-custom { margin-bottom: 0; width: 100%; border-collapse: collapse; }
        .table-custom th { color: #345E6F; font-weight: bold; font-size: 15px; text-align: center; padding: 15px 10px; border-bottom: 2px solid #333 !important; }
        .table-custom td { padding: 12px 10px; text-align: center; border-bottom: 1px solid #333; vertical-align: middle; font-size: 14px; color: #345E6F; font-weight: 600;}
        
        /* Checkbox styling */
        .form-check-input-custom { width: 18px; height: 18px; border: 2px solid #333; border-radius: 4px; cursor: pointer; }

        /* Avatar Styling */
        .avatar-img { width: 35px; height: 35px; border-radius: 50%; object-fit: cover; border: 2px solid #E53E3E; margin-right: 10px; }
        .nama-wrapper { display: flex; align-items: center; justify-content: flex-start; text-align: left; width: max-content; margin: 0 auto; }
        .nama-text { font-weight: bold; color: #345E6F; margin-bottom: 0; font-size: 14px; }
        .nip-text { font-size: 11px; color: #666; margin-bottom: 0; font-weight: 500; }

        /* Pagination */
        .pagination-area { padding: 15px 20px; display: flex; align-items: center; gap: 8px; background: white; }
        .page-item-custom { width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border: 1px solid #333; border-radius: 50%; font-size: 13px; color: #333; text-decoration: none; font-weight: 500; }
        .page-item-custom.active { background-color: #345E6F; color: white; border-color: #345E6F; }
        
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
        <li class="nav-item"><a href="{{ route('kepalalab.dashboard') }}" class="nav-link"><i class="bi bi-grid-fill me-2"></i> Dashboard</a></li>
        <li class="nav-item"><a href="{{ route('kepala.pegawai') }}" class="nav-link active"><i class="bi bi-people-fill me-2"></i> Pegawai</a></li>
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

<!-- Main Content -->
<div class="main-wrapper">
    <!-- Topbar -->
    <div class="topbar-card shadow-sm">
        <h3 class="fw-bold m-0" style="color: #333; font-family: serif;">Selamat Datang</h3>
        
        <!-- Area Profil yang bisa diklik (Dropdown) -->
        <div class="dropdown d-flex align-items-center" style="gap: 15px; position: relative;">
            <div>
                <p class="m-0 fw-bold" style="font-size: 14px; color: #333;">{{ Auth::user()->nama ?? 'Weka Athaya' }}</p>
                <small class="text-muted" style="font-size: 12px;">Kepala Lab</small>
            </div>
            <button class="dropdown-toggle" id="dropdownProfil" data-bs-toggle="dropdown" aria-expanded="false" style="background: none; border: none; padding: 0; cursor: pointer;">
                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->nama ?? 'WA' }}&background=E53E3E&color=fff" class="rounded-circle border border-2 border-danger" width="40">
            </button>
            
            <!-- Isi Menu Dropdown -->
            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownProfil" style="border: 2px solid #333; border-radius: 8px; min-width: 200px; z-index: 1050; position: absolute;">
                <li><a class="dropdown-item fw-bold text-secondary" href="{{ route('user.profile') }}" style="font-size: 14px;"><i class="bi bi-person-fill me-2"></i> Profil Saya</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item fw-bold text-secondary" href="{{ route('user.change-password') }}" style="font-size: 14px;"><i class="bi bi-shield-lock-fill me-2"></i> Ganti Password</a></li>
            </ul>
        </div>
    </div>
    
    <div class="dashboard-container">
        
        <!-- Toolbar Section (Search, Kategori, Print) -->
        <div class="toolbar-card">
            <div class="toolbar-title">Data Pegawai</div>
            <form method="GET" action="{{ route('kepala.pegawai') }}" class="d-flex justify-content-between align-items-center mt-2">
                
                <!-- Kiri: Search & Kategori -->
                <div class="d-flex gap-3 align-items-center">
                    <div class="search-wrapper">
                        <div class="search-icon-box">
                            <i class="bi bi-search"></i>
                        </div>
                        <input type="text" name="search" class="search-input" placeholder="Cari pegawai..." value="{{ $search ?? '' }}" onkeypress="if(event.key === 'Enter') this.form.submit();">
                    </div>
                </div>
            </form>
        </div>

        <!-- Table Section -->
        <div class="table-card shadow-sm">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th width="50"></th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Jabatan</th>
                        <th>Absensi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Baris Berisi Data dari Database -->
                    @forelse($personils as $person)
                    <tr>
                        <td><input type="checkbox" class="form-check-input-custom"></td>
                        <td>
                            <div class="nama-wrapper">
                                <!-- Avatar -->
                                @if($person->foto)
                                    <img src="{{ asset('images/pegawai/' . $person->foto) }}" class="avatar-img" alt="Avatar">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($person->nama_personil) }}&background=E53E3E&color=fff" class="avatar-img" alt="Avatar">
                                @endif
                                <div>
                                    <p class="nama-text">{{ $person->nama_personil }}</p>
                                    <p class="nip-text">NIP. {{ $person->nip }}</p>
                                </div>
                            </div>
                        </td>
                        <td>{{ $person->email }}</td>
                        <td>{{ $person->jabatan }}</td>
                        <td class="text-dark">-</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Tidak ada data petugas lab</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="pagination-area">
                {{ $personils->links() }}
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>