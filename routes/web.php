<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PermintaanLayananController;
use App\Http\Controllers\PeralatanController;
use App\Http\Controllers\PersonilController;
use App\Http\Controllers\DaftarSopController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\PnbpController;
use App\Http\Controllers\LaporanHasilController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\DashboardKepalaLabController;
use App\Http\Controllers\RiwayatPenelitianController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| FRONT PAGE
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('utama');
})->name('home');

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| ROUTE LOGIN (ALL USER)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | USER PROFILE & PASSWORD
    |--------------------------------------------------------------------------
    */
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/profile', [UserController::class, 'showProfile'])
            ->name('profile');
        Route::put('/profile', [UserController::class, 'updateProfile'])
            ->name('update-profile');
        Route::get('/change-password', [UserController::class, 'showChangePassword'])
            ->name('change-password');
        Route::put('/change-password', [UserController::class, 'updatePassword'])
            ->name('update-password');
    });
    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')
        ->middleware('admin')
        ->name('admin.')
        ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('permintaan', PermintaanLayananController::class);
        Route::put('/permintaan/{id}/update-status', [PermintaanLayananController::class, 'updateStatus'])
            ->name('permintaan.updateStatus');

        Route::resource('pnbp', PnbpController::class);
        Route::get('/pnbp/{id}/invoice', [PnbpController::class, 'cetakInvoice'])->name('pnbp.invoice');

        Route::resource('laporan', LaporanHasilController::class);
        Route::resource('dokumen', DokumenController::class);

        Route::resource('peralatan', PeralatanController::class);
        Route::resource('personil', PersonilController::class)->names([
            'index' => 'pegawai'
        ]);

        Route::resource('sop', DaftarSopController::class);
        Route::resource('absensi', AbsensiController::class);
        Route::get('/absensi/export', [AbsensiController::class, 'exportExcel'])
            ->name('absensi.export');

        Route::get('/riwayat-penelitian', [RiwayatPenelitianController::class, 'index'])
            ->name('riwayat-penelitian.index');

        Route::get('/riwayat-absensi', [AbsensiController::class, 'index'])
            ->name('riwayat-absensi.index');
    });


    /*
    |--------------------------------------------------------------------------
    | KEPALA LAB
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'kepala_lab'])->prefix('kepala')->group(function () {

    // Dashboard Kepala Lab
    Route::get('/dashboard', [DashboardKepalaLabController::class, 'index'])
        ->name('kepalalab.dashboard');

    // Absensi Kepala Lab
    Route::post('/absen-masuk', [DashboardKepalaLabController::class, 'absenMasuk'])
        ->name('kepala.absen-masuk');
    Route::post('/absen-pulang', [DashboardKepalaLabController::class, 'absenPulang'])
        ->name('kepala.absen-pulang');

    // Permintaan Layanan
    Route::get('/permintaan', [PermintaanLayananController::class, 'indexKepalaLab'])
        ->name('kepala.permintaan');

    // Monitoring Laporan
    Route::get('/laporan', [LaporanHasilController::class, 'index'])
        ->name('kepala.laporan');

    // Monitoring Riwayat
    Route::get('/riwayat', [RiwayatPenelitianController::class, 'indexKepalaLab'])
        ->name('kepala.riwayat');

    // Monitoring Peralatan
    Route::get('/peralatan', [PeralatanController::class, 'indexKepalaLab'])
        ->name('kepala.peralatan');

    // Monitoring Pegawai
    Route::get('/pegawai', [PersonilController::class, 'indexKepalaLab'])
        ->name('kepala.pegawai');

    // Monitoring SOP Kepala Lab
    Route::get('/sop', [DaftarSopController::class, 'indexKepalaLab'])
        ->name('kepala.sop');

});

    /*
    |--------------------------------------------------------------------------
    | CUSTOMER
    |--------------------------------------------------------------------------
    */
    Route::prefix('customer')
        ->name('customer.')
        ->group(function () {

        Route::get('/dashboard', function () {
            $permintaanlayanans = \App\Models\PermintaanLayanan::with('laporanHasil')
                ->where('id_user', Auth::user()->id_user)
                ->latest()
                ->get();

            return view('Customer.dashboardcustomer', compact('permintaanlayanans'));
        })->name('dashboard');

        Route::post('/permintaan', [PermintaanLayananController::class, 'store'])
            ->name('permintaan.store');
    });

});