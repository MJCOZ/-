<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Title;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * الصفحة الرئيسية: توصيات + أحدث الأعمال + الأعلى تقييماً.
     */
    public function index()
    {
        $latest = Title::latest()->take(6)->get();

        $topRated = Title::withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->whereHas('reviews')
            ->orderByDesc('reviews_avg_rating')
            ->take(6)
            ->get();

        $genres = Genre::withCount('titles')->orderByDesc('titles_count')->get();

        [$recommended, $personalized] = $this->recommendations();

        return view('home', compact('latest', 'topRated', 'genres', 'recommended', 'personalized'));
    }

    /**
     * توصيات شخصية للمستخدم الحالي، أو اختيارات عامة للزوّار.
     *
     * @return array{0: \Illuminate\Support\Collection, 1: bool}
     */
    private function recommendations(): array
    {
        // أولوية: ترشيحات يختارها المدير يدوياً
        $featured = Title::featured()
            ->withAvg('reviews', 'rating')
            ->latest()
            ->take(6)
            ->get();

        if ($featured->isNotEmpty()) {
            return [$featured, false];
        }

        $user = Auth::user();

        if ($user) {
            // التصنيفات التي تفاعل معها المستخدم (قيّمها ٤+ أو أضافها لقائمته)
            $genreIds = Genre::where(function ($q) use ($user) {
                $q->whereHas('titles.reviews', fn ($r) => $r->where('user_id', $user->id)->where('rating', '>=', 4))
                    ->orWhereHas('titles.watchlistedBy', fn ($w) => $w->where('users.id', $user->id));
            })->pluck('id');

            // استبعاد ما راجعه أو أضافه للقائمة بالفعل
            $excluded = $user->reviews()->pluck('title_id')
                ->merge($user->watchlist()->pluck('titles.id'))
                ->unique()->all();

            if ($genreIds->isNotEmpty()) {
                $recommended = Title::whereHas('genres', fn ($g) => $g->whereIn('genres.id', $genreIds))
                    ->whereNotIn('id', $excluded)
                    ->withAvg('reviews', 'rating')
                    ->orderByDesc('reviews_avg_rating')
                    ->take(6)
                    ->get();

                if ($recommended->isNotEmpty()) {
                    return [$recommended, true];
                }
            }
        }

        // احتياطي: الأعلى تقييماً (اختيارات عامة)
        $popular = Title::withAvg('reviews', 'rating')
            ->whereHas('reviews')
            ->orderByDesc('reviews_avg_rating')
            ->take(6)
            ->get();

        return [$popular, false];
    }
}
