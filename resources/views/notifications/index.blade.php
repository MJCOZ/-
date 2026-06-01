@extends('layouts.app')

@section('title', 'الإشعارات — MJ TV')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0"><i class="bi bi-bell-fill text-warning"></i> الإشعارات</h2>
        @if ($notifications->total() > 0)
            <form method="POST" action="{{ route('notifications.readAll') }}">
                @csrf
                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-check2-all"></i> تعليم الكل كمقروء</button>
            </form>
        @endif
    </div>

    @forelse ($notifications as $note)
        @php $d = $note->data; @endphp
        <a href="{{ route('titles.show', $d['title_id']) }}#comments-{{ $d['review_id'] }}"
           class="d-block text-reset text-decoration-none">
            <div class="bg-dark-2 p-3 rounded mb-2 {{ $note->read_at ? '' : 'border-start border-warning border-3' }}">
                <div class="d-flex justify-content-between">
                    <span>
                        <i class="bi bi-chat-left-text text-warning"></i>
                        <strong>{{ $d['commenter'] }}</strong> ردّ على مراجعتك في
                        <span class="text-warning">{{ $d['title_name'] }}</span>
                    </span>
                    <small class="text-secondary">{{ $note->created_at->diffForHumans() }}</small>
                </div>
                <div class="text-secondary small mt-1">«{{ $d['excerpt'] }}»</div>
            </div>
        </a>
    @empty
        <div class="alert alert-secondary text-center">لا توجد إشعارات بعد.</div>
    @endforelse

    <div class="mt-4 d-flex justify-content-center">{{ $notifications->links() }}</div>

@endsection
