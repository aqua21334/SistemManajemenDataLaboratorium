<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function index()
    {
        // Jika user sudah login, langsung lempar ke dashboard
        if (Auth::check()) {
            return redirect('/dashboard');
        }
        return view('auth.login'); // (View ini akan kita buat di tahap frontend nanti)
    }

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


            // 3. Cek Role dan arahkan ke tujuan masing-masing
            $role = Auth::user()->role->nama_role;

            if ($role === 'Admin') {
                return redirect()->intended('/admin/dashboard');
            } elseif ($role === 'Kepala_Lab') {
                return redirect()->intended('/kepala/dashboard');
            } elseif ($role === 'Petugas') {
                return redirect()->intended('/petugas/dashboard');
            } else {
                // Jika Customer, kembalikan ke Front Page ('/')
                return redirect()->intended('/'); 
            }
        }

        // Jika gagal login
        return back()->withErrors([
            'email' => 'Email atau Password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // Memproses logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}