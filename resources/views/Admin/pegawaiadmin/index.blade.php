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
        .custom-select { border: 2px solid #333; border-radius: 10px; padding: 5px 10px; font-weight: bold; background: white; }
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
    <div class="text-center mb-5 px-3">
        <img src="{{ asset('images/logo-btr.jpg') }}" width="60" class="rounded-circle border border-2 border-white">
        <p class="sidebar-logo-text fw-semibold">Sistem Manajemen Data<br>Laboratorium Balai Teknik Rawa</p>
    </div>
    <ul class="nav flex-column px-2">
        <li class="nav-item"><a href="{{ route('dashboard') }}" class="nav-link"><i class="bi bi-grid-fill me-2"></i> Dashboard</a></li>
        <li class="nav-item"><a href="{{ route('pegawai') }}" class="nav-link active"><i class="bi bi-people-fill me-2"></i> Pegawai</a></li>
        <li class="nav-item"><a href="{{ route('peralatan') }}" class="nav-link"><i class="bi bi-tools me-2"></i> Peralatan</a></li>
        <li class="nav-item"><a href="{{ route('sop.index') }}" class="nav-link"><i class="bi bi-file-earmark-check-fill me-2"></i> Daftar SOP</a></li>
        <li class="nav-item"><a href="{{ route('permintaan.index') }}" class="nav-link"><i class="bi bi-file-earmark-text-fill me-2"></i> Laporan</a></li>
        <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-exclamation-square-fill me-2"></i> Riwayat Laporan</a></li>
        <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-wallet2 me-2"></i> PNBP</a></li>
        <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-person-badge-fill me-2"></i> Riwayat Absensi</a></li>
    </ul>
    <div style="position: absolute; bottom: 30px; left: 25px;">
        <button class="border-0 bg-transparent text-white d-flex align-items-center"><i class="bi bi-box-arrow-left fs-4 me-2"></i> <span class="fw-bold">Keluar</span></button>
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
                <div class="search-container shadow-sm">
                    <i class="bi bi-search fs-5 text-muted"></i>
                    <input type="text" placeholder="Cari Pegawai...">
                </div>
            </div>
            <div class="col-md-7 d-flex justify-content-end align-items-center gap-2">
                <select class="custom-select shadow-sm me-2">
                    <option>Kategori Staf Laboratorium</option>
                </select>
                <a href="{{ route('pegawai.create') }}" class="btn btn-custom btn-tambah shadow-sm">Tambah</a>
            </div>
        </div>

        <div class="white-table-card shadow-sm">
            <table class="table table-pegawai">
                <thead>
                    <tr>
                        <th width="60"></th>
                        <th>Id User</th>
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
        <td class="text-center">
            <input type="checkbox" class="form-check-input" name="selected_pegawai[]" value="{{ $p->id_personil }}">
        </td>
        <td class="text-center fw-bold">{{ $p->id_user }}</td>
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
        <td class="text-center fw-bold text-success">Hadir</td>
        <td class="text-center">
            <a href="{{ route('pegawai.edit', $p->id_personil) }}" class="btn btn-custom btn-edit shadow-sm" style="padding: 4px 10px; font-size: 12px;">Edit</a>
            <form action="{{ route('pegawai.destroy', $p->id_personil) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-custom btn-hapus shadow-sm" style="padding: 4px 10px; font-size: 12px;" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
            </form>
        </td>
    </tr>i
    @empty
    {{-- Tampilan jika database kosong --}}
    <tr>
        <td colspan="7" class="text-center py-5">
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
            <td class="text-center"><input type="checkbox" class="form-check-input" disabled></td>
            <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
        </tr>
        @endfor
    @endif
</tbody>
            </table>

            <div class="pagination-area border-top">
                <a href="#" class="text-dark me-2"><i class="bi bi-chevron-left"></i></a>
                <a href="#" class="page-link-custom active">1</a>
                <a href="#" class="page-link-custom">2</a>
                <a href="#" class="page-link-custom">3</a>
                <a href="#" class="page-link-custom">4</a>
                <a href="#" class="page-link-custom">5</a>
                <span class="mx-1">.....</span>
                <a href="#" class="page-link-custom">10</a>
                <a href="#" class="text-dark ms-2"><i class="bi bi-chevron-right"></i></a>
            </div>
        </div>
    </div>
</div>

</body>
</html>