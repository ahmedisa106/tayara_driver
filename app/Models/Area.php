<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Area extends Model
{

    protected $table = 'areas';

    protected $fillable = [
        'government_id', 'city_id', 'name', 'delivery_price'
    ];

    /**
     * @return BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }// end of city function


}
