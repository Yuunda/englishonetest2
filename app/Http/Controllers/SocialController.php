<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SocialController extends Controller
{
    public function redirectToProvider($provider) {
    return Socialite::driver($provider)->redirect();
}

public function handleProviderCallback($provider) {
    try {
        $socialUser = Socialite::driver($provider)->user();
        $email = $socialUser->getEmail();
        $user = User::where('email', $email)->first();
        
        // Cari user berdasarkan email atau social_id
        if (!$user) {

    $role = str_contains($email, 'mteacher')
        ? 'teacher'
        : 'student';

    $user = User::create([
        'name' => $socialUser->getName(),
        'email' => $email,
        'social_id' => $socialUser->getId(),
        'social_type' => $provider,
        'password' => null,
        'role' => $role,
    ]);
}

        Auth::login($user);
        
        // Cek role: Kalau user baru, arahkan sesuai logika aplikasimu
        return $user->role === 'teacher'
    ? redirect()->route('teacher.menu')
    : redirect()->route('student.home'); 

    } catch (\Exception $e) {
        return redirect('/login')->with('error', 'Login failed!');
    }
}
}
