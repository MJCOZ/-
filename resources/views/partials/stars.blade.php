{{-- عرض التقييم بالنجوم. متغير: $rating (0..5) --}}
@php $rating = (float) ($rating ?? 0); @endphp
<span class="rating-stars">
    @for ($i = 1; $i <= 5; $i++)
        @if ($i <= floor($rating))
            <i class="bi bi-star-fill"></i>
        @elseif ($i - 0.5 <= $rating)
            <i class="bi bi-star-half"></i>
        @else
            <i class="bi bi-star text-secondary"></i>
        @endif
    @endfor
</span>
