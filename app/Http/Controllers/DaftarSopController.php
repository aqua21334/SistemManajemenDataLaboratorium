<?php

namespace App\Http\Controllers;

use App\Models\DaftarSop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DaftarSopController extends Controller
{
    public function index()
    {
        $search = request('search');
        
        $query = DaftarSop::with('user');
        
        if (!empty($search)) {
            $query->where(function ($builder) use ($search) {
                $builder->where('id_sop', 'like', '%' . $search . '%')
                    ->orWhere('jenis_sop', 'like', '%' . $search . '%')
                    ->orWhere('judul_sop', 'like', '%' . $search . '%');
            });
        }
        
        $sops = $query->orderBy('judul_sop', 'asc')->get();
        
        return view('Admin.sop.index', compact('sops', 'search'));
    }

    public function create()
    {
        return view('Admin.sop.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_sop' => 'required|string|max:50|unique:daftar_sops,id_sop',
            'jenis_sop' => 'required|string|max:50',
            'judul_sop' => 'required|string|max:100',
            'file_sop' => 'nullable|mimes:pdf,doc,docx,xlsx,xls|max:5120',
        ], [
            'id_sop.unique' => 'Gagal! ID Dokumen ' . $request->id_sop . ' sudah terdaftar di sistem.',
        ]);

        $data = [
            'id_sop' => $request->id_sop,
            'id_user' => Auth::user()->id_user,
            'jenis_sop' => $request->jenis_sop,
            'judul_sop' => $request->judul_sop,
        ];

        // Handle file upload
        if ($request->hasFile('file_sop')) {
            $file = $request->file('file_sop');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/sop'), $fileName);
            $data['file_sop'] = $fileName;
        }

        DaftarSop::create($data);

        return redirect()->route('admin.sop.index')->with('success', 'SOP baru berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $sop = DaftarSop::findOrFail($id);
        return view('Admin.sop.edit', compact('sop'));
    }

    public function update(Request $request, $id)
    {
        $sop = DaftarSop::findOrFail($id);

        $request->validate([
            'jenis_sop' => 'required|string|max:50',
            'judul_sop' => 'required|string|max:100',
            'file_sop' => 'nullable|mimes:pdf,doc,docx,xlsx,xls|max:5120' // Max 5MB
        ]);

        $data = [
            'jenis_sop' => $request->jenis_sop,
            'judul_sop' => $request->judul_sop,
        ];

        // Handle file upload (replace old file if new file provided)
        if ($request->hasFile('file_sop')) {
            // Delete old file if exists
            if ($sop->file_sop && file_exists(public_path('uploads/sop/' . $sop->file_sop))) {
                unlink(public_path('uploads/sop/' . $sop->file_sop));
            }

            $file = $request->file('file_sop');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/sop'), $fileName);
            $data['file_sop'] = $fileName;
        }

        $sop->update($data);

        return redirect()->route('admin.sop.index')->with('success', 'SOP berhasil diperbarui!');
    }

    public function destroy($id)
    {
        DaftarSop::findOrFail($id)->delete();
        return redirect()->route('admin.sop.index')->with('success', 'SOP berhasil dihapus!');
    }

    /**
     * Menampilkan daftar SOP untuk Kepala Lab
     */
    public function indexKepalaLab()
    {
        $search = request('search');
        
        $query = DaftarSop::with('user');
        
        if (!empty($search)) {
            $query->where(function ($builder) use ($search) {
                $builder->where('id_sop', 'like', '%' . $search . '%')
                    ->orWhere('jenis_sop', 'like', '%' . $search . '%')
                    ->orWhere('judul_sop', 'like', '%' . $search . '%');
            });
        }
        
        $sops = $query->orderBy('judul_sop', 'asc')->paginate(10);
        
        return view('KepalaLab.sopkepala.index', compact('sops', 'search'));
    }
}