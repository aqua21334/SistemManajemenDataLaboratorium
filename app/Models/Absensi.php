<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_absensi';

    protected $fillable = [
        'nama', 'jabatan', 'tanggal', 'foto', 'lokasi'
    ];
    
    // Tidak ada belongsTo karena berdiri mandiri sesuai desain PDM
}