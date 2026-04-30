<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Pegawai - Lab Rawa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body { background-color: #B2C3CF; font-family: 'Inter', sans-serif; }
        
        .sidebar { background-color: #345E6F; min-height: 100vh; color: white; padding: 20px 10px; width: 240px; position: fixed; }
        .sidebar-logo-text { font-size: 10px; line-height: 1.3; color: #E2E8F0; margin-top: 10px; }
        .nav-link { color: #CBD5E0; font-size: 14px; padding: 8px 15px; margin-bottom: 5px; border-radius: 8px; }
        .nav-link.active { background-color: rgba(255,255,255,0.1); color: white; }
        .main-wrapper { margin-left: 240px; padding: 25px; }

        .topbar-card { background: white; padding: 15px 30px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; }

        .dashboard-container { background-color: #DAE4EB; border-radius: 10px; padding: 25px; }
        .form-container { background-color: white; border-radius: 15px; padding: 40px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }

        .form-group-custom label { color: #345E6F; font-weight: bold; margin-bottom: 8px; display: block; }
        .form-control-custom { border: 2px solid #333; border-radius: 10px; padding: 10px 15px; width: 100%; outline: none; transition: 0.3s; background-color: white; }
        .form-control-custom:focus { border-color: #345E6F; }

        .input-file-wrapper { display: flex; border: 2px solid #333; border-radius: 10px; overflow: hidden; background: white; }
        .btn-unggah { background-color: #345E6F; color: white; padding: 10px 25px; border: none; font-weight: bold; cursor: pointer; }
        .file-name-display { padding: 10px; flex-grow: 1; color: #555; font-size: 13px; align-self: center; }

        .btn-form { border: 2px solid #333; border-radius: 10px; padding: 10px 45px; font-weight: bold; transition: 0.3s; text-decoration: none; display: inline-block; }
        .btn-kembali { background: white; color: black; }
        .btn-edit-action { background: #B2C3CF; color: black; border: 2px solid #333; }
        .btn-edit-action:hover { background: #9db1bd; }
    </style>
</head>
<body>

<div class="sidebar shadow">
    <div class="text-center mb-5 px-3">
        <img src="{{ asset('images/logo-btr.jpg') }}" width="60" class="rounded-circle border border-2 border-white">
        <p class="sidebar-logo-text fw-semibold">Sistem Manajemen Data<br>Laboratorium Balai Teknik Rawa</p>
    </div>
    <ul class="nav flex-column px-2">
        <li class="nav-item"><a href="{{ route('dashboard') }}" class="nav-link"><i class="bi bi-grid-fill me-2"></i> Dashboard</a></li>
        <li class="nav-item"><a href="{{ route('pegawai') }}" class="nav-link active"><i class="bi bi-people-fill me-2"></i> Pegawai</a></li>
        <li class="nav-item"><a href="{{ route('peralatan') }}" class="nav-link"><i class="bi bi-tools me-2"></i> Peralatan</a></li>
        <li class="nav-item"><a href="{{ route('sop.index') }}" class="nav-link"><i class="bi bi-file-earmark-check-fill me-2"></i> Daftar SOP</a></li>
        <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-file-earmark-text-fill me-2"></i> Laporan</a></li>
        <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-exclamation-square-fill me-2"></i> Riwayat Laporan</a></li>
        <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-wallet2 me-2"></i> PNBP</a></li>
        <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-person-badge-fill me-2"></i> Riwayat Absensi</a></li>
    </ul>
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
        <div class="form-container">
            <h4 class="fw-bold mb-5" style="color: #345E6F;">Edit Data Pegawai</h4>

            {{-- Menampilkan error validasi jika ada --}}
            @if ($errors->any())
                <div class="alert alert-danger mb-4 border-2 border-dark">
                    <ul class="m-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('pegawai.update', $personil->id_personil) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row g-5">
                    <div class="col-md-6">
                        <div class="form-group-custom mb-4">
                            <label>Id User</label>
                            <input type="text" class="form-control-custom shadow-sm" value="{{ $personil->id_user }}" disabled>
                            <input type="hidden" name="id_user" value="{{ $personil->id_user }}">
                        </div>
                        <div class="form-group-custom mb-4">
                            <label>Nama</label>
                            <input type="text" class="form-control-custom shadow-sm" name="nama" value="{{ old('nama', $personil->nama_personil) }}" required>
                        </div>
                        <div class="form-group-custom mb-4">
                            <label>Nomor Induk Pegawai</label>
                            <input type="text" class="form-control-custom shadow-sm" name="nip" value="{{ old('nip', $personil->nip) }}" required>
                        </div>
                        <div class="form-group-custom mb-4">
                            <label>Email</label>
                            <input type="email" class="form-control-custom shadow-sm" name="email" value="{{ old('email', $personil->email) }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-custom mb-4">
                            <label>Foto</label>
                            <div class="input-file-wrapper shadow-sm">
                                <label for="foto" class="btn-unggah m-0">Unggah</label>
                                <input type="file" id="foto" name="foto" hidden accept="image/*">
                                <div class="file-name-display" id="file-chosen">
                                    {{ $personil->foto ? $personil->foto : 'Ganti foto...' }}
                                </div>
                            </div>
                            @if($personil->foto)
                                <small class="text-muted d-block mt-2">Foto saat ini: <a href="{{ asset('images/pegawai/'.$personil->foto) }}" target="_blank">Lihat</a></small>
                            @endif
                        </div>
                        <div class="form-group-custom mb-4">
                            <label>Jabatan</label>
                            <input type="text" class="form-control-custom shadow-sm" name="jabatan" value="{{ old('jabatan', $personil->jabatan) }}" required>
                        </div>

                        <div class="d-flex justify-content-end gap-3 mt-5">
                            <a href="{{ route('pegawai') }}" class="btn-form btn-kembali">Kembali</a>
                            <button type="submit" class="btn-form btn-edit-action">Edit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const actualBtn = document.getElementById('foto');
    const fileChosen = document.getElementById('file-chosen');
    actualBtn.addEventListener('change', function(){
        fileChosen.textContent = this.files[0].name
    })
</script>

</body>
</html>