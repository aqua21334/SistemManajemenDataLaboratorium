<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Laporan - Lab Rawa</title>
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

        /* Search Elements */
        .search-wrapper { display: flex; align-items: center; border: 1px solid #333; border-radius: 6px; overflow: hidden; height: 35px; width: 300px; }
        .search-icon-box { background-color: #2A4B5C; color: white; padding: 0 15px; height: 100%; display: flex; align-items: center; border-right: 1px solid #333; }
        .search-input { border: none; outline: none; padding: 0 15px; width: 100%; font-size: 14px; }

        /* Dropdown & Buttons */
        .custom-select { border: 1px solid #333; border-radius: 6px; padding: 0 15px; height: 35px; font-weight: bold; font-size: 14px; width: 200px; outline: none; background-color: white;}
        .btn-custom { border: 2px solid #333; border-radius: 6px; font-weight: bold; font-size: 14px; height: 35px; padding: 0 35px; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; color: #333; cursor: pointer; }
        .btn-print { background-color: white; }
        .btn-print:hover { background-color: #f8f9fa; }

        /* Table Card */
        .table-card { background: white; border: 2px solid #333; border-radius: 10px; overflow: hidden; padding-bottom: 10px; }
        .table-custom { margin-bottom: 0; width: 100%; border-collapse: collapse; }
        .table-custom th { color: #345E6F; font-weight: bold; font-size: 14px; text-align: center; padding: 15px 10px; border-bottom: 2px solid #333 !important; }
        .table-custom td { padding: 12px 10px; text-align: center; border-bottom: 1px solid #333; vertical-align: middle; height: 45px; }
        
        /* Checkbox styling */
        .form-check-input-custom { width: 18px; height: 18px; border: 2px solid #333; border-radius: 4px; cursor: pointer; margin-top: 5px; }

        /* File Icon styling */
        .file-icon-link { text-decoration: none; display: inline-flex; align-items: center; justify-content: center; }
        .file-icon { font-size: 32px; color: #DC143C; transition: 0.2s; }
        .file-icon-link:hover .file-icon { color: #A00527; }

        /* Action Button */
        .btn-action { background-color: #2A4B5C; color: white; border: none; border-radius: 6px; padding: 6px 15px; font-size: 13px; text-decoration: none; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: 0.2s; }
        .btn-action:hover { background-color: #1f3744; color: white; }

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
        
        <!-- Area Profil Dropdown -->
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
        
        <!-- Toolbar Section -->
        <div class="toolbar-card">
            <div class="toolbar-title">Data Laporan</div>
            <form method="GET" action="{{ route('petugas.laporanpetugas.index') }}" class="d-flex flex-column gap-3 mt-3">
                
                <!-- Search Box di Atas -->
                <div class="search-wrapper">
                    <div class="search-icon-box">
                        <i class="bi bi-search"></i>
                    </div>
                    <input type="text" name="search" class="search-input" placeholder="Cari nama laporan, pemohon, atau ID" value="{{ $search ?? '' }}">
                </div>

                <!-- Dropdown Status & Print Button di Bawahnya -->
                <div class="d-flex gap-3">
                    <select name="status" class="custom-select text-muted">
                        <option value="">Status</option>
                        <option value="sedang diproses" {{ ($status ?? '') === 'sedang diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                        <option value="diverifikasi" {{ ($status ?? '') === 'diverifikasi' ? 'selected' : '' }}>Diverifikasi</option>
                    </select>
                    <button type="submit" class="btn-custom">Cari</button>
                </div>
            </form>
        </div>

        <!-- Table Section -->
        <div class="table-card shadow-sm">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th width="50"></th>
                        <th>Nama Laporan</th>
                        <th>Pemohon</th>
                        <th>Status</th>
                        <th>File Pendukung</th>
                        <th>File Hasil</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($permintaans) && count($permintaans) > 0)
                        @foreach($permintaans as $permintaan)
                        <tr>
                            <td><input type="checkbox" class="form-check-input-custom"></td>
                            <td>{{ $permintaan->jenis_permintaan }}</td>
                            <td>{{ $permintaan->pemohon }}</td>
                            <td>{{ $permintaan->status }}</td>
                            <td>
                                @if($permintaan->file_layanan)
                                    <a href="{{ asset('uploads/permintaan/' . $permintaan->file_layanan) }}" class="file-icon-link" title="Unduh {{ basename($permintaan->file_layanan) }}" download>
                                        <i class="bi bi-file-pdf file-icon"></i>
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($permintaan->laporanHasil && $permintaan->laporanHasil->file_hasil)
                                    <a href="{{ asset('uploads/laporan/' . $permintaan->laporanHasil->file_hasil) }}" class="file-icon-link" title="Unduh {{ basename($permintaan->laporanHasil->file_hasil) }}" download>
                                        <i class="bi bi-file-pdf file-icon"></i>
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td><a href="{{ route('petugas.laporanpetugas.edit', $permintaan->id_permintaan) }}" class="btn-action"><i class="bi bi-pencil-square"></i> Edit</a></td>
                        </tr>
                        @endforeach
                    @else
                        @for($i=0; $i<7; $i++)
                        <tr>
                            <td><input type="checkbox" class="form-check-input-custom"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><a href="#" class="btn-action" style="pointer-events: none; opacity: 0.5;"><i class="bi bi-pencil-square"></i> Edit</a></td>
                        </tr>
                        @endfor
                    @endif
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="pagination-area">
                {{ $permintaans->appends(request()->query())->links() }}
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>