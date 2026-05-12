<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Tambahkan baris ini agar Laravel tahu Primary Key-mu adalah id_user
    protected $primaryKey = 'id_user'; 

    protected $fillable = [
        'nama',
        'email',
        'password',
        'id_role', // Jangan lupa tambahkan id_role ke sini
    ];
    // Relasi ke Role (Admin, Customer, dll)
    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role', 'id_role');
    }

    // Relasi ke detail Personil (jika dia pegawai lab)
    public function personil()
    {
        return $this->hasOne(Personil::class, 'id_user', 'id_user');
    }

    // Riwayat absensi milik user ini
    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'id_user', 'id_user');
    }

    // Relasi ke Permintaan Layanan (jika dia customer yang mengajukan)
    public function permintaanLayanans()
    {
        return $this->hasMany(PermintaanLayanan::class, 'id_user', 'id_user');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
