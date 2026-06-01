<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Genre extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    /**
     * أعمال هذا التصنيف (علاقة متعدّدة لمتعدّدة).
     */
    public function titles(): BelongsToMany
    {
        return $this->belongsToMany(Title::class, 'genre_title');
    }
}
