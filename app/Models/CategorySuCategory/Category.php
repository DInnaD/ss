<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Category
 *
 * @property int $id
 * @property string $title
 * @property int|null $parent_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Category|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Product[] $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Category[] $subCategories
 * @property-read int|null $sub_categories_count
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Category onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Category withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Category withoutTrashed()
 * @mixin \Eloquent
 */
class Category extends Model
{
    use SoftDeletes;

    /******* Properties *******/

    protected $fillable = [
        'title',
        'parent_id'];

    /******* Relations *******/

    public function category(): BelongsTo
    {
        return $this->belongsTo($this, 'parent_id');
    }

    public function subCategories(): HasMany
    {
        return $this->hasMany($this, 'parent_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany('App\Product');
    }
    /******* CRUD *******/    
    /******* Getters *******/
    /******* Setters *******/

    public function setCategory($id){

        $this->parent_id = $id;
        $this->save();
    }

    /******* Packages *******/
}

