<?php

namespace App\Http\Controllers;

use App\Models\Peralatan;
use Illuminate\Http\Request;

class PeralatanController extends Controller
{
    public function index()
    {
        $peralatans = Peralatan::all();
        return view('Admin.peralatanadmin.index', compact('peralatans'));
    }

    public function create()
    {
        return view('Admin.peralatanadmin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_bmn' => 'required|string|max:50|unique:peralatans,kode_bmn',
            'nama_peralatan' => 'required|string|max:100',
            'tanggal_kalibrasi' => 'required|date',
        ]);

        // Status default adalah 'belum dikalibrasi' - akan diubah oleh petugas lab saat selesai kalibrasi
        Peralatan::create([
            'kode_bmn' => $request->kode_bmn,
            'nama_peralatan' => $request->nama_peralatan,
            'tanggal_kalibrasi' => $request->tanggal_kalibrasi,
            'status' => 'belum dikalibrasi',
        ]);

        return redirect()->route('peralatan')->with('success', 'Peralatan baru berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $peralatan = Peralatan::findOrFail($id);
        return view('Admin.peralatanadmin.edit', compact('peralatan'));
    }

    public function update(Request $request, $id)
    {
        $peralatan = Peralatan::findOrFail($id);
        
        $request->validate([
            'kode_bmn' => 'required|string|max:50|unique:peralatans,kode_bmn,'.$peralatan->id,
            'nama_peralatan' => 'required|string|max:100',
            'tanggal_kalibrasi' => 'required|date',
            'status' => 'required|in:belum dikalibrasi,terkalibrasi',
        ]);

        // Status dapat diubah oleh petugas lab
        $peralatan->update([
            'kode_bmn' => $request->kode_bmn,
            'nama_peralatan' => $request->nama_peralatan,
            'tanggal_kalibrasi' => $request->tanggal_kalibrasi,
            'status' => $request->status,
        ]);

        return redirect()->route('peralatan')->with('success', 'Data peralatan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $peralatan = Peralatan::findOrFail($id);
        $peralatan->delete();
        return redirect()->route('peralatan')->with('success', 'Data peralatan berhasil dihapus!');
    }
}