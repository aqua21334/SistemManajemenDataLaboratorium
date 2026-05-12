<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Throwable;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $customer = User::where('email', $request->email)
            ->whereHas('role', function ($query) {
                $query->where('nama_role', 'Customer');
            })
            ->first();

        if (! $customer) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan sebagai akun Customer.',
            ])->onlyInput('email');
        }

        try {
            $status = Password::sendResetLink(
                $request->only('email')
            );
        } catch (Throwable $e) {
            Log::error('Gagal mengirim email reset password.', [
                'email' => $request->email,
                'error' => $e->getMessage(),
            ]);

            return back()->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Email reset gagal dikirim. Periksa konfigurasi SMTP (host, port, username, password) lalu coba lagi.',
                ]);
        }

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('success', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}
