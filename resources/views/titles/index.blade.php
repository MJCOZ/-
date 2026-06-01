@extends('layouts.app')

@section('title', ($q ?? '') !== '' ? 'بحث: ' . $q . ' — MJCOZ TV' : 'الأفلام والمسلسلات — MJCOZ TV')

@section('content')

    <h2 class="mb-4"><i class="bi bi-collection-play text-warning"></i> الأفلام والمسلسلات</h2>

    {{-- أدوات البحث والفلترة --}}
    <form method="GET" class="row g-2 mb-3 bg-dark-2 p-3 rounded-3 shadow-sm">
        <div class="col-md-12 col-lg-4">
            <div class="input-group">
                <span class="input-group-text bg-dark border-secondary text-warning"><i class="bi bi-search"></i></span>
                <input type="search" name="q" value="{{ $q ?? request('q') }}" autofocus
                       class="form-control bg-dark text-light border-secondary"
                       placeholder="ابحث بالاسم، الوصف، التصنيف، الوسم...">
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <select name="genre" class="form-select bg-dark text-light border-secondary">
                <option value="">كل التصنيفات</option>
                @foreach ($genres as $genre)
                    <option value="{{ $genre->id }}" @selected(request('genre') == $genre->id)>{{ $genre->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-lg-2">
            <select name="type" class="form-select bg-dark text-light border-secondary">
                <option value="">النوع: الكل</option>
                <option value="movie" @selected(request('type') === 'movie')>أفلام</option>
                <option value="series" @selected(request('type') === 'series')>مسلسلات</option>
            </select>
        </div>
        <div class="col-6 col-lg-2">
            <select name="sort" class="form-select bg-dark text-light border-secondary">
                <option value="">الأحدث</option>
                <option value="rating" @selected(request('sort') === 'rating')>الأعلى تقييماً</option>
                <option value="year" @selected(request('sort') === 'year')>سنة الإصدار</option>
            </select>
        </div>
        <div class="col-6 col-lg-1 d-grid">
            <button class="btn btn-warning"><i class="bi bi-funnel"></i></button>
        </div>
    </form>

    {{-- ملخّص النتائج --}}
    <div class="d-flex justify-content-between align-items-center mb-3 text-secondary small">
        <span>
            @if (($q ?? '') !== '')
                نتائج البحث عن: <span class="text-warning">«{{ $q }}»</span> —
            @endif
            {{ $titles->total() }} عمل
        </span>
        @if (($q ?? '') !== '' || request('genre') || request('type') || request('sort'))
            <a href="{{ route('titles.index') }}" class="text-warning"><i class="bi bi-x-circle"></i> مسح الفلاتر</a>
        @endif
    </div>

    @if ($titles->isEmpty())
        <div class="alert alert-secondary text-center py-5">
            <i class="bi bi-search fs-3 d-block mb-2"></i>
            لا توجد نتائج مطابقة 🤷
            @if (($q ?? '') !== '')
                <div class="mt-2"><a href="{{ route('titles.index') }}" class="text-warning">تصفّح كل الأعمال</a></div>
            @endif
        </div>
    @else
        <div class="row g-3">
            @foreach ($titles as $title)
                <div class="col-6 col-md-4 col-lg-3">
                    @include('partials.title-card', ['title' => $title])
                </div>
            @endforeach
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $titles->links() }}
        </div>
    @endif

@endsection
