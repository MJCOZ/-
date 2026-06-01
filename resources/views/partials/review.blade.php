{{-- مراجعة واحدة مع التصويت والردود. متغير: $review --}}
@php
    $myVote = $review->myVote();
    $isOwner = auth()->check() && $review->user_id === auth()->id();
@endphp
<div class="bg-dark-2 p-3 rounded mb-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <i class="bi bi-person-circle"></i>
            <strong>{{ $review->user->name }}</strong>
            @if ($isOwner)
                <span class="badge bg-warning text-dark ms-1">أنت</span>
            @endif
            <small class="text-secondary ms-2">{{ $review->created_at->diffForHumans() }}</small>
        </div>
        @include('partials.stars', ['rating' => $review->rating])
    </div>

    <p class="mb-2">{{ $review->body }}</p>

    {{-- شريط التفاعل: تصويت + حذف --}}
    <div class="d-flex flex-wrap align-items-center gap-2 small">
        @auth
            @unless ($isOwner)
                <form method="POST" action="{{ route('reviews.vote', $review) }}" class="d-inline">
                    @csrf
                    <input type="hidden" name="helpful" value="1">
                    <button class="btn btn-sm {{ $myVote === true ? 'btn-success' : 'btn-outline-success' }}">
                        <i class="bi bi-hand-thumbs-up"></i> مفيد ({{ $review->helpfulCount() }})
                    </button>
                </form>
                <form method="POST" action="{{ route('reviews.vote', $review) }}" class="d-inline">
                    @csrf
                    <input type="hidden" name="helpful" value="0">
                    <button class="btn btn-sm {{ $myVote === false ? 'btn-danger' : 'btn-outline-danger' }}">
                        <i class="bi bi-hand-thumbs-down"></i> غير مفيد ({{ $review->notHelpfulCount() }})
                    </button>
                </form>
            @else
                <span class="text-secondary">
                    <i class="bi bi-hand-thumbs-up"></i> {{ $review->helpfulCount() }}
                    <i class="bi bi-hand-thumbs-down ms-2"></i> {{ $review->notHelpfulCount() }}
                </span>
            @endunless
        @else
            <span class="text-secondary">
                <i class="bi bi-hand-thumbs-up"></i> {{ $review->helpfulCount() }}
                <i class="bi bi-hand-thumbs-down ms-2"></i> {{ $review->notHelpfulCount() }}
            </span>
        @endauth

        <button class="btn btn-sm btn-outline-secondary" type="button"
                data-bs-toggle="collapse" data-bs-target="#comments-{{ $review->id }}">
            <i class="bi bi-chat"></i> ردود ({{ $review->comments->count() }})
        </button>

        @auth
            @if ($isOwner)
                <form method="POST" action="{{ route('reviews.destroy', $review) }}" class="d-inline"
                      onsubmit="return confirm('هل تريد حذف مراجعتك؟');">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i> حذف</button>
                </form>
            @endif
        @endauth
    </div>

    {{-- الردود --}}
    <div class="collapse mt-3" id="comments-{{ $review->id }}">
        <div class="ps-3 border-start border-secondary">
            @forelse ($review->comments->sortBy('created_at') as $comment)
                <div class="mb-2">
                    <div class="d-flex justify-content-between">
                        <span>
                            <i class="bi bi-person"></i>
                            <strong class="small">{{ $comment->user->name }}</strong>
                            <small class="text-secondary ms-1">{{ $comment->created_at->diffForHumans() }}</small>
                        </span>
                        @auth
                            @if ($comment->user_id === auth()->id() || auth()->user()->isAdmin())
                                <form method="POST" action="{{ route('comments.destroy', $comment) }}"
                                      onsubmit="return confirm('حذف الرد؟');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm text-danger p-0"><i class="bi bi-x-lg"></i></button>
                                </form>
                            @endif
                        @endauth
                    </div>
                    <div class="small">{{ $comment->body }}</div>
                </div>
            @empty
                <p class="text-secondary small mb-2">لا توجد ردود بعد.</p>
            @endforelse

            @auth
                <form method="POST" action="{{ route('comments.store', $review) }}" class="d-flex gap-2 mt-2">
                    @csrf
                    <input type="text" name="body" required maxlength="1000"
                           class="form-control form-control-sm bg-dark text-light border-secondary"
                           placeholder="اكتب رداً...">
                    <button class="btn btn-sm btn-warning"><i class="bi bi-send"></i></button>
                </form>
            @endauth
        </div>
    </div>
</div>
