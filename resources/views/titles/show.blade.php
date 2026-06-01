@extends('layouts.app')

@section('title', $title->name . ' — CineReview')

@section('content')

    {{-- تفاصيل العمل --}}
    <div class="row g-4 mb-5">
        <div class="col-md-4 col-lg-3">
            <img src="{{ $title->posterUrl() }}" alt="{{ $title->name }}" class="img-fluid rounded shadow">
        </div>
        <div class="col-md-8 col-lg-9">
            <span class="badge {{ $title->isMovie() ? 'bg-primary' : 'bg-success' }} mb-2">
                {{ $title->isMovie() ? 'فيلم' : 'مسلسل' }}
            </span>
            <h1 class="fw-bold">{{ $title->name }}</h1>

            <div class="d-flex flex-wrap gap-3 align-items-center text-secondary mb-3">
                <span><i class="bi bi-calendar3"></i> {{ $title->release_year }}</span>
                @if ($title->genre)
                    <span><i class="bi bi-tag"></i> {{ $title->genre->name }}</span>
                @endif
                <span>
                    @include('partials.stars', ['rating' => $avg])
                    <strong class="text-light">{{ $avg ?: '—' }}</strong>
                    <small>({{ $title->reviews->count() }} مراجعة)</small>
                </span>
            </div>

            <p class="lead">{{ $title->description }}</p>
        </div>
    </div>

    {{-- المراجعات --}}
    <section>
        <h3 class="mb-4"><i class="bi bi-chat-quote text-warning"></i> آراء المشاهدين</h3>

        {{-- نموذج كتابة مراجعة (سيُفعّل بعد إضافة تسجيل الدخول) --}}
        <div class="bg-dark-2 p-4 rounded mb-4 text-center text-secondary">
            <i class="bi bi-pencil-square"></i> سجّل دخولك لكتابة رأيك وتقييمك لهذا العمل.
            <span class="badge bg-warning text-dark ms-2">قريباً</span>
        </div>

        @forelse ($title->reviews->sortByDesc('created_at') as $review)
            <div class="bg-dark-2 p-3 rounded mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <i class="bi bi-person-circle"></i>
                        <strong>{{ $review->user->name }}</strong>
                        <small class="text-secondary ms-2">{{ $review->created_at->diffForHumans() }}</small>
                    </div>
                    @include('partials.stars', ['rating' => $review->rating])
                </div>
                <p class="mb-0">{{ $review->body }}</p>
            </div>
        @empty
            <div class="alert alert-secondary text-center">لا توجد مراجعات بعد. كن أول من يكتب رأيه!</div>
        @endforelse
    </section>

@endsection
