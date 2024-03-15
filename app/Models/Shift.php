<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shift extends Model
{

    protected $fillable =[
        'driver_id','start_at','end_at'
    ];


    protected $casts = [
        'start_at'=>'datetime',
        'end_at'=>'datetime',
    ];

    /**
     * Display driver relationship
     *
     * @return BelongsTo
     */
    public function driver(){
        return $this->belongsTo(Driver::class,'driver_id');
    }
}
