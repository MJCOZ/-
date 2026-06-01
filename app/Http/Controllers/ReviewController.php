<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Title;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * حفظ مراجعة جديدة أو تعديل مراجعة المستخدم الحالية.
     */
    public function store(Request $request, Title $title)
    {
        $data = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'body' => ['required', 'string', 'min:3', 'max:2000'],
        ], [], [
            'rating' => 'التقييم',
            'body' => 'المراجعة',
        ]);

        $title->reviews()->updateOrCreate(
            ['user_id' => Auth::id()],
            ['rating' => $data['rating'], 'body' => $data['body']],
        );

        return redirect()->route('titles.show', $title)
            ->with('status', 'تم حفظ مراجعتك، شكراً لمشاركتك!');
    }

    /**
     * حذف مراجعة (صاحبها فقط).
     */
    public function destroy(Review $review)
    {
        abort_unless($review->user_id === Auth::id(), 403);

        $title = $review->title;
        $review->delete();

        return redirect()->route('titles.show', $title)
            ->with('status', 'تم حذف مراجعتك.');
    }
}
