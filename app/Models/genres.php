<?php

namespace App\Models;

use App\Models\movies;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class genres extends Model
{
    protected $fillable = [
        'name'
    ];

   
    public function movies(): BelongsToMany
    {
        return $this->belongsToMany(movies::class, 'movie_genres', 'genre_id', 'movie_id');
    }
}
