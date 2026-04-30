<?php

namespace App\Http\Controllers;

use App\Models\PermintaanLayanan;
use App\Models\RiwayatPenelitian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermintaanLayananController extends Controller
{
 // 1. Menampilkan data permintaan (Halaman Index Admin)
public function index()
{
    $user = Auth::user();

    if ($user->role->nama_role == 'Customer') {
        $permintaans = PermintaanLayanan::with('laporanHasil')
            ->where('id_user', $user->id_user)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('front.index', compact('permintaans'));
    }

    // SESUAIKAN DENGAN FOTO: Admin/permintaanlayanan/index
    $laporans = PermintaanLayanan::with('user')->orderBy('created_at', 'desc')->get();
    
    return view('Admin.permintaanlayanan.index', compact('laporans'));
}

// 2. Menampilkan detail
public function show($id)
{
    $permintaan = PermintaanLayanan::with(['dokumens', 'pnbp', 'laporanHasil', 'riwayats'])->findOrFail($id);
    
    // SESUAIKAN DENGAN FOTO: Admin/permintaanlayanan/show
    // (Pastikan kamu sudah buat file show.blade.php di folder tersebut)
    return view('Admin.permintaanlayanan.show', compact('permintaan'));
}

// 4. Menampilkan form untuk edit
public function edit($id)
{
    $permintaan = PermintaanLayanan::findOrFail($id);
    
    // SESUAIKAN DENGAN FOTO: Admin/permintaanlayanan/edit
    return view('Admin.permintaanlayanan.edit', compact('permintaan'));
}
    // 5. Menyimpan perubahan permintaan (Admin)
    public function update(Request $request, $id)
    {
        $permintaan = PermintaanLayanan::findOrFail($id);

        $request->validate([
            'jenis_permintaan' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'pemohon' => 'required|string|max:100',
            'tanggal_permintaan' => 'required|date',
            'status' => 'required|in:sedang diproses,diverifikasi,selesai',
            'file_layanan' => 'nullable|file|mimes:pdf,doc,docx,zip,rar|max:5120', 
        ]);

        $data = [
            'jenis_permintaan' => $request->jenis_permintaan,
            'no_hp' => $request->no_hp,
            'pemohon' => $request->pemohon,
            'tanggal_permintaan' => $request->tanggal_permintaan,
            'status' => $request->status,
        ];

        // Handle file upload jika ada file baru
        if ($request->hasFile('file_layanan')) {
            // Hapus file lama jika ada
            if ($permintaan->file_layanan && file_exists(public_path('uploads/permintaan/' . $permintaan->file_layanan))) {
                unlink(public_path('uploads/permintaan/' . $permintaan->file_layanan));
            }

            $file = $request->file('file_layanan');
            $nama_file = time() . "_" . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move(public_path('uploads/permintaan'), $nama_file);
            $data['file_layanan'] = $nama_file;
        }

        $permintaan->update($data);

        return back()->with('success', 'Permintaan berhasil diperbarui!');
    }

    // 6. Update Status (Untuk Petugas/Admin)
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
    
    // 8. Menyimpan Permintaan Baru (Customer)
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

    // 9. Menghapus Permintaan
    public function destroy($id)
    {
        $permintaan = PermintaanLayanan::findOrFail($id);

        // Hapus file jika ada
        if ($permintaan->file_layanan && file_exists(public_path('uploads/permintaan/' . $permintaan->file_layanan))) {
            unlink(public_path('uploads/permintaan/' . $permintaan->file_layanan));
        }

        $permintaan->delete();
        return back()->with('success', 'Permintaan berhasil dihapus!');
    }
}