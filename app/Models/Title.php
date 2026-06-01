<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Title extends Model
{
    use HasFactory;

    protected $fillable = [
        'genre_id',
        'name',
        'type',
        'description',
        'platform',
        'watch_url',
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
     * التصنيف الأساسي (للتوافق وتجميع الصفحات).
     */
    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class);
    }

    /**
     * تصنيفات العمل (متعدّدة).
     */
    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'genre_title');
    }

    /**
     * العمل له عدة مراجعات.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * وسوم العمل (متعددة).
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'title_tag');
    }

    /**
     * المستخدمون الذين أضافوا العمل لقائمة مشاهدتهم.
     */
    public function watchlistedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'watchlists')->withTimestamps();
    }

    /**
     * هل العمل في قائمة مشاهدة المستخدم الحالي؟
     */
    public function inMyWatchlist(): bool
    {
        return auth()->check()
            && $this->watchlistedBy->contains('id', auth()->id());
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
        if (filled($this->poster)) {
            return str_starts_with($this->poster, 'http')
                ? $this->poster
                : asset('storage/' . $this->poster);
        }

        // عمل محفوظ بدون بوستر → بوستر مولّد. عمل جديد (بلا id) → معاينة بديلة.
        if ($this->exists) {
            return route('titles.poster', $this);
        }

        return 'data:image/svg+xml,'
            . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="200" height="300"><rect width="200" height="300" fill="#1a1d27"/><text x="100" y="155" font-size="60" text-anchor="middle">🎬</text></svg>');
    }
}
