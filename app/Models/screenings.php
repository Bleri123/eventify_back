<?php

namespace App\Models;

use App\Models\movies;
use App\Models\showrooms;
use App\Models\bookings;
use App\Models\tickets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class screenings extends Model
{
    protected $fillable = [
        'movie_id',
        'showroom_id',
        'start_time',
        'base_price',
        'status',
    ];

    public function movie() : BelongsTo
    {
        return $this->belongsTo(movies::class);
    }

    public function showroom() : BelongsTo
    {
        return $this->belongsTo(showrooms::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(bookings::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(tickets::class);
    }
}
