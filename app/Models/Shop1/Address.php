<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'address',
        'city',
        'ground_floor',
        'floor',
        'lift',
        'is_main',
        'user_id'

    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function order()
    {
        return $this->hasMany('App\Models\Order');
    }



}
