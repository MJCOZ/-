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

        if ($q = trim((string) $request->get('q'))) {
            $query->where('name', 'like', "%{$q}%");
        }

        if ($request->filled('genre')) {
            $query->where('genre_id', $request->integer('genre'));
        }

        if (in_array($request->get('type'), ['movie', 'series'], true)) {
            $query->where('type', $request->get('type'));
        }

        $titles = $query->latest()->paginate(12)->withQueryString();
        $genres = Genre::orderBy('name')->get();

        return view('titles.index', compact('titles', 'genres'));
    }

    /**
     * صفحة تفاصيل عمل واحد.
     */
    public function show(Title $title)
    {
        $title->load([
            'genre',
            'reviews.user',
            'reviews.comments.user',
            'reviews.likes',
        ]);
        $avg = $title->averageRating();

        // مراجعة المستخدم الحالي إن وُجدت
        $myReview = auth()->check()
            ? $title->reviews->firstWhere('user_id', auth()->id())
            : null;

        // أعمال مشابهة (نفس التصنيف)
        $similar = Title::where('id', '!=', $title->id)
            ->when($title->genre_id, fn ($q) => $q->where('genre_id', $title->genre_id))
            ->withAvg('reviews', 'rating')
            ->inRandomOrder()
            ->take(6)
            ->get();

        return view('titles.show', compact('title', 'avg', 'myReview', 'similar'));
    }
}
