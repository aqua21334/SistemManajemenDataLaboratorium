<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\User;
use Illuminate\Http\Request;
use App\Exports\AbsensiExport;          // <-- WAJIB: Import class export yang sudah kita buat
use Maatwebsite\Excel\Facades\Excel;    // <-- WAJIB: Import library Excel dari Maatwebsite
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        $query = Absensi::with(['user.personil']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        $absensis = $query->orderByDesc('tanggal')
            ->orderByDesc('id_absensi')
            ->get()
            ->unique('id_user')
            ->values();

        if ($search !== '') {
            $searchLower = mb_strtolower($search);

            $absensis = $absensis->filter(function ($absensi) use ($searchLower) {
                $personil = $absensi->user?->personil;

                $namaPegawai = mb_strtolower((string) ($personil?->nama_personil ?? $absensi->user?->nama ?? ''));
                $jabatanPegawai = mb_strtolower((string) ($personil?->jabatan ?? ''));
                $lokasi = mb_strtolower((string) ($absensi->lokasi ?? ''));
                $status = mb_strtolower((string) ($absensi->status ?? ''));
                $idAbsensi = (string) $absensi->id_absensi;
                $idUser = (string) $absensi->id_user;

                return str_contains($namaPegawai, $searchLower)
                    || str_contains($jabatanPegawai, $searchLower)
                    || str_contains($lokasi, $searchLower)
                    || str_contains($status, $searchLower)
                    || str_contains($idAbsensi, $searchLower)
                    || str_contains($idUser, $searchLower);
            })->values();
        }
        
        // Paginate the collection manually
        $perPage = 5;
        $page = $request->get('page', 1);
        $absensis = new \Illuminate\Pagination\Paginator(
            $absensis->forPage($page, $perPage)->values(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );
        
        // Return view Admin panel (Riwayat Absensi)
        return view('Admin.riwayatabsensi.index', compact('absensis', 'search'));
    }

    /**
     * Menampilkan halaman absensi untuk Petugas Lab (form hadir/pulang)
     */
    public function indexPetugas()
    {
        $today = Carbon::now()->toDateString();
        $absenToday = Absensi::where('id_user', Auth::id())
            ->whereDate('tanggal', $today)
            ->first();

        return view('PetugasLab.absenpetugas.index', compact('absenToday'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id_user',
            'tanggal' => 'required|date',
            'jam_masuk' => 'nullable|date_format:H:i:s',
            'jam_pulang' => 'nullable|date_format:H:i:s',
            'status' => 'nullable|string|max:20',
            'lokasi' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        Absensi::create($request->only([
            'id_user',
            'tanggal',
            'jam_masuk',
            'jam_pulang',
            'status',
            'lokasi',
            'latitude',
            'longitude',
        ]));
        return back()->with('success', 'Absensi berhasil dicatat!');
    }

    public function destroy($id)
    {
        Absensi::findOrFail($id)->delete();
        return back()->with('success', 'Data absensi dihapus!');
    }

    public function show(Absensi $absensi)
    {
        $absensi->load(['user.personil']);

        $personil = $absensi->user?->personil;
        $riwayatAbsensi = Absensi::with(['user.personil'])
            ->where('id_user', $absensi->id_user)
            ->orderByDesc('tanggal')
            ->get();

        return view('Admin.riwayatabsensi.show', compact('absensi', 'personil', 'riwayatAbsensi'));
    }

    // --- FUNGSI BARU UNTUK EXPORT EXCEL (VERSI MULTIPLE SHEETS) ---
    public function exportExcel(Request $request)
    {
        // Menangkap input tahun dan user dari URL/Form. Jika kosong, gunakan tahun saat ini
        $tahun = (int) $request->input('tahun', date('Y'));
        $idUser = $request->filled('id_user') ? (int) $request->input('id_user') : null;

        if ($idUser) {
            $user = User::with('personil')->find($idUser);
            $namaPegawai = $user?->personil?->nama_personil ?? $user?->nama ?? 'Pegawai';
            $namaPegawai = preg_replace('/[^A-Za-z0-9]+/', '_', trim($namaPegawai));
            $namaFile = 'Rekap_Absensi_' . $namaPegawai . '.xlsx';
        } else {
            $namaFile = 'Rekap_Absensi_' . $tahun . '.xlsx';
        }

        // Memanggil Master Export dan memberikan variabel tahun
        return Excel::download(new AbsensiExport($tahun, $idUser), $namaFile);
    }

    // Absen masuk untuk Petugas Lab (dengan lokasi)
    public function absenMasukPetugas(Request $request)
    {
        $request->validate([
            'status' => 'nullable|in:hadir,izin,sakit',
            'lokasi' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $today = Carbon::now()->toDateString();

        $absenExist = Absensi::where('id_user', Auth::id())
            ->whereDate('tanggal', $today)
            ->first();

        if ($absenExist && $absenExist->jam_masuk) {
            return back()->with('error', 'Anda sudah absen hadir hari ini!');
        }

        // Build data payload only with columns that exist in DB
        $data = [
            'id_user' => Auth::id(),
            'tanggal' => $today,
            'jam_masuk' => Carbon::now()->format('H:i:s'),
        ];

        if (Schema::hasColumn('absensis', 'status') && $request->filled('status')) {
            $data['status'] = $request->status;
        }
        if (Schema::hasColumn('absensis', 'lokasi') && $request->filled('lokasi')) {
            $data['lokasi'] = $request->lokasi;
        }
        if (Schema::hasColumn('absensis', 'latitude') && $request->filled('latitude')) {
            $data['latitude'] = $request->latitude;
        }
        if (Schema::hasColumn('absensis', 'longitude') && $request->filled('longitude')) {
            $data['longitude'] = $request->longitude;
        }

        if ($absenExist) {
            $absenExist->update($data);
        } else {
            Absensi::create($data);
        }

        return back()->with('success', 'Absen masuk berhasil dicatat!');
    }

    // Absen pulang untuk Petugas Lab (dengan lokasi optional)
    public function absenPulangPetugas(Request $request)
    {
        $today = Carbon::now()->toDateString();

        $absenExist = Absensi::where('id_user', Auth::id())
            ->whereDate('tanggal', $today)
            ->first();

        if (!$absenExist || !$absenExist->jam_masuk) {
            return back()->with('error', 'Anda belum absen hadir hari ini!');
        }

        if ($absenExist->jam_pulang) {
            return back()->with('error', 'Anda sudah absen pulang hari ini!');
        }

        $update = [
            'jam_pulang' => Carbon::now()->format('H:i:s'),
        ];

        if (Schema::hasColumn('absensis', 'lokasi')) {
            $update['lokasi'] = $request->input('lokasi', $absenExist->lokasi);
        }
        if (Schema::hasColumn('absensis', 'latitude')) {
            $update['latitude'] = $request->input('latitude', $absenExist->latitude);
        }
        if (Schema::hasColumn('absensis', 'longitude')) {
            $update['longitude'] = $request->input('longitude', $absenExist->longitude);
        }

        $absenExist->update($update);

        return back()->with('success', 'Absen pulang berhasil dicatat!');
    }
}