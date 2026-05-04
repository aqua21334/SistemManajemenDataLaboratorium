<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Permintaan - Lab Rawa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body { background-color: #B2C3CF; font-family: 'Inter', sans-serif; }
        
        /* Sidebar Styling */
        .sidebar { background-color: #345E6F; min-height: 100vh; color: white; padding: 20px 10px; width: 240px; position: fixed; }
        .sidebar-logo-text { font-size: 10px; line-height: 1.3; color: #E2E8F0; margin-top: 10px; }
        .nav-link { color: #CBD5E0; font-size: 14px; padding: 8px 15px; margin-bottom: 5px; border-radius: 8px; }
        .nav-link.active { background-color: rgba(255,255,255,0.1); color: white; }
        .main-wrapper { margin-left: 240px; padding: 25px; }

        /* Topbar White Box */
        .topbar-card { background: white; padding: 15px 30px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; }

        /* Form Container Styling */
        .dashboard-container { background-color: #DAE4EB; border-radius: 10px; padding: 25px; }
        .form-container { background-color: white; border-radius: 15px; padding: 40px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }

        .form-group-custom label { color: #345E6F; font-weight: bold; margin-bottom: 8px; display: block; }
        .form-control-custom { border: 2px solid #333; border-radius: 10px; padding: 10px 15px; width: 100%; outline: none; transition: 0.3s; background: white; }
        .form-control-custom:disabled { background: #f5f5f5; color: #999; }

        /* Status Radio Styling */
        .status-options { display: flex; gap: 20px; align-items: center; flex-wrap: wrap; }
        .status-item { display: flex; align-items: center; gap: 8px; color: #345E6F; font-weight: 500; }
        .status-item input[type="radio"] { width: 22px; height: 22px; border: 2px solid #333; cursor: pointer; }

        /* Input File / Unggah */
        .input-file-wrapper { display: flex; border: 2px solid #333; border-radius: 10px; overflow: hidden; background: white; }
        .btn-unggah { background-color: #1F4557; color: white; padding: 10px 25px; border: none; font-weight: bold; cursor: pointer; }
        .file-name-display { padding: 10px; flex-grow: 1; color: #555; font-size: 14px; align-self: center; }

        /* Date Input Wrapper */
        .date-input-wrapper { display: flex; gap: 10px; align-items: center; }
        .date-icon { font-size: 2rem; color: #333; }

        /* Buttons */
        .btn-form { border: 2px solid #333; border-radius: 10px; padding: 10px 45px; font-weight: bold; transition: 0.3s; text-decoration: none; display: inline-block; }
        .btn-kembali { background: white; color: black; }
        .btn-edit-action { background: #B2C3CF; color: black; }
        .btn-edit-action:hover { background: #9db1bd; }

        .current-file-info { font-size: 12px; color: #666; margin-top: 8px; }
        .current-file-info a { color: #345E6F; font-weight: 500; text-decoration: none; }
    </style>
</head>
<body>

<div class="sidebar shadow">
    <div class="text-center mb-5 px-3">
        <img src="{{ asset('images/logo-btr.jpg') }}" width="60" class="rounded-circle border border-2 border-white">
        <p class="sidebar-logo-text fw-semibold">Sistem Manajemen Data<br>Laboratorium Balai Teknik Rawa</p>
    </div>
    <ul class="nav flex-column px-2">
        <li class="nav-item"><a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="bi bi-grid-fill me-2"></i> Dashboard</a></li>
        <li class="nav-item"><a href="{{ route('admin.pegawai') }}" class="nav-link"><i class="bi bi-people-fill me-2"></i> Pegawai</a></li>
        <li class="nav-item"><a href="{{ route('admin.peralatan.index') }}" class="nav-link"><i class="bi bi-tools me-2"></i> Peralatan</a></li>
        <li class="nav-item"><a href="{{ route('admin.sop.index') }}" class="nav-link"><i class="bi bi-file-earmark-check-fill me-2"></i> Daftar SOP</a></li>
        <li class="nav-item"><a href="{{ route('admin.permintaan.index') }}" class="nav-link active"><i class="bi bi-file-earmark-text-fill me-2"></i> Laporan</a></li>
        <li class="nav-item"><a href="{{ route('admin.riwayat-penelitian.index') }}" class="nav-link"><i class="bi bi-file-earmark-bar-graph-fill me-2"></i> Riwayat Penelitian</a></li>
        <li class="nav-item"><a href="{{ route('admin.pnbp.index') }}" class="nav-link"><i class="bi bi-cash-stack me-2"></i> PNBP</a></li>
        <li class="nav-item"><a href="{{ route('admin.riwayat-absensi.index') }}" class="nav-link"><i class="bi bi-person-badge-fill me-2"></i> Riwayat Absensi</a></li>
    </ul>
    <div style="position: absolute; bottom: 30px; left: 25px;">
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
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
        <div class="form-container">
            <h4 class="fw-bold mb-5" style="color: #345E6F;">Edit Data Permintaan</h4>

            <form action="{{ route('admin.permintaan.update', $permintaan->id_permintaan) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row g-5">
                    <div class="col-md-6">
                        <div class="form-group-custom mb-4">
                            <label>Id Permintaan</label>
                            <input type="text" class="form-control-custom" name="id_permintaan" value="{{ $permintaan->id_permintaan }}" disabled>
                            <small class="text-muted">Id tidak dapat diubah</small>
                        </div>
                        <div class="form-group-custom mb-4">
                            <label>Jenis Permintaan</label>
                            <input type="text" class="form-control-custom @error('jenis_permintaan') is-invalid @enderror" name="jenis_permintaan" value="{{ $permintaan->jenis_permintaan }}" required>
                            @error('jenis_permintaan')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group-custom mb-4">
                            <label>Status</label>
                            <div class="status-options mt-2">
                                <div class="status-item">
                                    <input type="radio" name="status" value="sedang diproses" {{ $permintaan->status == 'sedang diproses' ? 'checked' : '' }}> Sedang Diproses
                                </div>
                                <div class="status-item">
                                    <input type="radio" name="status" value="diverifikasi" {{ $permintaan->status == 'diverifikasi' ? 'checked' : '' }}> Diverifikasi
                                </div>
                                <div class="status-item">
                                    <input type="radio" name="status" value="selesai" {{ $permintaan->status == 'selesai' ? 'checked' : '' }}> Selesai
                                </div>
                            </div>
                        </div>
                        <div class="form-group-custom mb-4">
                            <label>Tanggal Permintaan</label>
                            <div class="date-input-wrapper">
                                <input type="date" class="form-control-custom @error('tanggal_permintaan') is-invalid @enderror" name="tanggal_permintaan" value="{{ $permintaan->tanggal_permintaan }}" required>
                                <i class="bi bi-calendar3-event date-icon"></i>
                            </div>
                            @error('tanggal_permintaan')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-custom mb-4">
                            <label>Pemohon</label>
                            <input type="text" class="form-control-custom @error('pemohon') is-invalid @enderror" name="pemohon" value="{{ $permintaan->pemohon }}" required>
                            @error('pemohon')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group-custom mb-4">
                            <label>No. HP</label>
                            <input type="text" class="form-control-custom @error('no_hp') is-invalid @enderror" name="no_hp" value="{{ $permintaan->no_hp }}" required>
                            @error('no_hp')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group-custom mb-4">
                            <label>File Layanan</label>
                            <div class="input-file-wrapper">
                                <label for="file_input" class="btn-unggah m-0">Unggah</label>
                                <input type="file" id="file_input" name="file_layanan" hidden accept=".pdf,.doc,.docx,.zip,.rar">
                                <div class="file-name-display" id="file-chosen">{{ $permintaan->file_layanan ?? 'Pilih file...' }}</div>
                            </div>
                            @if($permintaan->file_layanan)
                                <div class="current-file-info">
                                    <i class="bi bi-file-earmark"></i> File aktual: <a href="{{ asset('uploads/permintaan/'.$permintaan->file_layanan) }}" target="_blank">{{ $permintaan->file_layanan }}</a>
                                </div>
                            @endif
                            @error('file_layanan')
                                <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-3 mt-5">
                            <a href="{{ route('admin.permintaan.index') }}" class="btn-form btn-kembali">Kembali</a>
                            <button type="submit" class="btn-form btn-edit-action">Edit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const actualBtn = document.getElementById('file_input');
    const fileChosen = document.getElementById('file-chosen');
    actualBtn.addEventListener('change', function(){
        fileChosen.textContent = this.files[0].name
    })
</script>

</body>
</html>
