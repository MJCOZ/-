@extends('layouts.app')

@section('title', 'لوحة التحكم — MJCOZ TV')

@section('content')
    <h2 class="mb-4"><i class="bi bi-speedometer2 text-warning"></i> لوحة التحكم</h2>
    @include('admin.partials.nav', ['active' => 'dashboard'])

    {{-- حالة قاعدة البيانات --}}
    @if ($dbDriver === 'sqlite')
        <div class="alert alert-warning d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>قاعدة البيانات الحالية <strong>SQLite (مؤقتة)</strong> — البيانات تُمسح عند إعادة النشر/التشغيل. اربط PostgreSQL لحفظها دائماً.</div>
        </div>
    @else
        <div class="alert alert-success d-flex align-items-center gap-2">
            <i class="bi bi-shield-check"></i>
            <div>قاعدة البيانات <strong>{{ $dbDriver }} (دائمة)</strong> ✓ — كل إضافاتك وتعديلاتك محفوظة وتبقى بعد النشر.</div>
        </div>
    @endif

    {{-- بطاقات الإحصائيات --}}
    <div class="row g-3 mb-4">
        @php
            $cards = [
                ['الأعمال', $stats['titles'], 'bi-film', 'primary'],
                ['الأفلام', $stats['movies'], 'bi-camera-reels', 'info'],
                ['المسلسلات', $stats['series'], 'bi-tv', 'success'],
                ['التصنيفات', $stats['genres'], 'bi-tags', 'warning'],
                ['المراجعات', $stats['reviews'], 'bi-chat-quote', 'danger'],
                ['المستخدمون', $stats['users'], 'bi-people', 'secondary'],
            ];
        @endphp
        @foreach ($cards as [$label, $value, $icon, $color])
            <div class="col-6 col-md-4 col-lg-2">
                <div class="bg-dark-2 p-3 rounded text-center h-100">
                    <i class="bi {{ $icon }} text-{{ $color }}" style="font-size: 1.8rem;"></i>
                    <div class="fs-3 fw-bold">{{ $value }}</div>
                    <div class="text-secondary small">{{ $label }}</div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- آخر المراجعات --}}
    <h4 class="mb-3"><i class="bi bi-clock-history text-warning"></i> آخر المراجعات</h4>
    <div class="bg-dark-2 rounded p-3">
        @forelse ($latestReviews as $review)
            <div class="d-flex justify-content-between border-bottom border-secondary py-2">
                <div>
                    <strong>{{ $review->user->name }}</strong>
                    <span class="text-secondary">راجع</span>
                    <a href="{{ route('titles.show', $review->title) }}" class="text-warning">{{ $review->title->name }}</a>
                </div>
                <div>
                    @include('partials.stars', ['rating' => $review->rating])
                    <small class="text-secondary ms-2">{{ $review->created_at->diffForHumans() }}</small>
                </div>
            </div>
        @empty
            <p class="text-secondary mb-0 text-center">لا توجد مراجعات بعد.</p>
        @endforelse
    </div>
@endsection
