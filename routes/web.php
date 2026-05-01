<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\SocialController;
use App\Http\Middleware\IsTeacher;
use App\Http\Middleware\IsStudent;
use App\Http\Middleware\IsSuperTeacher;


// 1. Halaman Utama (Langsung lempar ke login saja dulu)
Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->role === 'teacher' 
            ? redirect()->route('teacher.menu') 
            : redirect()->route('student.home');
    }
    return redirect()->route('login');
});

Route::get('auth/{provider}', [SocialController::class, 'redirectToProvider']);
Route::get('auth/{provider}/callback', [SocialController::class, 'handleProviderCallback']);

// 2. Jalur Login (Hanya bisa diakses kalau BELUM login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);

    // Jalur Register (Baru ditambahkan)
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'storeRegister'])->name('register.store');
});


// 3. Jalur Dashboard & Logout (Hanya bisa diakses kalau SUDAH login)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware(IsTeacher::class)->group(function () {
        
        Route::get('/teacher/menu', function () {
            return view('teacher.menu'); 
        })->name('teacher.menu');

        Route::get('/teacher/submission/{id}', [TestController::class, 'detailSubmission'])->name('teacher.submission.detail');
        Route::get('/teacher/review', [TestController::class, 'reviewResults'])->name('teacher.review');

        // --- ZONA KHUSUS AKUN MS. SEPTI ---
        Route::middleware(IsSuperTeacher::class)->group(function () {
            Route::get('/teacher/manage-tests', [TestController::class, 'index'])->name('test.index');
            Route::post('/test/store', [TestController::class, 'store'])->name('test.store');
            Route::delete('/test/{id}', [TestController::class, 'destroy'])->name('test.destroy');
            // Tambahkan ini untuk hapus hasil submission
            Route::delete('/teacher/submission/{id}', [TestController::class, 'destroySubmission'])->name('teacher.submission.destroy');
        });
    });


    Route::middleware(IsStudent::class)->group(function () {
        
        Route::get('/student/home', function () {
            return view('student.home');
        })->name('student.home');

        Route::get('/student/levels/{level}/classes', [TestController::class, 'pickClass'])->name('student.classes');
        Route::get('/student/levels/{level}/classes/{class}/tests', [TestController::class, 'availableTests'])->name('student.tests.selection');
        Route::get('/student/exam/{test_id}', [TestController::class, 'startExam'])->name('student.exam');
        Route::post('/student/exam/{id}/submit', [TestController::class, 'submitExam'])->name('student.exam.submit');
        
        Route::get('/student/exam-success', function () {
            return view('student.success');
        })->name('student.exam.success');
    });
});