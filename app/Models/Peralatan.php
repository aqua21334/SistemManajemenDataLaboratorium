<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peralatan extends Model
{
    use HasFactory;

    protected $primaryKey = 'kode_bmn'; // PK beda sendiri
    
    protected $fillable = [
        'nama_peralatan', 'tanggal_kalibrasi'
    ];

    // 1 Alat bisa punya banyak riwayat Status/Perbaikan
    public function statusPeralatan()
    {
        return $this->hasMany(StatusPeralatan::class, 'kode_bmn', 'kode_bmn');
    }
}