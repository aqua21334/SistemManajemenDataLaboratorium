<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\PermintaanLayanan;
use Illuminate\Http\Request;

class DokumenController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi input dan tipe file (hanya boleh PDF, JPG, PNG maksimal 5MB)
        $request->validate([
            'id_permintaan' => 'required|exists:permintaan_layanans,id_permintaan',
            'nama_file' => 'required|string|max:100',
            'jenis_permintaan' => 'required|string|max:100',
            'file' => 'required|mimes:pdf,jpg,jpeg,png|max:5120', 
        ]);

        // 2. Proses Upload File
        $file = $request->file('file');
        // Membuat nama file unik agar tidak bentrok jika namanya sama
        $filename = time() . '_Dokumen_' . $file->getClientOriginalName(); 
        // Memindahkan file ke folder public/uploads/dokumen
        $file->move(public_path('uploads/dokumen'), $filename); 

        // 3. Simpan data ke database
        Dokumen::create([
            'id_permintaan' => $request->id_permintaan,
            'nama_file' => $request->nama_file,
            'jenis_permintaan' => $request->jenis_permintaan,
            'file' => $filename, // Yang disimpan di database hanya NAMA FILE-nya saja
        ]);

        return back()->with('success', 'Dokumen pendukung berhasil diunggah!');
    }

    public function destroy($id)
    {
        $dokumen = Dokumen::findOrFail($id);
        
        // Opsional: Menghapus file fisik dari folder saat data dihapus
        $filePath = public_path('uploads/dokumen/' . $dokumen->file);
        if (file_exists($filePath)) {
            unlink($filePath); 
        }

        $dokumen->delete();
        return back()->with('success', 'Dokumen berhasil dihapus!');
    }
}