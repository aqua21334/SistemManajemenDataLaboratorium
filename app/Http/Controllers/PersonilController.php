<?php

namespace App\Http\Controllers;

use App\Models\Personil;
use App\Models\User;
use Illuminate\Http\Request;

class PersonilController extends Controller
{
  public function index()
{
    // Mengambil semua data personil dari database
    $personils = \App\Models\Personil::all(); 
    
    // Kirim data ke view (pastikan path folder Admin sesuai dengan strukturmu)
    return view('Admin.pegawaiadmin.index', compact('personils'));
}

public function create()
{
    // Hitung jumlah personil yang sudah ada, maka id_user berikutnya adalah count + 1
    $countPersonil = Personil::count();
    $nextUserId = $countPersonil + 1;
    
    return view('Admin.pegawaiadmin.create', compact('nextUserId'));
}
   
public function store(Request $request)
{
    // 1. Validasi (Hapus no_hp dari sini)
    $request->validate([
        'id_user'       => 'required|exists:users,id_user',
        'nama'          => 'required|string|max:100',
        'jabatan'       => 'required|string|max:50',
        'nip'           => 'required|string|max:25',
        'email'         => 'required|email',
        'foto'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    // 2. Siapkan Data (Hapus no_hp dari array ini)
    $data = [
        'id_user'       => $request->id_user,
        'nama_personil' => $request->nama,
        'jabatan'       => $request->jabatan,
        'nip'           => $request->nip,
        'email'         => $request->email,
    ];

    // 3. Logika Upload Foto
    if ($request->hasFile('foto')) {
        $file = $request->file('foto');
        $nama_foto = time() . "_" . $file->getClientOriginalName();
        $file->move(public_path('images/pegawai'), $nama_foto);
        $data['foto'] = $nama_foto;
    }

    // 4. Simpan ke Database
    Personil::create($data);

    return redirect()->route('pegawai')->with('success', 'Data personil berhasil ditambahkan!');
}
   
// ... kode lainnya ...

/**
 * Menampilkan Form Edit Pegawai
 */
public function edit($id)
{
    // Mengambil data satu orang personil berdasarkan ID
    // Jika ID tidak ditemukan, Laravel otomatis memunculkan halaman error 404
    $personil = Personil::findOrFail($id);
    
    // Pastikan path folder Admin/pegawaiadmin/edit sesuai struktur file Anda
    return view('Admin.pegawaiadmin.edit', compact('personil'));
}

/**
 * Memproses perubahan data ke Database
 */
public function update(Request $request, $id)
{
    // Validasi data yang masuk
    $request->validate([
        'nama'    => 'required|string|max:100',
        'jabatan' => 'required|string|max:50',
        'nip'     => 'required|string|max:25',
        'email'   => 'required|email',
        'foto'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $personil = Personil::findOrFail($id);
    
    $data = [
        'nama_personil' => $request->nama,
        'jabatan'       => $request->jabatan,
        'nip'           => $request->nip,
        'email'         => $request->email,
    ];

    // Logika jika ada unggahan foto baru
    if ($request->hasFile('foto')) {
        // Hapus foto lama dari folder public/images/pegawai jika ada
        if ($personil->foto && file_exists(public_path('images/pegawai/' . $personil->foto))) {
            unlink(public_path('images/pegawai/' . $personil->foto));
        }

        $file = $request->file('foto');
        $nama_foto = time() . "_" . $file->getClientOriginalName();
        $file->move(public_path('images/pegawai'), $nama_foto);
        $data['foto'] = $nama_foto;
    }

    $personil->update($data);

    return redirect()->route('pegawai')->with('success', 'Data pegawai berhasil diperbarui!');
}

/**
 * Menghapus data personil dari database.
 */
public function destroy($id)
{
    // 1. Cari data personil berdasarkan ID
    $personil = Personil::findOrFail($id);

    // 2. Logika hapus file foto jika personil memiliki foto
    if ($personil->foto && file_exists(public_path('images/pegawai/' . $personil->foto))) {
        unlink(public_path('images/pegawai/' . $personil->foto));
    }

    // 3. Hapus data dari database
    $personil->delete();

    // 4. Kembali ke halaman daftar dengan pesan sukses
    return redirect()->route('pegawai')->with('success', 'Data personil berhasil dihapus!');
}

    
}