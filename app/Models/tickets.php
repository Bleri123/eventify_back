<?php

namespace App\Models;

use App\Models\bookings;
use App\Models\screenings;
use App\Models\seats;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class tickets extends Model
{
    protected $fillable = [
        'booking_id',
        'screening_id',
        'seat_id',
        'total_price',
        'status',
    ];

    public function booking() : BelongsTo
    {
        return $this->belongsTo(bookings::class);
    }

    public function screening() : BelongsTo
    {
        return $this->belongsTo(screenings::class);
    }

    public function seat() : BelongsTo
    {
        return $this->belongsTo(seats::class);
    }
}
