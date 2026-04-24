<?php

namespace App\Http\Controllers;

use App\Models\Pnbp;
use App\Models\PermintaanLayanan;
use Illuminate\Http\Request;

class PnbpController extends Controller
{
    public function index()
    {
        $pnbps = Pnbp::with('permintaanLayanan')->get();
        $permintaans = PermintaanLayanan::where('status', '!=', 'selesai')->get();
        return view('pnbp.index', compact('pnbps', 'permintaans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_permintaan' => 'required|exists:permintaan_layanans,id_permintaan',
            'jumlah' => 'required|numeric',
            'tanggal_bayar' => 'nullable|date',
            'invoice' => 'nullable|file|mimes:pdf,jpg,png|max:2048', // Validasi file invoice
        ]);

        $data = $request->all();

        // Logika sederhana untuk menyimpan file jika ada yang diupload
        if ($request->hasFile('invoice')) {
            $file = $request->file('invoice');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/invoices'), $filename);
            $data['invoice'] = $filename;
        }

        Pnbp::create($data);
        return back()->with('success', 'Data PNBP berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        Pnbp::findOrFail($id)->delete();
        return back()->with('success', 'Data PNBP dihapus!');
    }
}