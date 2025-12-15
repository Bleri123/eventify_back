<?php

namespace App\Models;

use App\Models\genres;
use App\Models\screenings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class movies extends Model
{
    protected $fillable = [
        'title',
        'description',
        'duration_minutes',
        'release_date',
        'movie_language',
        'status',
        'poster_url',
    ];

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(genres::class, 'movie_genres', 'movie_id', 'genre_id');
    }

    public function screenings(): HasMany
    {
        return $this->hasMany(screenings::class, 'movie_id'); // Explicitly specify foreign key
    }
}