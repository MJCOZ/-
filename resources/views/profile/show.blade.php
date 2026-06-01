@extends('layouts.app')

@section('title', 'ملفي الشخصي — MJ TV')

@section('content')

    <div class="bg-dark-2 p-4 rounded mb-4 d-flex align-items-center gap-3">
        <i class="bi bi-person-circle text-warning" style="font-size: 3.5rem;"></i>
        <div class="flex-grow-1">
            <h3 class="mb-1">{{ $user->name }}</h3>
            <p class="text-secondary mb-0"><i class="bi bi-envelope"></i> {{ $user->email }}</p>
            <small class="text-secondary">عضو منذ {{ $user->created_at->translatedFormat('F Y') }}</small>
        </div>
        <a href="{{ route('profile.edit') }}" class="btn btn-outline-warning">
            <i class="bi bi-gear"></i> تعديل
        </a>
    </div>

    <h4 class="mb-3"><i class="bi bi-chat-quote text-warning"></i> مراجعاتي ({{ $reviews->count() }})</h4>

    @forelse ($reviews as $review)
        <div class="bg-dark-2 p-3 rounded mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <a href="{{ route('titles.show', $review->title) }}" class="text-warning fw-bold">
                    {{ $review->title->name }}
                </a>
                @include('partials.stars', ['rating' => $review->rating])
            </div>
            <p class="mb-1">{{ $review->body }}</p>
            <small class="text-secondary">{{ $review->created_at->diffForHumans() }}</small>
        </div>
    @empty
        <div class="alert alert-secondary text-center">
            لم تكتب أي مراجعة بعد. <a href="{{ route('titles.index') }}" class="text-warning">تصفّح الأعمال</a> وابدأ!
        </div>
    @endforelse

@endsection
