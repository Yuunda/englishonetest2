<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // WAJIB ADA

class IsTeacher
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek jika user login DAN rolenya 'teacher'
        if (Auth::check() && Auth::user()->role === 'teacher') {
            return $next($request);
        }

        // Kalau bukan, lempar error 403
        abort(403, 'Akses Ditolak. Anda bukan Guru.');
    }
}