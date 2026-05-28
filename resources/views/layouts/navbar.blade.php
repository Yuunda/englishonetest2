<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EnglishONE - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            font-family: 'Arial', sans-serif; 
            margin: 0; 
            background-color: #ffffff; 
        }
        .header { 
            width: 100%; height: 80px; 
            /* Gradient sesuai screenshot */
            background: linear-gradient(90deg, #65DBFF 0%, #0B003D 45%, #BFC231 70%, #6C6C6C 100%);
            display: flex; 
            justify-content: space-between; 
            align-items: center;
            padding: 0 50px; 
            box-sizing: border-box;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .logo-box { 
            background: white; 
            padding: 5px 15px; 
            display: flex;
            align-items: center;
            border-radius: 4px;
            height: 60px; /* Menjaga tinggi kotak logo tetap konsisten */
        }
        .logo-box img {
            max-height: 50px; /* Sesuaikan tinggi logo kamu */
            width: auto;
        }
        .logout-btn { 
            color: #ff4d4d; 
            font-weight: 800; 
            border: 2px solid #ff4d4d; 
            background: white; 
            cursor: pointer; 
            padding: 8px 20px;
            border-radius: 8px;
            text-transform: uppercase;
            font-size: 0.8rem;
            transition: all 0.3s ease; 
        }
        .logout-btn:hover { 
            background: #ff4d4d; 
            color: white; 
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }   
        /* Menghilangkan text-align center global agar konten per page bisa diatur sendiri */
        .main-content { 
            margin-top: 30px; 
            padding: 20px; 
        }

        .change-pw-btn { 
    color: #0077a3; /* Warna biru */
    font-weight: 800; 
    border: 2px solid #0077a3; 
    background: white; 
    cursor: pointer; 
    padding: 8px 20px;
    border-radius: 8px;
    text-decoration: none; /* Biar link gak ada garis bawah */
    font-size: 0.8rem;
    transition: all 0.3s ease; 
    margin-right: 10px; /* Kasih jarak sama tombol logout */
}
.change-pw-btn:hover { 
    background: #0077a3; 
    color: white; 
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-box">
            {{-- Logika Navigasi Dinamis --}}
        @auth
            @if(auth()->user()->role === 'teacher')
                <a href="{{ route('teacher.menu') }}">
            @else
                <a href="{{ route('student.home') }}">
            @endif
        @else
            {{-- Jika belum login, balik ke landing page atau login --}}
            <a href="/">
        @endauth
            <img src="{{ asset('images/EnglishONELogo.png') }}" alt="EnglishONE Logo">
        </a>
        </div>
        
        <div class="d-flex align-items-center">
            <a href="{{ route('password.change') }}" class="change-pw-btn">CHANGE PASSWORD</a>

            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="logout-btn">LOGOUT</button>
            </form>
        </div>
    </div>

    <div class="container main-content text-center">
        @yield('content') 
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>