<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title_id',
        'rating',
        'body',
    ];

    /**
     * المراجعة تخص مستخدم.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * المراجعة تخص عملاً.
     */
    public function title(): BelongsTo
    {
        return $this->belongsTo(Title::class);
    }

    /**
     * ردود/تعليقات على المراجعة.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * تصويتات (مفيد/غير مفيد) على المراجعة.
     */
    public function likes(): HasMany
    {
        return $this->hasMany(ReviewLike::class);
    }

    /**
     * عدد تصويتات "مفيد".
     */
    public function helpfulCount(): int
    {
        return $this->likes->where('helpful', true)->count();
    }

    /**
     * عدد تصويتات "غير مفيد".
     */
    public function notHelpfulCount(): int
    {
        return $this->likes->where('helpful', false)->count();
    }

    /**
     * تصويت المستخدم الحالي على هذه المراجعة (true/false/null).
     */
    public function myVote(): ?bool
    {
        if (! auth()->check()) {
            return null;
        }

        $vote = $this->likes->firstWhere('user_id', auth()->id());

        return $vote?->helpful;
    }
}
