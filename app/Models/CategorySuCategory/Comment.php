<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Comment
 *
 * @property int $id
 * @property string|null $comment
 * @property int|null $rating
 * @property float|null $price
 * @property int $user_id
 * @property int $product_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Comment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Comment withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Comment withoutTrashed()
 * @mixin \Eloquent
 */
class Comment extends Model
{
    use SoftDeletes;

    /******* Properties *******/

    protected $fillable = [
        'price',
        'comment',
        'rating',
        'user_id',
        'product_id'
    ];

    //protected $touches = ['user'];//bind timemetka to owner update at 

    public static function boot()
    {
        parent::boot();

        static::creating(function(self $model)
        {
            if(\Auth::id()){
                $model->user_id = \Auth::id();
            }
        });
    }

    /******* Relations *******/

    public function user(): BelongsTo
    {
        return $this->belongsTo('App\User');
    }

    public function product(): HasMany
    {
        return $this->belongsTo('App\Product');
    }
    /******* CRUD *******/    
    /******* Setters *******/

    public function setUser($id){

        $this->user_id = $id;
        $this->save();
    }

    public function setProduct($id){

        $this->product_id = $id;
        $this->save();
    }
    /******* Setters *******/
    /******* Packages *******/
}

