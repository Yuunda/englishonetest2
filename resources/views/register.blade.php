<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - EnglishONE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #C0C0C0; }
        .header-bg {
            height: 80px;
            background: linear-gradient(90deg, #1e3a8a 0%, #000000 50%, #d9f99d 100%);
        }
        .login-card { border-radius: 30px; width: 100%; max-width: 450px; }
        .bg-light-grey { background-color: #E0E0E0; }
        .btn-custom-blue { background-color: #0077a3; color: white; }
    </style>
</head>
<body>

    <div class="header-bg d-flex align-items-center px-5">
        <div class="bg-white px-3 py-1 rounded">
            <span class="fw-bold text-info">English</span><span class="fw-bold text-dark">ONE</span>
        </div>
    </div>

    <div class="container d-flex justify-content-center align-items-center flex-grow-1" style="min-height: 80vh; padding-top: 2rem; padding-bottom: 2rem;">
        <div class="card login-card border-0 shadow p-4">
            <h2 class="text-info fw-bold text-center">Daftar EnglishONE</h2>
            <p class="text-center small">Sudah punya akun? <a href="{{ route('login') }}" class="text-danger fw-bold text-decoration-none">Login di sini</a></p>

            <a href="{{ url('auth/google') }}" class="btn bg-light-grey w-100 mb-2 fw-bold d-flex align-items-center justify-content-center">
                <img src="https://cdn-icons-png.flaticon.com/512/2991/2991148.png" width="20" class="me-2">
                Daftar dengan Google
            </a>

            <div class="d-flex align-items-center mb-4 mt-2">
                <div class="flex-grow-1 border-bottom border-info" style="height: 1px;"></div>
                <span class="mx-2 fw-bold text-info">OR</span>
                <div class="flex-grow-1 border-bottom border-info" style="height: 1px;"></div>
            </div>

            @if($errors->any())
                <div class="alert alert-danger py-2 small">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.store') }}" method="POST">
                @csrf
                <input type="text" name="name" value="{{ old('name') }}" class="form-control bg-light-grey border-0 py-3 mb-3 shadow-sm" placeholder="Nama Lengkap..." required>
                
                <input type="email" name="email" value="{{ old('email') }}" class="form-control bg-light-grey border-0 py-3 mb-3 shadow-sm" placeholder="Email aktif..." required>
                
                <input type="password" name="password" class="form-control bg-light-grey border-0 py-3 mb-3 shadow-sm" placeholder="Buat Password..." required minlength="6">

                <button type="submit" class="btn btn-custom-blue w-100 py-3 mt-2 rounded-3 fw-bold shadow">Buat Akun Siswa</button>
            </form>
        </div>
    </div>

</body>
</html>