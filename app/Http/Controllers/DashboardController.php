<?php

namespace App\Http\Controllers;

use App\Models\Personil;
use App\Models\Peralatan;
use App\Models\DaftarSop;
use App\Models\Dokumen;
use App\Models\LaporanHasil;
use App\Models\RiwayatPenelitian;
use App\Models\PermintaanLayanan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // --- STATISTIK RINGKASAN ---
        $countPegawai = Personil::count();
        $countPeralatan = Peralatan::count();
        $countSop = DaftarSop::count();
        $countDokumen = Dokumen::count();
        $countPermintaanLayanan = PermintaanLayanan::count();
        $countRiwayat = RiwayatPenelitian::count();

        // --- PERALATAN YANG BELUM DIKALIBRASI ---
        $peralatanKalibrasi = Peralatan::where('status', 'belum dikalibrasi')->get();

        // --- DATA UNTUK PIE CHART (Pengajuan Layanan) ---
        $permintaanSudah = PermintaanLayanan::where('status', 'selesai')->count();
        $permintaanBelum = PermintaanLayanan::where('status', '!=', 'selesai')->count();

        // --- DATA UNTUK LINE CHART (Pengajuan 6 Bulan Terakhir) ---
        $chartData = [];
        $labels = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $bulan = $date->format('M'); // Jan, Feb, etc
            $labels[] = $bulan;
            
            // Hitung permintaan per bulan
            $count = PermintaanLayanan::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            
            $chartData[] = $count;
        }

        return view('admin.dashboard', compact(
            'countPegawai',
            'countPeralatan',
            'countSop',
            'countDokumen',
            'countPermintaanLayanan',
            'countRiwayat',
            'peralatanKalibrasi',
            'permintaanSudah',
            'permintaanBelum',
            'chartData',
            'labels'
        ));
    }
}
