<?php

namespace App\Http\Controllers;

use App\Models\PermintaanLayanan;
use App\Models\RiwayatPenelitian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermintaanLayananController extends Controller
{
    // 1. Menampilkan semua data permintaan
    public function index()
    {
        // Mengambil data beserta relasi user-nya agar query lebih ringan (Eager Loading)
        $permintaans = PermintaanLayanan::with('user')->orderBy('created_at', 'desc')->get();
        
        // Mengirim data ke view (nanti kita buat file view-nya)
        return view('permintaan.index', compact('permintaans'));
    }

    // 2. Menampilkan detail 1 permintaan beserta dokumen & tagihannya
    public function show($id)
    {
        // Cari data berdasarkan Primary Key custom kamu (id_permintaan)
        $permintaan = PermintaanLayanan::with(['dokumens', 'pnbp', 'laporanHasil', 'riwayats'])->findOrFail($id);
        
        return view('permintaan.show', compact('permintaan'));
    }

    // 3. Fungsi KHUSUS: Untuk Update Status & Otomatis Mencatat Log Riwayat
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:sedang diproses,diverifikasi,selesai'
        ]);

        $permintaan = PermintaanLayanan::findOrFail($id);
        $permintaan->status = $request->status;
        $permintaan->save(); 
        // Saat save() dijalankan, MySQL akan mendeteksi perubahan dan otomatis menjalankan Trigger!

        return back()->with('success', 'Status berhasil diperbarui! (Riwayat dicatat otomatis oleh Trigger DB)');
    }
    
    // (Fungsi create, store, edit, update, destroy biarkan ada, nanti diisi bertahap)
}