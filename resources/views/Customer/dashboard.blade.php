<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Laboratorium - Balai Teknik Rawa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* Styling Khusus */
        .hero-section {
            background-image: linear-gradient(rgba(0,0,0,0.5),rgba(0,0,0,0.5)), url("{{ asset('images/hero-btr.jpg') }}");
            background-size: cover;
            background-position: center;
            height: 300px;
            display: flex;
            align-items: center;
            color: white;
            border-bottom: 3px solid #FACC15; /* Garis kuning PU */
        }
        
        .navbar-custom {
            border-bottom: 2px solid #333;
            background-color: #fff;
            padding: 15px 0;
        }

        /* Styling untuk logo bundar di tengah */
        .center-logo-container {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            top: -30px; /* Mengangkat logo agar menabrak hero image */
            z-index: 10;
        }
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

        .section-border {
            border-bottom: 2px solid #ccc;
            padding: 40px 0;
        }

        .btn-custom-dark {
            background-color: #1e3a5f;
            color: white;
        }
        .btn-custom-dark:hover {
            background-color: #152b47;
            color: white;
        }
        
        .table-custom th {
            background-color: #1e3a5f;
            color: white;
        }
    </style>
</head>
<body>

    <div class="hero-section">
        <div class="container">
            <h1 class="display-5 fw-bold">Selamat Datang Di,</h1>
            <h2 class="fw-semibold">Laboratorium Balai Teknik Rawa</h2>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-custom position-relative">
        <div class="container">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 fw-bold">
                <li class="nav-item"><a class="nav-link text-dark" href="#">Beranda</a></li>
                <li class="nav-item"><a class="nav-link text-dark" href="#layanan">Layanan</a></li>
            </ul>

            <div class="logo-tengah">
    <img src="{{ asset('images/logo-btr.jpg') }}" alt="Logo PU" style="width: 82px; height: 82px; border-radius: 50%; object-fit: contain; padding: 2px;">
</div>

            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 fw-bold align-items-center">
                <li class="nav-item me-3"><a class="nav-link text-dark" href="#">Dokumentasi</a></li>
                
                @guest
                    <li class="nav-item me-2">
                        <a href="{{ route('login') }}" class="btn btn-custom-dark px-4">Masuk</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="btn btn-secondary px-4">Daftar</a>
                    </li>
                @endguest

                @auth
                    <li class="nav-item me-2">
                        @if(Auth::user()->role->nama_role == 'Customer')
                            <span class="navbar-text text-success fw-bold me-3">Halo, {{ Auth::user()->nama }}</span>
                        @else
                            <a href="/admin/dashboard" class="btn btn-outline-primary px-3">Ke Dashboard</a>
                        @endif
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger px-3">Logout</button>
                        </form>
                    </li>
                @endauth
            </ul>
        </div>
    </nav>

    <div class="container section-border">
        <div class="row">
            <div class="col-md-6 pe-md-5 border-end border-2">
                <h4 class="fw-bold mb-3">Profil Singkat:</h4>
                <p class="text-justify">Balai Teknik Rawa merupakan unit kerja teknis yang bergerak di bidang penelitian, pengembangan, dan pengelolaan sumber daya rawa serta lahan basah. Instansi ini berperan dalam mendukung pengembangan teknologi dan inovasi untuk pengelolaan rawa yang berkelanjutan, ramah lingkungan, serta bermanfaat bagi masyarakat.</p>
            </div>
            <div class="col-md-6 ps-md-5">
                <h5 class="fw-bold mb-3">Apa Itu Sistem Manajemen Data Penelitian Laboratorium Balai Teknik Rawa?</h5>
                <p class="text-justify">Sistem Manajemen berbasis web yang dirancang untuk mengelola seluruh data penelitian laboratorium secara terstruktur, terintegrasi, dan mudah diakses.</p>
            </div>
        </div>
    </div>

