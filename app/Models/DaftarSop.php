<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaftarSop extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_sop';
    public $incrementing = false; // Tidak auto-increment
    protected $keyType = 'string'; // Primary key adalah string

    protected $fillable = [
        'id_sop', 'id_user', 'jenis_sop', 'judul_sop', 'file_sop'
    ];

    // SOP ini diunggah oleh 1 User (Admin/Kepala Lab)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}