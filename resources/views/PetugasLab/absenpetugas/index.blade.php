<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Pegawai - Lab Rawa</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Leaflet (OpenStreetMap) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        body { background-color: #B2C3CF; font-family: 'Inter', sans-serif; overflow-x: hidden; }
        
        /* Sidebar Styling */
        .sidebar { background-color: #345E6F; min-height: 100vh; color: white; padding: 20px 0 20px 10px; width: 240px; position: fixed; z-index: 100; }
        .sidebar-logo-text { font-size: 10px; line-height: 1.3; color: #E2E8F0; margin-top: 10px; text-align: center; }
        .nav-link { color: #CBD5E0; font-size: 14px; padding: 10px 15px; margin-bottom: 5px; border-radius: 8px 0 0 8px; transition: 0.3s; margin-left: 10px; }
        .nav-link:hover { background-color: rgba(255,255,255,0.1); color: white; }
        
        /* Active Sidebar Item (Seamless to body) */
        .nav-link.active { background-color: #B2C3CF; color: #345E6F; font-weight: bold; position: relative; }
        .nav-link.active::after { content: ''; position: absolute; right: 0; top: 0; bottom: 0; width: 10px; background-color: #B2C3CF; margin-right: -10px; }

        .main-wrapper { margin-left: 240px; padding: 0; }

        /* Topbar */
        .topbar-card { background: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;}

        /* Content Container */
        .dashboard-container { padding: 0 30px 30px 30px; }

        /* Form Card */
        .form-card { background: white; border-radius: 8px; padding: 30px 40px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); max-width: 800px; margin: 0 auto; }
        .form-title { color: #345E6F; font-weight: bold; font-size: 18px; margin-bottom: 30px; }

        /* Labels */
        .custom-label { color: #345E6F; font-weight: bold; font-size: 16px; margin-bottom: 10px; display: block; }

        /* Action Buttons & Input Elements */
        .btn-icon-dark { background-color: #2A4B5C; color: white; border: 2px solid #000; border-radius: 6px; padding: 6px 30px; font-size: 18px; cursor: pointer; transition: 0.2s; }
        .btn-icon-dark:hover { background-color: #1f3744; color: white;}

        .box-lokasi-wrapper { 
    border: 2px solid #000; 
    border-radius: 6px; 
    height: 320px;
    width: 100%;
    max-width: 100%;
    position: relative;
    overflow: hidden;
    background: #ddd;
}

#lokasi-map {
    width: 100%;
    height: 100%;
    min-height: 320px;
    z-index: 1;
}
        .lokasi-overlay { position: absolute; left: 12px; bottom: 10px; z-index: 500; background: rgba(255,255,255,0.9); padding: 6px 10px; border-radius: 6px; border: 1px solid #000; font-size: 12px; color: #345E6F; }
        .btn-lokasi { position: absolute; top: 8px; right: 8px; z-index: 510; background-color: #2A4B5C; color: white; border: 2px solid #000; border-radius: 6px; padding: 8px 12px; font-size: 16px; cursor: pointer; transition: 0.2s; }
        .btn-lokasi:hover { background-color: #1f3744; }
        .location-hint { background: #FFF3CD; border: 1px solid #E0B100; color: #7A5A00; border-radius: 8px; padding: 12px 15px; margin-bottom: 18px; font-size: 14px; }

        /* Info Card */
        .info-card { background: white; border: 2px solid #000; border-radius: 12px; padding: 20px 25px; margin-bottom: 25px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .info-label { color: #7A8B95; font-size: 13px; font-weight: 500; margin-bottom: 5px; }
        .info-value { color: #345E6F; font-size: 24px; font-weight: bold; margin-bottom: 15px; }
        .info-time { color: #2A4B5C; font-size: 32px; font-weight: bold; font-family: 'Courier New', monospace; }

        .location-info { background: #F0F4F7; border-radius: 8px; padding: 12px 15px; margin-top: 15px; font-size: 13px; color: #345E6F; border-left: 3px solid #2A4B5C; }
        .location-info-label { font-weight: bold; display: block; margin-bottom: 5px; }
        .location-info-value { color: #555; }

        /* Status Buttons */
        .btn-status { border: 2px solid #000; border-radius: 6px; font-weight: bold; padding: 10px 25px; font-size: 15px; transition: 0.2s; }
        .btn-status-masuk { width: 160px; background-color: #2ECC71; color: white; }
        .btn-status-masuk:hover { background-color: #27AE60; color: white; }
        .btn-status-pulang { width: 160px; background-color: #E74C3C; color: white; }
        .btn-status-pulang:hover { background-color: #C0392B; color: white; }
        .btn-alfa { background-color: #E5A4A4; color: white; } /* Merah Muda / Salmon */
        .btn-alfa:hover { background-color: #d49393; color: white; }
        .btn-izin { background-color: #B2C3CF; color: #333; } /* Biru Abu-abu */
        .btn-izin:hover { background-color: #9eb0bd; }
        .btn-hadir { background-color: #2A4B5C; color: white; } /* Biru Gelap */
        .btn-hadir:hover { background-color: #1f3744; color: white; }
        .btn-hadir-done { background-color: #000; color: #fff; border-color: #000; }
        .btn-hadir-done:disabled { opacity: 1; }
        
        .btn-keluar { border: none; background: transparent; color: white; display: flex; align-items: center; padding-left: 20px; }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar shadow">
    <div class="text-center mb-4 px-3" style="padding-right: 15px;">
        <img src="{{ asset('images/logo-btr.jpg') }}" width="60" class="rounded-circle border border-2 border-white">
        <p class="sidebar-logo-text fw-semibold">Sistem Manajemen Data<br>Laboratorium Balai Teknik Rawa</p>
    </div>
   <ul class="nav flex-column">
        <!-- Menu Petugas Lab -->
        <li class="nav-item"><a href="{{ route('petugas.dashboard') }}" class="nav-link"><i class="bi bi-grid-fill me-2"></i> Dashboard</a></li>
        <li class="nav-item"><a href="{{ route('petugas.absensi.index') }}" class="nav-link active"><i class="bi bi-fingerprint me-2"></i> Absensi</a></li>
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

<!-- Main Content -->
<div class="main-wrapper">
    <!-- Topbar -->
    <div class="topbar-card shadow-sm">
        <h3 class="fw-bold m-0" style="color: #333; font-family: serif;">Selamat Datang</h3>
        
        <!-- Area Profil -->
        <div class="dropdown">
            <div class="d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                <div class="text-end me-3">
                    <p class="m-0 fw-bold" style="font-size: 14px; color: #333;">{{ Auth::user()->nama ?? 'Weka Athaya' }}</p>
                    <small class="text-muted">Petugas Lab</small>
                </div>
                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->nama ?? 'WA' }}&background=E53E3E&color=fff" class="rounded-circle border border-2 border-danger" width="45">
            </div>
            
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-2 mt-2" style="min-width: 200px; border-color:#333;">
                <li><a class="dropdown-item py-2 fw-bold text-secondary" href="{{ route('user.profile') }}"><i class="bi bi-person-circle me-2"></i> Profil Saya</a></li>
                <li><a class="dropdown-item py-2 fw-bold text-secondary" href="{{ route('user.change-password') }}"><i class="bi bi-shield-lock me-2"></i> Ganti Kata Sandi</a></li>
            </ul>
        </div>
    </div>

    <!-- Form Container -->
    <div class="dashboard-container">
        <!-- Rata Kiri sesuai desain, tapi lebarnya dibatasi (max-width: 800px) -->
        <div class="form-card mx-0">
            <h4 class="form-title">Absensi Harian</h4>

            @php
                $hasMasuk = isset($absenToday) && !empty($absenToday->jam_masuk);
                $hasPulang = isset($absenToday) && !empty($absenToday->jam_pulang);
                $statusToday = isset($absenToday) ? ($absenToday->status ?? null) : null;
                $isIzinOrSakit = in_array($statusToday, ['sakit', 'izin']);
                $needsLocation = !$isIzinOrSakit && (!isset($absenToday) || empty($absenToday->lokasi) || empty($absenToday->latitude) || empty($absenToday->longitude));
            @endphp

            @if($needsLocation && !$isIzinOrSakit)
                <div class="location-hint">
                    Lokasi belum tersedia. Silakan tekan <strong>Dapatkan Lokasi GPS</strong> sebelum absen masuk.
                </div>
            @endif

            <!-- Info Card - Date, Time, Location -->
            <div class="info-card">
                <div class="info-label">📅 Tanggal</div>
                <div class="info-value" id="display-date">-</div>
                
                <div class="info-label">⏰ Jam</div>
                <div class="info-time" id="display-time">00:00:00</div>

                <div class="location-info">
                    <span class="location-info-label">📍 Lokasi Saat Ini</span>
                    <span class="location-info-value" id="current-location">{{ isset($absenToday) && $absenToday->lokasi ? $absenToday->lokasi . ' (' . ($absenToday->latitude ? number_format($absenToday->latitude,4) : '') . ', ' . ($absenToday->longitude ? number_format($absenToday->longitude,4) : '') . ')' : 'Menunggu data lokasi...' }}</span>
                </div>
            </div>

            <form action="{{ route('petugas.absen.masuk') }}" method="POST" id="form-absen-masuk">
                @csrf
                <input type="hidden" name="status" id="status-input" value="hadir">
                <input type="hidden" name="type" id="type-input" value="masuk">
                
                <!-- Input Lokasi dengan Map -->
                @if(!$hasMasuk && !$isIzinOrSakit)
                <div class="mb-5">
                    <label class="custom-label">Lokasi GPS Pegawai</label>
                    <div class="box-lokasi-wrapper">
                        <div id="lokasi-map" class="lokasi-map"></div>
                        <div id="lokasi-display" class="lokasi-overlay">Tekan tombol GPS untuk mengambil lokasi pegawai</div>
                        <input type="hidden" name="lokasi" id="lokasi-input">
                        <input type="hidden" name="latitude" id="latitude-input">
                        <input type="hidden" name="longitude" id="longitude-input">
                        
                        <button type="button" class="btn-lokasi" id="btn-get-lokasi" title="Dapatkan Lokasi GPS">
                            <i class="bi bi-geo-alt"></i>
                        </button>
                    </div>
                </div>
                @endif

                <!-- Tombol Status -->
                <div class="d-flex justify-content-center gap-3 mt-5 pt-3 flex-wrap">
                    @if(!$isIzinOrSakit)
                    <button type="button" id="btn-masuk" 
                        class="btn btn-status {{ $hasMasuk ? 'btn-hadir-done' : 'btn-status-masuk' }}"
                        {{ $hasMasuk ? 'disabled' : '' }}>
                        <i class="bi bi-check-circle me-2"></i> Absen Masuk
                    </button>

                    <button type="button" id="btn-sakit" 
                        class="btn btn-status btn-izin"
                        {{ $hasMasuk ? 'disabled' : '' }}>
                        <i class="bi bi-hospital me-2"></i> Sakit
                    </button>

                    <button type="button" id="btn-izin" 
                        class="btn btn-status btn-izin"
                        {{ $hasMasuk ? 'disabled' : '' }}>
                        <i class="bi bi-file-earmark-check me-2"></i> Izin
                    </button>

                    <button type="button" id="btn-pulang" 
                        class="btn btn-status btn-status-pulang"
                        style="{{ (!$hasMasuk) ? 'display: none;' : '' }}"
                        {{ (!$hasMasuk || $hasPulang) ? 'disabled' : '' }}>
                        <i class="bi bi-x-circle me-2"></i> Absen Pulang
                    </button>
                    @else
                    <!-- Jika sudah sakit/izin, jangan tampilkan tombol Absen Masuk/Pulang -->
                    <div class="text-center text-muted">Keterangan: {{ ucfirst($statusToday) }}</div>
                    @endif
                </div>

                <!-- Alert Messages -->
                @if ($errors->any())
                    <div class="alert alert-danger mt-3">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger mt-3">
                        {{ session('error') }}
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi Map jika container ada
        const defaultCenter = [-2.548926, 118.014863];
        const mapContainer = document.getElementById('lokasi-map');
        let map = null;
        let marker = null;
        let userMarker = null; // Marker khusus untuk lokasi GPS user

        if (mapContainer) {
            map = L.map('lokasi-map').setView(defaultCenter, 5);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
            }).addTo(map);

            marker = L.marker(defaultCenter).addTo(map);
            map.whenReady(function () {
    map.invalidateSize();
});

            // Invalidate map size setelah rendering, dan juga on resize
            setTimeout(() => {map.invalidateSize();}, 800);
            window.addEventListener('resize', function() { setTimeout(function() { map.invalidateSize(); }, 150); });
        }

        // Format tanggal ke Bahasa Indonesia
        function formatTanggal(date) {
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            return date.toLocaleDateString('id-ID', options).replace(/^./, c => c.toUpperCase());
        }

        // Update tanggal
        function updateDate() {
            const now = new Date();
            document.getElementById('display-date').textContent = formatTanggal(now);
        }

        // Update jam
        function updateTime() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('display-time').textContent = `${hours}:${minutes}:${seconds}`;
        }

        // Inisialisasi waktu saat halaman dimuat
        updateDate();
        updateTime();
        setInterval(updateTime, 1000);

        // Fungsi reverse geocoding (mendapatkan nama lokasi dari koordinat)
        async function getLocationName(lat, lng) {
            try {
                const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`);
                const data = await response.json();
                return data.address.city || data.address.town || data.address.village || `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
            } catch (error) {
                return `Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;
            }
        }

        function setLocationDisplay(lat, lng) {
            const disp = document.getElementById('lokasi-display');
            const lokasiText = `Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;
            if (disp) disp.textContent = lokasiText;
            const lokasiInput = document.getElementById('lokasi-input');
            const latInput = document.getElementById('latitude-input');
            const lngInput = document.getElementById('longitude-input');
            if (lokasiInput) lokasiInput.value = lokasiText;
            if (latInput) latInput.value = lat;
            if (lngInput) lngInput.value = lng;

            if (marker && map) {
                marker.setLatLng([lat, lng]);
                map.setView([lat, lng], 17);
            }

            // Update current location info card
            getLocationName(lat, lng).then(name => {
                const cur = document.getElementById('current-location');
                if (cur) cur.textContent = name + ` (${lat.toFixed(4)}, ${lng.toFixed(4)})`;
            });
        }

        function setCoordinates(lat, lng) {
            setLocationDisplay(lat, lng);
        }

        const btnGetLokasi = document.getElementById('btn-get-lokasi');
        if (btnGetLokasi) {
            btnGetLokasi.addEventListener('click', function() {
                if (!navigator.geolocation) {
                    alert('Geolocation tidak didukung oleh browser Anda.');
                    return;
                }

                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    setCoordinates(lat, lng);

                    // Buat atau update marker khusus user dengan popup "Lokasi Saya"
                    if (map) {
                        // Buat custom icon biru untuk user location
                        const userIcon = L.icon({
                            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41],
                            popupAnchor: [1, -34],
                            shadowSize: [41, 41]
                        });

                        if (userMarker) {
                            userMarker.setLatLng([lat, lng]).setIcon(userIcon).openPopup();
                        } else {
                            userMarker = L.marker([lat, lng], { icon: userIcon }).addTo(map)
                                .bindPopup('Lokasi Saya').openPopup();
                        }

                        // Pindahkan map ke lokasi user
                        map.setView([lat, lng], 17);
                    }
                }, function(err) {
                    alert('Gagal mendapatkan lokasi: ' + err.message);
                }, { enableHighAccuracy: true });
            });
        }

        const needsLocation = @json($needsLocation && !$isIzinOrSakit);
        if (needsLocation) {
            const locationModalEl = document.createElement('div');
            locationModalEl.className = 'modal fade';
            locationModalEl.id = 'locationReminderModal';
            locationModalEl.tabIndex = -1;
            locationModalEl.setAttribute('aria-hidden', 'true');
            locationModalEl.innerHTML = `
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-2 border-dark">
                        <div class="modal-header bg-warning text-dark">
                            <h5 class="modal-title fw-bold">Berikan Lokasi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Lokasi belum ada. Tekan <strong>Dapatkan Lokasi GPS</strong> untuk mengambil lokasi pegawai sebelum absen masuk.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Nanti</button>
                            <button type="button" class="btn btn-primary" id="modal-get-location-btn">Dapatkan Lokasi GPS</button>
                        </div>
                    </div>
                </div>`;
            document.body.appendChild(locationModalEl);
            const locationReminderModal = new bootstrap.Modal(locationModalEl, { backdrop: 'static', keyboard: false });
            locationReminderModal.show();

            locationModalEl.querySelector('#modal-get-location-btn').addEventListener('click', function () {
                const btn = document.getElementById('btn-get-lokasi');
                if (btn) {
                    btn.click();
                }
                locationReminderModal.hide();
            });
        }

        // Handle tombol Absen Masuk (hanya jika tombol ada)
        const btnMasuk = document.getElementById('btn-masuk');
        if (btnMasuk) {
            btnMasuk.addEventListener('click', function() {
                const latEl = document.getElementById('latitude-input');
                const lngEl = document.getElementById('longitude-input');
                const latitude = latEl ? latEl.value : '';
                const longitude = lngEl ? lngEl.value : '';

                if (!latitude || !longitude) {
                    alert('Silakan pilih lokasi terlebih dahulu!');
                    return;
                }

                document.getElementById('status-input').value = 'hadir';
                document.getElementById('type-input').value = 'masuk';
                document.getElementById('form-absen-masuk').action = "{{ route('petugas.absen.masuk') }}";
                document.getElementById('form-absen-masuk').submit();
            });
        }

        // Handle tombol Sakit (tidak perlu lokasi)
        const btnSakit = document.getElementById('btn-sakit');
        if (btnSakit) {
            btnSakit.addEventListener('click', function() {
                document.getElementById('status-input').value = 'sakit';
                document.getElementById('type-input').value = 'masuk';
                document.getElementById('form-absen-masuk').action = "{{ route('petugas.absen.masuk') }}";
                document.getElementById('form-absen-masuk').submit();
            });
        }

        // Handle tombol Izin (tidak perlu lokasi)
        const btnIzin = document.getElementById('btn-izin');
        if (btnIzin) {
            btnIzin.addEventListener('click', function() {
                document.getElementById('status-input').value = 'izin';
                document.getElementById('type-input').value = 'masuk';
                document.getElementById('form-absen-masuk').action = "{{ route('petugas.absen.masuk') }}";
                document.getElementById('form-absen-masuk').submit();
            });
        }

        // Handle tombol Absen Pulang (tidak perlu lokasi)
        const btnPulang = document.getElementById('btn-pulang');
        if (btnPulang) {
            btnPulang.addEventListener('click', function() {
                document.getElementById('type-input').value = 'pulang';
                document.getElementById('form-absen-masuk').action = "{{ route('petugas.absen.pulang') }}";
                document.getElementById('form-absen-masuk').submit();
            });
        }
    });
</script>
</body>
</html>