<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsSuperTeacher
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->email !== 'mteacher25.englishone@gmail.com') {
        abort(403, 'Akses Ditolak. Hanya Super Teacher yang dapat mengelola ujian.');
    }
        return $next($request);
    }
}
