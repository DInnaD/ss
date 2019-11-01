<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'last_name', 'password','phone','role','email',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function devices()
    {
        return $this->hasMany('App\Models\Device');
    }

    public function baskets()
    {
        return $this->hasMany('App\Models\Basket');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }
   public function address()
    {
        return $this->hasMany('App\Models\Address');
    }

    public function scopeGetByEmail($query, $email)
    {
        return $query->where('email', $email)->first();
    }

    public function scopeFilterRole($query, $roleName)
    {
        return $query->where('role', 'Admin')->first();
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
}
