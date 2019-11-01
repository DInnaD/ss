<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Order extends Model
{
    protected $fillable = ['user_id','comment','address_id','payment_id'];

    public function users()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }


    public function payment()
    {
        return $this->belongsTo('App\Models\Payment','payment_id');
    }

    public function address()
    {
        return $this->belongsTo('App\Models\Address','address_id');
    }


}
