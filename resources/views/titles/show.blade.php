@extends('layouts.app')

@section('title', $title->name . ' — MJCOZ TV')

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
            @if ($title->watched)
                <span class="badge bg-warning text-dark mb-2"><i class="bi bi-eye-fill"></i> شاهدتها</span>
            @endif
            <h1 class="fw-bold">{{ $title->name }}</h1>

            <div class="d-flex flex-wrap gap-3 align-items-center text-secondary mb-2">
                <span><i class="bi bi-calendar3"></i> {{ $title->release_year }}</span>
                @foreach ($title->genres as $genre)
                    <a href="{{ route('genres.show', $genre) }}" class="text-warning">
                        <i class="bi bi-tag"></i> {{ $genre->name }}
                    </a>
                @endforeach
                <span>
                    @include('partials.stars', ['rating' => $avg])
                    <strong class="text-light">{{ $avg ?: '—' }}</strong>
                    <small>({{ $title->reviews->count() }} مراجعة)</small>
                </span>
            </div>

            {{-- تقييمات IMDb / Rotten Tomatoes / الشخصي --}}
            @include('partials.external-ratings', ['title' => $title])

            {{-- الوسوم --}}
            @if ($title->tags->isNotEmpty())
                <div class="d-flex flex-wrap gap-1 my-2">
                    @foreach ($title->tags as $tag)
                        <a href="{{ route('tags.show', $tag) }}" class="badge bg-info text-dark text-decoration-none">
                            #{{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            @endif

            <p class="lead mt-2">{{ $title->description }}</p>

            {{-- منصة العرض ورابط المشاهدة + قائمة المشاهدة --}}
            <div class="d-flex flex-wrap gap-2 align-items-center mb-2">
                @if ($title->watch_url)
                    <a href="{{ $title->watch_url }}" target="_blank" rel="noopener" class="btn btn-danger">
                        <i class="bi bi-play-fill"></i> شاهد الآن{{ $title->platform ? ' على ' . $title->platform : '' }}
                    </a>
                @elseif ($title->platform)
                    <span class="badge bg-secondary fs-6 align-self-center">
                        <i class="bi bi-tv"></i> متوفّر على {{ $title->platform }}
                    </span>
                @endif

                @if ($title->trailer_url)
                    <a href="{{ $title->trailer_url }}" target="_blank" rel="noopener" class="btn btn-outline-danger">
                        <i class="bi bi-youtube"></i> الإعلان
                    </a>
                @endif

                @include('partials.watchlist-button', ['title' => $title])
            </div>
        </div>
    </div>

    {{-- المراجعات --}}
    <section>
        <h3 class="mb-4"><i class="bi bi-chat-quote text-warning"></i> آراء المشاهدين</h3>

        {{-- نموذج كتابة / تعديل مراجعة --}}
        @auth
            <div class="bg-dark-2 p-4 rounded mb-4">
                <h5 class="mb-3">
                    <i class="bi bi-pencil-square text-warning"></i>
                    {{ $myReview ? 'عدّل مراجعتك' : 'اكتب رأيك' }}
                </h5>
                <form method="POST" action="{{ route('reviews.store', $title) }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label d-block">تقييمك</label>
                        <div class="star-input fs-3">
                            @for ($i = 5; $i >= 1; $i--)
                                <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}"
                                       @checked(old('rating', $myReview->rating ?? 0) == $i) required>
                                <label for="star{{ $i }}" title="{{ $i }}"><i class="bi bi-star-fill"></i></label>
                            @endfor
                        </div>
                        @error('rating') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <textarea name="body" rows="3" required maxlength="2000"
                                  class="form-control bg-dark text-light border-secondary @error('body') is-invalid @enderror"
                                  placeholder="شاركنا رأيك في هذا العمل...">{{ old('body', $myReview->body ?? '') }}</textarea>
                        @error('body') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <button class="btn btn-warning">
                        <i class="bi bi-send"></i> {{ $myReview ? 'تحديث المراجعة' : 'نشر المراجعة' }}
                    </button>
                </form>
            </div>
        @else
            <div class="bg-dark-2 p-4 rounded mb-4 text-center text-secondary">
                <i class="bi bi-pencil-square"></i>
                <a href="{{ route('login') }}" class="text-warning">سجّل دخولك</a>
                لكتابة رأيك وتقييمك لهذا العمل.
            </div>
        @endauth

        @forelse ($title->reviews->sortByDesc('created_at') as $review)
            @include('partials.review', ['review' => $review])
        @empty
            <div class="alert alert-secondary text-center">لا توجد مراجعات بعد. كن أول من يكتب رأيه!</div>
        @endforelse
    </section>

    {{-- أعمال مشابهة --}}
    @if ($similar->isNotEmpty())
        <section class="mt-5">
            <h3 class="mb-3"><i class="bi bi-collection text-warning"></i> أعمال مشابهة</h3>
            <div class="row g-3">
                @foreach ($similar as $item)
                    <div class="col-6 col-md-4 col-lg-2">
                        @include('partials.title-card', ['title' => $item])
                    </div>
                @endforeach
            </div>
        </section>
    @endif

@endsection
