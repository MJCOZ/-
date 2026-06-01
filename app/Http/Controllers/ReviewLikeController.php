<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewLikeController extends Controller
{
    /**
     * تصويت "مفيد" أو "غير مفيد" على مراجعة.
     * إعادة نفس التصويت تلغيه (toggle).
     */
    public function store(Request $request, Review $review)
    {
        $data = $request->validate([
            'helpful' => ['required', 'boolean'],
        ]);

        // لا يصوّت المستخدم على مراجعته الخاصة
        abort_if($review->user_id === Auth::id(), 403, 'لا يمكنك التصويت على مراجعتك.');

        $existing = $review->likes()->where('user_id', Auth::id())->first();

        if ($existing && (bool) $existing->helpful === (bool) $data['helpful']) {
            // نفس التصويت → إلغاء
            $existing->delete();
        } else {
            $review->likes()->updateOrCreate(
                ['user_id' => Auth::id()],
                ['helpful' => $data['helpful']],
            );
        }

        return back();
    }
}
