<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends Model
{

    protected $fillable = [
        'driver_id', 'start_at', 'end_at'
    ];


    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_in_shift' => "boolean"
    ];

    /**
     * Display driver relationship
     *
     * @return BelongsTo
     */
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    /**
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'shift_id');
    }
}
