<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'drivers';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'status',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    public function shifts()
    {
        return $this->hasMany(Shift::class, 'driver_id');
    }

    public function currentShift()
    {
        return $this->hasOne(Shift::class, 'driver_id')->whereNull('end_at')->latest()->first();
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'driver_id')
            ->where('shift_id', auth()->user()->currentShift()?->id)
            ->whereNotNull('shift_id');
    }
}
