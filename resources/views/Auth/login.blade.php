<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Balai Teknik Rawa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <style>
        body { 
            background:
                linear-gradient(rgba(20, 30, 40, 0.55), rgba(20, 30, 40, 0.55)),
                url("{{ asset('images/hero-btr.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            display: flex; justify-content: center; align-items: center; 
            min-height: 100vh; font-family: 'Times New Roman', serif; margin: 0;
        }
        * { box-sizing: border-box; }

        .auth-card {
            display: flex;
            width: 100%;
            max-width: 900px;
            background: #fff;
            box-shadow: 0 16px 40px rgba(0,0,0,0.45);
            border-radius: 6px;
            overflow: hidden;
            margin: 32px;
        }

        .form-side {
            flex: 1 1 50%;
            padding: 50px;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .panel-side {
            flex: 1 1 50%;
            padding: 50px;
            background-color: #2b4c65;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .shape-1 { position: absolute; bottom: -20px; left: -20px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); transform: rotate(45deg); }
        .shape-2 { position: absolute; top: 10%; right: 10%; width: 50px; height: 50px; background: rgba(255,255,255,0.05); border-radius: 50%; }
        
        /* Header Logo - Dibuat bisa di-hover & diklik */
        .logo-header { position: absolute; top: 30px; left: 30px; display: flex; align-items: center; gap: 10px; text-decoration: none; transition: opacity 0.2s;}
        .logo-header:hover { opacity: 0.7; }
        .logo-header img { width: 40px; }
        .logo-header p { margin: 0; font-size: 10px; font-family: sans-serif; font-weight: bold; line-height: 1.2; color: #333; }
        
        h3 { font-weight: bold; color: #2b4c65; text-align: center; }
        .sub-text { font-family: sans-serif; font-size: 12px; color: #666; text-align: center; margin-bottom: 30px; }
        
        .input-group { border: 1px solid #333; border-radius: 4px; margin-bottom: 20px; overflow: hidden; }
        .input-group-text { background: transparent; border: none; color: #2b4c65; }
        .form-control { border: none; font-family: sans-serif; font-size: 14px; }
        .form-control:focus { box-shadow: none; outline: none; }
        
        .btn-custom { background-color: #b0c4de; color: #2b4c65; border: 1px solid #333; font-weight: bold; padding: 8px 40px; border-radius: 4px; }
        .btn-custom:hover { background-color: #9cb4d1; color: #1a3042; }

        /* Tautan Kembali */
        .back-link { font-family: sans-serif; font-size: 13px; color: #666; text-decoration: none; transition: color 0.2s; }
        .back-link:hover { color: #2b4c65; text-decoration: underline; }
        @media (max-width: 767.98px) {
            body { background-attachment: scroll; }
            .auth-card { flex-direction: column; margin: 16px; }
            .form-side, .panel-side { padding: 20px; width: 100%; }
            .logo-header { position: static; margin-bottom: 10px; }
            .logo-header p { font-size: 12px; }
            .btn-custom { padding: 8px 20px; }
        }
    </style>
</head>
<body>

    <div class="auth-card">
        <div class="form-side">
            <a href="/" class="logo-header">
                <img src="{{ asset('images/logo-btr.jpg') }}" alt="Logo">
                <p>Sistem Manajemen Data<br>Laboratorium Balai Teknik Rawa</p>
            </a>

            <div style="margin-top: 60px;">
                <h3>Masuk</h3>
                <p class="sub-text">Masukan terlebih dahulu email dan kata sandi Anda</p>

                @if(session('success'))
                    <div class="alert alert-success text-center py-2" style="font-family: sans-serif; font-size: 13px;">
                        {{ session('success') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger text-center py-2" style="font-family: sans-serif; font-size: 13px;">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="/login" method="POST">
                    @csrf
                    
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="email" class="form-control" name="email" placeholder="Email" required>
                    </div>

                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" name="password" placeholder="Kata Sandi" required>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-custom">Masuk</button>
                    </div>

                    <div class="text-center mt-3">
                        <a href="{{ route('password.request') }}" class="back-link">
                            Lupa Password?
                        </a>
                    </div>

                    <div class="text-center mt-4">
                        <a href="/" class="back-link">
                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Halaman Utama
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="panel-side">
            <div class="shape-1"></div>
            <div class="shape-2"></div>
            <i class="bi bi-star-fill text-white opacity-25 position-absolute" style="top: 30%; left: 20%;"></i>
            <i class="bi bi-star-fill text-white opacity-25 position-absolute" style="bottom: 20%; right: 30%;"></i>

            <h3 class="text-white mb-3">Selamat Datang Kembali</h3>
            <p style="font-family: sans-serif; font-size: 13px; margin-bottom: 40px; color: #e2e8f0;">
                Masukan Detail Pribadi Anda<br>Apabila Anda Belum Memiliki Akun
            </p>
            
            <a href="/register" class="btn btn-custom">Daftar</a>
        </div>
    </div>

</body>
</html>