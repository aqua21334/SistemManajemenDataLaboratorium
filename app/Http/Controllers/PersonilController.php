<?php

namespace App\Http\Controllers;

use App\Models\Personil;
use App\Models\User;
use Illuminate\Http\Request;

class PersonilController extends Controller
{
    public function index()
    {
        $personils = Personil::with('user')->get();
        $users = User::where('id_role', '!=', 4)->get(); // Ambil user selain Customer (asumsi id_role 4 adalah Customer)
        return view('personil.index', compact('personils', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id_user',
            'nama_personil' => 'required|string|max:100',
            'jabatan' => 'required|string|max:50',
            'nip' => 'nullable|string|max:25',
        ]);

        Personil::create($request->all());
        return back()->with('success', 'Data personil berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        Personil::findOrFail($id)->delete();
        return back()->with('success', 'Data personil dihapus!');
    }
}