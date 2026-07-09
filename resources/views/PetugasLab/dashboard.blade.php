<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas Lab - Lab Rawa</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        body { background-color: #DAE4EB; font-family: 'Inter', sans-serif; overflow-x: hidden; }
        
        /* Sidebar Styling */
        .sidebar { background-color: #345E6F; min-height: 100vh; color: white; padding: 20px 0 20px 10px; width: 240px; position: fixed; z-index: 100; }
        .sidebar-logo-text { font-size: 10px; line-height: 1.3; color: #E2E8F0; margin-top: 10px; text-align: center; }
        .nav-link { color: #CBD5E0; font-size: 14px; padding: 10px 15px; margin-bottom: 5px; border-radius: 8px 0 0 8px; transition: 0.3s; margin-left: 10px; }
        .nav-link:hover { background-color: rgba(255,255,255,0.1); color: white; }
        
        /* Active Link Styling (Seamless to body) */
        .nav-link.active { background-color: #DAE4EB; color: #345E6F; font-weight: bold; position: relative; }
        .nav-link.active::after { content: ''; position: absolute; right: 0; top: 0; bottom: 0; width: 10px; background-color: #DAE4EB; margin-right: -10px; }

        .main-wrapper { margin-left: 240px; padding: 0; }

        /* Topbar White Box */
        .topbar-card { display: flex; justify-content: space-between; align-items: center; padding: 15px 30px; background: white; margin-bottom: 25px; }

        /* Content Container */
        .dashboard-container { padding: 0 20px 20px 20px; }

        /* Base Card Styling */
        .white-card { background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); margin-bottom: 20px; }
        .card-title-custom { color: #333; font-weight: bold; font-size: 15px; margin-bottom: 15px; }

        /* Inner Box Styling (Ringkasan & Pegawai) */
        .inner-box { 
            background-color: #EAF4F4; /* Light cyan/green bg */
            border: 1px solid #333; 
            border-radius: 8px; 
            padding: 12px 15px; 
            display: flex; 
            align-items: center; 
            gap: 15px;
            height: 100%;
        }
        .inner-box-icon { background: transparent; font-size: 24px; color: #333; }
        .inner-box-text h3 { margin: 0; font-size: 18px; font-weight: bold; color: #333; }
        .inner-box-text p { margin: 0; font-size: 12px; color: #333; font-weight: 500; }

        /* Custom Column for 5 Items in Row */
        .col-custom-5 { flex: 0 0 auto; width: 25%; padding: 0 8px; }

        /* List Peralatan Styling */
        .list-card { border: 1px solid #333; border-radius: 8px; padding: 12px 15px; margin-bottom: 10px; background: white; }
        .list-card h6 { margin: 0; font-weight: bold; font-size: 14px; color: #333; }
        .list-card p { margin: 0; font-size: 11px; color: #666; }
        .text-belum-kalibrasi { color: #E53E3E; font-weight: bold; font-size: 13px; }

        /* Legend Chart Styling */
        .legend-item { display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: bold; color: #333; margin-bottom: 8px; }
        .legend-color { width: 15px; height: 15px; border-radius: 4px; border: 1px solid #333; }

        .reminder-title { font-size: 20px; font-weight: 700; color: #333; margin-bottom: 8px; }
        .reminder-text { font-size: 13px; color: #555; margin-bottom: 0; }
        .btn-reminder { background-color: #345E6F; color: white; border-radius: 8px; border: none; padding: 8px 14px; font-size: 13px; font-weight: 600; }
        .btn-reminder:hover { background-color: #2a4b5c; color: white; }

        .btn-keluar { border: none; background: transparent; color: white; display: flex; align-items: center; padding-left: 20px; }
    </style>
</head>
<body>

<div class="sidebar shadow">
    <div class="text-center mb-4 px-3" style="padding-right: 15px;">
        <img src="{{ asset('images/logo-btr.jpg') }}" width="60" class="rounded-circle border border-2 border-white">
        <p class="sidebar-logo-text fw-semibold">Sistem Manajemen Data<br>Laboratorium Balai Teknik Rawa</p>
    </div>
    <ul class="nav flex-column">
        <!-- Menu Petugas Lab -->
        <li class="nav-item"><a href="{{ route('petugas.dashboard') }}" class="nav-link active"><i class="bi bi-grid-fill me-2"></i> Dashboard</a></li>
        <li class="nav-item"><a href="{{ route('petugas.absensi.index') }}" class="nav-link"><i class="bi bi-fingerprint me-2"></i> Absensi</a></li>
        <li class="nav-item"><a href="{{ route('petugas.laporanpetugas.index') }}" class="nav-link"><i class="bi bi-chat-square-text-fill me-2"></i> Laporan</a></li>
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

<div class="main-wrapper">
    @php
        $authUser = Auth::user();
        $authPhoto = $authUser?->personil?->foto;
        $authAvatar = $authPhoto && file_exists(public_path('images/pegawai/' . $authPhoto))
            ? asset('images/pegawai/' . $authPhoto)
            : 'https://ui-avatars.com/api/?name=' . urlencode($authUser?->nama ?? 'WA') . '&background=E53E3E&color=fff';
    @endphp
    <!-- Topbar dengan Fitur Dropdown Profil -->
    <div class="topbar-card shadow-sm">
        <h3 class="fw-bold m-0" style="color: #333; font-family: serif;">Selamat Datang</h3>
        
        <!-- Area Profil Dropdown -->
        <div class="dropdown">
            <div class="d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                <div class="text-end me-3">
                    <p class="m-0 fw-bold" style="font-size: 14px; color: #333;">{{ Auth::user()->nama ?? 'Weka Athaya' }}</p>
                    <small class="text-muted">Petugas Lab</small>
                </div>
                <img src="{{ $authAvatar }}" class="rounded-circle border border-2 border-danger" width="45">
            </div>
            
            <!-- Menu Dropdown -->
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-2 mt-2" style="min-width: 200px; border-color:#333;">
                <li>
                    <a class="dropdown-item py-2 fw-bold text-secondary" href="{{ route('user.profile') }}">
                        <i class="bi bi-person-circle me-2"></i> Profil Saya
                    </a>
                </li>
                <li>
                    <a class="dropdown-item py-2 fw-bold text-secondary" href="{{ route('user.change-password') }}">
                        <i class="bi bi-shield-lock me-2"></i> Ganti Kata Sandi
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="dashboard-container">
        
        <!-- Baris 1: Ringkasan (5 Kotak) -->
        <div class="white-card">
            <h5 class="card-title-custom">Ringkasan</h5>
            <div class="row g-2"> <!-- g-2 untuk memperkecil jarak antar kolom -->
                <div class="col-custom-5">
                    <div class="inner-box">
                        <i class="bi bi-wrench-adjustable inner-box-icon"></i>
                        <div class="inner-box-text">
                            <h3>{{ $countPeralatan }}</h3><p>Peralatan</p>
                        </div>
                    </div>
                </div>
                <div class="col-custom-5">
                    <div class="inner-box">
                        <i class="bi bi-file-earmark-check inner-box-icon"></i>
                        <div class="inner-box-text">
                            <h3>{{ $countSop }}</h3><p>SOP</p>
                        </div>
                    </div>
                </div>
                <div class="col-custom-5">
                    <div class="inner-box">
                        <i class="bi bi-chat-square-text inner-box-icon"></i>
                        <div class="inner-box-text">
                            <h3>{{ $countLaporan }}</h3><p>Laporan</p>
                        </div>
                    </div>
                </div>
                <div class="col-custom-5">
                    <div class="inner-box">
                        <i class="bi bi-clock-history inner-box-icon"></i>
                        <div class="inner-box-text">
                            <h3>{{ $countRiwayat }}</h3><p>Riwayat</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <!-- Kolom Kiri (Grafik & Pegawai) -->
            <div class="col-md-8">
                <div class="row g-3">
                    <!-- Pengingat Absen -->
                    <div class="col-md-4">
                        <div class="white-card h-100 mb-0">
                            <h5 class="card-title-custom">Pengingat Absen</h5>
                            <div class="inner-box flex-column justify-content-center text-center h-75 mt-3">
                                <i class="bi bi-bell inner-box-icon fs-1 mb-0"></i>
                                <div class="inner-box-text mt-2">
                                    <div class="reminder-title">Jangan lupa absen</div>
                                    <p class="reminder-text">Pastikan absen masuk dan pulang sebelum dan sesudah bekerja.</p>
                                    <a href="{{ route('petugas.absensi.index') }}" class="btn btn-reminder mt-3">
                                        Buka Absensi
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pengajuan Layanan (Pie Chart) -->
                    <div class="col-md-8">
                        <div class="white-card h-100 mb-0">
                            <h5 class="card-title-custom">Pengajuan Layanan</h5>
                            <div class="d-flex align-items-center justify-content-around h-75 mt-3">
                                <div style="width: 150px; height: 150px; position: relative;">
                                    <canvas id="pieChart"></canvas>
                                </div>
                                <div>
                                    <div class="legend-item">
                                        <div class="legend-color" style="background-color: #2A4B5C;"></div> Sudah
                                    </div>
                                    <div class="legend-item">
                                        <div class="legend-color" style="background-color: #A9C2D1;"></div> Belum
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pengajuan 6 Bulan Terakhir (Line Chart) -->
                    <div class="col-12 mt-3">
                        <div class="white-card mb-0">
                            <h5 class="card-title-custom">Pengajuan 6 Bulan Terakhir</h5>
                            <div style="height: 250px; width: 100%;">
                                <canvas id="lineChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan (List Peralatan) -->
            <div class="col-md-4">
                <div class="white-card h-100 mb-0" style="max-height: 650px; overflow-y: auto;">
                    <h5 class="card-title-custom">Peralatan Yang Belum Di Kalibrasi</h5>
                    
                    @forelse($peralatanKalibrasi as $peralatan)
                    <div class="list-card d-flex justify-content-between align-items-center">
                        <div>
                            <h6>{{ $peralatan->nama_peralatan }}</h6>
                            <p>Kode: {{ $peralatan->kode_bmn }}</p>
                            <p style="font-size: 10px;">Tanggal Terakhir Kalibrasi: {{ $peralatan->tanggal_kalibrasi ? \Carbon\Carbon::parse($peralatan->tanggal_kalibrasi)->format('d-m-Y') : '-' }}</p>
                        </div>
                        <div class="text-belum-kalibrasi text-end">
                            Belum Di Kalibrasi
                        </div>
                    </div>
                    @empty
                    <div class="list-card text-center text-muted">
                        Tidak ada peralatan yang belum dikalibrasi.
                    </div>
                    @endforelse

                </div>
            </div>
        </div>

    </div>
</div>

<!-- Scripts (Bootstrap Bundle untuk Dropdown & Chart.js) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Konfigurasi Pie Chart
        const ctxPie = document.getElementById('pieChart').getContext('2d');
        new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: @json(['Sudah', 'Belum']),
                datasets: [{
                    data: @json([$permintaanSudah, $permintaanBelum]),
                    backgroundColor: ['#2A4B5C', '#A9C2D1'],
                    borderWidth: 1,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }, 
                    tooltip: { enabled: true }
                }
            }
        });

        // 2. Konfigurasi Line Chart
        const ctxLine = document.getElementById('lineChart').getContext('2d');
        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Pengajuan',
                    data: @json($chartData),
                    borderColor: '#2A4B5C',
                    backgroundColor: '#2A4B5C',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.4, 
                    pointRadius: 0 
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) { return value + "%" },
                            stepSize: 20
                        },
                        grid: { borderDash: [5, 5] }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>

</body>
</html>