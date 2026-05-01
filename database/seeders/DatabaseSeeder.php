<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        //User::factory()->create([
           // 'name' => 'Test User',
           // 'email' => 'test@example.com',
       // ]);

       // 1. Akun untuk Teacher
        User::updateOrCreate(
    ['email' => 'mteacher25.englishone@gmail.com'], // Cari berdasarkan email saja
    [
        'name' => 'Ms. Septi',
        'password' => bcrypt('password123'),
        'role' => 'teacher',
        'level' => null,
    ]
);
        User::updateOrCreate(
    ['email' => 'teacher@test.com'],
    [
        'name' => 'Admin Guru EnglishOne',
        'password' => bcrypt('password123'),
        'role' => 'teacher',
        'level' => null,
    ]
);

        // 2. Akun untuk Student
        User::updateOrCreate([
            'name' => 'Siswa Contoh',
            'email' => 'student@test.com',
            'password' => bcrypt('password123'),
            'role' => 'student',
            'level' => 'elementary',
        ]);
    }
}
