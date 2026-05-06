<?php

namespace App\Http\Controllers;

use App\Models\RiwayatPenelitian;
use Illuminate\Http\Request;

class RiwayatPenelitianController extends Controller
{
    // Hanya menampilkan daftar penelitian yang sudah selesai
    public function index()
    {
        $query = RiwayatPenelitian::with('permintaanLayanan')->orderBy('created_at', 'desc');
        
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
        
        $riwayatPenelitians = $query->get();
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
        
        $riwayatPenelitians = $query->paginate(10);
        
        \Log::info('Riwayat Search', [
            'search' => $search,
            'total' => $riwayatPenelitians->total(),
            'per_page' => $riwayatPenelitians->perPage()
        ]);
        
        return view('PetugasLab.riwayatpetugas.index', compact('riwayatPenelitians', 'search'));
    }

    /**
     * Menampilkan daftar riwayat penelitian untuk Kepala Lab
     */
    public function indexKepalaLab()
    {
        $query = RiwayatPenelitian::with('permintaanLayanan')->where('status', 'selesai')->orderBy('created_at', 'desc');
        
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
        
        $riwayatPenelitians = $query->paginate(10);
        return view('KepalaLab.riwayatkepala.index', compact('riwayatPenelitians', 'search'));
    }
}