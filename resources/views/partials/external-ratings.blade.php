{{-- تقييمات خارجية وشخصية. متغير: $title --}}
@if ($title->imdb_rating || $title->rt_rating || $title->personal_rating)
    <div class="d-flex flex-wrap gap-2 my-2">
        @if ($title->imdb_rating)
            <span class="badge bg-warning text-dark fs-6">
                <i class="bi bi-film"></i> IMDb {{ rtrim(rtrim($title->imdb_rating, '0'), '.') }}/10
            </span>
        @endif
        @if ($title->rt_rating !== null)
            <span class="badge bg-danger fs-6">🍅 {{ $title->rt_rating }}%</span>
        @endif
        @if ($title->personal_rating)
            <span class="badge bg-info text-dark fs-6">⭐ تقييمي {{ $title->personal_rating }}/10</span>
        @endif
    </div>
@endif
