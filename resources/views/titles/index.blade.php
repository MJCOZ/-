@extends('layouts.app')

@section('title', 'الأفلام والمسلسلات — MJCOZ TV')

@section('content')

    <h2 class="mb-4"><i class="bi bi-collection-play text-warning"></i> الأفلام والمسلسلات</h2>

    {{-- أدوات الفلترة --}}
    <form method="GET" class="row g-2 mb-4 bg-dark-2 p-3 rounded">
        <div class="col-md-5">
            <input type="search" name="q" value="{{ request('q') }}"
                   class="form-control bg-dark text-light border-secondary" placeholder="ابحث بالاسم...">
        </div>
        <div class="col-md-3">
            <select name="genre" class="form-select bg-dark text-light border-secondary">
                <option value="">كل التصنيفات</option>
                @foreach ($genres as $genre)
                    <option value="{{ $genre->id }}" @selected(request('genre') == $genre->id)>{{ $genre->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="type" class="form-select bg-dark text-light border-secondary">
                <option value="">الكل</option>
                <option value="movie" @selected(request('type') === 'movie')>أفلام</option>
                <option value="series" @selected(request('type') === 'series')>مسلسلات</option>
            </select>
        </div>
        <div class="col-md-2 d-grid">
            <button class="btn btn-warning"><i class="bi bi-search"></i> بحث</button>
        </div>
    </form>

    @if ($titles->isEmpty())
        <div class="alert alert-secondary text-center">لا توجد نتائج مطابقة 🤷</div>
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
