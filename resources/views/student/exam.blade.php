@extends('layouts.navbar')

@section('title', 'Exam in Progress')

@section('content')
<div class="container mt-4">
    <div class="row">
        {{-- Sidebar: Timer & Info --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 mb-4 text-center sticky-top" style="top: 20px;">
                <div class="card-body">
                    <h5 class="text-secondary">Time Remaining</h5>
                    <h2 id="timer" class="fw-bold text-danger">--:--</h2>
                    <hr>
                    <h6 class="fw-bold">{{ auth()->user()->name }}</h6>
                    <p class="small text-muted mb-0">{{ $test->name }}</p>
                    <p class="small text-muted">Level: {{ ucfirst($test->level) }}</p>
                </div>
            </div>
        </div>

        {{-- Main Exam Area --}}
        <div class="col-md-9 text-start">
            <form id="examForm" action="{{ route('student.exam.submit', $test->id) }}" method="POST">
                @csrf
                
                @php $currentSection = null; @endphp

                @foreach($test->questions as $index => $q)
                    
                    {{-- LOGIKA OTOMATIS SECTION HEADER --}}
                    @if($currentSection !== $q->section)
                        @php $currentSection = $q->section; @endphp
                        <div class="section-header mb-4 mt-5 question-card" data-index="{{ $index }}">
                            <div class="bg-dark text-white p-3 rounded shadow-sm">
                                <h4 class="mb-0 fw-bold">
                                    SECTION {{ $currentSection }} : 
                                    @if($q->type === 'mcq') 
                                        MULTIPLE CHOICE
                                    @elseif($q->type === 'listening') 
                                        LISTENING COMPREHENSION
                                    @else 
                                        WRITTEN RESPONSE
                                    @endif
                                </h4>
                            </div>
                        </div>
                    @endif

                    <div class="card shadow-sm border-0 mb-4 question-card" data-index="{{ $index }}">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">
                                <span class="text-info">{{ $index + 1 }}.</span> {{ $q->question_text }}
                            </h5>

                            {{-- MEDIA HANDLER: Gambar atau Audio --}}
                            @if($q->image)
                                <div class="mb-4">
                                    @if(Str::endsWith(strtolower($q->image), ['.mp3', '.wav', '.ogg']))
                                        <div class="p-3 bg-light rounded border">
                                            <p class="small text-muted mb-2"><i class="fas fa-headphones"></i> Listen to the audio:</p>
                                            <audio controls controlsList="nodownload" class="w-100">
                                                <source src="{{ asset('images/soal/'.$q->image) }}" type="audio/mpeg">
                                                Browser Anda tidak mendukung pemutar audio.
                                            </audio>
                                        </div>
                                    @else
                                        <img src="{{ asset('images/soal/'.$q->image) }}" class="img-fluid rounded border" style="max-height: 400px;">
                                    @endif
                                </div>
                            @endif

                            {{-- ANSWER INPUTS --}}
                            <div class="options-list ms-3">
                                @if($q->type === 'mcq')
                                    @foreach(['a','b','c','d'] as $opt)
                                        @if($q->{'option_'.$opt})
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="answers[{{ $q->id }}]" id="q{{ $q->id }}{{ $opt }}" value="{{ $opt }}" required>
                                            <label class="form-check-label" for="q{{ $q->id }}{{ $opt }}">
                                                <strong>{{ strtoupper($opt) }}.</strong> {{ $q->{'option_'.$opt} }}
                                            </label>
                                        </div>
                                        @endif
                                    @endforeach

                                @elseif($q->type === 'listening')
                                    <textarea class="form-control" name="answers[{{ $q->id }}]" rows="4" placeholder="Type your answer based on the audio recording..." required></textarea>

                                @else
                                    <input type="text" class="form-control" name="answers[{{ $q->id }}]" placeholder="Type your answer here..." required>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- PAGINATION CONTROLS --}}
                <div class="d-flex justify-content-between mb-5 mt-4">
                    <button type="button" id="prevBtn" class="btn btn-secondary px-4 btn-lg shadow-sm">Previous</button>
                    <button type="button" id="nextBtn" class="btn btn-primary px-4 btn-lg shadow-sm">Next</button>
                </div>
                
                <div class="d-grid gap-2 mb-5" id="submitContainer" style="display: none;">
                    <button type="submit" class="btn btn-success btn-lg text-white fw-bold shadow-lg">Submit All Answers</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// 1. PAGINATION LOGIC
const questionsPerPage = 10;
let currentPage = 1;
const questionCards = document.querySelectorAll('.question-card');
const totalPages = Math.ceil(questionCards.length / questionsPerPage);

const prevBtn = document.getElementById('prevBtn');
const nextBtn = document.getElementById('nextBtn');
const submitContainer = document.getElementById('submitContainer');

function showPage(page) {
    let start = (page - 1) * questionsPerPage;
    let end = start + questionsPerPage;

    questionCards.forEach((card, index) => {
        card.style.display = (index >= start && index < end) ? 'block' : 'none';
    });

    prevBtn.style.display = (page === 1) ? 'none' : 'inline-block';

    if (page === totalPages || totalPages === 0) {
        nextBtn.style.display = 'none';
        submitContainer.style.display = 'block';
    } else {
        nextBtn.style.display = 'inline-block';
        submitContainer.style.display = 'none';
    }
    window.scrollTo(0, 0);
}

prevBtn.addEventListener('click', () => { if (currentPage > 1) { currentPage--; showPage(currentPage); } });
nextBtn.addEventListener('click', () => { if (currentPage < totalPages) { currentPage++; showPage(currentPage); } });

showPage(currentPage);

// 2. TIMER LOGIC
const userId = "{{ auth()->id() }}";
const testId = "{{ $test->id }}";
const timerKey = `timer_${userId}_${testId}`;

let duration = {{ $test->duration }} * 60;
let display = document.querySelector('#timer');

function startTimer(duration, display) {
    let timer = duration;
    let countdown = setInterval(function () {
        let minutes = Math.floor(timer / 60);
        let seconds = timer % 60;

        display.textContent = (minutes < 10 ? "0" : "") + minutes + ":" + (seconds < 10 ? "0" : "") + seconds;
        localStorage.setItem(timerKey, timer);

        if (timer <= 0) {
            clearInterval(countdown);
            localStorage.removeItem(timerKey);
            alert("Time is up! Your answers will be submitted automatically.");
            document.getElementById('examForm').submit();
        }
        timer--;
    }, 1000);
}

window.onload = function () {
    let savedTimer = localStorage.getItem(timerKey);
    if(savedTimer !== null) duration = parseInt(savedTimer);
    startTimer(duration, display);
};

document.getElementById('examForm').addEventListener('submit', () => localStorage.removeItem(timerKey));
</script>

<style>
    .section-header { border-left: 8px solid #17a2b8; }
    .question-card { transition: all 0.3s ease; }
    .form-check-input:checked { background-color: #17a2b8; border-color: #17a2b8; }
    .form-check-label { cursor: pointer; width: 100%; }
    audio::-webkit-media-controls-enclosure { border-radius: 5px; }
</style>
@endsection