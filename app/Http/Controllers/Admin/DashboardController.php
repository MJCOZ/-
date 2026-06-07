<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Models\Review;
use App\Models\Title;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * لوحة التحكم: إحصائيات سريعة.
     */
    public function index()
    {
        $stats = [
            'titles' => Title::count(),
            'movies' => Title::where('type', 'movie')->count(),
            'series' => Title::where('type', 'series')->count(),
            'genres' => Genre::count(),
            'reviews' => Review::count(),
            'users' => User::count(),
        ];

        $dbDriver = DB::connection()->getDriverName();
        $latestReviews = Review::with(['user', 'title'])->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'latestReviews', 'dbDriver'));
    }
}
