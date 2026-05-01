@extends('layouts.navbar')

@section('content')
<style>
    /* Styling khusus untuk page Home Student */
    .student-home-container {
        padding-top: 60px;
        min-height: 80vh;
        background-color: #fff;
    }
    .title-heading {
        font-weight: 800;
        color: #000;
        margin-bottom: 50px;
    }
    .btn-level {
        width: 280px;
        height: 100px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: 700;
        color: #fff !important;
        text-decoration: none !important;
        transition: transform 0.2s, box-shadow 0.2s;
        border: none;
    }
    .btn-level:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        filter: brightness(1.1);
    }

    /* Warna sesuai screenshot */
    .kinder { background-color: #65DBFF; }
    .elementary { background-color: #007497; }
    .middle { background-color: #004484; }
    .high { background-color: #EFD300; }
    .adult { background-color: #B8B8B8; }

    .level-row {
        gap: 25px;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
    }
</style>

<div class="student-home-container text-center">
    <h1 class="title-heading">Pick One Based on Your Current Class-Level</h1>

    <div class="container">
        <div class="level-row mb-4">
            <a href="{{ route('student.classes', 'kinder') }}" class="btn-level kinder shadow-sm">
                Kinder-Level
            </a>
            <a href="{{ route('student.classes', 'elementary') }}" class="btn-level elementary shadow-sm">
                Elementary-Level
            </a>
            <a href="{{ route('student.classes', 'middle') }}" class="btn-level middle shadow-sm">
                Middle-Level
            </a>
        </div>

        <div class="level-row">
            <a href="{{ route('student.classes', 'high') }}" class="btn-level high shadow-sm">
                High-Level
            </a>
            <a href="{{ route('student.classes', 'adult') }}" class="btn-level adult shadow-sm">
                Adult-Level
            </a>
        </div>
    </div>
</div>
@endsection