<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPasswordChange
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek apakah user sudah login
        if (Auth::check()) {
            $user = Auth::user();

            // 2. Jika password belum diganti DAN user tidak sedang di halaman ganti password/logout
            if (!$user->is_password_changed && 
                !$request->is('change-password*') && 
                !$request->is('logout')) {
                
                // Paksa pindah ke halaman ganti password
                return redirect()->route('password.change')
                                 ->with('info', 'Silakan ganti password default Anda terlebih dahulu.');
            }
        }

        return $next($request);
    }
}