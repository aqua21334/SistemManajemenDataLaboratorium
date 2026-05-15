<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Laboratorium - Balai Teknik Rawa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa; /* Latar abu-abu sangat terang */
            font-family: 'Times New Roman', Times, serif; 
        }

        /* Hero membentang 100% layar */
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

        /* Navbar membentang 100% layar */
        .nav-container {
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            position: relative;
            padding: 15px 0;
            margin-bottom: 40px;
        }
        
        .nav-links a { color: #000; text-decoration: none; font-weight: bold; margin-right: 20px; font-family: sans-serif;}
        
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

        .btn-masuk { background-color: #e2e8f0; color: white; }
        .btn-daftar { background-color: #e2e8f0; color: #000; }
        
        /* KUNCI: Membungkus konten agar tetap di tengah dan tidak melar */
        .content-box {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
            margin-bottom: 40px;
        }

        /* Elemen dalam konten */
        .garis-tengah { border-right: 2px solid #e2e8f0; }
        .table-custom { border: 1px solid #dee2e6; }
        .table-custom thead { background-color: #2b4c65; color: white; text-align: center;}
        h5 { font-weight: bold; }
        p { font-family: sans-serif; font-size: 15px; text-align: justify; }

        /* Footer membentang 100% */
        .footer-section {
            background-color: #2b4c65;
            color: white;
            padding: 50px 0 20px 0;
            margin-top: 50px;
        }
    </style>
</head>
<body>

    <div class="hero-section">
        <div class="container"> <h2 class="mb-0">Selamat Datang Di,</h2>
            <h1 class="fw-bold display-5">Laboratorium Balai Teknik Rawa</h1>
        </div>
    </div>

    <div class="nav-container">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="nav-links">
                <a href="#">Beranda</a>
                <a href="#">Layanan</a>
            </div>
            
            <div class="logo-tengah">
    <img src="{{ asset('images/Logo-pupr.jpeg') }}" alt="Logo PU" style="width: 82px; height: 82px; border-radius: 50%; object-fit: contain; padding: 2px;">
</div>

            <div class="nav-links">
                <a href="#">Dokumentasi</a>
               @guest
                    <a href="/login" class="btn btn-masuk btn-sm px-3 ms-2">Masuk</a>
                    <a href="/register" class="btn btn-daftar btn-sm px-3 ms-1">Daftar</a>
                @endguest

                @auth
                    @if(Auth::user()->role->nama_role == 'Customer')
                        <span class="text-success fw-bold ms-2">Halo, {{ Auth::user()->nama }}</span>
                    @else
                        <a href="/admin/dashboard" class="btn btn-outline-primary btn-sm px-2 ms-2">Dashboard</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm px-2 ms-1">Logout</button>
                    </form>
                @endauth            
		</div>
        </div>
    </div>

    <div class="container">
        
        <div class="content-box">
            <div class="row">
                <div class="col-md-6 garis-tengah pe-md-4 mb-4 mb-md-0">
                    <h5>Profil Singkat:</h5>
                    <p class="mb-0">Balai Teknik Rawa merupakan unit kerja teknis yang bergerak di bidang penelitian, pengembangan, dan pengelolaan sumber daya rawa serta lahan basah. Instansi ini berperan dalam mendukung pengembangan teknologi dan inovasi untuk pengelolaan rawa yang berkelanjutan, ramah lingkungan, serta bermanfaat bagi masyarakat.</p>
                </div>
                <div class="col-md-6 ps-md-4">
                    <h5>Apa Itu Sistem Manajemen Data Penelitian Laboratorium Balai Teknik Rawa?</h5>
                    <p class="mt-2 mb-0">Sistem Manajemen berbasis web yang dirancang untuk mengelola seluruh data penelitian laboratorium secara terstruktur, terintegrasi, dan mudah diakses.</p>
                </div>
            </div>
        </div>

        <div class="content-box">
            <h5 class="mb-4 border-bottom pb-2">Layanan:</h5>
            <form action="#" method="POST">
                <div class="row mb-4">
                    <div class="col-md-5">
                        <label class="form-label text-muted small fw-bold">Jenis Permintaan</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="col-md-5 offset-md-2">
                        <label class="form-label text-muted small fw-bold">Jenis Layanan</label>
                        <input type="file" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <label class="form-label text-muted small fw-bold">No. HandPhone</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="col-md-5 offset-md-2 d-flex align-items-end justify-content-end">
                      @guest
                            <button type="button" class="btn btn-masuk px-5" disabled style="opacity: 0.6; cursor: not-allowed;">Kirim</button>
                            <small class="text-danger mt-2 fst-italic text-end" style="font-size: 12px;">
                                *silakan Masuk (Login) terlebih dahulu untuk mengirim permintaan layanan!
                            </small>
                        @else
                            <button type="submit" class="btn btn-masuk px-5">Kirim</button>
                        @endguest
                    </div>
                </div>
            </form>
        </div>
            @for ($i = 0; $i < 3; $i++)
            <div class="row mb-4 align-items-center">
                <div class="col-md-4">
                    <div style="background-color: #0dcaf0; height: 140px; display:flex; align-items:center; justify-content:center; color:white; font-weight:bold; border-radius:8px;">
                        Imagine LIFE without THIS?
                    </div>
                </div>
                <div class="col-md-8">
                    <h5 class="mb-2">Daftar Pengaduan</h5>
                    <p class="text-muted text-justify">Balai Teknik Rawa merupakan unit kerja teknis yang bergerak di bidang penelitian, pengembangan, dan pengelolaan sumber daya rawa serta lahan basah. Instansi ini berperan dalam mendukung pengembangan teknologi dan inovasi untuk pengelolaan rawa yang berkelanjutan, ramah lingkungan, serta bermanfaat bagi masyarakat.</p>
                </div>
            </div>
            @endfor
        </div>

    </div>

      <footer class="container py-5 mt-5" style="border-top: 1px solid #d1d5db;">
        <div class="row gy-5"> <div class="col-md-6">
                <h5 class="fw-bold text-dark mb-3">Balai Teknik Rawa</h5>
                <p class="text-dark" style="line-height: 1.8;">Kementerian Pekerjaan Umum dan<br>Perumahan Rakyat Direktorat Sumber<br>Daya Air</p>
            </div>
            
            <div class="col-md-6">
                <h5 class="fw-bold text-dark mb-3">Alamat</h5>
                <p class="text-dark" style="line-height: 1.8;">Jl. Gatot Subroto No. 6, Kebun Bunga, Kec.<br>Banjarmasin Timur Kota Banjarmasin,<br>Kalimantan Selatan 70235</p>
            </div>
            
            <div class="col-md-6">
                <h5 class="fw-bold text-dark mb-3">Contact Us</h5>
                <p class="text-dark">Phone : 0511 - 3256623</p>
            </div>
            
            <div class="col-md-6">
                <h5 class="fw-bold text-dark mb-3">Email</h5>
                <p class="text-dark">balaiteknikrawa@pu.go.id</p>
            </div>
            
        </div>
    </footer>
    

</body>
</html>