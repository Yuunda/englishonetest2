<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Khusus ADMIN (Bisa akses form buat user lain nanti)
        User::updateOrCreate(
            ['email' => 'admin@englishone.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'role' => 'admin', // Role Baru
                'is_password_changed' => true, // Admin sudah aktif
            ]
        );

        // 2. Akun Teacher (Ms. Septi)
        User::updateOrCreate(
            ['email' => 'mteacher25.englishone@gmail.com'],
            [
                'name' => 'Ms. Septi',
                'password' => Hash::make('password123'),
                'role' => 'teacher',
                'is_password_changed' => false, // Dia nanti harus ganti password
            ]
        );

        // 3. Akun Student
        User::updateOrCreate(
            ['email' => 'student@test.com'],
            [
                'name' => 'Siswa Contoh',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'level' => 'elementary',
                'is_password_changed' => false,
            ]
        );
    }
}