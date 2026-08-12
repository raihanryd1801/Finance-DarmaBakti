<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRoleAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Jika sudah login dan rolenya admin, persilakan lewat
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // Jika bukan admin, tendang balik ke halaman report
        return redirect()->route('finance.report')->with('error', 'Akses Ditolak! Halaman tersebut khusus Administrator.');
    }
}