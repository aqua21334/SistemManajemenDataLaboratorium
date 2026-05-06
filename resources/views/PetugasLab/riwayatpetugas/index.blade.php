<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Laporan - Lab Rawa</title>
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
        
        /* Active Sidebar Item (Seamless to body) */
        .nav-link.active { background-color: #BBD0DE; color: #345E6F; font-weight: bold; position: relative; }
        .nav-link.active::after { content: ''; position: absolute; right: 0; top: 0; bottom: 0; width: 10px; background-color: #BBD0DE; margin-right: -10px; }

        .main-wrapper { margin-left: 240px; padding: 0; }

        /* Topbar */
        .topbar-card { background: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;}

        /* Content Container */
        .dashboard-container { padding: 0 30px 30px 30px; }

        /* Header Area (Title & Search) */
        .header-area { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .page-title { color: #345E6F; font-weight: bold; font-size: 20px; margin: 0; }

        /* Search Elements */
        .search-wrapper { display: flex; align-items: center; border: 1px solid #333; border-radius: 6px; overflow: hidden; height: 35px; width: 350px; background-color: white; }
        .search-icon-box { background-color: #2A4B5C; color: white; padding: 0 15px; height: 100%; display: flex; align-items: center; border-right: 1px solid #333; }
        .search-input { border: none; outline: none; padding: 0 15px; width: 100%; font-size: 14px; background: transparent; }

        /* Table Card */
        .table-card { background: white; border: 2px solid #333; border-radius: 8px; overflow: hidden; padding-bottom: 10px; }
        .table-custom { margin-bottom: 0; width: 100%; border-collapse: collapse; }
        .table-custom th { color: #345E6F; font-weight: bold; font-size: 14px; text-align: center; padding: 15px 10px; border-bottom: 2px solid #333 !important; }
        .table-custom td { padding: 12px 10px; text-align: center; border-bottom: 1px solid #333; vertical-align: middle; height: 50px; }

        /* Pagination */
        .pagination-area { padding: 15px 20px 0 20px; display: flex; align-items: center; gap: 8px; background: white; }
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
        <!-- Menu Petugas Lab -->
        <li class="nav-item"><a href="{{ route('petugas.dashboard') }}" class="nav-link"><i class="bi bi-grid-fill me-2"></i> Dashboard</a></li>
        <li class="nav-item"><a href="{{ route('petugas.absensi.index') }}" class="nav-link"><i class="bi bi-fingerprint me-2"></i> Absensi</a></li>
        <li class="nav-item"><a href="{{ route('petugas.laporanpetugas.index') }}" class="nav-link"><i class="bi bi-chat-square-text-fill me-2"></i> Laporan</a></li>
        <li class="nav-item"><a href="{{ route('petugas.peralatan.index') }}" class="nav-link"><i class="bi bi-tools me-2"></i> Peralatan</a></li>
        <li class="nav-item"><a href="{{ route('petugas.sop.index') }}" class="nav-link"><i class="bi bi-file-earmark-check-fill me-2"></i> Daftar SOP</a></li>
        <li class="nav-item"><a href="{{ route('petugas.riwayat.index') }}" class="nav-link active"><i class="bi bi-exclamation-square-fill me-2"></i> Riwayat Laporan</a></li>
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

    <div class="dashboard-container">
        
        <!-- Header Section (Title & Search Box) -->
        <div class="header-area">
            <h4 class="page-title">Riwayat Laporan</h4>
            
            <!-- Search Box -->
            <form method="GET" action="{{ route('petugas.riwayat.index') }}" style="width: 350px;">
                <div class="search-wrapper shadow-sm">
                    <div class="search-icon-box">
                        <i class="bi bi-search"></i>
                    </div>
                    <input type="text" class="search-input" name="search" placeholder="Cari riwayat..." value="{{ $search ?? '' }}">
                </div>
            </form>
        </div>
        
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Table Section (White Card) -->
        <div class="table-card shadow-sm">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Jenis Permintaan</th>
                        <th>Tanggal Selesai</th>
                        <th>File</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayatPenelitians as $riwayat)
                    <tr>
                        <td>{{ $riwayat->permintaanLayanan->jenis_permintaan ?? '-' }}</td>
                        <td>{{ $riwayat->tanggal_selesai ? \Carbon\Carbon::parse($riwayat->tanggal_selesai)->format('d/m/Y') : '-' }}</td>
                        <td>
                            @if($riwayat->laporanHasil && $riwayat->laporanHasil->file_hasil)
                                <a href="{{ asset('uploads/laporan/' . $riwayat->laporanHasil->file_hasil) }}" target="_blank" class="btn btn-sm btn-primary">
                                    <i class="bi bi-download"></i>
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $riwayat->status == 'selesai' ? 'success' : 'warning' }}">
                                {{ ucfirst($riwayat->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Tidak ada data riwayat penelitian</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="pagination-area">
                {{ $riwayatPenelitians->appends(request()->query())->links() }}
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>