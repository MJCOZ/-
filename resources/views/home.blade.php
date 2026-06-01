@extends('layouts.app')

@section('title', 'الرئيسية — MJCOZ TV')

@section('content')

    {{-- البانر --}}
    <section class="hero p-5 mb-5 text-center text-md-start">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="fw-bold mb-3">اكتشف، قيّم، وشارك رأيك 🎬</h1>
                <p class="lead text-secondary mb-4">
                    موقع MJCOZ TV يجمع لك أفضل الأفلام والمسلسلات مع آراء حقيقية من المشاهدين.
                    تصفّح الأعمال، اقرأ المراجعات، وأضف تقييمك الخاص.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('movies.index') }}" class="btn btn-warning btn-lg">
                        <i class="bi bi-camera-reels"></i> الأفلام
                    </a>
                    <a href="{{ route('series.index') }}" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-tv"></i> المسلسلات
                    </a>
                </div>
            </div>
            <div class="col-md-4 d-none d-md-block text-center">
                <i class="bi bi-film text-warning" style="font-size: 8rem; opacity:.85;"></i>
            </div>
        </div>
    </section>

    {{-- التصنيفات --}}
    <section class="mb-5">
        <div class="d-flex flex-wrap gap-2">
            @foreach ($genres as $genre)
                <a href="{{ route('genres.show', $genre) }}" class="btn btn-outline-secondary btn-sm rounded-pill">
                    {{ $genre->name }} <span class="badge bg-secondary">{{ $genre->titles_count }}</span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- الأعلى تقييماً --}}
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0"><i class="bi bi-trophy-fill text-warning"></i> الأعلى تقييماً</h3>
        </div>
        <div class="row g-3">
            @foreach ($topRated as $title)
                <div class="col-6 col-md-4 col-lg-2">
                    @include('partials.title-card', ['title' => $title])
                </div>
            @endforeach
        </div>
    </section>

    {{-- أحدث الإضافات --}}
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0"><i class="bi bi-stars text-warning"></i> أحدث الإضافات</h3>
            <a href="{{ url('/titles') }}" class="btn btn-link text-warning">عرض الكل</a>
        </div>
        <div class="row g-3">
            @foreach ($latest as $title)
                <div class="col-6 col-md-4 col-lg-2">
                    @include('partials.title-card', ['title' => $title])
                </div>
            @endforeach
        </div>
    </section>

@endsection
