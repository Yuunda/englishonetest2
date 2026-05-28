<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; // Untuk generate password random

class AdminController extends Controller
{
    // Menampilkan halaman form create account
    public function create()
    {
        // Contoh daftar kelas di EnglishONE, sesuaikan dengan kebutuhanmu
        $availableClasses = [
            'kinder', 
            'elementary', 
            'middle', 
            'high', 
            'adult'
        ];

        return view('admin.create_new_account', compact('availableClasses'));
    }

    // Proses menyimpan data akun baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|in:teacher,student',
            'assigned_class' => 'required|array', // Pastikan minimal pilih 1 kelas
        ], [
            'assigned_class.required' => 'Pilih minimal satu kelas untuk user ini.'
        ]);

        // Generate password sementara (8 karakter random: huruf & angka)
        $tempPassword = Str::random(8);

        // Ubah array assigned_class menjadi string dipisah koma (Misal: "Elementary,Middle")
        $assignedClassString = implode(',', $request->assigned_class);

        // Simpan ke database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($tempPassword),
            'role' => $request->role,
            'assigned_class' => $assignedClassString,
            'is_password_changed' => false, // Menandakan user belum ganti password
        ]);

        // Redirect ke dashboard admin dan bawa data password sementara
        return redirect()->route('admin.dashboard')
            ->with('success', 'Akun berhasil dibuat!')
            ->with('temp_password', $tempPassword)
            ->with('new_user_name', $user->name)
            ->with('new_user_email', $user->email);
    }
}