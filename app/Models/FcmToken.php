<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class FcmToken extends Model
{
    protected $guarded = [];

    protected $table = 'fcm_tokens';

    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }
}
