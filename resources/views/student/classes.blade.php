@extends('layouts.navbar')
@section('content')
    <h2>Pick Classes</h2>
    <div style="display: flex; flex-direction: column; align-items: flex-start; margin-left: 15%;">
        @foreach(range(1, $totalClass) as $classNum)
    <a href="{{ route('student.tests.selection', [
        'level' => $level,
        'class' => $classNum
    ]) }}"
       style="font-size: 2rem; color: #0077a3; text-decoration: none; font-weight: bold; margin-bottom: 10px;">
        {{ $classNum }}-Class
    </a>
@endforeach
    </div>
@endsection