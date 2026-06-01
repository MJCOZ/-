<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Title;

class HomeController extends Controller
{
    /**
     * الصفحة الرئيسية: أحدث الأعمال + الأعلى تقييماً.
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

        return view('home', compact('latest', 'topRated', 'genres'));
    }
}
