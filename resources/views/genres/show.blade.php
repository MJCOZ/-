@extends('layouts.app')

@section('title', $genre->name . ' — CineReview')

@section('content')

    <div class="d-flex align-items-center gap-2 mb-4">
        <i class="bi bi-tag-fill text-warning fs-3"></i>
        <h2 class="mb-0">تصنيف: {{ $genre->name }}</h2>
        <span class="badge bg-secondary align-self-center">{{ $titles->total() }} عمل</span>
    </div>

    @if ($titles->isEmpty())
        <div class="alert alert-secondary text-center">لا توجد أعمال في هذا التصنيف بعد.</div>
    @else
        <div class="row g-3">
            @foreach ($titles as $title)
                <div class="col-6 col-md-4 col-lg-3">
                    @include('partials.title-card', ['title' => $title])
                </div>
            @endforeach
        </div>
        <div class="mt-4 d-flex justify-content-center">{{ $titles->links() }}</div>
    @endif

@endsection
