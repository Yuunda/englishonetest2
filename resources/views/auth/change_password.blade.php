@extends('layouts.navbar')

@section('title', 'Ganti Password')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow border-0 rounded-4 p-4">
            <h2 class="text-primary fw-bold text-center mb-3">Ganti Password</h2>
            <p class="text-center text-muted">Demi keamanan, silakan buat password yang kuat.</p>

            @if ($errors->any())
                <div class="alert alert-danger py-2">
                    {{ $errors->first() }}
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success py-2">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Password Baru</label>
                    <input type="password" name="password" class="form-control form-control-lg bg-light" placeholder="Masukkan password baru..." required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control form-control-lg bg-light" placeholder="Ulangi password..." required>
                </div>
                
                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary btn-lg fw-bold">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection