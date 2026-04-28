<?php

namespace App\Http\Controllers;

use App\Models\Pnbp;
use App\Models\PermintaanLayanan;
use Illuminate\Http\Request;

class PnbpController extends Controller
{
    // ... (Fungsi index biarkan seperti biasa)

    // 1. Fungsi Admin Menetapkan Harga Awal (Buat Tagihan)
    public function store(Request $request)
    {
        $request->validate([
            'id_permintaan' => 'required|exists:permintaan_layanans,id_permintaan',
            'total_biaya' => 'required|numeric|min:0',
        ]);

        // Buat tagihan kosong, sisa tagihan = total biaya
        Pnbp::create([
            'id_permintaan' => $request->id_permintaan,
            'total_biaya' => $request->total_biaya,
            'jumlah_bayar' => 0,
            'sisa_tagihan' => $request->total_biaya,
            'status_pembayaran' => 'Belum Dibayar'
        ]);

        return back()->with('success', 'Harga awal berhasil ditetapkan, tagihan dikirim ke Customer!');
    }

    // 2. Fungsi Customer Membayar & Upload Bukti
    public function updatePembayaran(Request $request, $id)
    {
        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:0',
            'bukti_bayar' => 'required|file|mimes:jpg,png,pdf|max:2048',
        ]);

        $pnbp = Pnbp::findOrFail($id);

        // --- INI ADALAH LOGIKA PENGURANGANNYA ---
        $bayar_baru = $request->jumlah_bayar;
        $sisa = $pnbp->total_biaya - $bayar_baru;

        // Tentukan Status
        $status = 'Belum Lunas';
        if ($sisa <= 0) {
            $status = 'Lunas';
            $sisa = 0; // Pastikan tidak minus jika customer kelebihan bayar
        }

        // Proses Upload Bukti Bayar
        $file = $request->file('bukti_bayar');
        $filename = time() . '_BuktiBayar_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/pnbp'), $filename);

        // Simpan pembaruan ke database
        $pnbp->update([
            'jumlah_bayar' => $bayar_baru,
            'sisa_tagihan' => $sisa,
            'status_pembayaran' => $status,
            'tanggal_bayar' => now(),
            'bukti_bayar' => $filename
        ]);

        return back()->with('success', 'Pembayaran berhasil diverifikasi. Invoice telah diupdate!');
    }

    // 3. Fungsi Menampilkan Halaman Invoice untuk Customer
    public function cetakInvoice($id)
    {
        $pnbp = Pnbp::with('permintaanLayanan.user')->findOrFail($id);
        
        // Nanti kita buat file view khusus invoice yang rapi
        return view('pnbp.invoice', compact('pnbp')); 
    }
}