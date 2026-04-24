<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_dokumen';

    protected $fillable = [
        'id_permintaan', 'nama_file', 'jenis_permintaan', 'file'
    ];

    // Dokumen ini milik 1 Permintaan Layanan
    public function permintaanLayanan()
    {
        return $this->belongsTo(PermintaanLayanan::class, 'id_permintaan', 'id_permintaan');
    }
}