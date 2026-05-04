<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data SOP - Lab Rawa</title>
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
            max-width: 900px;
        }
        .form-card h5 { color: #345E6F; font-weight: bold; margin-bottom: 25px; }

        /* Form Row */
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }

        /* Form Input Styling */
        .form-label { color: #345E6F; font-weight: 500; margin-bottom: 8px; font-size: 14px; }
        .form-control { 
            border: 2px solid #333; 
            border-radius: 8px; 
            padding: 10px 12px; 
            font-size: 14px;
        }
        .form-control:focus { border-color: #345E6F; box-shadow: 0 0 0 0.2rem rgba(52, 94, 111, 0.25); }
        .form-control:disabled { background-color: #f5f5f5; color: #999; }

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

        /* Current File Display */
        .current-file { font-size: 12px; color: #666; margin-top: 8px; }
        .current-file a { color: #345E6F; text-decoration: none; font-weight: 500; }
        .current-file a:hover { text-decoration: underline; }

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
        .btn-edit { background: #A8C5D4; color: #345E6F; }
        .btn-edit:hover { background: #99b8c7; color: #345E6F; }

        .button-group { display: flex; gap: 15px; justify-content: flex-end; margin-top: 30px; }
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
        <li class="nav-item"><a href="{{ route('admin.sop.index') }}" class="nav-link active"><i class="bi bi-file-earmark-check-fill me-2"></i> Daftar SOP</a></li>
        <li class="nav-item"><a href="{{ route('admin.permintaan.index') }}" class="nav-link"><i class="bi bi-file-earmark-text-fill me-2"></i> Laporan</a></li>
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
        <h3 class="fw-light m-0">Selamat Datang !!</h3>
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
            <h5><i class="bi bi-file-earmark-edit me-2"></i>Edit Data SOP</h5>

            <form action="{{ route('admin.sop.update', $sop->id_sop) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div>
                        <label for="idDokumen" class="form-label">Id Dokumen</label>
                        <input type="text" class="form-control" id="idDokumen" name="id_sop" value="{{ $sop->id_sop }}" disabled>
                        <small class="text-muted">Id tidak dapat diubah</small>
                    </div>
                    
                    <div>
                        <label for="fileDokumen" class="form-label">File Dokumen</label>
                        <div class="file-upload-wrapper">
                            <input type="text" class="file-input-text" id="fileDisplay" placeholder="Tidak ada file dipilih" readonly>
                            <button type="button" class="btn-upload" onclick="document.getElementById('fileInput').click()">
                                <i class="bi bi-cloud-arrow-up me-1"></i>Unggah
                            </button>
                            <input type="file" id="fileInput" name="file_sop" class="@error('file_sop') is-invalid @enderror" accept=".pdf,.doc,.docx,.xlsx,.xls">
                        </div>
                        @if($sop->file_sop)
                            <div class="current-file">
                                <i class="bi bi-file-earmark"></i> File: <a href="{{ asset('uploads/sop/'.$sop->file_sop) }}" target="_blank">{{ $sop->file_sop }}</a>
                            </div>
                        @endif
                        @error('file_sop')
                            <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div>
                        <label for="jenisDokumen" class="form-label">Jenis Dokumen</label>
                        <select class="form-control @error('jenis_sop') is-invalid @enderror" id="jenisDokumen" name="jenis_sop">
                            <option value="" disabled>-- Pilih Jenis SOP --</option>
                            <option value="Lab Tanah" {{ $sop->jenis_sop === 'Lab Tanah' ? 'selected' : '' }}>Lab Tanah</option>
                            <option value="Lab Air" {{ $sop->jenis_sop === 'Lab Air' ? 'selected' : '' }}>Lab Air</option>
                        </select>
                        @error('jenis_sop')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="judul" class="form-label">Judul</label>
                        <input type="text" class="form-control @error('judul_sop') is-invalid @enderror" id="judul" name="judul_sop" value="{{ $sop->judul_sop }}">
                        @error('judul_sop')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="button-group">
                    <a href="{{ route('admin.sop.index') }}" class="btn btn-custom btn-kembali">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                    <button type="submit" class="btn btn-custom btn-edit">
                        <i class="bi bi-pencil-square me-1"></i>Edit
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
