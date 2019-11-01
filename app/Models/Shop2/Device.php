<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
class Device extends Model
{
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function subscribe($user, $param){
        $deviceId = $param['device_id'];
        try{
            $this->token = $deviceId;
            $this->user()->associate($user);
            $this->save();
        } catch (\Exception $e){
            return $e->getMessage();
        }
        return response()->json(['success' => true, 'message' => Config::get('constants.errors.successSubscribe')]);
    }

    public function scopeGetByToken($query, $token){
        return $query->where('token', $token)->first();
    }
}