<div class="container section-border" id="layanan">
        <h4 class="fw-bold mb-4">Layanan:</h4>
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="/permintaan" method="POST" enctype="multipart/form-data">
            @csrf <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Jenis Permintaan</label>
                    <input type="text" class="form-control border-dark" name="jenis_permintaan" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Jenis Layanan (Dokumen)</label>
                    <div class="input-group">
                        <input type="file" class="form-control border-dark" id="fileLayanan" name="file_layanan" accept=".pdf,.doc,.docx,.zip,.rar" required>
                        <label class="input-group-text bg-white border-dark" for="fileLayanan">Unggah</label>
                    </div>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">No. HandPhone</label>
                    <input type="text" class="form-control border-dark" name="no_hp" required>
                </div>
                <div class="col-md-6 d-flex align-items-end justify-content-end">
                    <button type="submit" class="btn btn-custom-dark px-5">Kirim</button>
                </div>
            </div>
        </form>
    </div>

   <div class="container section-border">
    <h4 class="fw-bold mb-4">Daftar Pengaduan</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-custom text-center border-dark">
            <thead>
                <tr>
                    <th>Pemohon</th>
                    <th>Jenis Permintaan</th>
                    <th>Status</th>
                    <th>File</th>
                </tr>
            </thead>
            <tbody>
                {{-- Gunakan variabel huruf kecil sesuai compact di web.php --}}
                @forelse($permintaanlayanans as $p)
                    <tr>
                        <td class="align-middle text-start ps-3">{{ $p->pemohon ?? 'Tidak ada nama' }}</td>
                        <td class="align-middle text-start ps-3">{{ $p->jenis_permintaan }}</td>
                        <td class="align-middle">
                            @if($p->status == 'sedang diproses')
                                <span class="badge bg-warning text-dark px-3 py-2">Sedang Diproses</span>
                            @elseif($p->status == 'diverifikasi')
                                <span class="badge bg-info text-dark px-3 py-2">Diverifikasi</span>
                            @elseif($p->status == 'selesai')
                                <span class="badge bg-success px-3 py-2">Selesai</span>
                            @else
                                <span class="badge bg-secondary px-3 py-2">{{ $p->status }}</span>
                            @endif
                        </td>
                        <td class="align-middle">
                            @if($p->status == 'selesai' && $p->laporanHasil)
                                <a href="{{ asset('uploads/hasil/' . $p->laporanHasil->file_laporan) }}" 
                                   class="btn btn-sm btn-primary" target="_blank">
                                    <i class="bi bi-download me-1"></i> Unduh Hasil
                                </a>
                            @else
                                <span class="text-muted small fst-italic">
                                    <i class="bi bi-clock-history me-1"></i> Belum Tersedia
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-4 text-muted fst-italic">
                            Belum ada riwayat permintaan layanan untuk akun Anda.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

    <div class="container section-border">
        <h4 class="fw-bold mb-4">Dokumentasi</h4>
        <div class="row mb-4 align-items-center">
            <div class="col-md-4">
                <img src="https://via.placeholder.com/400x200/0dcaf0/ffffff?text=Imagine+Life" class="img-fluid rounded" alt="Ilustrasi">
            </div>
            <div class="col-md-8">
                <h5 class="fw-bold">Daftar Pengaduan</h5>
                <p>Balai Teknik Rawa merupakan unit kerja teknis yang bergerak di bidang penelitian, pengembangan, dan pengelolaan sumber daya rawa serta lahan basah. Instansi ini berperan dalam mendukung pengembangan teknologi dan inovasi untuk pengelolaan rawa yang berkelanjutan, ramah lingkungan, serta bermanfaat bagi masyarakat.</p>
            </div>
        </div>
        
        <div class="row mb-4 align-items-center">
            <div class="col-md-4">
                <img src="https://via.placeholder.com/400x200/0dcaf0/ffffff?text=Imagine+Life" class="img-fluid rounded" alt="Ilustrasi">
            </div>
            <div class="col-md-8">
                <h5 class="fw-bold">Daftar Pengaduan</h5>
                <p>Balai Teknik Rawa merupakan unit kerja teknis yang bergerak di bidang penelitian, pengembangan, dan pengelolaan sumber daya rawa serta lahan basah. Instansi ini berperan dalam mendukung pengembangan teknologi dan inovasi untuk pengelolaan rawa yang berkelanjutan, ramah lingkungan, serta bermanfaat bagi masyarakat.</p>
            </div>
        </div>
    </div>

    <footer class="container py-5">
        <div class="row">
            <div class="col-md-6 mb-3">
                <h5 class="fw-bold">Balai Teknik Rawa</h5>
                <p>Kementerian Pekerjaan Umum dan<br>Perumahan Rakyat Direktorat Sumber<br>Daya Air</p>
            </div>
            <div class="col-md-6 mb-3">
                <h5 class="fw-bold">Alamat</h5>
                <p>Jl. Gatot Subroto No. 6, Kebun Bunga, Kec.<br>Banjarmasin Timur Kota Banjarmasin,<br>Kalimantan Selatan 70235</p>
            </div>
            <div class="col-md-6">
                <h5 class="fw-bold">Contact Us</h5>
                <p>Phone : 0511 - 3256623</p>
            </div>
            <div class="col-md-6">
                <h5 class="fw-bold">Email</h5>
                <p>balaiteknikrawa@pu.go.id</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>