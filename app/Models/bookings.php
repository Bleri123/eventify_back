<?php

namespace App\Models;

use App\Models\User;
use App\Models\screenings;
use App\Models\tickets;
use App\Models\payments;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class bookings extends Model
{
    protected $fillable = [
        'user_id',
        'screening_id',
        'status',
        'total_price',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function screening() : BelongsTo
    {
        return $this->belongsTo(screenings::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(tickets::class, 'booking_id', 'id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(payments::class);
    }
}
