<?php

namespace App;

use Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Str;
use App\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
/**
 * App\User
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $password
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $role
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Product[] $products
 * @property-read int|null $products_count
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\User withoutTrashed()
 * @mixin \Eloquent
 */
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use SoftDeletes;

    public const ADMIN = 'admin';
    public const USER = 'user';

    /******* Properties *******/

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'image',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'deleted_at', 
        'pivot',
        'api_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    /******* Relations *******/

    public function products(): BelongsToMany
    {
        return $this->belongsToMany('App\Product', 'user_product');
    }

    /******* Packages *******/

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }


    /**
     * @return bool
     */
    public function isAdmin(): bool//    "message": "Maximum function nesting level of '256' reached, aborting!",
    {
        return $this->isAdmin()
            || $this->role === self::ADMIN;
    }

    /**
     * @return bool
     */
    public function isUser(): bool
    {
        return $this->isAdmin()
            || $this->role === self::USER;
    }

    /******* CRUD *******/

    public function getImageUrl($image): string
    {
        return url('static/avatars/'.$image);
    }

    public static function add($fields)
    {
        $user = new static;
        $user->fill($fields);
        $user->save();

        return $user;
    }

    public function edit($fields)
    {
        $this->fill($fields);

        $this->save();
    }

    public function generatePassword($password)
    {
        if($password != null)
        {
            $this->password = Hash::make($password);
            $this->save();
        }
    }
    public function generateToken()
    {
        $this->api_token = Str::random(60);
        $this->save();
        return $this->api_token;
    }

    public function remove()
    {
        $this->removeAvatar();
        $this->delete();
    }

    public function uploadAvatar($image)
    {
        if($image == null) { return; }

        $this->removeAvatar();

        $filename = str_random(10) . '.' . $image->extension();
        $image->storeAs('uploads', $filename);
        $this->image = $filename;
        $this->save();
    }

    public function removeAvatar()
    {
        if($this->image != null)
        {
            Storage::delete('uploads/' . $this->avatar);
        }
    }

    public function getImage()
    {
        if($this->image == null)
        {
            return '/img/no-image.png';
        }

        return '/uploads/' . $this->image;
    }


    /******* CRUD *******/
     
    public static function getSearchList(Request $request)
    {
        $search = $request->get('search');        
        $search = $search ? '%' . $search . '%' : null;
       
        return User::where('first_name', 'LIKE', '%'.$search.'%')
            ->orWhere('last_name', 'LIKE', '%'.$search.'%')->get();
    }   
    /******* Relations *******/
    /******* Properties *******/
    /******* CRUD *******/
    /******* Getters *******/
    /******* Setters *******/
    /******* Packages *******/
}
