<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            if (Auth::user()->role === 'teacher') {
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

    // Menampilkan halaman form register
    public function register()
    {
        return view('register'); // Buat file register.blade.php nanti
    }

    // Memproses data pendaftaran
    public function storeRegister(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        // Simpan ke database (otomatis jadi real user)
        \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'student', // Default pendaftar public adalah student
        ]);

        return redirect('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }
}