<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Personil;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class PersonilController extends Controller
{
    /**
     * Menampilkan daftar personil dengan fitur Pencarian.
     */
    public function index(Request $request)
    {
        // 1. Ambil keyword dari input 'search' di URL
        $search = $request->query('search');

        // 2. Gunakan Query Builder agar bisa memfilter
        $query = Personil::query();

        // 3. Jika ada input pencarian, filter berdasarkan nama, nip, atau jabatan
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('nama_personil', 'like', '%' . $search . '%')
                  ->orWhere('nip', 'like', '%' . $search . '%')
                  ->orWhere('jabatan', 'like', '%' . $search . '%');
            });
        }

        // 4. Ambil data dengan Pagination (5 data per halaman)
        // Gunakan paginate() agar link di bawah tabel berfungsi otomatis
        $personils = $query->orderBy('nama_personil', 'asc')->paginate(5);

        // Status absensi per user untuk hari ini (hadir / sakit / izin)
        $statusAbsensiUser = Absensi::whereDate('tanggal', Carbon::today())
            ->orderByDesc('id_absensi')
            ->get()
            ->unique('id_user')
            ->mapWithKeys(function ($absensi) {
                $status = $absensi->status;

                if (empty($status) && !empty($absensi->jam_masuk)) {
                    $status = 'hadir';
                }

                return [$absensi->id_user => $status];
            })
            ->toArray();
        
        // Kirim data ke view
        return view('Admin.pegawaiadmin.index', compact('personils', 'statusAbsensiUser'));
    }

    public function create()
    {
        return view('Admin.pegawaiadmin.create');
    }
       
    public function store(Request $request)
    {
        $request->validate([
            'nama'          => 'required|string|max:100',
            'jabatan'       => 'required|string|max:50',
            'nip'           => 'required|string|max:25',
            'email'         => 'required|email|unique:users,email',
            'foto'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'email.unique' => 'Gagal! Email ini sudah dipakai oleh akun lain.',
        ]);

        // --- VALIDASI 1: CEK NIP DUPLIKAT ---
        $nipExists = Personil::where('nip', $request->nip)->exists();
        if ($nipExists) {
            return back()->withInput()->withErrors(['nip' => 'Gagal! NIP ' . $request->nip . ' sudah terdaftar di sistem.']);
        }

        // --- VALIDASI 2: CEK KEPALA LAB TUNGGAL ---
        if ($request->jabatan == 'Kepala Lab') {
            $bossExists = Personil::where('jabatan', 'Kepala Lab')->exists();
            if ($bossExists) {
                return back()->withInput()->withErrors(['jabatan' => 'Gagal! Jabatan Kepala Lab sudah terisi. Hanya diperbolehkan satu orang.']);
            }
        }

        $idRolePegawai = $request->jabatan === 'Kepala Lab' ? 2 : 3;

        DB::transaction(function () use ($request, $idRolePegawai) {
            $user = User::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->nip),
                'id_role' => $idRolePegawai,
            ]);

            $data = [
                'id_user'       => $user->id_user,
                'nama_personil' => $request->nama,
                'jabatan'       => $request->jabatan,
                'nip'           => $request->nip,
                'email'         => $request->email,
            ];

            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $nama_foto = time() . "_" . $file->getClientOriginalName();
                $file->move(public_path('images/pegawai'), $nama_foto);
                $data['foto'] = $nama_foto;
            }

            Personil::create($data);
        });

        return redirect()->route('admin.pegawai')->with('success', 'Data personil dan akun login berhasil dibuat! Password awal akun menggunakan NIP.');
    }

    public function edit($id)
    {
        $personil = Personil::findOrFail($id);
        return view('Admin.pegawaiadmin.edit', compact('personil'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'    => 'required|string|max:100',
            'jabatan' => 'required|string|max:50',
            'nip'     => 'required|string|max:25',
            'email'   => 'required|email',
            'foto'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $personil = Personil::findOrFail($id);

        // --- VALIDASI 1: CEK NIP MILIK ORANG LAIN ---
        $nipUsedByOther = Personil::where('nip', $request->nip)
                                  ->where('id_personil', '!=', $id)
                                  ->exists();
        if ($nipUsedByOther) {
            return back()->withInput()->withErrors(['nip' => 'Gagal! NIP sudah digunakan oleh pegawai lain.']);
        }

        // --- VALIDASI 2: CEK KEPALA LAB (KECUALI DIRINYA SENDIRI) ---
        if ($request->jabatan == 'Kepala Lab') {
            $bossUsedByOther = Personil::where('jabatan', 'Kepala Lab')
                                       ->where('id_personil', '!=', $id)
                                       ->exists();
            if ($bossUsedByOther) {
                return back()->withInput()->withErrors(['jabatan' => 'Gagal! Jabatan Kepala Lab sudah dipegang orang lain.']);
            }
        }

        $data = [
            'nama_personil' => $request->nama,
            'jabatan'       => $request->jabatan,
            'nip'           => $request->nip,
            'email'         => $request->email,
        ];

        $personil->user?->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'id_role' => $request->jabatan === 'Kepala Lab' ? 2 : 3,
        ]);

        if ($request->hasFile('foto')) {
            if ($personil->foto && file_exists(public_path('images/pegawai/' . $personil->foto))) {
                unlink(public_path('images/pegawai/' . $personil->foto));
            }
            $file = $request->file('foto');
            $nama_foto = time() . "_" . $file->getClientOriginalName();
            $file->move(public_path('images/pegawai'), $nama_foto);
            $data['foto'] = $nama_foto;
        }

        $personil->update($data);
        return redirect()->route('admin.pegawai')->with('success', 'Data pegawai berhasil diperbarui!');
    }


    public function destroy($id)
    {
        $personil = Personil::findOrFail($id);

        try {
            DB::transaction(function () use ($personil) {
                $user = $personil->user;

                if ($personil->foto && file_exists(public_path('images/pegawai/' . $personil->foto))) {
                    unlink(public_path('images/pegawai/' . $personil->foto));
                }

                // Hapus data personil, lalu hapus akun login terkait agar tidak tersisa di tabel users.
                $personil->delete();
                $user?->delete();
            });

            return redirect()->route('admin.pegawai')->with('success', 'Data personil berhasil dihapus!');
        } catch (\Throwable $e) {
            return redirect()->route('admin.pegawai')->with('error', 'Gagal menghapus data pegawai: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan daftar personil untuk Kepala Lab (Hanya Petugas Lab)
     */
    public function indexKepalaLab(Request $request)
    {
        // 1. Ambil keyword dari input 'search' di URL
        $search = $request->query('search');

        // 2. Gunakan Query Builder agar bisa memfilter
        $query = Personil::with(['user.absensis' => function ($absensiQuery) {
            $absensiQuery->orderByDesc('tanggal')->orderByDesc('id_absensi');
        }]);

        // 3. Filter hanya Petugas Lab, exclude Kepala Lab
        $query->where('jabatan', '!=', 'Kepala Lab');

        // 4. Jika ada input pencarian, filter berdasarkan nama, nip, atau jabatan
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('nama_personil', 'like', '%' . $search . '%')
                  ->orWhere('nip', 'like', '%' . $search . '%')
                  ->orWhere('jabatan', 'like', '%' . $search . '%');
            });
        }

        // 5. Ambil data dengan Pagination
        $personils = $query->orderBy('nama_personil', 'asc')->paginate(5);
        
        // Kirim data ke view kepala lab
        return view('KepalaLab.pegawaikepala.index', compact('personils', 'search'));
    }
}