<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Pastikan ini ada di paling atas file

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function login()
    {
        return view('login');
    }

    // Proses pengecekan login
    public function authenticate(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Cek role untuk menentukan arah redirect
            $role = Auth::user()->role;
            if ($role === 'admin') {
                return redirect()->route('admin.dashboard'); // Nanti kita buat
            } elseif ($role === 'teacher') {
                return redirect()->route('teacher.menu');
            }

            return redirect()->route('student.home');
        }

        // Kalau gagal, balikkan ke login dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // Proses logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function showChangePasswordForm()
{
    return view('auth.change_password');
}

public function updatePassword(Request $request)
{
    $request->validate([
        'password' => 'required|min:6|confirmed', // 'confirmed' artinya butuh input password_confirmation
    ]);

    $user = Auth::user();
    $user->password = Hash::make($request->password);
    $user->is_password_changed = true; // Tandai sudah ganti password
    $user->save();

    // Setelah ganti, lempar ke dashboard masing-masing
    if ($user->role === 'teacher') {
        return redirect()->route('teacher.menu')->with('success', 'Password berhasil diperbarui!');
    }
    return redirect()->route('student.home')->with('success', 'Password berhasil diperbarui!');
    }
}