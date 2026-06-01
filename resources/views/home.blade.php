@extends('layouts.app')

@section('title', 'الرئيسية — MJCOZ TV')

@section('content')

    {{-- سلايدر الأعمال المميّزة --}}
    @php $featured = $topRated->take(5); @endphp
    @if ($featured->isNotEmpty())
        <div id="featured" class="carousel slide hero mb-5" data-bs-ride="carousel">
            <div class="carousel-inner rounded-4">
                @foreach ($featured as $i => $item)
                    <div class="carousel-item @if($i === 0) active @endif">
                        <div class="row align-items-center p-4 p-md-5 g-4">
                            <div class="col-md-8 text-center text-md-start order-2 order-md-1">
                                <span class="badge {{ $item->isMovie() ? 'bg-primary' : 'bg-success' }}">
                                    {{ $item->isMovie() ? 'فيلم' : 'مسلسل' }}
                                </span>
                                @if ($item->watched)
                                    <span class="badge bg-warning text-dark"><i class="bi bi-eye-fill"></i> شاهدتها</span>
                                @endif
                                <h2 class="fw-bold mt-2">{{ $item->name }}</h2>
                                <div class="mb-2">
                                    @include('partials.stars', ['rating' => round($item->reviews_avg_rating, 1)])
                                    <strong>{{ round($item->reviews_avg_rating, 1) }}</strong>
                                    <span class="text-secondary">· {{ $item->release_year }}</span>
                                </div>
                                <p class="text-secondary d-none d-md-block mb-3">
                                    {{ \Illuminate\Support\Str::limit($item->description, 150) }}
                                </p>
                                <a href="{{ route('titles.show', $item) }}" class="btn btn-warning btn-lg">
                                    <i class="bi bi-play-circle"></i> التفاصيل والمراجعات
                                </a>
                            </div>
                            <div class="col-md-4 text-center order-1 order-md-2">
                                <a href="{{ route('titles.show', $item) }}">
                                    <img src="{{ $item->posterUrl() }}" alt="{{ $item->name }}"
                                         class="rounded-3 shadow" style="max-height: 280px; aspect-ratio:2/3; object-fit:cover;">
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($featured->count() > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#featured" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#featured" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
                <div class="carousel-indicators">
                    @foreach ($featured as $i => $item)
                        <button type="button" data-bs-target="#featured" data-bs-slide-to="{{ $i }}"
                                @if($i === 0) class="active" @endif></button>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

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

    {{-- موصى به لك (توصيات شخصية) --}}
    @if ($recommended->isNotEmpty())
        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">
                    <i class="bi bi-magic text-warning"></i>
                    {{ $personalized ? 'موصى به لك' : 'اخترنا لك' }}
                </h3>
                @auth
                    @if (! $personalized)
                        <small class="text-secondary">قيّم بعض الأعمال لنخصّص لك التوصيات</small>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-link text-warning btn-sm">سجّل دخولك لتوصيات تخصّك</a>
                @endauth
            </div>
            <div class="row g-3">
                @foreach ($recommended as $title)
                    <div class="col-6 col-md-4 col-lg-2">
                        @include('partials.title-card', ['title' => $title])
                    </div>
                @endforeach
            </div>
        </section>
    @endif

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
