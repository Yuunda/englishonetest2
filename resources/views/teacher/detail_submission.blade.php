@extends('layouts.navbar')

@section('content')
<div class="container mt-4 text-start">
    {{-- 1. Definisikan variabel di sini agar bisa dipakai di seluruh file --}}
    @php 
        $answers = is_array($submission->answers) 
                   ? $submission->answers 
                   : json_decode($submission->answers, true);
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold">Student Answers</h2>
            <p class="text-muted">Student: <strong>{{ $submission->user->name }}</strong> | 
            Test: <strong>{{ $submission->test_name_snapshot ?? ($submission->test->name ?? 'Deleted Test') }}</strong></p>
        </div>
        <a href="{{ route('teacher.review') }}" class="btn btn-secondary">Back to List</a>
    </div>

@if($submission->test)
        @foreach($submission->test->questions as $index => $q)
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <h5 class="fw-bold">{{ $index + 1 }}. {{ $q->question_text }}</h5>
                    <div class="ms-3">
                        @php 
                            $studentChoice = strtolower(trim($answers[$q->id] ?? ''));
                            $correctKey = strtolower(trim($q->correct_answer));
                            $isCorrect = ($studentChoice !== '') && str_starts_with($correctKey, $studentChoice);
                        @endphp
                @php 
                    // Ambil jawaban murid dari kolom 'answers' yang sudah jadi array
                    // Ambil jawaban murid dari kolom 'answers' (pastikan formatnya array)
                    $answers = is_array($submission->answers) ? $submission->answers : json_decode($submission->answers, true);
                    
                    // Jawaban murid (isinya cuma: a, b, c, atau d)
                    $studentChoice = strtolower(trim($answers[$q->id] ?? ''));
                    
                    // Kunci jawaban dari guru (isinya misal: 'c. jumping')
                    $correctKey = strtolower(trim($q->correct_answer));

                    // LOGIKA BARU: Cek apakah kunci jawaban diawali dengan huruf pilihan murid
                    // Contoh: Apakah "c. jumping" diawali dengan "c"
                    $isCorrect = ($studentChoice !== '') && str_starts_with($correctKey, $studentChoice);
                @endphp

                <p class="mb-1">Student Answer: 
                    <span class="fw-bold {{ $isCorrect ? 'text-success' : 'text-danger' }}">
                    @if($studentChoice)
                        {{ strtoupper($studentChoice) }}. {{ $q->{'option_'.$studentChoice} ?? '' }}
                    @else
                        No Answer
                    @endif
                </span>
                </p>
                <p class="small text-muted">Correct Key: <strong>{{ strtoupper($q->correct_answer) }}</strong></p>
            </div>
        </div>
    </div>
    @endforeach

    @else
    <div class="alert alert-warning border-0 shadow-sm" style="border-radius: 15px;">
        <h4 class="alert-heading fw-bold">Test Data Not Found</h4>
        <p>Original test questions have been deleted. Below are the student's raw choices recorded:</p>
        <hr>
        <ul class="list-group list-group-flush">
            {{-- Gunakan $loop->iteration untuk nomor urut 1, 2, 3... --}}
            @foreach($answers as $questionId => $choice)
                <li class="list-group-item bg-transparent">
                    <strong>Question {{ $loop->iteration }}:</strong> 
                    <span class="badge bg-secondary">{{ strtoupper($choice) }}</span>
                </li>
            @endforeach
        </ul>
    </div>
@endif
</div>
@endsection