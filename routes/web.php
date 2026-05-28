<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\AdminController;

// 1. Halaman Utama (Redirect otomatis berdasarkan role)
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->role === 'admin') return redirect()->route('admin.dashboard');
        if ($user->role === 'teacher') return redirect()->route('teacher.menu');
        return redirect()->route('student.home');
    }
    return redirect()->route('login');
});

// 2. Jalur Login (Hanya untuk tamu)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);
});

// 3. Jalur Authenticated (User SUDAH Login)
Route::middleware('auth')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Fitur Ganti Password
    Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/change-password', [AuthController::class, 'updatePassword'])->name('password.update');

    // Dashboard Admin
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Route untuk Admin Create Account
    Route::get('/admin/create_new_account', [AdminController::class, 'create'])->name('admin.create');
    Route::post('/admin/create_new_account', [AdminController::class, 'store'])->name('admin.store');

    // --- ZONA TEACHER ---
    // Menggunakan middleware 'is_teacher' untuk SEMUA guru
    Route::middleware('is_teacher')->group(function () {
        Route::get('/teacher/menu', function () { return view('teacher.menu'); })->name('teacher.menu');
        
        Route::get('/teacher/manage-tests', [TestController::class, 'index'])->name('test.index');
        Route::post('/test/store', [TestController::class, 'store'])->name('test.store');
        Route::delete('/test/{id}', [TestController::class, 'destroy'])->name('test.destroy');
        
        Route::get('/teacher/submission/{id}', [TestController::class, 'detailSubmission'])->name('teacher.submission.detail');
        Route::delete('/teacher/submission/{id}', [TestController::class, 'destroySubmission'])->name('teacher.submission.destroy');
        Route::get('/teacher/review', [TestController::class, 'reviewResults'])->name('teacher.review');
    });

    // --- ZONA STUDENT ---
    // Menggunakan middleware 'is_student'
    Route::middleware('is_student')->group(function () {
        Route::get('/student/home', function () { return view('student.home'); })->name('student.home');
        
        Route::get('/student/levels/{level}/classes', [TestController::class, 'pickClass'])->name('student.classes');
        Route::get('/student/levels/{level}/classes/{class}/tests', [TestController::class, 'availableTests'])->name('student.tests.selection');
        Route::get('/student/exam/{test_id}', [TestController::class, 'startExam'])->name('student.exam');
        Route::post('/student/exam/{id}/submit', [TestController::class, 'submitExam'])->name('student.exam.submit');
        Route::get('/student/exam-success', function () { return view('student.success'); })->name('student.exam.success');
    });
});