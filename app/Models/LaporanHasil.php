<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanHasil extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_laporan';

    protected $fillable = [
        'id_permintaan', 'nama_laporan', 'file_hasil', 'tanggal'
    ];

    // Laporan ini milik 1 Permintaan Layanan
    public function permintaanLayanan()
    {
        return $this->belongsTo(PermintaanLayanan::class, 'id_permintaan', 'id_permintaan');
    }
}