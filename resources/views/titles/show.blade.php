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
            <div class="bg-dark-2 p-3 rounded mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <i class="bi bi-person-circle"></i>
                        <strong>{{ $review->user->name }}</strong>
                        @auth
                            @if ($review->user_id === auth()->id())
                                <span class="badge bg-warning text-dark ms-1">أنت</span>
                            @endif
                        @endauth
                        <small class="text-secondary ms-2">{{ $review->created_at->diffForHumans() }}</small>
                    </div>
                    @include('partials.stars', ['rating' => $review->rating])
                </div>
                <p class="mb-0">{{ $review->body }}</p>
                @auth
                    @if ($review->user_id === auth()->id())
                        <form method="POST" action="{{ route('reviews.destroy', $review) }}" class="mt-2"
                              onsubmit="return confirm('هل تريد حذف مراجعتك؟');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i> حذف</button>
                        </form>
                    @endif
                @endauth
            </div>
        @empty
            <div class="alert alert-secondary text-center">لا توجد مراجعات بعد. كن أول من يكتب رأيه!</div>
        @endforelse
    </section>

@endsection
