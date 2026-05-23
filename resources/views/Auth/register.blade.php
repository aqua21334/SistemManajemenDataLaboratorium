<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Balai Teknik Rawa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <style>
        body { 
            background-color: #222; display: flex; justify-content: center; align-items: center; 
            min-height: 100vh; font-family: 'Times New Roman', serif; margin: 0;
        }
        .auth-card { display: flex; width: 900px; background: #fff; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        .form-side { width: 50%; padding: 50px; display: flex; flex-direction: column; justify-content: center; }
        .panel-side { 
            width: 50%; padding: 50px; background-color: #2b4c65; color: white; 
            display: flex; flex-direction: column; justify-content: center; align-items: center; 
            text-align: center; position: relative; overflow: hidden;
        }
        
        .shape-1 { position: absolute; top: 40%; right: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.05); transform: rotate(45deg); }
        .shape-2 { position: absolute; bottom: 10%; left: 10%; width: 60px; height: 15px; background: rgba(255,255,255,0.05); transform: rotate(-30deg); }
        
        .logo-header { position: absolute; top: 30px; left: 30px; display: flex; align-items: center; gap: 10px; z-index: 10;}
        .logo-header img { width: 40px; }
        .logo-header p { margin: 0; font-size: 10px; font-family: sans-serif; font-weight: bold; line-height: 1.2; color: #fff; text-align: left; }
        
        h3 { font-weight: bold; color: #2b4c65; text-align: center; }
        .sub-text { font-family: sans-serif; font-size: 12px; color: #666; text-align: center; margin-bottom: 30px; }
        
        .input-group { border: 1px solid #333; border-radius: 4px; margin-bottom: 20px; overflow: hidden; }
        .input-group-text { background: transparent; border: none; color: #2b4c65; }
        .form-control { border: none; font-family: sans-serif; font-size: 14px; }
        .form-control:focus { box-shadow: none; outline: none; }
        
        .btn-custom { background-color: #b0c4de; color: #2b4c65; border: 1px solid #333; font-weight: bold; padding: 8px 40px; border-radius: 4px; }
        .btn-custom:hover { background-color: #9cb4d1; color: #1a3042; }
    </style>
</head>
<body>

    <div class="auth-card">
        <div class="panel-side">
            <div class="logo-header">
              <img src="{{ asset('images/logo-btr.jpg') }}" alt="Logo">
                <p>Sistem Manajemen Data<br>Laboratorium Balai Teknik Rawa</p>
            </div>

            <div class="shape-1"></div>
            <div class="shape-2"></div>
            <i class="bi bi-star-fill text-white opacity-25 position-absolute" style="top: 30%; right: 20%;"></i>

            <h3 class="text-white mb-3 mt-5">Selamat Datang</h3>
            <p style="font-family: sans-serif; font-size: 13px; margin-bottom: 40px; color: #e2e8f0;">
                Untuk tetap terhubung dengan kami<br>Masuk dengan informasi pribadi Anda
            </p>
            
            <a href="/login" class="btn btn-custom">Masuk</a>
        </div>

        <div class="form-side">
            <div>
                <h3>Buat Akun</h3>
                <p class="sub-text">Masukan nama, email dan kata sandi anda untuk pendaftaran</p>

                <form action="/register" method="POST">
                    @csrf
                    
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control" name="nama" placeholder="Nama" required>
                    </div>

                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control" name="email" placeholder="Email" required>
                    </div>

                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" name="password" placeholder="Kata Sandi" required>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-custom">Daftar</button>
                    </div>
                    <div class="text-center mt-4">
                        <a href="/" class="back-link">
                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Halaman Utama
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>