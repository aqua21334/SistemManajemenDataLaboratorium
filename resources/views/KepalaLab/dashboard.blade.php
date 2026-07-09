<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Lab Rawa</title>
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
        .topbar-card { background: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; position: relative; overflow: visible; }

        /* Content Container */
        .dashboard-container { padding: 20px; }

        /* Base Card Styling */
        .white-card { background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); margin-bottom: 20px; }
        .card-title-custom { color: #333; font-weight: bold; font-size: 15px; margin-bottom: 15px; }

        /* Inner Box Styling (Ringkasan & Pegawai) */
        .inner-box { 
            background-color: #EAF4F4; /* Light cyan/green bg */
            border: 1px solid #333; 
            border-radius: 10px; 
            padding: 15px; 
            display: flex; 
            align-items: center; 
            gap: 15px;
        }
        .inner-box-icon { background: transparent; font-size: 24px; color: #333; }
        .inner-box-text h3 { margin: 0; font-size: 18px; font-weight: bold; color: #333; }
        .inner-box-text p { margin: 0; font-size: 13px; color: #333; font-weight: 500; }

        /* List Peralatan Styling */
        .list-card { border: 1px solid #333; border-radius: 8px; padding: 12px 15px; margin-bottom: 10px; background: white; }
        .list-card h6 { margin: 0; font-weight: bold; font-size: 14px; color: #333; }
        .list-card p { margin: 0; font-size: 11px; color: #666; }
        .text-belum-kalibrasi { color: #E53E3E; font-weight: bold; font-size: 13px; }

        /* Legend Chart Styling */
        .legend-item { display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: bold; color: #333; margin-bottom: 8px; }
        .legend-color { width: 15px; height: 15px; border-radius: 4px; border: 1px solid #333; }

        /* Button Absensi Styling */
        .btn-custom-absen-masuk:disabled,
        .btn-custom-absen-pulang:disabled { 
            background-color: #BDC3C7 !important; 
            cursor: not-allowed; 
            opacity: 0.6; 
        }
        .btn-custom-absen-masuk:hover:not(:disabled) { background-color: #229954 !important; }
        .btn-custom-absen-pulang:hover:not(:disabled) { background-color: #C0392B !important; }

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
        <li class="nav-item"><a href="{{ route('kepalalab.dashboard') }}" class="nav-link active"><i class="bi bi-grid-fill me-2"></i> Dashboard</a></li>
        <li class="nav-item"><a href="{{ route('kepala.pegawai') }}" class="nav-link"><i class="bi bi-people-fill me-2"></i> Pegawai</a></li>
        <li class="nav-item"><a href="{{ route('kepala.peralatan') }}" class="nav-link"><i class="bi bi-tools me-2"></i> Monitoring Peralatan</a></li>
        <li class="nav-item"><a href="{{ route('kepala.sop') }}" class="nav-link"><i class="bi bi-file-earmark-check-fill me-2"></i> SOP</a></li>
        <li class="nav-item"><a href="{{ route('kepala.permintaan') }}" class="nav-link"><i class="bi bi-file-earmark-text-fill me-2"></i> Permintaan Layanan</a></li>
        <li class="nav-item"><a href="{{ route('kepala.riwayat') }}" class="nav-link"><i class="bi bi-file-earmark-bar-graph-fill me-2"></i> Riwayat Penelitian</a></li>
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
    <!-- Topbar -->
    <div class="topbar-card shadow-sm">
        <h3 class="fw-bold m-0" style="color: #333; font-family: serif;">Selamat Datang</h3>
        
        <!-- Area Profil yang bisa diklik (Dropdown) -->
        <div class="dropdown d-flex align-items-center" style="gap: 15px; position: relative;">
            <div>
                <p class="m-0 fw-bold" style="font-size: 14px; color: #333;">{{ Auth::user()->nama ?? 'Weka Athaya' }}</p>
                <small class="text-muted" style="font-size: 12px;">Kepala Lab</small>
            </div>
            <button class="dropdown-toggle" id="dropdownProfil" data-bs-toggle="dropdown" aria-expanded="false" style="background: none; border: none; padding: 0; cursor: pointer;">
                <img src="{{ $authAvatar }}" class="rounded-circle border border-2 border-danger" width="40">
            </button>
            
            <!-- Isi Menu Dropdown -->
            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownProfil" style="border: 2px solid #333; border-radius: 8px; min-width: 200px; z-index: 1050; position: absolute;">
                <li><a class="dropdown-item fw-bold text-secondary" href="{{ route('user.profile') }}" style="font-size: 14px;"><i class="bi bi-person-fill me-2"></i> Profil Saya</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item fw-bold text-secondary" href="{{ route('user.change-password') }}" style="font-size: 14px;"><i class="bi bi-shield-lock-fill me-2"></i> Ganti Password</a></li>
            </ul>
        </div>
    </div>

    <div class="dashboard-container">
        
        <!-- Alert Messages -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 8px; border: none;">
            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 8px; border: none;">
            <i class="bi bi-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        
        <!-- Baris 1: Ringkasan -->
        <div class="white-card">
            <h5 class="card-title-custom">Ringkasan</h5>
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="inner-box">
                        <i class="bi bi-wrench-adjustable inner-box-icon"></i>
                        <div class="inner-box-text">
                            <h3>{{ $totalPeralatan }}</h3><p>Peralatan</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="inner-box">
                        <i class="bi bi-file-earmark-check inner-box-icon"></i>
                        <div class="inner-box-text">
                            <h3>{{ $totalSop }}</h3><p>SOP</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="inner-box">
                        <i class="bi bi-file-earmark-text inner-box-icon"></i>
                        <div class="inner-box-text">
                            <h3>{{ $totalPermintaan }}</h3><p>Permintaan Layanan</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="inner-box">
                        <i class="bi bi-clock-history inner-box-icon"></i>
                        <div class="inner-box-text">
                            <h3>{{ $totalRiwayat }}</h3><p>Permintaan Layanan Selesai</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <!-- Kolom Kiri (Grafik & Pegawai) -->
            <div class="col-md-8">
                <div class="row g-3">
                    <!-- Absensi Harian -->
                    <div class="col-md-4">
                        <div class="white-card h-100">
                            <h5 class="card-title-custom"><i class="bi bi-clock-fill me-2"></i>Absensi Harian</h5>
                            
                            <!-- Tanggal dan Jam -->
                            <div style="background-color: #EAF4F4; border: 1px solid #333; border-radius: 10px; padding: 15px; margin-bottom: 15px; text-align: center;">
                                <p style="margin: 0; font-size: 13px; color: #666; font-weight: 500;">Tanggal</p>
                                <h6 style="margin: 5px 0 15px 0; color: #333; font-weight: bold;" id="tanggalHariIni"></h6>
                                
                                <p style="margin: 0; font-size: 13px; color: #666; font-weight: 500;">Jam</p>
                                <h3 style="margin: 5px 0; color: #345E6F; font-weight: bold;" id="jamSekarang">--:--:--</h3>
                            </div>

                            <!-- Tombol Absensi -->
                            <div class="d-grid gap-2">
                                <form action="{{ route('kepala.absen-masuk') }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" 
                                            class="btn btn-custom-absen-masuk w-100" 
                                            id="btnAbsenMasuk"
                                            @if($absenHariIni && $absenHariIni->jam_masuk) disabled @endif
                                            style="background-color: #27AE60; color: white; border: none; padding: 10px; border-radius: 6px; font-weight: 600; margin-bottom: 8px; cursor: pointer;">
                                        <i class="bi bi-arrow-down-circle me-2"></i>Absen Masuk
                                    </button>
                                </form>

                                <form action="{{ route('kepala.absen-pulang') }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" 
                                            class="btn btn-custom-absen-pulang w-100" 
                                            id="btnAbsenPulang"
                                            @if(!$absenHariIni || !$absenHariIni->jam_masuk) disabled @endif
                                            style="background-color: #E74C3C; color: white; border: none; padding: 10px; border-radius: 6px; font-weight: 600; cursor: pointer;">
                                        <i class="bi bi-arrow-up-circle me-2"></i>Absen Pulang
                                    </button>
                                </form>
                            </div>

                            <!-- Status Absensi -->
                            @if($absenHariIni)
                            <div style="background-color: #E8F5E9; border: 1px solid #27AE60; border-radius: 6px; padding: 10px; margin-top: 15px; font-size: 12px;">
                                @if($absenHariIni->jam_masuk)
                                    <p style="margin: 0; color: #27AE60; font-weight: bold;">
                                        <i class="bi bi-check-circle me-1"></i>Masuk: {{ date('H:i:s', strtotime($absenHariIni->jam_masuk)) }}
                                    </p>
                                @endif
                                @if($absenHariIni->jam_pulang)
                                    <p style="margin: 5px 0 0 0; color: #E74C3C; font-weight: bold;">
                                        <i class="bi bi-check-circle me-1"></i>Pulang: {{ date('H:i:s', strtotime($absenHariIni->jam_pulang)) }}
                                    </p>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Pengajuan Layanan (Pie Chart) -->
                    <div class="col-md-8">
                        <div class="white-card h-100">
                            <h5 class="card-title-custom">Pengajuan Layanan</h5>
                            <div style="height: 200px; width: 100%;">
                                <canvas id="pieChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Pengajuan 6 Bulan Terakhir (Line Chart) -->
                    <div class="col-12">
                        <div class="white-card">
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
                <div class="white-card h-100" style="max-height: 650px; overflow-y: auto;">
                    <h5 class="card-title-custom">Peralatan Yang Belum Di Kalibrasi</h5>
                    
                    <!-- Loop List Peralatan -->
                    @forelse($peralatanBelumKalibrasi as $item)
                    <div class="list-card d-flex justify-content-between align-items-center">
                        <div>
                            <h6>{{ $item->nama_peralatan }}</h6>
                            <p>Kode: {{ $item->kode_bmn }}</p>
                            <p style="font-size: 10px;">Tanggal Terakhir Kalibrasi: {{ $item->tanggal_kalibrasi ? \Carbon\Carbon::parse($item->tanggal_kalibrasi)->format('d-m-Y') : 'Belum tersedia' }}</p>
                        </div>
                        <div class="text-belum-kalibrasi text-end">
                            Belum Di Kalibrasi
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">
                        <p>Semua peralatan sudah dikalibrasi</p>
                    </div>
                    @endforelse

                </div>
            </div>
        </div>

    </div>
</div>

<!-- Script Untuk Chart.js -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Konfigurasi Pie Chart
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
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 12,
                            font: { size: 10 }
                        }
                    },
                    tooltip: { enabled: true }
                }
            }
        });

        // 2. Konfigurasi Line Chart
        const ctxLine = document.getElementById('lineChart').getContext('2d');
        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: {!! $monthlyLabels !!},
                datasets: [{
                    label: 'Pengajuan',
                    data: {!! $monthlyData !!},
                    borderColor: '#2A4B5C',
                    backgroundColor: '#2A4B5C',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.4, // Membuat garis melengkung (smooth)
                    pointRadius: 0 // Menghilangkan titik (dot) pada garis
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        },
                        grid: { borderDash: [5, 5] } // Garis putus-putus
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // 3. Jam Real-time dan Tanggal
        function updateJamTanggal() {
            const now = new Date();
            
            // Tampilkan tanggal
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const tanggal = now.toLocaleDateString('id-ID', options);
            document.getElementById('tanggalHariIni').textContent = tanggal;
            
            // Tampilkan jam dengan format HH:MM:SS
            const jam = String(now.getHours()).padStart(2, '0');
            const menit = String(now.getMinutes()).padStart(2, '0');
            const detik = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('jamSekarang').textContent = `${jam}:${menit}:${detik}`;
        }
        
        // Update jam setiap detik
        updateJamTanggal();
        setInterval(updateJamTanggal, 1000);
    });
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>