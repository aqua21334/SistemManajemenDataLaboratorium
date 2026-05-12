<?php

namespace App\Http\Controllers;

use App\Models\LaporanHasil;
use App\Models\PermintaanLayanan;
use App\Models\RiwayatPenelitian;
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

    public function indexPetugas(Request $request)
    {
        $search = $request->input('search', '');
        $status = $request->input('status', '');
        $allowedStatuses = ['sedang diproses', 'diverifikasi'];
        
        $query = \App\Models\PermintaanLayanan::with(['laporanHasil' => function ($q) {
            $q->orderByDesc('id_laporan');
        }])
            ->whereIn('status', $allowedStatuses)
            ->orderByDesc('created_at');

        if (!empty($search)) {
            $query->where(function ($builder) use ($search) {
                $builder->where('jenis_permintaan', 'like', '%' . $search . '%')
                    ->orWhere('pemohon', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')
                    ->orWhere('id_permintaan', 'like', '%' . $search . '%');
            });
        }

        if (!empty($status) && in_array($status, $allowedStatuses, true)) {
            $query->where('status', $status);
        }

        $permintaans = $query->paginate(10)->appends($request->query());
        
        \Log::info('Laporan Search', [
            'search' => $search,
            'status' => $status,
            'total' => $permintaans->total(),
            'per_page' => $permintaans->perPage()
        ]);
        
        return view('PetugasLab.laporanpetugas.index', compact('permintaans', 'search', 'status'));
    }

    public function editPetugas($id)
    {
        $permintaan = PermintaanLayanan::findOrFail($id);
        $laporan = $permintaan->laporanHasil ? $permintaan->laporanHasil->first() : null;
        return view('PetugasLab.laporanpetugas.upload', compact('permintaan', 'laporan'));
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

    public function uploadHasil(Request $request, $id)
    {
        $request->validate([
            'hasil_penelitian' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,txt|max:5120', // Maksimal 5MB
        ]);

        try {
            $permintaan = PermintaanLayanan::findOrFail($id);
            
            // Handle file upload
            if ($request->hasFile('hasil_penelitian')) {
                $file = $request->file('hasil_penelitian');
                
                // Pastikan folder ada
                $uploadPath = public_path('uploads/laporan');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move($uploadPath, $filename);

                // Create atau update laporan hasil
                LaporanHasil::updateOrCreate(
                    ['id_permintaan' => $id],
                    [
                        'id_permintaan' => $id,
                        'nama_laporan' => $permintaan->jenis_permintaan ?? 'Laporan ' . date('Y-m-d'),
                        'file_hasil' => $filename,
                        'tanggal' => now()->format('Y-m-d'),
                    ]
                );

                // Ubah status permintaan agar langsung tampil sebagai diverifikasi
                $permintaan->update([
                    'status' => 'diverifikasi',
                ]);

                // Update atau create riwayat penelitian dengan status diverifikasi dan tanggal selesai
                RiwayatPenelitian::updateOrCreate(
                    ['id_permintaan' => $id],
                    [
                        'id_permintaan' => $id,
                        'id_user' => auth()->id(),
                        'nama_laporan' => $permintaan->jenis_permintaan ?? 'Laporan ' . date('Y-m-d'),
                        'tanggal_selesai' => now(),
                        'status' => 'diverifikasi',
                    ]
                );

                return redirect()->route('petugas.laporanpetugas.index')->with('success', 'File hasil berhasil diunggah!');
            }

            return back()->with('error', 'Gagal mengupload file: File tidak ditemukan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengupload file: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        LaporanHasil::findOrFail($id)->delete();
        return back()->with('success', 'Laporan berhasil dihapus!');
    }
}