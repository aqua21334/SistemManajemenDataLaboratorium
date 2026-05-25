<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Riwayat Absensi - Lab Rawa</title>
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

        /* Content Container */
        .dashboard-container { background-color: white; border-radius: 10px; padding: 25px; min-height: 150px; border: 1px solid #dee2e6; }

        /* Toolbar Area */
        .search-container {
            background: white; border: 2px solid #333; border-radius: 10px;
            padding: 5px 15px; display: flex; align-items: center; width: 300px;
        }
        .search-container i { background: #1F4557; color: white; padding: 5px 10px; border-radius: 5px; margin-left: -10px; margin-right: 10px; }
        .search-container input { border: none; outline: none; width: 100%; font-size: 14px; }

        /* Table */
        .white-table-card { background: white; border: 2px solid #333; border-radius: 15px; overflow: hidden; margin-top: 25px; }
        .table-absensi { margin-bottom: 0; width: 100%; border-collapse: collapse; }
        .table-absensi th { color: #345E6F; border-bottom: 2px solid #333 !important; padding: 15px; text-align: center; font-weight: bold; font-size: 16px; }
        .table-absensi td { padding: 12px; vertical-align: middle; border-bottom: 1px solid #333; text-align: center; }
        .table-absensi td:first-child { text-align: left; }

        .foto-avatar { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #345E6F; }

        /* Pagination */
        .pagination-area { padding: 15px; display: flex; justify-content: flex-start; align-items: center; gap: 8px; }
        .page-link-custom { 
            width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; 
            border: 1px solid #333; border-radius: 50%; text-decoration: none; color: black; font-size: 13px;
        }
        .page-link-custom.active { background: #345E6F; color: white; border-color: #345E6F; }
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
        <li class="nav-item"><a href="{{ route('admin.peralatan.index') }}" class="nav-link"><i class="bi bi-tools me-2"></i> Peralatan</a></li>
        <li class="nav-item"><a href="{{ route('admin.sop.index') }}" class="nav-link"><i class="bi bi-file-earmark-check-fill me-2"></i> Daftar SOP</a></li>
        <li class="nav-item"><a href="{{ route('admin.permintaan.index') }}" class="nav-link"><i class="bi bi-file-earmark-text-fill me-2"></i> Laporan</a></li>
        <li class="nav-item"><a href="{{ route('admin.riwayat-penelitian.index') }}" class="nav-link"><i class="bi bi-file-earmark-bar-graph-fill me-2"></i> Riwayat Penelitian</a></li>
        <li class="nav-item"><a href="{{ route('admin.pnbp.index') }}" class="nav-link"><i class="bi bi-cash-stack me-2"></i> PNBP</a></li>
        <li class="nav-item"><a href="{{ route('admin.riwayat-absensi.index') }}" class="nav-link active"><i class="bi bi-person-badge-fill me-2"></i> Riwayat Absensi</a></li>
    </ul>
    <div style="position: absolute; bottom: 30px; left: 25px;">
        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" class="border-0 bg-transparent text-white d-flex align-items-center" style="cursor: pointer;">
                <i class="bi bi-box-arrow-left fs-4 me-2"></i> <span class="fw-bold">Keluar</span>
            </button>
        </form>
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

    <!-- Toolbar Card -->
    <div class="dashboard-container shadow-sm">
        <h5 class="fw-bold mb-4" style="color: #345E6F;">Data Riwayat Absensi</h5>
        <form action="{{ route('admin.riwayat-absensi.index') }}" method="GET" id="filterForm" class="d-flex justify-content-between align-items-center">
            <div>
                <!-- Search -->
                <div class="search-container">
                    <i class="bi bi-search"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..." id="searchInput">
                </div>
            </div>
            <div>
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="white-table-card shadow-sm">
        <table class="table-absensi" id="absensiTable">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Rekab Absensi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($absensis as $absensi)
                <tr>
                    <td style="display: flex; align-items: center; gap: 10px; text-align: left;">
                        @php
                            $personil = $absensi->user?->personil;
                            $namaPegawai = $personil?->nama_personil ?? $absensi->nama ?? '-';
                            $jabatanPegawai = $personil?->jabatan ?? $absensi->jabatan ?? '-';
                        @endphp
                        @if($personil && $personil->foto)
                            <img src="{{ asset('images/pegawai/' . $personil->foto) }}" alt="{{ $namaPegawai }}" class="foto-avatar">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($namaPegawai) }}&background=345E6F&color=fff" alt="{{ $namaPegawai }}" class="foto-avatar">
                        @endif
                        <div style="text-align: left;">
                            <a href="{{ route('admin.absensi.show', $absensi->id_absensi) }}" style="font-weight: bold; font-size: 14px; color: inherit; text-decoration: none;">
                                {{ $namaPegawai }}
                            </a>
                        </div>
                    </td>
                    <td>{{ $jabatanPegawai }}</td>
                    <td>
                        <a href="{{ route('admin.absensi.export', ['tahun' => date('Y'), 'id_user' => $absensi->id_user]) }}" class="btn btn-sm btn-outline-secondary" target="_blank">
                            <i class="bi bi-file-earmark-pdf me-1"></i> File
                        </a>
                    </td>
                </tr>
                @empty
                    {{-- Dummy rows jika data kosong --}}
                    @for($i=0; $i<7; $i++)
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    @endfor
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination-area">
            {{ $absensis->appends(request()->query())->render('vendor.pagination.custom') }}
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


