<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'title'
    ];

    public function order()
    {
        return $this->hasMany('App\Models\Order');
    }
}
