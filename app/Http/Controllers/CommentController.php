<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Review;
use App\Notifications\ReviewCommented;
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

        $comment = $review->comments()->create([
            'user_id' => Auth::id(),
            'body' => $data['body'],
        ]);

        // إشعار صاحب المراجعة (إلا إذا علّق على مراجعته)
        if ($review->user_id !== Auth::id()) {
            $review->loadMissing('title');
            $review->user->notify(new ReviewCommented($comment));
        }

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
