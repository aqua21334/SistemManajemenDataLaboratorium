<?php

namespace App\Http\Controllers;

use App\Models\Peralatan;
use App\Models\DaftarSop;
use App\Models\LaporanHasil;
use App\Models\RiwayatPenelitian;
use App\Models\Personil;
use App\Models\PermintaanLayanan;
use App\Models\Absensi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardKepalaLabController extends Controller
{
    public function index()
    {
        // 1. Ringkasan Data
        $totalPeralatan = Peralatan::count();
        $totalSop = DaftarSop::count();
        $totalLaporan = LaporanHasil::count();
        $totalRiwayat = RiwayatPenelitian::where('status', 'selesai')->count();
        $totalPegawai = Personil::count();

        // 2. Data Pengajuan Layanan (Pie Chart - Sudah/Belum)
        $permintaanSudah = PermintaanLayanan::where('status', 'selesai')->count();
        $permintaanBelum = PermintaanLayanan::whereIn('status', ['sedang diproses', 'diverifikasi'])->count();
        $totalPermintaan = $permintaanSudah + $permintaanBelum;
        
        // Hitung persentase untuk pie chart
        $sudahPercent = $totalPermintaan > 0 ? round(($permintaanSudah / $totalPermintaan) * 100) : 0;
        $belumPercent = 100 - $sudahPercent;

        // 3. Data Pengajuan 6 Bulan Terakhir (Line Chart)
        $sixMonthsAgo = Carbon::now()->subMonths(6);
        $monthlyData = [];
        $labels = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $month = $date->format('M');
            $labels[] = $month;

            $count = PermintaanLayanan::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $monthlyData[] = $count;
        }

        // 4. Peralatan yang belum dikalibrasi
        $peralatanBelumKalibrasi = Peralatan::where('status', 'belum dikalibrasi')
            ->orderBy('tanggal_kalibrasi', 'asc')
            ->take(6)
            ->get();

        // 5. Cek absen hari ini untuk kepala lab yang login
        $today = Carbon::now()->toDateString();
        $absenHariIni = Absensi::where('id_user', Auth::id())
            ->whereDate('tanggal', $today)
            ->first();

        return view('KepalaLab.dashboard', [
            'totalPeralatan' => $totalPeralatan,
            'totalSop' => $totalSop,
            'totalLaporan' => $totalLaporan,
            'totalPermintaan' => $totalPermintaan,
            'totalRiwayat' => $totalRiwayat,
            'totalPegawai' => $totalPegawai,
            'permintaanSudah' => $permintaanSudah,
            'permintaanBelum' => $permintaanBelum,
            'sudahPercent' => $sudahPercent,
            'belumPercent' => $belumPercent,
            'monthlyLabels' => json_encode($labels),
            'monthlyData' => json_encode($monthlyData),
            'peralatanBelumKalibrasi' => $peralatanBelumKalibrasi,
            'absenHariIni' => $absenHariIni,
        ]);
    }

    /**
     * Absen Masuk
     */
    public function absenMasuk()
    {
        $today = Carbon::now()->toDateString();
        
        // Cek apakah sudah absen masuk hari ini
        $absenExist = Absensi::where('id_user', Auth::id())
            ->whereDate('tanggal', $today)
            ->first();

        if ($absenExist && $absenExist->jam_masuk) {
            return back()->with('error', 'Anda sudah absen masuk hari ini!');
        }

        if ($absenExist) {
            $absenExist->update(['jam_masuk' => Carbon::now()->format('H:i:s')]);
        } else {
            Absensi::create([
                'id_user' => Auth::id(),
                'tanggal' => $today,
                'jam_masuk' => Carbon::now()->format('H:i:s'),
            ]);
        }

        return back()->with('success', 'Absen masuk berhasil dicatat!');
    }

    /**
     * Absen Pulang
     */
    public function absenPulang()
    {
        $today = Carbon::now()->toDateString();
        
        // Cek apakah sudah absen masuk
        $absenExist = Absensi::where('id_user', Auth::id())
            ->whereDate('tanggal', $today)
            ->first();

        if (!$absenExist || !$absenExist->jam_masuk) {
            return back()->with('error', 'Anda belum absen masuk!');
        }

        if ($absenExist->jam_pulang) {
            return back()->with('error', 'Anda sudah absen pulang hari ini!');
        }

        $absenExist->update(['jam_pulang' => Carbon::now()->format('H:i:s')]);

        return back()->with('success', 'Absen pulang berhasil dicatat!');
    }
}
