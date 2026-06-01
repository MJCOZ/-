{{-- زر إضافة/إزالة من قائمة المشاهدة. متغير: $title --}}
@auth
    @php $in = $title->inMyWatchlist(); @endphp
    <form method="POST" action="{{ route('watchlist.toggle', $title) }}" class="d-inline">
        @csrf
        <button class="btn {{ $in ? 'btn-warning' : 'btn-outline-warning' }}">
            <i class="bi {{ $in ? 'bi-bookmark-check-fill' : 'bi-bookmark-plus' }}"></i>
            {{ $in ? 'في قائمتي' : 'أريد مشاهدته' }}
        </button>
    </form>
@else
    <a href="{{ route('login') }}" class="btn btn-outline-warning">
        <i class="bi bi-bookmark-plus"></i> أريد مشاهدته
    </a>
@endauth
