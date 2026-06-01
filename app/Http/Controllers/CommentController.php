<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * إضافة رد على مراجعة.
     */
    public function store(Request $request, Review $review)
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'min:2', 'max:1000'],
        ], [], ['body' => 'الرد']);

        $review->comments()->create([
            'user_id' => Auth::id(),
            'body' => $data['body'],
        ]);

        return back()->with('status', 'تمت إضافة ردّك.');
    }

    /**
     * حذف رد (صاحبه أو المدير).
     */
    public function destroy(Comment $comment)
    {
        abort_unless($comment->user_id === Auth::id() || Auth::user()->isAdmin(), 403);

        $comment->delete();

        return back()->with('status', 'تم حذف الرد.');
    }
}
