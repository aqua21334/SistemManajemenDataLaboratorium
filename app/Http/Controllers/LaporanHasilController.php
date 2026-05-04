<?php

namespace App\Http\Controllers;

use App\Models\LaporanHasil;
use App\Models\PermintaanLayanan;
use Illuminate\Http\Request;

class LaporanHasilController extends Controller
{
    public function index(Request $request)
    {
        $query = LaporanHasil::with('permintaanLayanan');
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('nama_laporan', 'like', '%' . $search . '%')
                  ->orWhere('id_permintaan', 'like', '%' . $search . '%');
        }
        
        // Status filter
        if ($request->has('status') && $request->status) {
            $status = $request->status;
            $query->whereHas('permintaanLayanan', function($q) use ($status) {
                $q->where('status', $status);
            });
        }
        
        $laporans = $query->get();
        $permintaans = PermintaanLayanan::where('status', 'diverifikasi')->get();
        return view('KepalaLab.Laporan.index', compact('laporans', 'permintaans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_permintaan' => 'required|exists:permintaan_layanans,id_permintaan',
            'nama_laporan' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'file_hasil' => 'required|mimes:pdf|max:5120', // Maksimal 5MB PDF
        ]);

        $file = $request->file('file_hasil');
        $filename = time() . '_HasilLab_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/laporan'), $filename); // Simpan di folder public/uploads/laporan

        LaporanHasil::create([
            'id_permintaan' => $request->id_permintaan,
            'nama_laporan' => $request->nama_laporan,
            'tanggal' => $request->tanggal,
            'file_hasil' => $filename,
        ]);

        return back()->with('success', 'Laporan hasil berhasil diunggah!');
    }

    public function destroy($id)
    {
        LaporanHasil::findOrFail($id)->delete();
        return back()->with('success', 'Laporan berhasil dihapus!');
    }
}