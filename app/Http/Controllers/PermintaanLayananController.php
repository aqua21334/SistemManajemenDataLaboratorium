<?php

namespace App\Http\Controllers;

use App\Models\PermintaanLayanan;
use App\Models\RiwayatPenelitian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermintaanLayananController extends Controller
{
    /**
     * 1. Menampilkan data permintaan.
     * Untuk Admin/Petugas: Menampilkan semua data.
     * Untuk Customer: Hanya menampilkan data miliknya sendiri.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role->nama_role == 'Customer') {
            // Customer hanya melihat data miliknya sendiri + relasi laporanHasil untuk file unduhan
            $permintaans = PermintaanLayanan::with('laporanHasil')
                ->where('id_user', $user->id_user)
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Arahkan ke halaman depan atau dashboard customer tempat tabel berada
            return view('front.index', compact('permintaans'));
        }

        // Admin/Petugas melihat semua data
        $permintaans = PermintaanLayanan::with('user')->orderBy('created_at', 'desc')->get();
        return view('permintaan.index', compact('permintaans'));
    }

    // 2. Menampilkan detail 1 permintaan
    public function show($id)
    {
        $permintaan = PermintaanLayanan::with(['dokumens', 'pnbp', 'laporanHasil', 'riwayats'])->findOrFail($id);
        return view('permintaan.show', compact('permintaan'));
    }

    // 3. Update Status (Untuk Petugas/Admin)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:sedang diproses,diverifikasi,selesai'
        ]);

        $permintaan = PermintaanLayanan::findOrFail($id);
        $permintaan->status = $request->status;
        $permintaan->save(); 

        return back()->with('success', 'Status berhasil diperbarui!');
    }
    
    // 4. Menyimpan Permintaan Baru (Customer)
    public function store(Request $request)
    {
        $request->validate([
            'jenis_permintaan' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'file_layanan' => 'required|file|mimes:pdf,doc,docx,zip,rar|max:5120', 
        ]);

        $nama_file = null;
        if ($request->hasFile('file_layanan')) {
            $file = $request->file('file_layanan');
            $nama_file = time() . "_" . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move(public_path('uploads/permintaan'), $nama_file);
        }

        PermintaanLayanan::create([
            'id_user' => Auth::id(), 
            'pemohon' => Auth::user()->nama, 
            'jenis_permintaan' => $request->jenis_permintaan,
            'no_hp' => $request->no_hp,
            'file_layanan' => $nama_file,
            'status' => 'sedang diproses',
            'tanggal_permintaan' => now()->toDateString() 
        ]);

        return back()->with('success', 'Permintaan berhasil dikirim!');
    }
}