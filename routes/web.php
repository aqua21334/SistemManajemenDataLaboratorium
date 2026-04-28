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
use App\Models\PermintaanLayanan;

// --- ROUTE HALAMAN DEPAN ---
Route::get('/', function () {
    return view('welcome'); // <-- Mengarah ke tampilan Front Page yang baru kita buat
})->name('home');

// --- ROUTE AUTENTIKASI ---
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// <-- TAMBAHAN ROUTE UNTUK REGISTER -->
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

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
    // Cari baris ini di dalam rute yang dilindungi auth:
    Route::get('/customer/dashboard', function () {
    $permintaanlayanans = \App\Models\PermintaanLayanan::with('laporanHasil')
        ->where('id_user', Auth::user()->id_user)
        ->orderBy('created_at', 'desc')
        ->get();
    return view('Customer.dashboard', compact('permintaanlayanans'));
});

    // --- ROUTE TRANSAKSI UTAMA & PENDUKUNG ---
    Route::resource('permintaan', PermintaanLayananController::class);
    Route::put('/permintaan/{id}/update-status', [PermintaanLayananController::class, 'updateStatus'])->name('permintaan.updateStatus');
    
    // --- Pnpb ----
    Route::resource('pnbp', PnbpController::class);
    Route::put('/pnbp/{id}/bayar', [PnbpController::class, 'updatePembayaran'])->name('pnbp.bayar');
    Route::get('/pnbp/{id}/invoice', [PnbpController::class, 'cetakInvoice'])->name('pnbp.invoice');

    Route::resource('laporan', LaporanHasilController::class);
    Route::resource('dokumen', DokumenController::class);

    // --- ROUTE DATA MASTER & KEPEGAWAIAN ---
    Route::resource('peralatan', PeralatanController::class);
    Route::resource('personil', PersonilController::class);
    Route::resource('sop', DaftarSopController::class);
    Route::resource('absensi', AbsensiController::class);

    //--- ROUTE EXPORT EXCEL (Hanya untuk Admin & Kepala Lab) ---
    Route::get('/absensi/export', [AbsensiController::class, 'exportExcel'])->name('absensi.export');

}); // <-- Perhatikan, tutup blok perlindungan Auth ada di paling bawah sini