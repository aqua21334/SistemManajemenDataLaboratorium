<?php

namespace App\Http\Controllers;

use App\Models\Peralatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PeralatanController extends Controller
{
    public function index()
    {
        $search = request('search');
        $status = request('status');

        $query = Peralatan::query();

        if (!empty($search)) {
            $query->where(function ($builder) use ($search) {
                $builder->where('kode_bmn', 'like', '%' . $search . '%')
                    ->orWhere('nama_peralatan', 'like', '%' . $search . '%')
                    ->orWhere('tanggal_kalibrasi', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%');
            });
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        $peralatans = $query->orderBy('nama_peralatan', 'asc')->get();

        return view('Admin.peralatanadmin.index', compact('peralatans', 'search', 'status'));
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
        ], [
            'kode_bmn.unique' => 'Gagal! Kode BMN ' . $request->kode_bmn . ' sudah terdaftar di sistem.',
        ]);

        // Status default adalah 'belum dikalibrasi' - akan diubah oleh petugas lab saat selesai kalibrasi
        Peralatan::create([
            'kode_bmn' => $request->kode_bmn,
            'nama_peralatan' => $request->nama_peralatan,
            'tanggal_kalibrasi' => $request->tanggal_kalibrasi,
            'status' => 'belum dikalibrasi',
        ]);

        return redirect()->route('admin.peralatan.index')->with('success', 'Peralatan baru berhasil ditambahkan!');
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

        return redirect()->route('admin.peralatan.index')->with('success', 'Data peralatan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $peralatan = Peralatan::findOrFail($id);
        $peralatan->delete();
        return redirect()->route('admin.peralatan.index')->with('success', 'Data peralatan berhasil dihapus!');
    }

    /**
     * Menampilkan daftar peralatan untuk Kepala Lab
     */
    public function indexKepalaLab()
    {
        $search = request('search');
        $status = request('status');

        $query = Peralatan::query();

        if (!empty($search)) {
            $query->where(function ($builder) use ($search) {
                $builder->where('kode_bmn', 'like', '%' . $search . '%')
                    ->orWhere('nama_peralatan', 'like', '%' . $search . '%')
                    ->orWhere('tanggal_kalibrasi', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%');
            });
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        $peralatans = $query->orderBy('nama_peralatan', 'asc')->paginate(10);

        return view('KepalaLab.peralatankepala.index', compact('peralatans', 'search', 'status'));
    }

    /**
     * Menampilkan daftar peralatan untuk Petugas Lab
     */
    public function indexPetugas()
    {
        $search = request('search');
        $status = request('status');

        $query = Peralatan::query();

        if (!empty($search)) {
            $query->where(function ($builder) use ($search) {
                $builder->where('kode_bmn', 'like', '%' . $search . '%')
                    ->orWhere('nama_peralatan', 'like', '%' . $search . '%')
                    ->orWhere('tanggal_kalibrasi', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%');
            });
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        $peralatans = $query->orderBy('nama_peralatan', 'asc')->paginate(10);
        
        \Log::info('Peralatan Search', [
            'search' => $search,
            'status' => $status,
            'total' => $peralatans->total(),
            'per_page' => $peralatans->perPage()
        ]);

        return view('PetugasLab.peralatanpetugas.index', compact('peralatans', 'search', 'status'));
    }

    /**
     * Menampilkan form tambah peralatan untuk Petugas Lab
     */
    public function createPetugas()
    {
        return view('PetugasLab.peralatanpetugas.create');
    }

    /**
     * Simpan data peralatan baru untuk Petugas Lab
     */
    public function storePetugas(Request $request)
    {
        $request->validate([
            'kode_bmn' => 'required|string|max:50|unique:peralatans,kode_bmn',
            'nama_peralatan' => 'required|string|max:100',
            'tanggal_kalibrasi' => 'required|date',
        ], [
            'kode_bmn.unique' => 'Gagal! Kode BMN ' . $request->kode_bmn . ' sudah terdaftar di sistem.',
        ]);

        try {
            Peralatan::create([
                'kode_bmn' => $request->kode_bmn,
                'nama_peralatan' => $request->nama_peralatan,
                'tanggal_kalibrasi' => $request->tanggal_kalibrasi,
                'status' => 'belum dikalibrasi',
            ]);

            return redirect()->route('petugas.peralatan.index')->with('success', 'Data peralatan berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Failed to store peralatan (petugas): ' . $e->getMessage(), [
                'request' => $request->only(['kode_bmn', 'nama_peralatan', 'tanggal_kalibrasi'])
            ]);

            return back()->withInput()->with('error', 'Gagal menyimpan data peralatan. Silakan coba lagi atau hubungi admin.');
        }
    }

    /**
     * Menampilkan form edit peralatan untuk Petugas Lab
     */
    public function editPetugas($id)
    {
        $peralatan = Peralatan::findOrFail($id);

        return view('PetugasLab.peralatanpetugas.update', compact('peralatan'));
    }

    /**
     * Update data peralatan untuk Petugas Lab
     */
    public function updatePetugas(Request $request, $id)
    {
        $peralatan = Peralatan::findOrFail($id);

        $request->validate([
            'kode_bmn' => 'required|string|max:50',
            'nama_peralatan' => 'required|string|max:100',
            'tanggal_kalibrasi' => 'required|date',
            'status' => 'required|in:belum dikalibrasi,sudah dikalibrasi',
        ]);

        $peralatan->update([
            'kode_bmn' => $request->kode_bmn,
            'nama_peralatan' => $request->nama_peralatan,
            'tanggal_kalibrasi' => $request->tanggal_kalibrasi,
            'status' => $request->status,
        ]);

        return redirect()->route('petugas.peralatan.index')->with('success', 'Data peralatan berhasil diperbarui.');
    }
}