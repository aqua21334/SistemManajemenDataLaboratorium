<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data SOP - Lab Rawa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body { background-color: #B2C3CF; font-family: 'Inter', sans-serif; }
        
        /* Sidebar */
        .sidebar { background-color: #345E6F; min-height: 100vh; color: white; padding: 20px 10px; width: 240px; position: fixed; }
        .sidebar-logo-text { font-size: 10px; line-height: 1.3; color: #E2E8F0; margin-top: 10px; }
        .nav-link { color: #CBD5E0; font-size: 14px; padding: 8px 15px; margin-bottom: 5px; border-radius: 8px; }
        .nav-link:hover, .nav-link.active { background-color: rgba(255,255,255,0.1); color: white; }
        .main-wrapper { margin-left: 240px; padding: 25px; }

        /* Topbar */
        .topbar-card { background: white; padding: 15px 30px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; }

        /* Container Content */
        .dashboard-container { background-color: #DAE4EB; border-radius: 10px; padding: 25px; min-height: 80vh; }

        /* Form Card */
        .form-card { 
            background: white; 
            border: 2px solid #333; 
            border-radius: 15px; 
            padding: 30px; 
            max-width: 600px;
        }
        .form-card h5 { color: #345E6F; font-weight: bold; margin-bottom: 25px; }

        /* Form Input Styling */
        .form-label { color: #345E6F; font-weight: 500; margin-bottom: 8px; font-size: 14px; }
        .form-control { 
            border: 2px solid #333; 
            border-radius: 8px; 
            padding: 10px 12px; 
            font-size: 14px;
        }
        .form-control:focus { border-color: #345E6F; box-shadow: 0 0 0 0.2rem rgba(52, 94, 111, 0.25); }

        /* File Upload Styling */
        .file-upload-wrapper { display: flex; align-items: center; gap: 10px; }
        .file-input-text { 
            flex: 1; 
            border: 2px solid #333; 
            border-radius: 8px; 
            padding: 10px 12px; 
            background: #f8f9fa;
            color: #666;
            font-size: 14px;
        }
        .btn-upload { 
            background: #345E6F; 
            color: white; 
            border: 2px solid #345E6F; 
            border-radius: 8px; 
            padding: 8px 20px; 
            font-weight: bold;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-upload:hover { background: #2a4a5a; }

        #fileInput { display: none; }

        /* Button Styling */
        .btn-custom { 
            border: 2px solid #333; 
            border-radius: 10px; 
            font-weight: bold; 
            padding: 8px 30px; 
            font-size: 14px;
        }
        .btn-kembali { background: white; color: #345E6F; }
        .btn-kembali:hover { background: #f8f9fa; color: #345E6F; }
        .btn-tambah { background: #345E6F; color: white; }
        .btn-tambah:hover { background: #2a4a5a; color: white; }

        .button-group { display: flex; gap: 15px; justify-content: flex-end; margin-top: 30px; }
    </style>
</head>
<body>

<div class="sidebar shadow">
    <div class="text-center mb-5 px-3">
        <img src="{{ asset('images/Logo-pupr.jpeg') }}" width="60" class="rounded-circle border border-2 border-white">
        <p class="sidebar-logo-text fw-semibold">Sistem Manajemen Data<br>Laboratorium Balai Teknik Rawa</p>
    </div>
    <ul class="nav flex-column px-2">
        <li class="nav-item"><a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="bi bi-grid-fill me-2"></i> Dashboard</a></li>
        <li class="nav-item"><a href="{{ route('admin.pegawai') }}" class="nav-link"><i class="bi bi-people-fill me-2"></i> Pegawai</a></li>
        <li class="nav-item"><a href="{{ route('admin.peralatan.index') }}" class="nav-link"><i class="bi bi-tools me-2"></i> Peralatan</a></li>
        <li class="nav-item"><a href="{{ route('admin.sop.index') }}" class="nav-link active"><i class="bi bi-file-earmark-check-fill me-2"></i> Daftar SOP</a></li>
        <li class="nav-item"><a href="{{ route('admin.permintaan.index') }}" class="nav-link"><i class="bi bi-file-earmark-text-fill me-2"></i> Laporan</a></li>
        <li class="nav-item"><a href="{{ route('admin.riwayat-penelitian.index') }}" class="nav-link"><i class="bi bi-file-earmark-bar-graph-fill me-2"></i> Riwayat Penelitian</a></li>
       <li class="nav-item"><a href="{{ route('admin.pnbp.index') }}" class="nav-link"><i class="bi bi-cash-stack me-2"></i> PNBP</a></li>
        <li class="nav-item"><a href="{{ route('admin.riwayat-absensi.index') }}" class="nav-link"><i class="bi bi-person-badge-fill me-2"></i> Riwayat Absensi</a></li>
    </ul>
    <div style="position: absolute; bottom: 30px; left: 25px;">
        <form action="{{ route('logout') }}" method="POST">
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
        <div class="form-card shadow-sm">
            <h5><i class="bi bi-file-earmark-plus me-2"></i>Tambah Data SOP</h5>

            @if ($errors->any())
                <div class="alert alert-danger mb-4 border-2 border-dark">
                    <ul class="m-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.sop.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label for="idDokumen" class="form-label">Id SOP</label>
                    <input type="text" class="form-control @error('id_sop') is-invalid @enderror" id="idDokumen" name="id_sop" placeholder="" value="{{ old('id_sop') }}">
                    @error('id_sop')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="jenisDokumen" class="form-label">Jenis SOP</label>
                    <select class="form-control @error('jenis_sop') is-invalid @enderror" id="jenisDokumen" name="jenis_sop">
                        <option value="" selected disabled>-- Pilih Jenis SOP --</option>
                        <option value="Lab Tanah" {{ old('jenis_sop') === 'Lab Tanah' ? 'selected' : '' }}>Lab Tanah</option>
                        <option value="Lab Air" {{ old('jenis_sop') === 'Lab Air' ? 'selected' : '' }}>Lab Air</option>
                    </select>
                    @error('jenis_sop')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="fileDokumen" class="form-label">File SOP</label>
                    <div class="file-upload-wrapper">
                        <input type="text" class="file-input-text" id="fileDisplay" placeholder="Tidak ada file dipilih" readonly>
                        <button type="button" class="btn-upload" onclick="document.getElementById('fileInput').click()">
                            <i class="bi bi-cloud-arrow-up me-1"></i>Unggah
                        </button>
                        <input type="file" id="fileInput" name="file_sop" class="@error('file_sop') is-invalid @enderror" accept=".pdf,.doc,.docx,.xlsx,.xls">
                    </div>
                    @error('file_sop')
                        <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="judul" class="form-label">Judul</label>
                    <input type="text" class="form-control @error('judul_sop') is-invalid @enderror" id="judul" name="judul_sop" placeholder="" value="{{ old('judul_sop') }}">
                    @error('judul_sop')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="button-group">
                    <a href="{{ route('admin.sop.index') }}" class="btn btn-custom btn-kembali">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                    <button type="submit" class="btn btn-custom btn-tambah">
                        <i class="bi bi-plus-circle me-1"></i>Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Handle file upload display
    document.getElementById('fileInput').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name || 'Tidak ada file dipilih';
        document.getElementById('fileDisplay').value = fileName;
    });
</script>

</body>
</html>


