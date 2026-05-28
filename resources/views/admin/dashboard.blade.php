@extends('layouts.navbar') 

@section('title', 'Admin Dashboard')

@section('content')
<div class="container mt-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <h5 class="alert-heading"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</h5>
            @if(session('temp_password'))
                <hr>
                <p class="mb-1">Tolong berikan informasi login ini kepada user yang bersangkutan:</p>
                <ul class="mb-0">
                    <li><strong>Nama:</strong> {{ session('new_user_name') }}</li>
                    <li><strong>Email:</strong> {{ session('new_user_email') }}</li>
                    <li><strong>Password Sementara:</strong> <span class="badge bg-danger fs-6">{{ session('temp_password') }}</span></li>
                </ul>
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Admin Dashboard</h1>
        <span class="badge bg-primary fs-6">Admin Panel</span>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title text-primary"><i class="bi bi-people-fill"></i> Kelola User</h5>
                    <p class="card-text">Buat akun baru untuk teacher atau student, dan tentukan akses level mereka.</p>
                    <a href="{{ route('admin.create') }}" class="btn btn-outline-primary">Buat Akun Baru</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title text-success"><i class="bi bi-graph-up"></i> Monitoring</h5>
                    <p class="card-text">Lihat progres belajar student dan performa setiap pengajar.</p>
                    <a href="#" class="btn btn-outline-success">Lihat Laporan</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection