<?php

namespace App\Http\Controllers;

use App\Models\Pnbp;
use App\Models\PermintaanLayanan;
use Illuminate\Http\Request;

class PnbpController extends Controller
{
    // Menampilkan daftar semua PNBP/Tagihan
    public function index()
    {
        // Tampilkan semua Permintaan Layanan dengan status PNBP-nya
        $query = PermintaanLayanan::with('user', 'pnbp')->orderBy('created_at', 'desc');
        
        // Search functionality
        $search = request('search');
        if ($search) {
            $query->where(function($builder) use ($search) {
                $builder->where('id_permintaan', 'like', '%' . $search . '%')
                        ->orWhere('jenis_permintaan', 'like', '%' . $search . '%')
                        ->orWhere('pemohon', 'like', '%' . $search . '%')
                        ->orWhereHas('user', function($q) use ($search) {
                            $q->where('nama', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('pnbp', function($q) use ($search) {
                            $q->where('status_pembayaran', 'like', '%' . $search . '%');
                        });
            });
        }
        
        $permintaans = $query->get();
        return view('Admin.pnbp.index', compact('permintaans', 'search'));
    }

    // Form Buat Tagihan
    public function create()
    {
        $permintaans = PermintaanLayanan::with('user')->get();
        return view('Admin.pnbp.create', compact('permintaans'));
    }

    // 1. Fungsi Admin Menetapkan Harga Awal (Buat Tagihan)
    public function store(Request $request)
    {
        $request->validate([
            'id_permintaan' => 'required|exists:permintaan_layanans,id_permintaan',
            'total_biaya' => 'required|numeric|min:0',
        ]);

        // Cek apakah PNBP sudah ada untuk permintaan ini
        $existingPnbp = Pnbp::where('id_permintaan', $request->id_permintaan)->first();
        if($existingPnbp) {
            return back()->with('error', 'Tagihan untuk permintaan ini sudah ada!');
        }

        // Buat tagihan baru
        Pnbp::create([
            'id_permintaan' => $request->id_permintaan,
            'total_biaya' => $request->total_biaya,
            'jumlah_bayar' => 0,
            'sisa_tagihan' => $request->total_biaya,
            'status_pembayaran' => 'Belum Dibayar'
        ]);

        return redirect()->route('admin.pnbp.index')->with('success', 'Tagihan berhasil dibuat dengan total Rp ' . number_format($request->total_biaya, 0, ',', '.'));
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

    // 3. Fungsi Menampilkan Form Edit PNBP
    public function edit($id)
    {
        $pnbp = Pnbp::with('permintaanLayanan.user')->findOrFail($id);
        return view('Admin.pnbp.edit', compact('pnbp'));
    }

    // 4. Fungsi Update Status Pembayaran
    public function update(Request $request, $id)
    {
        $request->validate([
            'status_pembayaran' => 'required|in:Belum Dibayar,Belum Lunas,Lunas'
        ]);

        $pnbp = Pnbp::findOrFail($id);
        $pnbp->update([
            'status_pembayaran' => $request->status_pembayaran
        ]);

        return redirect()->route('admin.pnbp.index')->with('success', 'Status pembayaran berhasil diupdate!');
    }

    // 5. Fungsi Menampilkan Halaman Invoice untuk Customer
    public function cetakInvoice($id)
    {
        $pnbp = Pnbp::with('permintaanLayanan.user')->findOrFail($id);
        
        return view('Admin.pnbp.invoice', compact('pnbp')); 
    }
}