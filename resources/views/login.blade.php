<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EnglishONE</title>
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

    <div class="container d-flex justify-content-center align-items-center flex-grow-1" style="min-height: 80vh;">
        <div class="card login-card border-0 shadow p-4">
            <h2 class="text-info fw-bold text-center">Login to EnglishONE</h2>

            @if($errors->any())
                <div class="alert alert-danger py-2 small">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <input type="email" name="email" class="form-control bg-light-grey border-0 py-3 mb-3 shadow-sm" placeholder="Enter your email..." required>
                <input type="password" name="password" class="form-control bg-light-grey border-0 py-3 mb-3 shadow-sm" placeholder="Password..." required>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember">
                        <label class="form-check-label small text-secondary" for="remember">Remember Me</label>
                    </div>
                    <button type="submit" class="btn btn-custom-blue px-4 py-2 rounded-3 fw-bold shadow">Login</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>