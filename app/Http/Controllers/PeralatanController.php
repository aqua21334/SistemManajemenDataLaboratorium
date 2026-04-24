<?php

namespace App\Http\Controllers;

use App\Models\Peralatan;
use Illuminate\Http\Request;

class PeralatanController extends Controller
{
    public function index()
    {
        $peralatans = Peralatan::all();
        return view('peralatan.index', compact('peralatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_peralatan' => 'required|string|max:100',
            'tanggal_kalibrasi' => 'required|date',
        ]);

        Peralatan::create($request->all());

        return back()->with('success', 'Alat baru berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $alat = Peralatan::where('kode_bmn', $id)->firstOrFail();
        $alat->delete();

        return back()->with('success', 'Data alat berhasil dihapus!');
    }
}