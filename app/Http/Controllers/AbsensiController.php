<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;
use App\Exports\AbsensiExport;          // <-- WAJIB: Import class export yang sudah kita buat
use Maatwebsite\Excel\Facades\Excel;    // <-- WAJIB: Import library Excel dari Maatwebsite
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $query = Absensi::query();

        // Search functionality
        $search = request('search');
        if ($search) {
            $query->where(function($builder) use ($search) {
                $builder->where('id_absensi', 'like', '%' . $search . '%')
                        ->orWhere('nama', 'like', '%' . $search . '%')
                        ->orWhere('jabatan', 'like', '%' . $search . '%')
                        ->orWhere('lokasi', 'like', '%' . $search . '%');
            });
        }

        // Logika tambahan: Jika ada filter tanggal dari halaman view, terapkan ke query
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        $absensis = $query->orderBy('tanggal', 'desc')->get();
        
        // Return view Admin panel (Riwayat Absensi)
        return view('Admin.riwayatabsensi.index', compact('absensis', 'search'));
    }

    /**
     * Menampilkan halaman absensi untuk Petugas Lab (form hadir/pulang)
     */
    public function indexPetugas()
    {
        $today = Carbon::now()->toDateString();
        $absenToday = Absensi::where('id_user', Auth::id())
            ->whereDate('tanggal', $today)
            ->first();

        return view('PetugasLab.absenpetugas.index', compact('absenToday'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'jabatan' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'lokasi' => 'required|string|max:100',
        ]);

        Absensi::create($request->all());
        return back()->with('success', 'Absensi berhasil dicatat!');
    }

    public function destroy($id)
    {
        Absensi::findOrFail($id)->delete();
        return back()->with('success', 'Data absensi dihapus!');
    }
    // --- FUNGSI BARU UNTUK EXPORT EXCEL (VERSI MULTIPLE SHEETS) ---
    public function exportExcel(Request $request)
    {
        // Menangkap input tahun dari URL/Form. Jika kosong, gunakan tahun saat ini
        $tahun = $request->input('tahun', date('Y'));

        // Nama file akan dinamis, contoh: Rekap_Absensi_2026.xlsx
        $namaFile = 'Rekap_Absensi_' . $tahun . '.xlsx';

        // Memanggil Master Export dan memberikan variabel tahun
        return Excel::download(new AbsensiExport($tahun), $namaFile);
    }

    // Absen masuk untuk Petugas Lab (dengan lokasi)
    public function absenMasukPetugas(Request $request)
    {
        $request->validate([
            'status' => 'nullable|in:hadir,izin',
            'lokasi' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $today = Carbon::now()->toDateString();

        $absenExist = Absensi::where('id_user', Auth::id())
            ->whereDate('tanggal', $today)
            ->first();

        if ($absenExist && $absenExist->jam_masuk) {
            return back()->with('error', 'Anda sudah absen hadir hari ini!');
        }

        // Build data payload only with columns that exist in DB
        $data = [
            'id_user' => Auth::id(),
            'tanggal' => $today,
            'jam_masuk' => Carbon::now()->format('H:i:s'),
        ];

        if (Schema::hasColumn('absensis', 'status') && $request->filled('status')) {
            $data['status'] = $request->status;
        }
        if (Schema::hasColumn('absensis', 'lokasi') && $request->filled('lokasi')) {
            $data['lokasi'] = $request->lokasi;
        }
        if (Schema::hasColumn('absensis', 'latitude') && $request->filled('latitude')) {
            $data['latitude'] = $request->latitude;
        }
        if (Schema::hasColumn('absensis', 'longitude') && $request->filled('longitude')) {
            $data['longitude'] = $request->longitude;
        }

        if ($absenExist) {
            $absenExist->update($data);
        } else {
            Absensi::create($data);
        }

        return back()->with('success', 'Absen masuk berhasil dicatat!');
    }

    // Absen pulang untuk Petugas Lab (dengan lokasi optional)
    public function absenPulangPetugas(Request $request)
    {
        $today = Carbon::now()->toDateString();

        $absenExist = Absensi::where('id_user', Auth::id())
            ->whereDate('tanggal', $today)
            ->first();

        if (!$absenExist || !$absenExist->jam_masuk) {
            return back()->with('error', 'Anda belum absen hadir hari ini!');
        }

        if ($absenExist->jam_pulang) {
            return back()->with('error', 'Anda sudah absen pulang hari ini!');
        }

        $update = [
            'jam_pulang' => Carbon::now()->format('H:i:s'),
        ];

        if (Schema::hasColumn('absensis', 'lokasi')) {
            $update['lokasi'] = $request->input('lokasi', $absenExist->lokasi);
        }
        if (Schema::hasColumn('absensis', 'latitude')) {
            $update['latitude'] = $request->input('latitude', $absenExist->latitude);
        }
        if (Schema::hasColumn('absensis', 'longitude')) {
            $update['longitude'] = $request->input('longitude', $absenExist->longitude);
        }

        $absenExist->update($update);

        return back()->with('success', 'Absen pulang berhasil dicatat!');
    }
}