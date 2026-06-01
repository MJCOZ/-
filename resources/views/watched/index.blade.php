@extends('layouts.app')

@section('title', 'شاهدتها — MJCOZ TV')

@section('content')

    <div class="hero p-4 mb-4">
        <h2 class="mb-1"><i class="bi bi-eye-fill text-warning"></i> شاهدتها</h2>
        <p class="text-secondary mb-0">أعمال شاهدتها وأضفت لها تقييمي الشخصي إلى جانب تقييمات IMDb و Rotten Tomatoes.</p>
    </div>

    @if ($titles->isEmpty())
        <div class="alert alert-secondary text-center">لم تتم إضافة أي عمل لقائمة المشاهدة بعد.</div>
    @else
        @foreach ($titles as $title)
            <div class="bg-dark-2 p-3 rounded mb-3">
                <div class="row g-3 align-items-center">
                    <div class="col-3 col-md-1">
                        <a href="{{ route('titles.show', $title) }}">
                            <img src="{{ $title->posterUrl() }}" alt="{{ $title->name }}"
                                 class="img-fluid rounded" style="aspect-ratio:2/3; object-fit:cover;">
                        </a>
                    </div>
                    <div class="col-9 col-md-11">
                        <div class="d-flex justify-content-between align-items-start flex-wrap">
                            <div>
                                <a href="{{ route('titles.show', $title) }}" class="text-reset text-decoration-none">
                                    <h5 class="mb-1">
                                        {{ $title->name }}
                                        <span class="badge {{ $title->isMovie() ? 'bg-primary' : 'bg-success' }}">
                                            {{ $title->isMovie() ? 'فيلم' : 'مسلسل' }}
                                        </span>
                                    </h5>
                                </a>
                                <div class="text-secondary small">
                                    {{ $title->release_year }}
                                    @if ($title->genre) · {{ $title->genre->name }} @endif
                                    @if ($title->watched_at) · شوهد {{ $title->watched_at->translatedFormat('j F Y') }} @endif
                                </div>
                            </div>
                        </div>
                        @include('partials.external-ratings', ['title' => $title])
                    </div>
                </div>
            </div>
        @endforeach
        <div class="mt-4 d-flex justify-content-center">{{ $titles->links() }}</div>
    @endif

@endsection
