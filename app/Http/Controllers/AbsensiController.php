<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index()
    {
        $absensis = Absensi::orderBy('tanggal', 'desc')->get();
        return view('absensi.index', compact('absensis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'jabatan' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'lokasi' => 'required|string|max:100',
        ]);

        Absensi::create($request->all());
        return back()->with('success', 'Absensi berhasil dicatat!');
    }

    public function destroy($id)
    {
        Absensi::findOrFail($id)->delete();
        return back()->with('success', 'Data absensi dihapus!');
    }
}