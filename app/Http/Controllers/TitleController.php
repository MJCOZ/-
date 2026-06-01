<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Title;
use Illuminate\Http\Request;

class TitleController extends Controller
{
    /**
     * قائمة الأعمال مع البحث والفلترة.
     */
    public function index(Request $request)
    {
        $query = Title::query()->withAvg('reviews', 'rating');

        $q = trim((string) $request->get('q'));
        if ($q !== '') {
            // بحث موسّع: الاسم أو الوصف أو المنصة أو التصنيف أو الوسم
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhere('platform', 'like', "%{$q}%")
                    ->orWhereHas('genres', fn ($g) => $g->where('name', 'like', "%{$q}%"))
                    ->orWhereHas('tags', fn ($t) => $t->where('name', 'like', "%{$q}%"));
            });
        }

        if ($request->filled('genre')) {
            $query->whereHas('genres', fn ($g) => $g->where('genres.id', $request->integer('genre')));
        }

        if (in_array($request->get('type'), ['movie', 'series'], true)) {
            $query->where('type', $request->get('type'));
        }

        if ($request->get('sort') === 'rating') {
            $query->orderByDesc('reviews_avg_rating');
        } elseif ($request->get('sort') === 'year') {
            $query->orderByDesc('release_year');
        } else {
            $query->latest();
        }

        $titles = $query->paginate(12)->withQueryString();
        $genres = Genre::orderBy('name')->get();

        return view('titles.index', compact('titles', 'genres', 'q'));
    }

    /**
     * خانة الأفلام: أفلام مصنّفة حسب التصنيف.
     */
    public function movies()
    {
        return $this->byType('movie', 'الأفلام');
    }

    /**
     * خانة المسلسلات: مسلسلات مصنّفة حسب التصنيف.
     */
    public function series()
    {
        return $this->byType('series', 'المسلسلات');
    }

    /**
     * عرض الأعمال من نوع معيّن مجمّعة حسب التصنيف.
     */
    private function byType(string $type, string $heading)
    {
        // التصنيفات التي تحتوي أعمالاً من هذا النوع، مع أعمالها
        $genres = Genre::whereHas('titles', fn ($q) => $q->where('type', $type))
            ->with(['titles' => fn ($q) => $q->where('type', $type)
                ->withAvg('reviews', 'rating')
                ->latest('titles.created_at')])
            ->orderBy('name')
            ->get();

        // أعمال بلا تصنيف
        $ungrouped = Title::where('type', $type)
            ->whereDoesntHave('genres')
            ->withAvg('reviews', 'rating')
            ->latest()
            ->get();

        $total = Title::where('type', $type)->count();

        return view('titles.by-type', compact('genres', 'ungrouped', 'type', 'heading', 'total'));
    }

    /**
     * صفحة تفاصيل عمل واحد.
     */
    public function show(Title $title)
    {
        $title->load([
            'genres',
            'tags',
            'watchlistedBy',
            'reviews.user',
            'reviews.comments.user',
            'reviews.likes',
        ]);
        $avg = $title->averageRating();

        // مراجعة المستخدم الحالي إن وُجدت
        $myReview = auth()->check()
            ? $title->reviews->firstWhere('user_id', auth()->id())
            : null;

        // أعمال مشابهة (تشترك في أحد التصنيفات)
        $genreIds = $title->genres->pluck('id');
        $similar = Title::where('id', '!=', $title->id)
            ->when($genreIds->isNotEmpty(), fn ($q) => $q->whereHas('genres', fn ($g) => $g->whereIn('genres.id', $genreIds)))
            ->withAvg('reviews', 'rating')
            ->inRandomOrder()
            ->take(6)
            ->get();

        return view('titles.show', compact('title', 'avg', 'myReview', 'similar'));
    }
}
