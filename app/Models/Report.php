<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'reports';

    protected $fillable = [
        'screening_id',
        'user_id',
        'booking_id',
        'first_name',
        'email',
        'seats_reserved',
        'row_reserved',
        'total_price',
        'status',
        'booked_at',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'booked_at' => 'datetime',
    ];

    /**
     * Get the screening associated with the report
     */
    public function screening()
    {
        return $this->belongsTo(screenings::class);
    }

    /**
     * Get the user associated with the report
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the booking associated with the report
     */
    public function booking()
    {
        return $this->belongsTo(bookings::class);
    }
}
