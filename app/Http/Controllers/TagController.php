<?php

namespace App\Http\Controllers;

use App\Models\Tag;

class TagController extends Controller
{
    /**
     * صفحة وسم واحد مع أعماله.
     */
    public function show(Tag $tag)
    {
        $titles = $tag->titles()
            ->withAvg('reviews', 'rating')
            ->latest('titles.created_at')
            ->paginate(12);

        return view('tags.show', compact('tag', 'titles'));
    }
}
