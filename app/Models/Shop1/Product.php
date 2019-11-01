<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
class Product extends Model
{
protected $fillable = [
  'image',
    'description',
    'price',
    'name',
    'category_id',
];

    public function categories()
    {
        return $this->belongsTo('App\Models\Category','category_id');
    }

    public function baskets()
    {
        return $this->hasMany('App\Models\Basket','product_id');
    }

    public function getImageAttribute($value)
    {
        $getPath = Config::get('constants.image_folder.product_photos.get_path');
        return url($getPath . $value);
    }
}
