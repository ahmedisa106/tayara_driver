<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $table = 'providers';
    protected $guarded = [];

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }
}
