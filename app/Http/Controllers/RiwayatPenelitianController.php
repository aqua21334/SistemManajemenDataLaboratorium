<?php

namespace App\Http\Controllers;

use App\Models\RiwayatPenelitian;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class RiwayatPenelitianController extends Controller
{
    // Hanya menampilkan daftar penelitian yang sudah selesai
    public function index()
    {
        $query = RiwayatPenelitian::with('permintaanLayanan', 'laporanHasil')
            ->whereNotNull('nama_laporan')
            ->orderBy('created_at', 'desc');
        
        // Search functionality
        $search = request('search');
        if ($search) {
            $query->where(function($builder) use ($search) {
                $builder->where('id_permintaan', 'like', '%' . $search . '%')
                        ->orWhere('nama_laporan', 'like', '%' . $search . '%')
                        ->orWhere('status', 'like', '%' . $search . '%')
                        ->orWhereHas('permintaanLayanan', function($q) use ($search) {
                            $q->where('jenis_permintaan', 'like', '%' . $search . '%');
                        });
            });
        }
        
        // Ambil semua, hapus duplikat berdasarkan id_permintaan (tampilkan catatan terakhir per permintaan)
        $all = $query->get()->unique('id_permintaan')->values();

        // Paginate collection (5 per halaman)
        $page = request()->get('page', 1);
        $perPage = 5;
        $slice = $all->slice(($page - 1) * $perPage, $perPage);
        $riwayatPenelitians = new LengthAwarePaginator(
            $slice->values(),
            $all->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('Admin.riwayatpenelitian.index', compact('riwayatPenelitians', 'search'));
    }

    // Menampilkan detail penelitian tertentu
    public function show($id)
    {
        $riwayatPenelitian = RiwayatPenelitian::findOrFail($id);
        return view('Admin.riwayatpenelitian.show', compact('riwayatPenelitian'));
    }

    /**
     * Menampilkan daftar riwayat penelitian untuk Petugas Lab
     */
    public function indexPetugas()
    {
        $search = request('search');
        
        $query = RiwayatPenelitian::with('permintaanLayanan', 'laporanHasil')
                                    ->whereNotNull('nama_laporan')
                                    ->where('status', 'selesai')
                                    ->orderBy('created_at', 'desc');
        
        // Search functionality
        if (!empty($search)) {
            $query->where(function($builder) use ($search) {
                $builder->where('id_permintaan', 'like', '%' . $search . '%')
                        ->orWhere('nama_laporan', 'like', '%' . $search . '%')
                        ->orWhereHas('permintaanLayanan', function($q) use ($search) {
                            $q->where('jenis_permintaan', 'like', '%' . $search . '%');
                        });
            });
        }
        
        $all = $query->get()->unique('id_permintaan')->values();

        $page = request()->get('page', 1);
        $perPage = 5;
        $slice = $all->slice(($page - 1) * $perPage, $perPage);
        $riwayatPenelitians = new LengthAwarePaginator(
            $slice->values(),
            $all->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        \Log::info('Riwayat Search', [
            'search' => $search,
            'total' => $riwayatPenelitians->total(),
            'per_page' => $riwayatPenelitians->perPage()
        ]);
        
        return view('petugaslab.riwayatpetugas.index', compact('riwayatPenelitians', 'search'));
    }

    /**
     * Menampilkan daftar riwayat penelitian untuk Kepala Lab
     */
    public function indexKepalaLab()
    {
        $query = RiwayatPenelitian::with(['permintaanLayanan', 'user.personil', 'laporanHasil'])
            ->whereNotNull('nama_laporan')
            ->where('status', 'selesai')
            ->orderByDesc('tanggal_selesai')
            ->orderByDesc('created_at');
        
        // Search functionality
        $search = request('search');
        if ($search) {
            $query->where(function($builder) use ($search) {
                $builder->where('id_permintaan', 'like', '%' . $search . '%')
                        ->orWhere('nama_laporan', 'like', '%' . $search . '%')
                        ->orWhere('status', 'like', '%' . $search . '%')
                        ->orWhereHas('permintaanLayanan', function($q) use ($search) {
                            $q->where('jenis_permintaan', 'like', '%' . $search . '%');
                        });
            });
        }
        
        $riwayatPenelitians = $query->paginate(5);
        return view('KepalaLab.riwayatkepala.index', compact('riwayatPenelitians', 'search'));
    }

    public function lihatFile($id)
    {
        $riwayat = RiwayatPenelitian::with('laporanHasil')->findOrFail($id);
        $laporan = $riwayat->laporanHasil;

        abort_unless($laporan && $laporan->file_hasil, 404);

        $fileName = basename($laporan->file_hasil);
        $publicFile = public_path('uploads/laporan/' . $fileName);
        $storageFile = storage_path('app/public/uploads/laporan/' . $fileName);

        $filePath = is_file($publicFile)
            ? $publicFile
            : (is_file($storageFile) ? $storageFile : null);

        abort_unless($filePath, 404);

        return response()->file($filePath);
    }
}