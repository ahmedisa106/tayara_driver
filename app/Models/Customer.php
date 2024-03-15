<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Notifications\Notifiable;

class Customer extends Model
{
    use Notifiable;
    protected $table = 'customers';
    protected $guarded = [];

    /**
     * @return MorphOne
     */
    public function notifiable(): MorphOne
    {
        return $this->MorphOne(FcmToken::class, 'notifiable');
    }// end of model function
}
