<?php

namespace App\Http\Controllers;

use App\Models\DaftarSop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DaftarSopController extends Controller
{
    public function index()
    {
        $sops = DaftarSop::with('user')->get();
        return view('sop.index', compact('sops'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_sop' => 'required|string|max:50',
            'judul_sop' => 'required|string|max:100',
            // File validasi (jika ada form upload file PDF)
            // 'file_sop' => 'required|mimes:pdf|max:2048'
        ]);

        DaftarSop::create([
            'id_user' => Auth::user()->id_user,
            'jenis_sop' => $request->jenis_sop,
            'judul_sop' => $request->judul_sop,
        ]);

        return back()->with('success', 'SOP baru berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        DaftarSop::findOrFail($id)->delete();
        return back()->with('success', 'SOP berhasil dihapus!');
    }
}