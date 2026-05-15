<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Riwayat Absensi - Lab Rawa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #DAE4EB; font-family: 'Inter', sans-serif; }
        .sidebar { background-color: #345E6F; min-height: 100vh; color: white; padding: 20px 10px; width: 240px; position: fixed; z-index: 100; }
        .sidebar-logo-text { font-size: 10px; line-height: 1.3; color: #E2E8F0; margin-top: 10px; }
        .nav-link { color: #CBD5E0; font-size: 14px; padding: 8px 15px; margin-bottom: 5px; border-radius: 8px; }
        .nav-link:hover, .nav-link.active { background-color: rgba(255,255,255,0.1); color: white; }
        .main-wrapper { margin-left: 240px; padding: 25px; }
        .topbar-card { background: white; padding: 15px 30px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; border-radius: 0 0 10px 10px; }
        .panel-card { background: white; border-radius: 15px; border: 2px solid #333; padding: 25px; }
        .detail-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 15px; }
        .detail-item { border: 1px solid #d9e0e6; border-radius: 12px; padding: 14px 16px; background: #fdfdfd; }
        .detail-label { font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 4px; }
        .detail-value { font-size: 15px; font-weight: 700; color: #1f2937; }
        .avatar-large { width: 88px; height: 88px; border-radius: 50%; object-fit: cover; border: 3px solid #345E6F; }
        .btn-custom { border: 2px solid #333; border-radius: 10px; font-weight: bold; padding: 8px 22px; font-size: 14px; text-decoration: none; display: inline-block; }
        .btn-export { background: white; color: #333; }
        .btn-export:hover { background: #f5f5f5; }
        .btn-back { background: #345E6F; color: white; border-color: #345E6F; }
        .btn-back:hover { background: #284957; color: white; }
        .table-wrap { margin-top: 20px; overflow: hidden; border-radius: 12px; border: 1px solid #d9e0e6; }
        .table-detail { margin-bottom: 0; }
        .table-detail th { background: #345E6F; color: white; text-align: center; }
        .table-detail td { vertical-align: middle; text-align: center; }
        .badge-soft { background: #e8f1f4; color: #345E6F; border-radius: 999px; padding: 6px 12px; font-weight: 600; font-size: 12px; }
        @media (max-width: 768px) {
            .main-wrapper { margin-left: 0; }
            .sidebar { position: relative; width: auto; min-height: auto; }
            .detail-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<div class="sidebar shadow">
    <div class="text-center mb-5 px-3">
        <img src="{{ asset('images/Logo-pupr.jpeg') }}" width="60" class="rounded-circle border border-2 border-white">
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

    <div class="panel-card shadow-sm">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
            <div class="d-flex align-items-center gap-3">
                @if($personil && $personil->foto)
                    <img src="{{ asset('images/pegawai/' . $personil->foto) }}" alt="{{ $personil->nama_personil }}" class="avatar-large">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($personil?->nama_personil ?? $absensi->nama ?? '-') }}&background=345E6F&color=fff" alt="{{ $personil?->nama_personil ?? $absensi->nama ?? '-' }}" class="avatar-large">
                @endif
                <div>
                    <h4 class="fw-bold mb-1" style="color: #345E6F;">Detail Riwayat Absensi</h4>
                    <div class="text-muted">Data absensi pegawai dari tabel personil dan absensi</div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.absensi.export', ['tahun' => date('Y'), 'id_user' => $absensi->id_user]) }}" class="btn-custom btn-export" target="_blank">
                    <i class="bi bi-download me-2"></i> Export
                </a>
                <a href="{{ route('admin.riwayat-absensi.index') }}" class="btn-custom btn-back">
                    <i class="bi bi-arrow-left me-2"></i> Kembali
                </a>
            </div>
        </div>

        @php
            $namaPegawai = $personil?->nama_personil ?? $absensi->nama ?? '-';
            $jabatanPegawai = $personil?->jabatan ?? $absensi->jabatan ?? '-';
        @endphp

        <div class="detail-grid mb-4">
            <div class="detail-item">
                <div class="detail-label">Nama Pegawai</div>
                <div class="detail-value">{{ $namaPegawai }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Jabatan</div>
                <div class="detail-value">{{ $jabatanPegawai }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Jumlah Riwayat</div>
                <div class="detail-value">{{ $riwayatAbsensi->count() }} data</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Export Rekap</div>
                <div class="detail-value">Tahun {{ date('Y') }}</div>
            </div>
        </div>

        <div class="table-wrap">
            <table class="table table-detail table-bordered align-middle mb-0">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jam Masuk</th>
                        <th>Jam Pulang</th>
                        <th>Lokasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayatAbsensi as $item)
                        <tr>
                            <td>{{ optional($item->tanggal)->format('d/m/Y') }}</td>
                            <td>{{ $item->jam_masuk ? \Carbon\Carbon::parse($item->jam_masuk)->format('H:i:s') : '-' }}</td>
                            <td>{{ $item->jam_pulang ? \Carbon\Carbon::parse($item->jam_pulang)->format('H:i:s') : '-' }}</td>
                            <td>{{ $item->lokasi ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-muted fst-italic py-4">Belum ada riwayat absensi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>


