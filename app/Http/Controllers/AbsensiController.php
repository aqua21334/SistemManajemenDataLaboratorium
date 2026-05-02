<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;
use App\Exports\AbsensiExport;          // <-- WAJIB: Import class export yang sudah kita buat
use Maatwebsite\Excel\Facades\Excel;    // <-- WAJIB: Import library Excel dari Maatwebsite

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
}