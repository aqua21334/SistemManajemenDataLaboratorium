<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pegawai - Lab Rawa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body { background-color: #B2C3CF; font-family: 'Inter', sans-serif; }
        
        /* Sidebar Tetap Sama */
        .sidebar { background-color: #345E6F; min-height: 100vh; color: white; padding: 20px 10px; width: 240px; position: fixed; }
        .sidebar-logo-text { font-size: 10px; line-height: 1.3; color: #E2E8F0; margin-top: 10px; }
        .nav-link { color: #CBD5E0; font-size: 14px; padding: 8px 15px; margin-bottom: 5px; border-radius: 8px; }
        .nav-link:hover, .nav-link.active { background-color: rgba(255,255,255,0.1); color: white; }
        .main-wrapper { margin-left: 240px; padding: 25px; }

        /* Topbar */
        .topbar-card { background: white; padding: 15px 30px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; }

        /* Container Content */
        .dashboard-container { background-color: #DAE4EB; border-radius: 10px; padding: 25px; min-height: 80vh; }

        /* Search Bar Custom */
        .search-container {
            background: white;
            border: 2px solid #333;
            border-radius: 10px;
            padding: 5px 15px;
            display: flex;
            align-items: center;
            max-width: 450px;
        }
        .search-container input { border: none; outline: none; width: 100%; margin-left: 10px; font-size: 14px; }

        /* Filter & Buttons */
        .btn-custom { border: 2px solid #333; border-radius: 10px; font-weight: bold; padding: 6px 20px; font-size: 14px; }
        .btn-print { background: white; }
        .btn-tambah { background: #345E6F; color: white; }
        .btn-edit { background: #B2C3CF; }
        .btn-hapus { background: #E5A4A4; }

        /* Table Pegawai */
        .white-table-card { background: white; border: 2px solid #333; border-radius: 15px; overflow: hidden; margin-top: 25px; }
        .table-pegawai { margin-bottom: 0; }
        .table-pegawai th { color: #345E6F; border-bottom: 2px solid #333 !important; padding: 15px; text-align: center; }
        .table-pegawai td { padding: 12px; vertical-align: middle; border-bottom: 1px solid #333; }
        
        .avatar-img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #E53E3E; margin-right: 12px; }
        .form-check-input { border: 2px solid #333; width: 20px; height: 20px; }

        /* Pagination */
        .pagination-area { padding: 15px; display: flex; justify-content: center; align-items: center; gap: 10px; }
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
        <li class="nav-item"><a href="{{ route('admin.pegawai') }}" class="nav-link active"><i class="bi bi-people-fill me-2"></i> Pegawai</a></li>
        <li class="nav-item"><a href="{{ route('admin.peralatan.index') }}" class="nav-link"><i class="bi bi-tools me-2"></i> Peralatan</a></li>
        <li class="nav-item"><a href="{{ route('admin.sop.index') }}" class="nav-link"><i class="bi bi-file-earmark-check-fill me-2"></i> Daftar SOP</a></li>
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

    <div class="dashboard-container shadow-sm">
        <h5 class="fw-bold mb-4" style="color: #345E6F;">Data Pegawai</h5>

        <div class="row g-3 align-items-center">
            <div class="col-md-5">
    <!-- Tambahkan Form dengan method GET -->
    <form action="{{ route('admin.pegawai') }}" method="GET">
        <div class="search-container shadow-sm">
            <i class="bi bi-search fs-5 text-muted"></i>
            <!-- Berikan name="search" dan value dari request sebelumnya -->
            <input type="text" name="search" placeholder="Cari Pegawai (Nama/NIP)..." value="{{ request('search') }}">
            <!-- Tambahkan tombol submit tersembunyi agar bisa tekan Enter -->
            <button type="submit" class="d-none"></button>
        </div>
    </form>
</div>
            <div class="col-md-7 d-flex justify-content-end align-items-center gap-2">
                <a href="{{ route('admin.personil.create') }}" class="btn btn-custom btn-tambah shadow-sm">Tambah</a>
            </div>
        </div>

        <div class="white-table-card shadow-sm">
            <table class="table table-pegawai">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Jabatan</th>
                        <th>Absensi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
    {{-- Loop data personil dari database --}}
    @forelse($personils as $p)
    <tr>
        <td>
            <div class="d-flex align-items-center">
                {{-- Foto: Jika ada di DB pakai itu, jika tidak pakai inisial nama --}}
                <img src="{{ $p->foto ? asset('images/pegawai/'.$p->foto) : 'https://ui-avatars.com/api/?name='.urlencode($p->nama_personil).'&background=random' }}" class="avatar-img">
                <div>
                    <div class="fw-bold" style="font-size: 15px;">{{ $p->nama_personil }}</div>
                    <div class="text-muted" style="font-size: 11px;">NIP. {{ $p->nip ?? '-' }}</div>
                </div>
            </div>
        </td>
        <td class="text-center">{{ $p->email ?? '-' }}</td>
        <td class="text-center">{{ $p->jabatan }}</td>
        <td class="text-center fw-bold {{ in_array($p->id_user, $hadirUserIds ?? []) ? 'text-success' : 'text-danger' }}">
            {{ in_array($p->id_user, $hadirUserIds ?? []) ? 'Hadir' : 'Belum Absen' }}
        </td>
        <td class="text-center">
            <a href="{{ route('admin.personil.edit', $p->id_personil) }}" class="btn btn-custom btn-edit shadow-sm" style="padding: 4px 10px; font-size: 12px;">Edit</a>
            <form action="{{ route('admin.personil.destroy', $p->id_personil) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-custom btn-hapus shadow-sm" style="padding: 4px 10px; font-size: 12px;" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
            </form>
        </td>
    </tr>
    @empty
    {{-- Tampilan jika database kosong --}}
    <tr>
        <td colspan="5" class="text-center py-5">
            <div class="text-muted">
                <i class="bi bi-people fs-1 d-block mb-2"></i>
                <p class="mb-0">Belum ada data pegawai di database.</p>
                <small>Klik tombol <strong>Tambah</strong> untuk memasukkan data baru.</small>
            </div>
        </td>
    </tr>
    @endforelse

    {{-- Baris penyeimbang (opsional): agar tabel tidak terlalu pendek jika data sedikit --}}
    @if(count($personils) > 0 && count($personils) < 5)
        @for($i = 0; $i < (5 - count($personils)); $i++)
        <tr>
            <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
        </tr>
        @endfor
    @endif
</tbody>
            </table>

            <div class="pagination-area border-top">
                {{ $personils->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

</body>
</html>

