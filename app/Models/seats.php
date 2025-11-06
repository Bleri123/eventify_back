<?php

namespace App\Models;

use App\Models\showrooms;
use App\Models\tickets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class seats extends Model
{
    protected $fillable = [
        'showroom_id',
        'row_label',
        'seat_number',
        'is_active',
    ];

    public function showroom() : BelongsTo
    {
        return $this->belongsTo(showrooms::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(tickets::class);
    }
}
