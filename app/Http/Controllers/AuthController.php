<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function index()
    {
        // Jika user sudah login, lemparkan kembali ke halaman depan
        if (Auth::check()) {
            return redirect('/'); 
        }
        return view('auth.login'); 
    }

    // Memproses data login
    // Memproses data login
    public function login(Request $request)
    {
        // 1. Validasi inputan
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Cek kecocokan di database
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // 3. Ambil id_role langsung dari user yang sedang login
            $id_role = Auth::user()->id_role;

            // 4. Arahkan sesuai ID Role dari tabel roles
            if ($id_role == 1) { 
                // 1 = Admin
                return redirect()->intended(route('admin.dashboard'));
            } 
            elseif ($id_role == 2) { 
                // 2 = Kepala_Lab
                return redirect()->intended(route('kepalalab.dashboard'));
            } 
            elseif ($id_role == 3) { 
                // 3 = Petugas
                return redirect()->route('petugas.dashboard');
            } 
            elseif ($id_role == 4) { 
                // 4 = Customer
                return redirect()->intended('/customer/dashboard'); 
            } 
            else {
                // Jika entah kenapa ID role tidak terdaftar, kembalikan ke beranda
                return redirect()->intended('/'); 
            }
        }

        // Jika gagal login (email/password salah)
        return back()->withErrors([
            'email' => 'Email atau Password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // --- FUNGSI UNTUK MENAMPILKAN HALAMAN REGISTER ---
    public function showRegister()
    {
        return view('auth.register');
    }

    // --- FUNGSI UNTUK MEMPROSES DATA PENDAFTARAN ---
    public function register(Request $request)
    {
        // 1. Validasi input dari form
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email', // Pastikan email belum pernah dipakai
            'password' => 'required|min:6' // Minimal 6 karakter
        ], [
            // Pesan error custom (opsional, agar bahasanya enak dibaca)
            'email.unique' => 'Email ini sudah terdaftar!',
            'password.min' => 'Kata sandi minimal 6 karakter!'
        ]);

        // 2. Cari ID Role untuk 'Customer' secara otomatis
        // Asumsi: Kita ingin semua orang yang mendaftar lewat halaman depan otomatis menjadi Customer
        $roleCustomer = \App\Models\Role::where('nama_role', 'Customer')->first();
        
        // Jika karena suatu alasan role Customer tidak ditemukan di database, set manual ke angka (misal: 4)
        $id_role_customer = $roleCustomer ? $roleCustomer->id_role : 4; 

        // 3. Simpan data user baru ke database
        $user = \App\Models\User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password), // Password WAJIB dienkripsi (Hash)
            'id_role' => $id_role_customer
        ]);

        // 4. Arahkan kembali ke halaman login dengan membawa pesan sukses
        return redirect('/login')->with('success', 'Akun berhasil dibuat! Silakan masuk menggunakan Email dan Kata Sandi Anda.');
    }

    // Memproses logout
    // Memproses logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Mengarahkan kembali ke rute halaman depan (/)
        return redirect('/');
    }
}