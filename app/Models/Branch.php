<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Branch extends Model
{
    protected $table = 'branches';
    protected $guarded = [];
    protected $hidden = ['password'];

    public function provider()
    {
        return $this->belongsTo(Provider::class,'provider_id');
    }
}
