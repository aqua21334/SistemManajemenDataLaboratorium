<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash; // Tambahkan ini di atas untuk enkripsi password

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Role Admin terlebih dahulu
        Role::create([
            'nama_role' => 'Admin'
        ]);

        // 2. Buat Akun Admin
        User::create([
            'id_role' => 1, // Mengacu pada Role Admin yang baru dibuat
            'nama' => 'Administrator',
            'email' => 'admin@silab.com',
            'password' => Hash::make('admin123'), // Passwordnya: admin123
        ]);
    }
}
