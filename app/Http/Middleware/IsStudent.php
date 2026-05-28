<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // WAJIB ADA

class IsStudent
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek jika user login DAN rolenya 'student'
        if (Auth::check() && Auth::user()->role === 'student') {
            return $next($request);
        }

        // Kalau bukan, lempar error 403
        abort(403, 'AKSES DITOLAK. ANDA BUKAN MURID.');
    }
}