<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
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
}
