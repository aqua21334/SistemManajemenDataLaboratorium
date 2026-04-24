<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personil extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_personil';
    
    protected $fillable = [
        'id_user', 'nama_personil', 'jabatan', 'nip', 'foto', 'email'
    ];

    // Personil ini tertaut ke 1 Akun User (untuk login)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}