<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Laboratorium - Balai Teknik Rawa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <style>
        body {
            background-color: #f8f9fa; /* Latar abu-abu sangat terang */
            font-family: 'Times New Roman', Times, serif; 
        }

        html {
            scroll-behavior: smooth;
        }

        * { box-sizing: border-box; }

        /* Hero membentang 100% layar */
        .hero-section {
            background-image: linear-gradient(rgba(0,0,0,0.5),rgba(0,0,0,0.5)), url("{{ asset('images/hero-btr.jpg') }}");
            background-size: cover;
            background-position: center;
            min-height: 450px;
            display: flex;
            align-items: center;
            color: white;
            border-bottom: 3px solid #FACC15; /* Garis kuning PU */
            padding: 48px 0;
        }

        /* Navbar membentang 100% layar */
        .nav-container {
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            position: relative;
            padding: 15px 0;
            margin-bottom: 40px;
        }
        
        .nav-links {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .nav-links a { color: #000; text-decoration: none; font-weight: bold; margin-right: 0; font-family: sans-serif;}
        .nav-links a:hover { color: #2b4c65; }
        
        /* Logo PU Bundar tetap di tengah */
        .logo-tengah {
            position: absolute;
            left: 50%;
            transform: translate(-50%, -50%);
            top: 0; 
            width: 90px;
            height: 90px;
            background-color: #FACC15; 
            border: 4px solid #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            z-index: 10;
        }

        .btn-masuk { background-color: #e2e8f0; color: #000; font-weight: 600; }
        .btn-daftar { background-color: #e2e8f0; color: #000; font-weight: 600; }
        
        /* Tombol Custom Tema Biru PU */
        .btn-custom-dark { background-color: #2b4c65; color: white; transition: 0.3s; }
        .btn-custom-dark:hover { background-color: #1c3345; color: white; }
        
        /* Membungkus konten agar tetap di tengah dan rapi */
        .content-box {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
            margin-bottom: 40px;
        }

        .document-image {
            width: 100%;
            height: 280px;
            object-fit: cover;
        }

        .document-title { font-size: 30px; font-weight: bold; }
        .document-text { font-size: 20px; line-height: 1.8; }

        /* Elemen dalam konten */
        .garis-tengah { border-right: 2px solid #e2e8f0; }
        .table-custom { border: 1px solid #dee2e6; background-color: #fff; }
        .table-custom thead { background-color: #2b4c65; color: white; text-align: center; }
        h5 { font-weight: bold; }
        p { font-family: sans-serif; font-size: 15px; text-align: justify; }

        /* Invoice styles */
        .invoice-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 6px 12px;
            border: 1px solid #1e5bff;
            border-radius: 6px;
            background: #1e5bff;
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }
        .invoice-link:hover {
            background: #1748cc;
            color: #fff;
        }
        .invoice-empty { color: #6c757d; font-style: italic; font-size: 14px; }

        /* Footer membentang 100% */
        .footer-section {
            background-color: #2b4c65;
            color: white;
            padding: 50px 0 20px 0;
            margin-top: 50px;
        }
        .footer-section h5, .footer-section p { color: white !important; }

        @media (max-width: 767.98px) {
            .hero-section { min-height: 280px; padding: 32px 0; text-align: center; }
            .hero-section h1 { font-size: 2rem; }
            .hero-section h2 { font-size: 1.1rem; }
            .nav-container { padding: 18px 0 16px; margin-bottom: 24px; }
            .nav-container .container { flex-direction: column; gap: 12px; }
            .logo-tengah { position: static; transform: none; margin: 0 auto; width: 76px; height: 76px; }
            .logo-tengah img { width: 68px !important; height: 68px !important; }
            .content-box { padding: 20px 12px; }
            .garis-tengah { border-right: 0; border-bottom: 2px solid #e2e8f0; padding-bottom: 16px; margin-bottom: 16px; }
            .document-image { height: 200px; margin-bottom: 12px; }
            .document-title { font-size: 1.25rem !important; }
            .document-text { font-size: 1rem !important; }
            .footer-section { padding-top: 32px; }
        }
    </style>
</head>
<body>

    <!-- HERO SECTION -->
    <div class="hero-section" id="beranda">
        <div class="container"> 
            <h2 class="mb-0">Selamat Datang Di,</h2>
            <h1 class="fw-bold display-5">Laboratorium Balai Teknik Rawa</h1>
        </div>
    </div>

    <!-- NAVBAR SECTION -->
    <div class="nav-container">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
            
            <div class="nav-links">
                <a href="#beranda">Beranda</a>
                <a href="#layanan">Layanan</a>
                <a href="#dokumentasi">Dokumentasi</a>
            </div>
            
            <div class="logo-tengah">
                <img src="{{ asset('images/logo-pupr-copy.jpg') }}" alt="Logo PU" style="width: 82px; height: 82px; border-radius: 50%; object-fit: contain; padding: 2px;">
            </div>

            <div class="nav-links d-flex align-items-center">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-masuk btn-sm px-3 ms-2">Masuk</a>
                    <a href="#" class="btn btn-daftar btn-sm px-3 ms-1">Daftar</a>
                @endguest

                @auth
                    @if(Auth::user()->role->nama_role == 'Customer')
                        <span class="text-success fw-bold ms-2" style="font-family: sans-serif;">Halo, {{ Auth::user()->nama }}</span>
                    @else
                        <a href="/admin/dashboard" class="btn btn-outline-primary btn-sm px-2 ms-2">Dashboard</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm px-2 ms-2">Logout</button>
                    </form>
                @endauth            
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="container">
        
        <!-- PROFIL SINGKAT -->
        <div class="content-box">
            <div class="row">
                <div class="col-md-6 garis-tengah pe-md-4 mb-4 mb-md-0">
                    <h5>Profil Singkat</h5>
                    <p class="mb-0">Balai Teknik Rawa merupakan unit kerja teknis yang bergerak di bidang penelitian, pengembangan, dan pengelolaan sumber daya rawa serta lahan basah. Instansi ini berperan dalam mendukung pengembangan teknologi dan inovasi untuk pengelolaan rawa yang berkelanjutan, ramah lingkungan, serta bermanfaat bagi masyarakat.</p>
                </div>
                <div class="col-md-6 ps-md-4">
                    <h5>Apa Itu Sistem Manajemen Data Penelitian Laboratorium Balai Teknik Rawa?</h5>
                    <p class="mt-2 mb-0">Sistem Manajemen berbasis web yang dirancang untuk mengelola seluruh data penelitian laboratorium secara terstruktur, terintegrasi, dan mudah diakses.</p>
                </div>
            </div>
        </div>

        <!-- FORM LAYANAN -->
        <div class="mb-5" id="layanan">
            <h4 class="fw-bold mb-4 border-bottom pb-2">Layanan</h4>
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="content-box py-4 px-4 shadow-sm">
                <form action="{{ route('customer.permintaan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf 
                    <div class="row mb-3">
                        <!-- KOLOM KIRI -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold" style="font-family: sans-serif;">Jenis Permintaan</label>
                                <input type="text" class="form-control border-secondary" name="jenis_permintaan" required>
                            </div>
                            <div class="mb-3 mb-md-0">
                                <label class="form-label fw-semibold" style="font-family: sans-serif;">No. HandPhone</label>
                                <input type="text" class="form-control border-secondary" name="no_hp" required>
                            </div>
                        </div>

                        <!-- KOLOM KANAN -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-family: sans-serif;">File Pendukung (Dokumen)</label>
                            <div class="input-group mb-3">
                                <input type="file" class="form-control border-secondary" id="fileLayanan" name="file_layanan" accept=".pdf,.doc,.docx,.zip,.rar" required>
                                <label class="input-group-text bg-light border-secondary" for="fileLayanan">Unggah</label>
                            </div>

                            <label class="form-label fw-semibold" style="font-family: sans-serif;">Bukti Pembayaran</label>
                            <div class="input-group">
                                <input type="file" class="form-control border-secondary" id="buktiBayar" name="bukti_bayar" accept="image/*,.pdf">
                                <label class="input-group-text bg-light border-secondary" for="buktiBayar">Unggah</label>
                            </div>
                            <small class="text-muted" style="font-family: sans-serif;">Format: jpg, png, pdf. Maks 2MB.</small>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6 ms-auto d-flex align-items-center justify-content-between">
                            <div class="alert alert-info mb-0 py-2" role="alert" style="font-family: sans-serif;">
                                <strong>Tarif:</strong> Rp 100.000
                            </div>
                            <button type="submit" class="btn btn-custom-dark px-5">Kirim</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- DAFTAR PENGADUAN -->
        <div class="mb-5">
            <h4 class="fw-bold mb-4 border-bottom pb-2">Daftar Pengaduan Yang Telah Anda Ajukan</h4>
            <div class="table-responsive shadow-sm rounded">
                <table class="table table-bordered table-custom text-center align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="py-3">Pemohon</th>
                            <th class="py-3">Jenis Permintaan</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">File Hasil</th>
                            <th class="py-3">Invoice</th>
                        </tr>
                    </thead>
                    <tbody style="font-family: sans-serif;">
                        @forelse($permintaanlayanans ?? [] as $p)
                            <tr>
                                <td class="text-start ps-3">{{ $p->pemohon ?? 'Tidak ada nama' }}</td>
                                <td class="text-start ps-3">{{ $p->jenis_permintaan }}</td>
                                <td>
                                    @if($p->status == 'sedang diproses')
                                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Sedang Diproses</span>
                                    @elseif($p->status == 'diverifikasi')
                                        <span class="badge bg-info text-dark px-3 py-2 rounded-pill">Diverifikasi</span>
                                    @elseif($p->status == 'selesai')
                                        <span class="badge bg-success px-3 py-2 rounded-pill">Selesai</span>
                                    @else
                                        <span class="badge bg-secondary px-3 py-2 rounded-pill">{{ $p->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if(($p->status == 'selesai' || $p->status == 'diverifikasi') && $p->laporanHasil)
                                        <a href="{{ route('customer.hasil.download', $p->id_permintaan) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                            <i class="bi bi-download me-1"></i> Unduh Hasil
                                        </a>
                                    @else
                                        <span class="text-muted small fst-italic"><i class="bi bi-clock-history me-1"></i> Belum Tersedia</span>
                                    @endif
                                </td>
                                <td>
                                    @if($p->pnbp)
                                        <a href="{{ route('customer.invoice', $p->pnbp->id_pnbp) }}" target="_blank" class="invoice-link">
                                            <i class="bi bi-file-earmark-pdf"></i> Invoice
                                        </a>
                                    @else
                                        <span class="invoice-empty">Belum Tersedia</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 text-muted fst-italic bg-light">
                                    Belum ada riwayat permintaan layanan untuk akun Anda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- DOKUMENTASI -->
        <div class="mb-5" id="dokumentasi">
            
            @php
                $data_dokumentasi = [
                    [
                        'foto' => 'images/dokumentasi1.jpg',
                        'judul' => 'Layanan Pelanggan',
                        'keterangan' => 'Layanan pelanggan di Balai Teknik Rawa merupakan bagian penting dalam memberikan informasi, bantuan, dan pelayanan kepada masyarakat maupun pihak terkait. Layanan ini bertujuan untuk membantu pengguna memperoleh informasi mengenai kegiatan, layanan laboratorium, konsultasi teknis, serta berbagai kebutuhan administrasi yang berkaitan dengan Balai Teknik Rawa.'
                    ],
                    [
                        'foto' => 'images/dokumentasi2.jpg',
                        'judul' => 'Pengukuran Topografi',
                        'keterangan' => 'Surveyor Laboratorium Balai Teknik Rawa melaksanakan kegiatan pengukuran topografi menggunakan peralatan GPS RTK Trimble R10 GNSS di Dadahup, Kalimantan Tengah. Kegiatan ini dilakukan untuk memperoleh data posisi dan kondisi permukaan lahan secara akurat sebagai dasar perencanaan dan pengelolaan wilayah. Pengukuran topografi bertujuan mengetahui karakteristik medan, seperti elevasi, kontur tanah, batas area, serta kondisi fisik lingkungan di lokasi pengukuran'
                    ],
                    [
                        'foto' => 'images/dokumentasi3.jpg',
                        'judul' => 'Pengoperasian Peralatan Kualitas Air',
                        'keterangan' => 'Surveyor Laboratorium Balai Teknik Rawa melaksanakan pengoperasian peralatan kualitas air Horiba U50 di Jejangkit, Kalimantan Selatan, untuk menguji kualitas air pada saluran irigasi. Kegiatan ini bertujuan memperoleh data kualitas air secara akurat sebagai bahan analisis dan evaluasi kondisi perairan. Pengujian ini penting untuk mendukung pengelolaan sumber daya air, khususnya pada wilayah dengan sistem irigasi.'
                    ],
                    [
                        'foto' => 'images/dokumentasi4.jpg', // Sesuaikan dengan nama file foto 4
                        'judul' => 'Pengoperasian Peralatan Acoustic Doppler Current Profiler',
                        'keterangan' => 'Pengoperasian Peralatan Acoustic Doppler Current Profiler (ADCP) merupakan kegiatan pengukuran kecepatan dan arah arus air menggunakan alat berbasis gelombang akustik dengan prinsip efek Doppler. ADCP dipasang pada perahu survei dan dioperasikan sepanjang jalur pengukuran yang telah ditentukan, sementara data direkam secara otomatis melalui perangkat lunak yang terhubung dengan GPS. Hasil pengukuran menghasilkan profil arus pada berbagai kedalaman yang dimanfaatkan untuk survei hidrografi, analisis kondisi perairan, serta mendukung perencanaan dan pengelolaan sumber daya air.'
                    ],
                ];
            @endphp

            <h4 class="fw-bold mb-4 border-bottom pb-2">
                Dokumentasi
            </h4>

            @foreach ($data_dokumentasi as $item)

            <div class="row mb-5 align-items-center">

                <!-- FOTO -->
                <div class="col-md-4">
                    @php($fotoDokumentasi = $item['foto'])
                    <img src="{{ asset($fotoDokumentasi) }}"
                         alt="{{ $item['judul'] }}"
                         class="img-fluid rounded shadow-sm document-image">
                </div>

                <!-- KETERANGAN -->
                <div class="col-md-8">

                    <h5 class="document-title" style="font-family:'Times New Roman',Times,serif;">
                        {{ $item['judul'] }}
                    </h5>

                    <p class="text-muted document-text" style="font-family:'Times New Roman',Times,serif; text-align:justify;">
                        {{ $item['keterangan'] }}
                    </p>

                </div>

            </div>

            @endforeach

        </div>

    </div>

    <!-- FOOTER -->
    <footer class="footer-section">
        <div class="container">
            <div class="row gy-4"> 
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Balai Teknik Rawa</h5>
                    <p style="line-height: 1.8;">Kementerian Pekerjaan Umum dan<br>Perumahan Rakyat Direktorat Sumber<br>Daya Air</p>
                </div>
                
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Alamat</h5>
                    <p style="line-height: 1.8;">Jl. Gatot Subroto No. 6, Kebun Bunga, Kec.<br>Banjarmasin Timur Kota Banjarmasin,<br>Kalimantan Selatan 70235</p>
                </div>
                
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Contact Us</h5>
                    <p>Phone : 0511 - 3256623</p>
                </div>
                
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Email</h5>
                    <p>balaiteknikrawa@pu.go.id</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>