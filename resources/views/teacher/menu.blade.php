@extends('layouts.navbar')

@section('title', 'Teacher Menu')

@section('content')
    <h2>Choose Action</h2>
    <div style="display: flex; gap: 40px; justify-content: center;">
        <a href="{{ route('teacher.review') }}" style="background:#0077a3; width:250px; height:150px; 
        border-radius:15px; display:flex; justify-content:center; align-items:center; color:white; 
        text-decoration:none; font-weight:bold;">
            Review Results
        </a>
    
{{-- Tombol ini HANYA akan muncul untuk Super Teacher --}}
    @if(auth()->user()->email === 'mteacher25.englishone@gmail.com')
        <a href="{{ route('test.index') }}" style="background:#e2e600; width:250px; 
        height:150px; border-radius:15px; display:flex; 
        justify-content:center; align-items:center; color:black; 
        text-decoration:none; font-weight:bold;">
            Add/Change Tests
        </a>
        @endif
    </div>
@endsection