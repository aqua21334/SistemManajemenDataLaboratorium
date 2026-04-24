<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// WAJIB: Import semua Controller yang digunakan di file ini
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PermintaanLayananController;
use App\Http\Controllers\PeralatanController;
use App\Http\Controllers\PersonilController;
use App\Http\Controllers\DaftarSopController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\PnbpController;
use App\Http\Controllers\LaporanHasilController;
use App\Http\Controllers\DokumenController;

// --- ROUTE AUTENTIKASI ---
Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- ROUTE YANG WAJIB LOGIN (Dilindungi Middleware Auth) ---
Route::middleware('auth')->group(function () {
    
    // --- ROUTE DASHBOARD (Berdasarkan Role) ---
    Route::get('/admin/dashboard', function () {
        return "Halo Admin " . Auth::user()->nama . ", Selamat Datang di Balai Teknik Rawa!";
    });
    Route::get('/kepala/dashboard', function () {
        return "Halo Kepala Lab " . Auth::user()->nama;
    });
    Route::get('/petugas/dashboard', function () {
        return "Halo Petugas " . Auth::user()->nama;
    });
    Route::get('/customer/dashboard', function () {
        return "Halo Customer " . Auth::user()->nama;
    });

    // --- ROUTE TRANSAKSI UTAMA & PENDUKUNG ---
    Route::resource('permintaan', PermintaanLayananController::class);
    Route::put('/permintaan/{id}/update-status', [PermintaanLayananController::class, 'updateStatus'])->name('permintaan.updateStatus');
    
    Route::resource('pnbp', PnbpController::class);
    Route::resource('laporan', LaporanHasilController::class);
    Route::resource('dokumen', DokumenController::class);

    // --- ROUTE DATA MASTER & KEPEGAWAIAN ---
    Route::resource('peralatan', PeralatanController::class);
    Route::resource('personil', PersonilController::class);
    Route::resource('sop', DaftarSopController::class);
    Route::resource('absensi', AbsensiController::class);

}); // <-- Perhatikan, tutup blok perlindungan Auth ada di paling bawah sini