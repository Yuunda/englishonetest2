@extends('layouts.navbar')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
    }
    .success-card {
        border: none;
        border-radius: 30px;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        padding: 3rem;
        transition: transform 0.3s ease;

        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }
    .success-card:hover {
        transform: translateY(-5px);
    }
    .check-container {
        width: 120px;
        height: 120px;
        background: #28a745;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin: 0 auto 2rem;
        box-shadow: 0 10px 20px rgba(40, 167, 69, 0.3);
        animation: scaleIn 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .check-icon {
        color: white;
        font-size: 3.5rem;
    }
    .btn-return {
        background-color: #0077a3;
        border: none;
        padding: 15px 40px;
        border-radius: 50px;
        font-weight: 700;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        text-decoration: none;
        color: white;
    }
    .btn-return:hover {
        background-color: #005f82;
        box-shadow: 0 8px 15px rgba(0, 119, 163, 0.3);
        transform: scale(1.05);
    }
    @keyframes scaleIn {
        from { transform: scale(0); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
</style>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="success-card text-center shadow-lg">
        <div class="check-container">
            <i class="fas fa-check check-icon"></i>
        </div>
        
        <h1 class="display-4 fw-bold text-dark">Submitted!</h1>
        
        <p class="lead text-secondary px-md-5">
            Your answers have been saved securely. <br>
            <strong>Let's wait for the teacher</strong> to review and post your grade soon!
        </p>

        <a href="{{ route('student.home') }}" class="btn btn-return btn-lg text-white text-uppercase">
            Return to Home
        </a>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection