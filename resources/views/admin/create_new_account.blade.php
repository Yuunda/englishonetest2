@extends('layouts.navbar') 

@section('title', 'Admin Create Account')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Buat Akun Guru / Murid Baru</h5>
                </div>
                <div class="card-body">
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Role (Peran)</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="" disabled selected>-- Pilih Role --</option>
                                <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Guru (Teacher)</option>
                                <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Murid (Student)</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label d-block">Akses Kelas (Bisa pilih lebih dari satu)</label>
                            <div class="row">
                                @foreach($availableClasses as $class)
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="assigned_class[]" value="{{ $class }}" id="class_{{ $class }}">
                                                <label class="form-check-label" for="class_{{ $class }}">
                                                {{ ucfirst($class) }} </label>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <small class="text-muted">Centang kelas mana saja yang boleh diakses/diajar oleh akun ini.</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Buat Akun & Generate Password</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection