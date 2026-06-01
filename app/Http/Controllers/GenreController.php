<?php

namespace App\Http\Controllers;

use App\Models\Genre;

class GenreController extends Controller
{
    /**
     * صفحة تصنيف واحد مع أعماله.
     */
    public function show(Genre $genre)
    {
        $titles = $genre->titles()
            ->withAvg('reviews', 'rating')
            ->latest('titles.created_at')
            ->paginate(12);

        return view('genres.show', compact('genre', 'titles'));
    }
}
