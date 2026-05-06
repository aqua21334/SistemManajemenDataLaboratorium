<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PetugasLabMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah user sudah login DAN id_role-nya adalah 3 (Petugas Lab)
        if (Auth::check() && Auth::user()->id_role == 3) {
            return $next($request);
        }

        // Jika bukan Petugas Lab, tendang ke halaman awal
        return redirect('/');
    }
}