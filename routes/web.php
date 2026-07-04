<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controllers
use App\Http\Controllers\DashboardController as dashboardController;
use App\Http\Controllers\PermintaanLayananController as permintaanLayananController;
use App\Http\Controllers\PeralatanController as peralatanController;
use App\Http\Controllers\PersonilController as personilController;
use App\Http\Controllers\DaftarSopController as daftarSopController;
use App\Http\Controllers\AbsensiController as absensiController;
use App\Http\Controllers\PnbpController as pnbpController;
use App\Http\Controllers\LaporanHasilController as laporanHasilController;
use App\Http\Controllers\DokumenController as dokumenController;
use App\Http\Controllers\DashboardKepalaLabController as dashboardKepalaLabController;
use App\Http\Controllers\RiwayatPenelitianController as riwayatPenelitianController;
use App\Http\Controllers\UserController as userController;

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
| PETUGAS LAB
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'petugas_lab'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', [dashboardController::class, 'indexPetugas'])
        ->name('dashboard');
    
    // Absensi Petugas (halaman khusus Petugas Lab)
    Route::get('/absensi', [absensiController::class, 'indexPetugas'])
        ->name('absensi.index');
    Route::post('/absen-masuk', [absensiController::class, 'absenMasukPetugas'])
        ->name('absen.masuk');
    Route::post('/absen-pulang', [absensiController::class, 'absenPulangPetugas'])
        ->name('absen.pulang');
    
    // Laporan Petugas
    Route::get('/laporan', [laporanHasilController::class, 'indexPetugas'])
        ->name('laporanpetugas.index');
    Route::get('/laporan/{id}/edit', [laporanHasilController::class, 'editPetugas'])
        ->name('laporanpetugas.edit');
    Route::post('/laporan/{id}/upload', [laporanHasilController::class, 'uploadHasil'])
        ->name('laporanpetugas.upload');

    // SOP Petugas
    Route::get('/sop', [daftarSopController::class, 'indexPetugas'])
        ->name('sop.index');
    
    // Riwayat Penelitian (Petugas Lab)
    Route::get('/riwayat', [riwayatPenelitianController::class, 'indexPetugas'])
        ->name('riwayat.index');

    // Peralatan (Petugas Lab)
    Route::get('/peralatan', [peralatanController::class, 'indexPetugas'])
        ->name('peralatan.index');
    Route::get('/peralatan/create', [peralatanController::class, 'createPetugas'])
        ->name('peralatan.create');
    Route::post('/peralatan', [peralatanController::class, 'storePetugas'])
        ->name('peralatan.store');
    Route::get('/peralatan/{id}/edit', [peralatanController::class, 'editPetugas'])
        ->name('peralatan.edit');
    Route::put('/peralatan/{id}', [peralatanController::class, 'updatePetugas'])
        ->name('peralatan.update');
});

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
        Route::get('/profile', [userController::class, 'showProfile'])
            ->name('profile');
        Route::put('/profile', [userController::class, 'updateProfile'])
            ->name('update-profile');
        Route::get('/change-password', [userController::class, 'showChangePassword'])
            ->name('change-password');
        Route::put('/change-password', [userController::class, 'updatePassword'])
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

        Route::get('/dashboard', [dashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('permintaan', permintaanLayananController::class);
        Route::put('/permintaan/{id}/update-status', [permintaanLayananController::class, 'updateStatus'])
            ->name('permintaan.updateStatus');

        Route::resource('pnbp', pnbpController::class);
        Route::get('/pnbp/{id}/invoice', [pnbpController::class, 'cetakInvoice'])->name('pnbp.invoice');

        Route::resource('laporan', laporanHasilController::class);
        Route::resource('dokumen', dokumenController::class);

        Route::resource('peralatan', peralatanController::class);
        Route::resource('personil', personilController::class)->names([
            'index' => 'pegawai'
        ]);

        Route::resource('sop', daftarSopController::class);
        Route::get('/absensi/export', [absensiController::class, 'exportExcel'])
            ->name('absensi.export');
        Route::resource('absensi', absensiController::class);

        Route::get('/riwayat-penelitian', [riwayatPenelitianController::class, 'index'])
            ->name('riwayat-penelitian.index');

        Route::get('/riwayat-absensi', [absensiController::class, 'index'])
            ->name('riwayat-absensi.index');
    });


    /*
    |--------------------------------------------------------------------------
    | KEPALA LAB
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'kepala_lab'])->prefix('kepala')->group(function () {

    // Dashboard Kepala Lab
    Route::get('/dashboard', [dashboardKepalaLabController::class, 'index'])
        ->name('kepalalab.dashboard');

    // Absensi Kepala Lab
    Route::post('/absen-masuk', [dashboardKepalaLabController::class, 'absenMasuk'])
        ->name('kepala.absen-masuk');
    Route::post('/absen-pulang', [dashboardKepalaLabController::class, 'absenPulang'])
        ->name('kepala.absen-pulang');

    // Permintaan Layanan
    Route::get('/permintaan', [permintaanLayananController::class, 'indexKepalaLab'])
        ->name('kepala.permintaan');
    Route::get('/permintaan/{id}/edit', [permintaanLayananController::class, 'editKepalaLab'])
        ->name('kepala.permintaan.edit');
    Route::put('/permintaan/{id}', [permintaanLayananController::class, 'updateKepalaLab'])
        ->name('kepala.permintaan.update');

    // Monitoring Laporan
    Route::get('/laporan', [laporanHasilController::class, 'index'])
        ->name('kepala.laporan');

    // Monitoring Riwayat
    Route::get('/riwayat', [riwayatPenelitianController::class, 'indexKepalaLab'])
        ->name('kepala.riwayat');

    // Monitoring Peralatan
    Route::get('/peralatan', [peralatanController::class, 'indexKepalaLab'])
        ->name('kepala.peralatan');

    // Monitoring Pegawai
    Route::get('/pegawai', [personilController::class, 'indexKepalaLab'])
        ->name('kepala.pegawai');

    // Monitoring SOP Kepala Lab
    Route::get('/sop', [daftarSopController::class, 'indexKepalaLab'])
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

        Route::get('/invoice/{id}', function ($id) {
            $pnbp = \App\Models\Pnbp::with('permintaanLayanan.user')->findOrFail($id);

            abort_unless(
                $pnbp->permintaanLayanan && $pnbp->permintaanLayanan->id_user === Auth::user()->id_user,
                403
            );

            return view('Admin.pnbp.invoice', compact('pnbp'));
        })->name('invoice');

        Route::get('/hasil/{id}', [permintaanLayananController::class, 'downloadHasilCustomer'])
            ->name('hasil.download');

        Route::post('/permintaan', [permintaanLayananController::class, 'store'])
            ->name('permintaan.store');
    });

});

require __DIR__.'/auth.php';