@extends('layouts.app')

@section('title', 'قائمتي — MJCOZ TV')

@section('content')

    <div class="d-flex align-items-center gap-2 mb-4">
        <i class="bi bi-bookmark-heart-fill text-warning fs-3"></i>
        <h2 class="mb-0">قائمة "أريد مشاهدته"</h2>
        <span class="badge bg-secondary align-self-center">{{ $titles->total() }}</span>
    </div>

    @if ($titles->isEmpty())
        <div class="alert alert-secondary text-center">
            قائمتك فارغة. تصفّح <a href="{{ route('movies.index') }}" class="text-warning">الأفلام</a>
            و <a href="{{ route('series.index') }}" class="text-warning">المسلسلات</a> وأضف ما يعجبك!
        </div>
    @else
        <div class="row g-3">
            @foreach ($titles as $title)
                <div class="col-6 col-md-4 col-lg-3">
                    @include('partials.title-card', ['title' => $title])
                    <form method="POST" action="{{ route('watchlist.toggle', $title) }}" class="mt-1 d-grid">
                        @csrf
                        <button class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-x-lg"></i> إزالة
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
        <div class="mt-4 d-flex justify-content-center">{{ $titles->links() }}</div>
    @endif

@endsection
