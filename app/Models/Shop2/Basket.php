<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'total_price'
    ];

    public function users()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function products()
    {
        return $this->belongsTo('App\Models\Product','product_id');
    }
}
