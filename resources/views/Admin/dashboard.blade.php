<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Lab Rawa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body { background-color: #B2C3CF; font-family: 'Inter', sans-serif; }
        .sidebar { background-color: #345E6F; min-height: 100vh; color: white; padding: 20px 10px; width: 240px; position: fixed; }
        .sidebar-logo-text { font-size: 10px; line-height: 1.3; color: #E2E8F0; margin-top: 10px; }
        .nav-link { color: #CBD5E0; font-size: 14px; padding: 8px 15px; margin-bottom: 5px; border-radius: 8px; }
        .nav-link:hover, .nav-link.active { background-color: rgba(255,255,255,0.1); color: white; }
        .main-wrapper { margin-left: 240px; padding: 25px; }
        .topbar-card { background: white; padding: 15px 30px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; }
        .dashboard-container { background-color: #DAE4EB; border-radius: 10px; padding: 25px; }
        .ringkasan-box { background: white; border: 1px solid #333; border-radius: 10px; padding: 15px; display: flex; align-items: center; justify-content: center; gap: 12px; }
        .icon-container { background-color: #D1E7DD; padding: 8px 12px; border-radius: 8px; border: 1px solid #333; }
        .white-card {
    background: white;
    border-radius: 12px;
    padding: 15px;
    border: 1px solid #cbd5e0;
    /* HAPUS height: 100% */
}
        .box-outline { border: 1px solid #333; border-radius: 10px; padding: 20px; background: #F8FAFC; }
    </style>
</head>
<body>

<div class="sidebar shadow">
    <div class="text-center mb-5 px-3">
        <img src="{{ asset('images/logo-btr.jpg') }}" width="60" class="rounded-circle border border-2 border-white">
        <p class="sidebar-logo-text fw-semibold">Sistem Manajemen Data<br>Laboratorium Balai Teknik Rawa</p>
    </div>

    <ul class="nav flex-column px-2">
        <li class="nav-item"><a href="{{ route('dashboard') }}" class="nav-link active"><i class="bi bi-grid-fill me-2"></i> Dashboard</a></li>
        <li class="nav-item"><a href="{{ route('pegawai') }}" class="nav-link"><i class="bi bi-people-fill me-2"></i> Pegawai</a></li>
        <li class="nav-item"><a href="{{ route('peralatan') }}" class="nav-link"><i class="bi bi-tools me-2"></i> Peralatan</a></li>
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
        <h5 class="fw-bold mb-4">Ringkasan</h5>
        
        <div class="row g-3 mb-5">
            @php
            $stats = [
                ['icon' => 'bi-tools', 'label' => 'Peralatan', 'count' => $countPeralatan ?? 0],
                ['icon' => 'bi-file-earmark-x', 'label' => 'SOP', 'count' => $countSop ?? 0],
                ['icon' => 'bi-file-earmark-check', 'label' => 'Permintaan Layanan', 'count' => $countPermintaanLayanan ?? 0],
                ['icon' => 'bi-camera-reels', 'label' => 'Riwayat', 'count' => $countRiwayat ?? 0],
            ];
            @endphp
            @foreach($stats as $s)
            <div class="col">
                <div class="ringkasan-box shadow-sm">
                    <div class="icon-container"><i class="bi {{ $s['icon'] }} fs-4"></i></div>
                    <div>
                        <h4 class="fw-bold m-0">{{ $s['count'] }}</h4>
                        <small class="text-muted" style="font-size: 12px;">{{ $s['label'] }}</small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="row g-4">
            <div class="col-md-7">
                <div class="row g-3 mb-4">
                    <div class="col-md-5">
                        <div class="white-card">
                            <h6 class="fw-bold mb-3">Pegawai</h6>
                            <div class="box-outline text-center">
                                <i class="bi bi-person fs-1 d-block mb-1"></i>
                                <h4 class="fw-bold m-0">{{ $countPegawai ?? 0 }} Orang</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="white-card text-center">
                            <h6 class="fw-bold mb-2">Pengajuan Layanan</h6>
                            <div style="max-width: 160px;" class="mx-auto">
                                <canvas id="pieChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="white-card">
                    <h6 class="fw-bold mb-3">Pengajuan 6 Bulan Terakhir</h6>
                    <div style="height: 220px; position: relative;">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="white-card">
                    <h6 class="fw-bold mb-3">Peralatan Yang Belum Di Kalibrasi</h6>
                    <div style="max-height: 400px; overflow-y: auto;" class="pe-2">
                        {{-- Logika untuk menampilkan data dari database jika ada --}}
                        @if(isset($peralatanKalibrasi) && $peralatanKalibrasi->count() > 0)
                            @foreach($peralatanKalibrasi as $item)
                                <div class="ringkasan-box mb-2 justify-content-between">
                                    <div>
                                        <h6 class="fw-bold mb-1" style="font-size: 13px;">{{ $item->nama_peralatan }}</h6>
                                        <p class="m-0 text-muted" style="font-size: 11px;">Kode: {{ $item->kode_bmn }}</p>
                                    </div>
                                    <div class="text-danger fw-bold" style="font-size: 11px;">Belum Di Kalibrasi</div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-clipboard-check fs-1 text-muted d-block mb-2"></i>
                                <p class="text-muted small italic">Tidak ada data peralatan<br>yang perlu dikalibrasi.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // PIE CHART - Status Pengajuan Layanan
    new Chart(document.getElementById('pieChart'), {
        type: 'pie',
        data: {
            labels: ['Sudah', 'Belum'],
            datasets: [{
                data: [{{ $permintaanSudah ?? 0 }}, {{ $permintaanBelum ?? 0 }}],
                backgroundColor: ['#345E6F', '#B2C3CF'],
                borderWidth: 1
            }]
        },
        options: { plugins: { legend: { position: 'right', labels: { boxWidth: 12, font: { size: 10 } } } } }
    });

    // LINE CHART - Pengajuan 6 Bulan Terakhir
    new Chart(document.getElementById('lineChart'), {
    type: 'line',
    data: {
        labels: {!! json_encode($labels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun']) !!},
        datasets: [{
            data: {!! json_encode($chartData ?? [1, 2, 3, 4, 5, 6]) !!},
            borderColor: '#345E6F',
            backgroundColor: 'rgba(52, 94, 111, 0.1)',
            fill: false,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false, // WAJIB: Agar chart tidak memaksa tinggi sendiri
        scales: {
            y: {
                beginAtZero: true,
                suggestedMax: 5, // Memberi ruang sedikit di atas angka 0
                ticks: { stepSize: 1 }
            },
            x: {
                grid: { display: false }
            }
        },
        plugins: {
            legend: { display: false }
        }
    }
});
</script>
</body>
</html>