<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
{
    // Cek apakah user sudah login dan apakah rolenya adalah Admin
    // Sesuaikan 'Admin' dengan nama role di databasemu
    if (auth()->check() && auth()->user()->role->nama_role == 'Admin') {
        return $next($request);
    }

    // Jika bukan admin, lempar ke halaman depan atau beri pesan error
    return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
}
}
