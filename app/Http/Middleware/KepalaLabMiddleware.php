<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class KepalaLabMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah user sudah login DAN id_role-nya adalah 2 (Kepala_Lab)
        if (Auth::check() && Auth::user()->id_role == 2) {
            return $next($request); // Silakan masuk ke halaman dashboard
        }

        // Jika bukan Kepala Lab, tendang ke halaman awal
        return redirect('/');
    }
}
