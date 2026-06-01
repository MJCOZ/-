<?php

namespace App\Http\Controllers;

use App\Models\Title;
use Illuminate\Support\Facades\Auth;

class WatchlistController extends Controller
{
    /**
     * قائمة "أريد مشاهدته" للمستخدم الحالي.
     */
    public function index()
    {
        $titles = Auth::user()->watchlist()
            ->withAvg('reviews', 'rating')
            ->orderByDesc('watchlists.created_at')
            ->paginate(12);

        return view('watchlist.index', compact('titles'));
    }

    /**
     * إضافة/إزالة عمل من القائمة (toggle).
     */
    public function toggle(Title $title)
    {
        $result = Auth::user()->watchlist()->toggle($title->id);

        $added = in_array($title->id, $result['attached'], true);

        return back()->with('status', $added
            ? 'تمت إضافة «' . $title->name . '» إلى قائمتك.'
            : 'تمت إزالة «' . $title->name . '» من قائمتك.');
    }
}
