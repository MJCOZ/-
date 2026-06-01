{{-- كرت عمل واحد. متغير: $title --}}
@php $avg = $title->averageRating(); @endphp
<a href="{{ url('/titles/'.$title->id) }}" class="text-reset">
    <div class="card-title-poster">
        <span class="poster-badge badge {{ $title->isMovie() ? 'bg-primary' : 'bg-success' }}">
            {{ $title->isMovie() ? 'فيلم' : 'مسلسل' }}
        </span>
        @if ($title->watched)
            <span class="poster-badge badge bg-warning text-dark" style="inset-inline-start:auto; inset-inline-end:.5rem;">
                <i class="bi bi-eye-fill"></i>
            </span>
        @endif
        <div class="poster-thumb">
            <img src="{{ $title->posterUrl() }}" alt="{{ $title->name }}" loading="lazy">
            <span class="poster-play"><i class="bi bi-play-circle-fill"></i></span>
        </div>
        <div class="p-2">
            <h6 class="mb-1 text-truncate">{{ $title->name }}</h6>
            <div class="d-flex justify-content-between align-items-center small text-secondary">
                <span>{{ $title->release_year }}</span>
                <span>
                    @include('partials.stars', ['rating' => $avg])
                    <span class="ms-1">{{ $avg ?: '—' }}</span>
                </span>
            </div>
        </div>
    </div>
</a>
