<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use App\Category;
use App\Comment;

/**
 * App\Product
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int|null $weight
 * @property string|null $image_1
 * @property string|null $image_2
 * @property string $barcode
 * @property int $category_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Category $category
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read \App\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @property-read int|null $users_count
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Product onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereImage1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereImage2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereWeight($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Product withoutTrashed()
 * @mixin \Eloquent
 */
class Product extends Model 
{
    use SoftDeletes;

    /******* Properties *******/

    protected $fillable = [
        'name',
        'description',
        'weight',
        'image_1',
        'image_2',
        'barcode',
        'category_id',
        'user_id'
    ];

    //protected $touches = ['user', 'category'];//bind timemetka to owner update at 

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

    public function category(): BelongsTo
    {
        return $this->belongsTo('App\Category');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo('App\User');//???????????????????????
    }

    public function comments(): HasMany
    {
        return $this->hasMany('App\Comment');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany('App\User', 'user_product');
    }

    /******* CRUD *******/ 

    public function getImageUrl($image): string
    {
        return url('static/product/'.$image);
    }
   
    /******* Getters *******/
    /******* Setters *******/

    public function setCategory($id){

        $this->category_id = $id;
        $this->save();
    }
    public function setUser($id){

        $this->user_id = $id;
        $this->save();
    }

    /******* Packages *******/
}

