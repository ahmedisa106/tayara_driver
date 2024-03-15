<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Model
{
    use  Notifiable;

    protected $table = 'admins';
    protected $guarded= [];

}
