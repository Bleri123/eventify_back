<?php

namespace App\Models;

use App\Models\seats;
use App\Models\screenings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class showrooms extends Model
{
    protected $fillable = [
        'name',
        'seat_rows',
        'seat_cols',
        'is_active',
    ];

    public function seats() : HasMany
    {
        return $this->hasMany(seats::class);
    }

    public function screenings(): HasMany
    {
        return $this->hasMany(screenings::class);
    }
}
