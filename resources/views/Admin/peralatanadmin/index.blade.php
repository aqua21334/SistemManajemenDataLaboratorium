<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Peralatan - Lab Rawa</title>
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
            padding: 5px 15px; display: flex; align-items: center; width: 450px;
        }
        .search-container input { border: none; outline: none; width: 100%; margin-left: 10px; font-size: 15px; }

        .custom-select { border: 2px solid #333; border-radius: 12px; padding: 8px 15px; font-weight: bold; background: white; min-width: 250px; }
        .btn-tambah { background: #345E6F; color: white; border: 2px solid #333; border-radius: 12px; font-weight: bold; padding: 8px 25px; text-decoration: none; display: inline-block; }
        .btn-tambah:hover { background: #2a4b59; color: white; }

        /* Table Sesuai Gambar */
        .white-table-card { background: white; border: 2px solid #333; border-radius: 20px; overflow: hidden; margin-top: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .table-peralatan { margin-bottom: 0; width: 100%; border-collapse: collapse; }
        .table-peralatan th { color: #345E6F; border-bottom: 2px solid #333 !important; padding: 20px 15px; text-align: center; font-weight: 800; font-size: 16px; }
        .table-peralatan td { padding: 15px; vertical-align: middle; border-bottom: 1px solid #333; text-align: center; font-weight: 500; color: #333; }
        
        /* Action Buttons */
        .btn-action-edit { background-color: #B2C3CF; border: 2px solid #333; border-radius: 10px; padding: 4px 15px; font-size: 13px; font-weight: bold; color: #333; text-decoration: none; display: inline-block; }
        .btn-action-hapus { background-color: #E5A4A4; border: 2px solid #333; border-radius: 10px; padding: 4px 15px; font-size: 13px; font-weight: bold; color: #333; cursor: pointer; }

        .form-check-input { border: 2px solid #333; width: 22px; height: 22px; border-radius: 6px; }

        /* Pagination Sesuai Gambar */
        .pagination-area { padding: 20px; display: flex; justify-content: center; align-items: center; gap: 10px; background: white; }
        .page-link-custom { 
            width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; 
            border: 1px solid #333; border-radius: 50%; text-decoration: none; color: black; font-size: 14px; font-weight: 500;
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
        <li class="nav-item"><a href="{{ route('dashboard') }}" class="nav-link"><i class="bi bi-grid-fill me-2"></i> Dashboard</a></li>
        <li class="nav-item"><a href="{{ route('pegawai') }}" class="nav-link"><i class="bi bi-people-fill me-2"></i> Pegawai</a></li>
        <li class="nav-item"><a href="{{ route('peralatan') }}" class="nav-link active"><i class="bi bi-tools me-2"></i> Peralatan</a></li>
        <li class="nav-item"><a href="{{ route('sop.index') }}" class="nav-link"><i class="bi bi-file-earmark-check-fill me-2"></i> Daftar SOP</a></li>
        <li class="nav-item"><a href="{{ route('permintaan.index') }}" class="nav-link"><i class="bi bi-file-earmark-text-fill me-2"></i> Laporan</a></li>
        <li class="nav-item"><a href="{{ route('riwayat-penelitian.index') }}" class="nav-link"><i class="bi bi-file-earmark-bar-graph-fill me-2"></i> Riwayat Penelitian</a></li>
        <li class="nav-item"><a href="{{ route('pnbp.index') }}" class="nav-link"><i class="bi bi-cash-stack me-2"></i> PNBP</a></li>
        <li class="nav-item"><a href="{{ route('riwayat-absensi.index') }}" class="nav-link"><i class="bi bi-person-badge-fill me-2"></i> Riwayat Absensi</a></li>
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

    <!-- Toolbar Area -->
    <form action="{{ route('peralatan') }}" method="GET" class="d-flex justify-content-between align-items-center mb-4 gap-3" id="filterForm">
        <div class="search-container shadow-sm">
            <i class="bi bi-search fs-5 text-muted"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Peralatan..." id="searchInput">
        </div>
        <div class="d-flex gap-3 align-items-center">
            <select class="custom-select shadow-sm" name="status" id="statusFilter">
                <option value="">Status</option>
                <option value="belum dikalibrasi" {{ request('status') == 'belum dikalibrasi' ? 'selected' : '' }}>Belum Dikalibrasi</option>
                <option value="terkalibrasi" {{ request('status') == 'terkalibrasi' ? 'selected' : '' }}>Terkalibrasi</option>
            </select>
            <a href="{{ route('peralatan.create') }}" class="btn btn-tambah shadow-sm">Tambah</a>
        </div>
    </form>

    <!-- Table -->
    <div class="white-table-card shadow-sm">
        <table class="table-peralatan">
            <thead>
                <tr>
                    <th width="80"></th>
                    <th>Kode BMN</th>
                    <th>Nama Peralatan</th>
                    <th>Tgl Selesai Kalibrasi</th>
                    <th>Status Kalibrasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peralatans as $item)
                <tr>
                    <td><input type="checkbox" class="form-check-input"></td>
                    <td class="fw-bold">{{ $item->kode_bmn }}</td>
                    <td>{{ $item->nama_peralatan }}</td>
                    <td>{{ $item->tanggal_kalibrasi }}</td>
                    <td class="fw-bold {{ $item->status == 'terkalibrasi' ? 'text-success' : 'text-warning' }}">
                        {{ ucfirst($item->status) }}
                    </td>
                    <td>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('peralatan.edit', $item->id) }}" class="btn-action-edit">Edit</a>
                            <form action="{{ route('peralatan.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-action-hapus" onclick="return confirm('Hapus data ini?')">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                    @for($i=0; $i<5; $i++)
                    <tr>
                        <td><input type="checkbox" class="form-check-input"></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
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
            <a href="#" class="text-dark me-2"><i class="bi bi-chevron-left"></i></a>
            <a href="#" class="page-link-custom active">1</a>
            <a href="#" class="page-link-custom">2</a>
            <a href="#" class="page-link-custom">3</a>
            <a href="#" class="page-link-custom">4</a>
            <a href="#" class="page-link-custom">5</a>
            <span class="mx-1 text-muted">.....</span>
            <a href="#" class="page-link-custom">10</a>
            <a href="#" class="text-dark ms-2"><i class="bi bi-chevron-right"></i></a>
        </div>
    </div>
</div>

<script>
    const filterForm = document.getElementById('filterForm');
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');

    let searchTimer;

    searchInput.addEventListener('input', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            filterForm.submit();
        }, 400);
    });

    statusFilter.addEventListener('change', function () {
        filterForm.submit();
    });
</script>

</body>
</html>