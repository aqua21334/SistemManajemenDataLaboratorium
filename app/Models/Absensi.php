<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_absensi';

    protected $fillable = [
        'id_user',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'status',
        'lokasi',
        'latitude',
        'longitude'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam_masuk' => 'datetime:H:i:s',
        'jam_pulang' => 'datetime:H:i:s',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}