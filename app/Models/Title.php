<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Title extends Model
{
    protected $fillable = [
        'genre_id',
        'name',
        'type',
        'description',
        'poster',
        'release_year',
    ];

    /**
     * العمل ينتمي لتصنيف واحد.
     */
    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class);
    }

    /**
     * العمل له عدة مراجعات.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * متوسط التقييم (من 5).
     */
    public function averageRating(): float
    {
        return round((float) $this->reviews()->avg('rating'), 1);
    }

    /**
     * هل العمل فيلم؟
     */
    public function isMovie(): bool
    {
        return $this->type === 'movie';
    }

    /**
     * رابط البوستر: ملف محفوظ إن وُجد، وإلا بوستر مولّد محلياً.
     */
    public function posterUrl(): string
    {
        if ($this->poster && ! str_starts_with($this->poster, 'http')) {
            return asset('storage/' . $this->poster);
        }

        return route('titles.poster', $this);
    }
}
