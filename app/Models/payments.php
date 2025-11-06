<?php

namespace App\Models;

use App\Models\bookings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class payments extends Model
{
    protected $fillable = [
        'booking_id',
        'total_price',
        'status',
        'transaction_ref',
    ];

    public function booking() : BelongsTo
    {
        return $this->belongsTo(bookings::class);
    }
}
