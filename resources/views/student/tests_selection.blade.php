@extends('layouts.navbar')
@section('content')
    <h2>Choose Your Test</h2>
    
    <div style="display: flex; flex-direction: column; gap: 20px; align-items: center; margin-top: 40px">
        @foreach(['PRE-TEST', 'MID-TEST', 'POST-TEST'] as $type)
            @php 
                // Cari test yang cocok di database (misal nama ujian mengandung kata MID)
                $currentTest = $tests->filter(fn($t) => stripos($t->name, explode('-', $type)[0]) !== false)->first();
            @endphp

            <button onclick="showPopup('{{ $currentTest->id ?? '' }}')" 
                style="background: #0077a3; color: white; width: 300px; padding: 20px; border-radius: 15px; border: none; font-weight: bold; cursor: pointer;">
                {{ $type }}
            </button>
        @endforeach
    </div>

    <div id="popup" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center;">
        <div style="background: white; padding: 40px; border-radius: 20px; text-align: center; width: 400px;">
            <span style="font-size: 5rem; color: #cc0000;">?</span>
            <h2 style="margin: 10px 0;">Are You Sure?</h2>
            <p>Click Start to start the test~</p>
            <div style="display: flex; gap: 20px; justify-content: center; margin-top: 30px;">
                <button onclick="hidePopup()" style="background: #cc0000; color: white; padding: 10px 30px; border-radius: 10px; border: none;">Cancel</button>
                <a id="startBtn" href="#" style="background: #0077a3; color: white; padding: 10px 30px; border-radius: 10px; text-decoration: none;">Start</a>
            </div>
        </div>
    </div>

    <script>
        function showPopup(testId) {
            if(!testId) { alert('Test not found for this category!'); return; }
            document.getElementById('popup').style.display = 'flex';
            document.getElementById('startBtn').href = "/student/exam/" + testId;
        }
        function hidePopup() {
            document.getElementById('popup').style.display = 'none';
        }
    </script>
@endsection