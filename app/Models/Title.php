<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Title extends Model
{
    use HasFactory;

    protected $fillable = [
        'genre_id',
        'name',
        'type',
        'description',
        'poster',
        'release_year',
        'imdb_rating',
        'rt_rating',
        'personal_rating',
        'watched',
        'watched_at',
    ];

    protected function casts(): array
    {
        return [
            'watched' => 'boolean',
            'watched_at' => 'date',
            'imdb_rating' => 'decimal:1',
        ];
    }

    /**
     * نطاق: الأعمال التي شاهدها المالك.
     */
    public function scopeWatched($query)
    {
        return $query->where('watched', true);
    }

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
     * رابط البوستر: رابط خارجي أو ملف مرفوع أو بوستر مولّد محلياً.
     */
    public function posterUrl(): string
    {
        if (blank($this->poster)) {
            return route('titles.poster', $this);
        }

        if (str_starts_with($this->poster, 'http')) {
            return $this->poster;
        }

        return asset('storage/' . $this->poster);
    }
}
