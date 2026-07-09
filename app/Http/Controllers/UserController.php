<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Show user profile page
     */
    public function showProfile()
    {
        $user = Auth::user()->fresh(['personil', 'role']);
        return view('User.profile', compact('user'));
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user()->fresh(['personil', 'role']);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id_user . ',id_user',
        ]);

        DB::transaction(function () use ($user, $validated) {
            $user->update($validated);

            if ($user->personil) {
                $user->personil->update([
                    'nama_personil' => $validated['nama'],
                    'email' => $validated['email'],
                ]);
            }
        });

        return redirect()->route('user.profile')->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Show change password page
     */
    public function showChangePassword()
    {
        $user = Auth::user()->fresh(['personil', 'role']);
        return view('User.change-password', compact('user'));
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::min(6)],
        ], [
            'current_password.required' => 'Password saat ini harus diisi',
            'current_password.current_password' => 'Password saat ini tidak sesuai',
            'password.required' => 'Password baru harus diisi',
            'password.confirmed' => 'Password baru dan konfirmasi tidak sesuai',
            'password.min' => 'Password harus minimal 6 karakter',
        ]);

        $user = Auth::user()->fresh(['personil', 'role']);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('user.change-password')->with('success', 'Password berhasil diubah!');
    }
}
