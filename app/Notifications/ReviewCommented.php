<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class ReviewCommented extends Notification
{
    use Queueable;

    public function __construct(public Comment $comment)
    {
    }

    /**
     * قنوات التسليم: قاعدة البيانات.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * تمثيل الإشعار في قاعدة البيانات.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $review = $this->comment->review;

        return [
            'comment_id' => $this->comment->id,
            'review_id' => $review->id,
            'title_id' => $review->title_id,
            'title_name' => $review->title->name,
            'commenter' => $this->comment->user->name,
            'excerpt' => Str::limit($this->comment->body, 60),
        ];
    }
}
