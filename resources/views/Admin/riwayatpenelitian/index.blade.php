<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Laporan - Lab Rawa</title>
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

        /* Toolbar Area */
        .search-container {
            background: white; border: 2px solid #333; border-radius: 12px;
            padding: 5px 15px; display: flex; align-items: center; width: 350px;
        }
        .search-btn-icon { background: #1F4557; color: white; padding: 5px 12px; border-radius: 8px; margin-right: -10px; }
        .search-container input { border: none; outline: none; width: 100%; margin-left: 15px; font-size: 15px; }

        /* Table Styling */
        .white-table-card { background: white; border: 2px solid #333; border-radius: 20px; overflow: hidden; margin-top: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .table-riwayat { margin-bottom: 0; width: 100%; border-collapse: collapse; }
        .table-riwayat th { color: #345E6F; border-bottom: 2px solid #333 !important; padding: 20px 10px; text-align: center; font-weight: 800; font-size: 15px; }
        .table-riwayat td { padding: 18px 10px; vertical-align: middle; border-bottom: 1px solid #333; text-align: center; font-weight: 500; color: #333; font-size: 14px; }
        
        /* Pagination Sesuai Gambar */
        .pagination-area { padding: 15px 25px; display: flex; justify-content: flex-start; align-items: center; gap: 10px; background: white; }
        .page-link-custom { 
            width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; 
            border: 1px solid #333; border-radius: 50%; text-decoration: none; color: black; font-size: 13px; font-weight: 500;
        }
        .page-link-custom.active { background: #345E6F; color: white; border-color: #333; }
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
        <li class="nav-item"><a href="{{ route('admin.riwayat-penelitian.index') }}" class="nav-link active"><i class="bi bi-file-earmark-bar-graph-fill me-2"></i> Riwayat Penelitian</a></li>
        <li class="nav-item"><a href="{{ route('admin.pnbp.index') }}" class="nav-link"><i class="bi bi-cash-stack me-2"></i> PNBP</a></li>
        <li class="nav-item"><a href="{{ route('admin.riwayat-absensi.index') }}" class="nav-link"><i class="bi bi-person-badge-fill me-2"></i> Riwayat Absensi</a></li>
    </ul>
    <div style="position: absolute; bottom: 30px; left: 25px;">
        <button class="border-0 bg-transparent text-white d-flex align-items-center">
            <i class="bi bi-box-arrow-left fs-4 me-2"></i> <span class="fw-bold">Keluar</span>
        </button>
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

    <!-- Content -->
    <div class="dashboard-container">
        <h2 class="fw-bold mb-4" style="color: #345E6F;">Riwayat Laporan</h2>

        <div class="d-flex justify-content-end mb-4">
            <!-- Search Bar Sesuai Gambar -->
            <form action="{{ route('admin.riwayat-penelitian.index') }}" method="GET" id="filterForm" class="search-container shadow-sm">
                <div class="search-btn-icon">
                    <i class="bi bi-search fs-6"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Riwayat Laporan..." id="searchInput">
            </form>
        </div>

        <!-- Table -->
        <div class="white-table-card shadow-sm">
            <table class="table-riwayat">
                <thead>
                    <tr>
                        <th>Id Permintaan</th>
                        <th>Jenis Permintaan</th>
                        <th>Tanggal Selesai</th>
                        <th>File</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayatPenelitians as $riwayat)
                    <tr>
                        <td>{{ $riwayat->id_permintaan }}</td>
                        <td>{{ $riwayat->permintaanLayanan->jenis_permintaan ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($riwayat->tanggal_selesai)->format('d/m/Y') }}</td>
                        <td>
                            @if($riwayat->file)
                                <a href="{{ asset('storage/riwayat/'.$riwayat->file) }}" class="text-danger"><i class="bi bi-file-earmark-pdf-fill fs-5"></i></a>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-success">Selesai</span>
                        </td>
                    </tr>
                    @empty
                        {{-- Baris kosong (dummy) agar sesuai dengan desain visual gambar --}}
                        @for($i=0; $i<6; $i++)
                        <tr>
                            <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                        </tr>
                        @endfor
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination Sesuai Gambar -->
            <div class="pagination-area">
                <a href="#" class="text-dark me-1"><i class="bi bi-chevron-left"></i></a>
                <a href="#" class="page-link-custom active">1</a>
                <a href="#" class="page-link-custom">2</a>
                <a href="#" class="page-link-custom">3</a>
                <a href="#" class="page-link-custom">4</a>
                <a href="#" class="page-link-custom">5</a>
                <span class="mx-1 text-muted">.....</span>
                <a href="#" class="page-link-custom">10</a>
                <a href="#" class="text-dark ms-1"><i class="bi bi-chevron-right"></i></a>
            </div>
        </div>
    </div>
</div>

<script>
    const filterForm = document.getElementById('filterForm');
    const searchInput = document.getElementById('searchInput');

    let searchTimer;

    searchInput.addEventListener('input', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            filterForm.submit();
        }, 400);
    });
</script>

</body>
</html>