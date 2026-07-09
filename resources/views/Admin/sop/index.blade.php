<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar SOP - Lab Rawa</title>
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
        .btn-tambah { background: #345E6F; color: white; border: 2px solid #333; border-radius: 12px; font-weight: bold; padding: 8px 25px; }
        .btn-tambah:hover { background: #2a4b59; color: white; }

        /* Table Styling Sesuai Gambar */
        .white-table-card { background: white; border: 2px solid #333; border-radius: 20px; overflow: hidden; margin-top: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .table-sop { margin-bottom: 0; width: 100%; border-collapse: collapse; }
        .table-sop th { color: #345E6F; border-bottom: 2px solid #333 !important; padding: 20px 15px; text-align: center; font-weight: 800; font-size: 16px; }
        .table-sop td { padding: 15px; vertical-align: middle; border-bottom: 1px solid #333; text-align: center; font-weight: 500; color: #333; }
        
        /* Action Buttons Di Dalam Tabel */
        .btn-action-edit { background-color: #B2C3CF; border: 2px solid #333; border-radius: 10px; padding: 4px 15px; font-size: 13px; font-weight: bold; color: #333; text-decoration: none; }
        .btn-action-hapus { background-color: #E5A4A4; border: 2px solid #333; border-radius: 10px; padding: 4px 15px; font-size: 13px; font-weight: bold; color: #333; }

        .pdf-file-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }
        .pdf-file-icon {
            position: relative;
            width: 30px;
            height: 38px;
            border: 2px solid #E11D48;
            border-radius: 7px;
            background: #fff;
            display: inline-flex;
            align-items: flex-end;
            justify-content: center;
            padding-bottom: 4px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.08);
            transition: all 0.2s ease;
        }
        .pdf-file-icon::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 11px;
            height: 11px;
            background: linear-gradient(135deg, #ffffff 0 50%, #E11D48 50% 100%);
            border-top-right-radius: 5px;
        }
        .pdf-file-icon span {
            font-size: 10px;
            font-weight: 800;
            color: #E11D48;
            line-height: 1;
            letter-spacing: 0.4px;
        }
        .pdf-file-link:hover .pdf-file-icon {
            border-color: #BE123C;
            background: #FFF1F4;
            transform: translateY(-1px);
        }

        .form-check-input { border: 2px solid #333; width: 22px; height: 22px; border-radius: 6px; }

        /* Pagination Sesuai Gambar */
        .pagination-area { padding: 20px; display: flex; justify-content: center; align-items: center; gap: 10px; background: white; }
        .page-link-custom { 
            width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; 
            border: 1px solid #333; border-radius: 50%; text-decoration: none; color: black; font-size: 14px; font-weight: 500;
        }
        .page-link-custom.active { background: #345E6F; color: white; border-color: #333; }

        /* Delete confirmation modal */
        .delete-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            padding: 20px;
        }
        .delete-modal-overlay.show { display: flex; }
        .delete-modal-card {
            width: min(420px, 100%);
            background: #fff;
            border: 2px solid #333;
            border-radius: 14px;
            padding: 22px;
            box-shadow: 0 16px 34px rgba(0, 0, 0, 0.22);
        }
        .delete-modal-text { margin: 0; font-weight: 600; color: #333; text-align: center; }
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
        <li class="nav-item"><a href="{{ route('admin.sop.index') }}" class="nav-link active"><i class="bi bi-file-earmark-check-fill me-2"></i> Daftar SOP</a></li>
        <li class="nav-item"><a href="{{ route('admin.permintaan.index') }}" class="nav-link"><i class="bi bi-file-earmark-text-fill me-2"></i> Laporan</a></li>
        <li class="nav-item"><a href="{{ route('admin.riwayat-penelitian.index') }}" class="nav-link"><i class="bi bi-file-earmark-bar-graph-fill me-2"></i> Riwayat Penelitian</a></li>
        <li class="nav-item"><a href="{{ route('admin.pnbp.index') }}" class="nav-link"><i class="bi bi-cash-stack me-2"></i> PNBP</a></li>
        <li class="nav-item"><a href="{{ route('admin.riwayat-absensi.index') }}" class="nav-link"><i class="bi bi-person-badge-fill me-2"></i> Riwayat Absensi</a></li>
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

    <!-- Notifikasi Sukses -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4 border-2 border-dark" role="alert">
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Toolbar Area -->
    <form action="{{ route('admin.sop.index') }}" method="GET" class="d-flex justify-content-between align-items-center mb-4 gap-3" id="filterForm">
        <div class="search-container shadow-sm">
            <i class="bi bi-search fs-5 text-muted"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Dokumen SOP..." id="searchInput">
        </div>
        <a href="{{ route('admin.sop.create') }}" class="btn btn-tambah shadow-sm">Tambah</a>
    </form>

    <!-- Table -->
    <div class="white-table-card shadow-sm">
        <table class="table-sop">
            <thead>
                <tr>
                    <th>Id Dokumen</th>
                    <th>Jenis Dokumen</th>
                    <th>Judul SOP</th>
                    <th>File</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sops as $sop)
                <tr>
                    <td class="fw-bold">{{ $sop->id_sop }}</td>
                    <td>{{ $sop->jenis_sop }}</td>
                    <td>{{ $sop->judul_sop }}</td>
                    <td>
                        @if($sop->file_sop)
                            <a href="{{ route('admin.sop.file', $sop->id_sop) }}" target="_blank" class="pdf-file-link" title="Lihat {{ basename($sop->file_sop) }}">
                                <span class="pdf-file-icon" aria-hidden="true"><span>PDF</span></span>
                            </a>
                        @else
                            <span class="text-muted">Kosong</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('admin.sop.edit', $sop->id_sop) }}" class="btn-action-edit">Edit</a>
                            <form action="{{ route('admin.sop.destroy', $sop->id_sop) }}" method="POST" class="d-inline delete-form">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-action-hapus">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                    {{-- Baris dummy agar sesuai desain visual gambar jika data kosong --}}
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

        <!-- Pagination Sesuai Gambar -->
        <div class="pagination-area">
            {{ $sops->appends(request()->query())->render('vendor.pagination.custom') }}
        </div>
    </div>
</div>

<div class="delete-modal-overlay" id="deleteConfirmModal" aria-hidden="true">
    <div class="delete-modal-card">
        <p class="delete-modal-text">Apakah Yakin Ingin Hapus</p>
        <div class="d-flex justify-content-center gap-2 mt-4">
            <button type="button" class="btn btn-secondary" id="cancelDeleteBtn">Batal</button>
            <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Hapus</button>
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

    const modal = document.getElementById('deleteConfirmModal');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const deleteForms = document.querySelectorAll('.delete-form');
    let selectedForm = null;

    function openModal(form) {
        selectedForm = form;
        modal.classList.add('show');
        modal.setAttribute('aria-hidden', 'false');
    }

    function closeModal() {
        selectedForm = null;
        modal.classList.remove('show');
        modal.setAttribute('aria-hidden', 'true');
    }

    deleteForms.forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            openModal(form);
        });
    });

    confirmDeleteBtn.addEventListener('click', function () {
        if (selectedForm) {
            selectedForm.submit();
        }
    });

    cancelDeleteBtn.addEventListener('click', closeModal);

    modal.addEventListener('click', function (event) {
        if (event.target === modal) {
            closeModal();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && modal.classList.contains('show')) {
            closeModal();
        }
    });
</script>

</body>
</html>

