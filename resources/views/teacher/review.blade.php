@extends('layouts.navbar')

@section('title', 'Review Submissions')

@section('content')
<div class="container py-5">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6 text-start">
            <h2 class="fw-bold mb-0" style="color: #0b003d;">Student Submissions</h2>
            <p class="text-muted">Review and manage student exam answers</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('teacher.menu') }}" class="btn btn-outline-dark fw-bold px-4 py-2 shadow-sm" style="border-radius: 10px;">
                <i class="fas fa-arrow-left me-2"></i> Back to Menu
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small fw-bold text-secondary">No</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary">Student Name</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary">Test Name</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary">Level</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary">Class</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary">Submitted At</th>
                            <th class="text-center py-3 text-uppercase small fw-bold text-secondary">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($results as $index => $res)
                        <tr>
                            <td class="ps-4 fw-bold text-secondary">{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center me-3" style="width: 35px; height: 35px; font-size: 0.8rem;">
                                        {{ strtoupper(substr($res->user->name, 0, 2)) }}
                                    </div>
                                    <span class="fw-bold text-dark">{{ $res->user->name }}</span>
                                </div>
                            </td>

                            {{-- Lebih aman, nama tes tidak akan hilang meski test-nya dihapus --}}
                            <td class="text-dark">{{ $res->test_name_snapshot ?? ($res->test->name ?? 'Deleted Test') }}</td>

                            {{-- 1. Bagian Level Badge --}}
                            <td>
                                @php
                                    // Ambil level dari test, kalau test sudah dihapus, kasih default/kosong
                                    $currentLevel = $res->test_level_snapshot ?? ($res->test->level ?? 'N/A');
                                    $badgeColor = match($currentLevel) {
                                        'kinder' => '#65DBFF',
                                        'elementary' => '#0077A3',
                                        'middle' => '#0B003D',
                                        'high' => '#BFC231',
                                        default => '#6C6C6C'
                                    };
                                @endphp
                                <span class="badge rounded-pill px-3 py-2" style="background-color: {{ $badgeColor }};">
                                    {{ ucfirst($currentLevel) }}
                                </span>
                            </td>

                            {{-- 2. Bagian Class --}}
                            <td class="text-center fw-bold">
                                {{ $res->test_class_snapshot ?? ($res->test->class ?? '-') }}
                            </td>

                            <td class="text-muted small">
                                <i class="far fa-clock me-1"></i> {{ $res->created_at->format('d M Y, H:i') }}
                            </td>

                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    {{-- Tombol View (Bisa dilihat semua teacher) --}}
                                    <a href="{{ route('teacher.submission.detail', $res->id) }}" 
                                    class="btn btn-primary fw-bold px-3 shadow-sm" 
                                    style="border-radius: 10px; background-color: #0077a3; border: none;">
                                        View
                                    </a>

                                    {{-- Tombol Delete (HANYA Ms. Septi) --}}
                                    @if(auth()->user()->email === 'mteacher25.englishone@gmail.com')
                                        <form action="{{ route('teacher.submission.destroy', $res->id) }}" method="POST" 
                                            onsubmit="return confirm('Are you sure you want to delete this student record?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger fw-bold shadow-sm" style="border-radius: 10px;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" alt="empty" style="width: 80px; opacity: 0.3;" class="mb-3">
                                <p class="text-muted">No submissions found yet.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection