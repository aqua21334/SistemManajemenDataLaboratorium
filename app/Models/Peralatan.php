<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peralatan extends Model
{
    use HasFactory;

    // Primary key adalah id (default)
    // kode_bmn adalah string biasa yang manual input
    
    protected $fillable = [
        'kode_bmn', 'nama_peralatan', 'tanggal_kalibrasi', 'status'
    ];

    // 1 Alat bisa punya banyak riwayat Status/Perbaikan
    public function statusPeralatan()
    {
        return $this->hasMany(StatusPeralatan::class, 'kode_bmn', 'kode_bmn');
    }

    // Ambil status kalibrasi terakhir beserta petugas yang menanganinya
    public function latestStatusPeralatan()
    {
        return $this->hasOne(StatusPeralatan::class, 'kode_bmn', 'kode_bmn')->latestOfMany('id_status');
    }
}