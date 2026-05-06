<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPenelitian extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_riwayat';

    protected $fillable = [
        'id_permintaan', 'id_user', 'nama_laporan', 'tanggal_selesai', 'status'
    ];

    // Riwayat ini milik 1 Permintaan Layanan
    public function permintaanLayanan()
    {
        return $this->belongsTo(PermintaanLayanan::class, 'id_permintaan', 'id_permintaan');
    }

    // Riwayat ini dicatat oleh 1 User (Petugas Lab yang mengubah status)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Riwayat ini memiliki 1 Laporan Hasil
    public function laporanHasil()
    {
        return $this->hasOne(LaporanHasil::class, 'id_permintaan', 'id_permintaan');
    }

}