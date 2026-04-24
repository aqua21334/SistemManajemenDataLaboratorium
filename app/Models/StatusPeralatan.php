<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusPeralatan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_status';

    protected $fillable = [
        'kode_bmn', 'petugas'
    ];

    // Status ini milik 1 Peralatan
    public function peralatan()
    {
        return $this->belongsTo(Peralatan::class, 'kode_bmn', 'kode_bmn');
    }
}