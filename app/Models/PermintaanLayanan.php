<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanLayanan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_permintaan';
    
    protected $fillable = [
        'id_user', 'pemohon', 'jenis_permintaan', 'status', 'tanggal_permintaan'
    ];

    // --- RELASI ---
    // 1 Permintaan milik 1 User (Customer)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // 1 Permintaan punya BANYAK Dokumen pendukung
    public function dokumens()
    {
        return $this->hasMany(Dokumen::class, 'id_permintaan', 'id_permintaan');
    }

    // 1 Permintaan punya BANYAK Riwayat log
    public function riwayats()
    {
        return $this->hasMany(RiwayatPenelitian::class, 'id_permintaan', 'id_permintaan');
    }

    // 1 Permintaan punya 1 tagihan PNBP
    public function pnbp()
    {
        return $this->hasOne(Pnbp::class, 'id_permintaan', 'id_permintaan');
    }

    // 1 Permintaan punya 1 Laporan Hasil akhir
    public function laporanHasil()
    {
        return $this->hasOne(LaporanHasil::class, 'id_permintaan', 'id_permintaan');
    }
}