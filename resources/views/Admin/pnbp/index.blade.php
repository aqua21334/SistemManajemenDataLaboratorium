<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data PNBP - Lab Rawa</title>
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

        .btn-custom { border: 2px solid #333; border-radius: 10px; font-weight: bold; padding: 6px 25px; font-size: 14px; transition: 0.3s; text-decoration: none; display: inline-block; }
        .btn-print { background: white; color: #333; }
        .btn-tambah { background: #1F4557; color: white; border: none; }
        .btn-edit { background: #B2C3CF; color: #333; }

        /* Table PNBP */
        .white-table-card { background: white; border: 2px solid #333; border-radius: 15px; overflow: hidden; margin-top: 25px; }
        .table-pnbp { margin-bottom: 0; width: 100%; border-collapse: collapse; }
        .table-pnbp th { color: #345E6F; border-bottom: 2px solid #333 !important; padding: 15px; text-align: center; font-weight: bold; font-size: 16px; }
        .table-pnbp td { padding: 12px; vertical-align: middle; border-bottom: 1px solid #333; text-align: center; }
        
        .btn-text-hapus { color: #E53E3E; text-decoration: none; font-weight: bold; background: none; border: none; cursor: pointer; }
        .form-check-input { border: 2px solid #333; width: 18px; height: 18px; cursor: pointer; }

        /* Pagination Sesuai Gambar */
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
    <div class="text-center mb-5 px-3">
        <img src="{{ asset('images/logo-btr.jpg') }}" width="60" class="rounded-circle border border-2 border-white">
        <p class="sidebar-logo-text fw-semibold">Sistem Manajemen Data<br>Laboratorium Balai Teknik Rawa</p>
    </div>
    <ul class="nav flex-column px-2">
        <li class="nav-item"><a href="{{ route('dashboard') }}" class="nav-link"><i class="bi bi-grid-fill me-2"></i> Dashboard</a></li>
        <li class="nav-item"><a href="{{ route('pegawai') }}" class="nav-link"><i class="bi bi-people-fill me-2"></i> Pegawai</a></li>
        <li class="nav-item"><a href="{{ route('peralatan') }}" class="nav-link"><i class="bi bi-tools me-2"></i> Peralatan</a></li>
        <li class="nav-item"><a href="{{ route('sop.index') }}" class="nav-link"><i class="bi bi-file-earmark-check-fill me-2"></i> Daftar SOP</a></li>
        <li class="nav-item"><a href="{{ route('permintaan.index') }}" class="nav-link"><i class="bi bi-file-earmark-text-fill me-2"></i> Laporan</a></li>
        <li class="nav-item"><a href="{{ route('riwayat-penelitian.index') }}" class="nav-link"><i class="bi bi-file-earmark-bar-graph-fill me-2"></i> Riwayat Penelitian</a></li>
        <li class="nav-item"><a href="{{ route('pnbp.index') }}" class="nav-link active"><i class="bi bi-cash-stack me-2"></i> PNBP</a></li>
        <li class="nav-item"><a href="{{ route('riwayat-absensi.index') }}" class="nav-link"><i class="bi bi-person-badge-fill me-2"></i> Riwayat Absensi</a></li>
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

    <!-- Toolbar Card -->
    <div class="dashboard-container shadow-sm">
        <h5 class="fw-bold mb-4" style="color: #345E6F;">Data PNBP</h5>
        <form action="{{ route('pnbp.index') }}" method="GET" id="filterForm" class="d-flex justify-content-between align-items-center">
            <div class="d-flex gap-3">
                <!-- Search -->
                <div class="search-container">
                    <i class="bi bi-search"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..." id="searchInput">
                </div>
            </div>
            <div class="d-flex gap-3">
                <!-- Action Buttons -->
                <a href="{{ route('pnbp.create') }}" class="btn-custom btn-tambah">Tambah</a>
                <button type="button" class="btn-custom btn-edit">Edit</button>
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="white-table-card shadow-sm">
        <table class="table-pnbp">
            <thead>
                <tr>
                    <th width="60"></th>
                    <th>Id Permintaan</th>
                    <th>Jenis Permintaan</th>
                    <th>Pemohon</th>
                    <th>Total Biaya</th>
                    <th>Sisa Tagihan</th>
                    <th>Status Pembayaran</th>
                    <th width="100">Aksi</th>
                </tr>
            </thead>
            <tbody>
                {{-- Data Loop --}}
                @forelse($permintaans as $p)
                <tr>
                    <td><input type="checkbox" class="form-check-input"></td>
                    <td>{{ $p->id_permintaan }}</td>
                    <td>{{ $p->jenis_permintaan ?? '-' }}</td>
                    <td>{{ $p->pemohon ?? $p->user->nama ?? '-' }}</td>
                    <td>
                        @if($p->pnbp)
                            Rp {{ number_format($p->pnbp->total_biaya, 0, ',', '.') }}
                        @else
                            <span class="badge bg-secondary">Belum ada tagihan</span>
                        @endif
                    </td>
                    <td>
                        @if($p->pnbp)
                            Rp {{ number_format($p->pnbp->sisa_tagihan, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($p->pnbp)
                            <span class="badge bg-{{ $p->pnbp->status_pembayaran === 'Lunas' ? 'success' : ($p->pnbp->status_pembayaran === 'Belum Lunas' ? 'warning' : 'danger') }}">
                                {{ $p->pnbp->status_pembayaran }}
                            </span>
                        @else
                            <span class="badge bg-info">Belum ada PNBP</span>
                        @endif
                    </td>
                    <td>
                        @if($p->pnbp)
                            <div style="display: flex; gap: 5px;">
                                <a href="{{ route('pnbp.edit', $p->pnbp->id_pnbp) }}" class="btn-text-hapus" style="color: #0d6efd; text-decoration: none;" title="Edit Status">✏️</a>
                                <a href="{{ route('pnbp.invoice', $p->pnbp->id_pnbp) }}" class="btn-text-hapus" style="color: #28a745; text-decoration: none;" title="Lihat Invoice">📄</a>
                                <form action="{{ route('pnbp.destroy', $p->pnbp->id_pnbp) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-text-hapus" onclick="return confirm('Hapus data?')">🗑️</button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('pnbp.create') }}?id_permintaan={{ $p->id_permintaan }}" class="btn-text-hapus" style="color: #0d6efd; text-decoration: none;">+ Buat</a>
                        @endif
                    </td>
                </tr>
                @empty
                    {{-- Dummy rows sesuai desain gambar jika data kosong --}}
                    @for($i=0; $i<7; $i++)
                    <tr>
                        <td><input type="checkbox" class="form-check-input"></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td><button class="btn-text-hapus">Hapus</button></td>
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