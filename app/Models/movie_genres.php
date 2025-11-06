<?php

namespace App\Models;

use App\Models\movies;
use App\Models\genres;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class movie_genres extends Model
{
    protected $fillable = [
        'movie_id',
        'genre_id',
    ];

    public function movie(): BelongsTo
    {
        return $this->belongsTo(movies::class);
    }

    public function genre(): BelongsTo
    {
        return $this->belongsTo(genres::class);
    }
}
