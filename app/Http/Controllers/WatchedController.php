<?php

namespace App\Http\Controllers;

use App\Models\Title;

class WatchedController extends Controller
{
    /**
     * صفحة "شاهدتها": الأعمال التي شاهدها المالك مع تقييماته.
     */
    public function index()
    {
        $titles = Title::watched()
            ->withAvg('reviews', 'rating')
            ->orderByDesc('watched_at')
            ->orderByDesc('id')
            ->paginate(12);

        return view('watched.index', compact('titles'));
    }
}
