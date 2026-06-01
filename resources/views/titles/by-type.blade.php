@extends('layouts.app')

@section('title', $heading . ' — CineReview')

@section('content')

    <div class="d-flex align-items-center gap-2 mb-4">
        <i class="bi {{ $type === 'movie' ? 'bi-camera-reels' : 'bi-tv' }} text-warning fs-3"></i>
        <h2 class="mb-0">{{ $heading }}</h2>
        <span class="badge bg-secondary align-self-center">{{ $total }} عمل</span>
    </div>

    {{-- روابط سريعة بين الأفلام والمسلسلات --}}
    <div class="mb-4 d-flex gap-2">
        <a href="{{ route('movies.index') }}"
           class="btn btn-sm {{ $type === 'movie' ? 'btn-warning' : 'btn-outline-secondary' }}">
            <i class="bi bi-camera-reels"></i> الأفلام
        </a>
        <a href="{{ route('series.index') }}"
           class="btn btn-sm {{ $type === 'series' ? 'btn-warning' : 'btn-outline-secondary' }}">
            <i class="bi bi-tv"></i> المسلسلات
        </a>
    </div>

    {{-- فهرس سريع للتصنيفات --}}
    @if ($genres->isNotEmpty())
        <div class="d-flex flex-wrap gap-2 mb-4">
            @foreach ($genres as $genre)
                <a href="#genre-{{ $genre->id }}" class="btn btn-outline-secondary btn-sm rounded-pill">
                    {{ $genre->name }} <span class="badge bg-secondary">{{ $genre->titles->count() }}</span>
                </a>
            @endforeach
        </div>
    @endif

    @forelse ($genres as $genre)
        <section id="genre-{{ $genre->id }}" class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">
                    <i class="bi bi-tag-fill text-warning"></i>
                    <a href="{{ route('genres.show', $genre) }}" class="text-reset">{{ $genre->name }}</a>
                </h4>
                <a href="{{ route('genres.show', $genre) }}" class="btn btn-link btn-sm text-warning">عرض الكل</a>
            </div>
            <div class="row g-3">
                @foreach ($genre->titles as $title)
                    <div class="col-6 col-md-4 col-lg-2">
                        @include('partials.title-card', ['title' => $title])
                    </div>
                @endforeach
            </div>
        </section>
    @empty
        <div class="alert alert-secondary text-center">لا توجد أعمال بعد.</div>
    @endforelse

    {{-- أعمال بدون تصنيف --}}
    @if ($ungrouped->isNotEmpty())
        <section class="mb-5">
            <h4 class="mb-3"><i class="bi bi-tag text-secondary"></i> غير مصنّف</h4>
            <div class="row g-3">
                @foreach ($ungrouped as $title)
                    <div class="col-6 col-md-4 col-lg-2">
                        @include('partials.title-card', ['title' => $title])
                    </div>
                @endforeach
            </div>
        </section>
    @endif

@endsection
