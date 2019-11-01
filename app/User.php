<?php

namespace App;

use Hash;
use \Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    /** Main Consts **/
    
    public const SUPER_ADMIN = 'super_admin';
    public const ADMIN = 'admin';
    public const USER = 'user';

    //after changeRole()

    public const REGISTER = 'register';

    public const SOUTH = 'south';

    public const WORKER = 'worker';

    /** Blogs Consts **/

    const IS_BANNED = 1;
    const IS_ACTIVE = 0;


    /******* Properties *******/

    protected $fillable = [
        //'type' package parent/child
        'role',
        'last_name',
        'first_name',
        'by_father_name',
        'code_name',
        'gender',
        'birth_date',
        'birth_place',
        'nationality',
        //'nationality_custom',
        'role_status',
        'passport',
        'passport_valid_from',
        'passport_valid_to',
        'work_perm_start',
        'phone',
        //'phone_with_viber',
        'phone_with_telegram',
        'bank_account',
        'bank_account_other',
        'contragent_id',
        //'contragent_other_id',
        //'contragent_another_id',
        'email',
        'image',
        'password',
        'social_id',
        //'social_google_id',
        /** blog **/
        'is_admin',
        'ban_status'
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
        'api_token',
        'nationality',
        //'nationality_custom',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'nationality_custom' => 'string',
        'email_verified_at' => 'datetime',
        'birth_date' => 'datetime:Y-m-d',
        'passport_valid_from' => 'datetime:Y-m-d',
        'passport_valid_to' => 'datetime:Y-m-d',
        'work_perm_start' => 'datetime:Y-m-d',
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
    ];

    /** Main Consts **/
    

     /**
     * Genders string list
     *
     * @var array
     */
    public const GENDERS = [
        'male',
        'female'
    ];

    /**
     * Nationalities list
     *
     * @var array
     */
    public const NATIONALITIES = [
        'Ukraine',
        'Russia',
        'Georgia',
        'Belarus',
        'Moldavia',
    ];

    /**
     * Statuses list
     *
     * @var array
     */
    public const STATUSES = [
        'product_finished',
        'product_started',
        'product_waiting_predoc',
        'product_waiting_doc',
        'product_waiting_prepermission',
        'product_waiting_permission',
        'product_not_started',
    ];

    
    /******* Relations *******/



    /** START Main Relations **/

     /******* ExtraUser::rename Relations *******/

    public function user()
    {
        return $this->belongsTo(User::class, 'creator_id')->withDefault();
    }

    public function creator()
    {
        return $this->user();
    }

     /******* User Responsibilities Relations *******/

    public function comment(): BelongsTo
    {
        return $this->belongsTo($this, 'parent_id');
    }

    public function subComments(): HasMany
    {
        return $this->hasMany($this, 'parent_id');
    }

    public function setcomment($id){

        $this->parent_id = $id;
        $this->save();
    }

    public function like(): BelongsTo
    {
        return $this->belongsTo($this, 'parent_id');
    }

    public function subLiks(): HasMany
    {
        return $this->hasMany($this, 'parent_id');
    }

    public function setlike($id){

        $this->parent_id = $id;
        $this->save();
    }

    /** END Main Relations **/

    /** START All Relations **/

     /******* Blogs, Shops-*Maxapi, max2,  Relations *******/

    public function category(): BelongsTo
    {
        return $this->belongsTo($this, 'parent_id');
    }

    public function subCategories(): HasMany
    {
        return $this->hasMany($this, 'parent_id');
    }
    
    public function setCategory($id){

        $this->parent_id = $id;
        $this->save();
    }

    /** END All Relations **/

    /**START BLOG Relations **/

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /** END BLOG Relations **/

    /**START SHOPS Relations **/



    public function devices()
    {
        return $this->hasMany('App\Models\Device');
    }

    public function baskets()
    {
        return $this->hasMany('App\Models\Basket');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }
   public function address()
    {
        return $this->hasMany('App\Models\Address');
    }

    public function scopeGetByEmail($query, $email)
    {
        return $query->where('email', $email)->first();
    }

    public function scopeFilterRole($query, $roleName)
    {
        return $query->where('role', 'Admin')->first();
    }

    /** END SHOPS Relations **/

    /**START MAXAPI Relations **/

    /** END MAXAPI Relations **/

    /**START MAX2 Relations **/

    public function vacancies()
        {
        return $this->belongsToMany(Vacancy::class);
        }

    public function organization()
    {
        return $this->hasOne(Organization::class, 'creator_id');
    }

    /** END MAX2 Relations **/

    // public function comments(): BelongsTo
    // {
    //     return $this->hasMany('App\Comment');//not use
    
    // }
//to organization which created product + add bookunbookfunc
    // public function tags(): BelongsToMany 
    // {
    //       return $this->belongsToMany(Tag::class, 'user_tag', 'user_id');
    // }

    // public function products(): BelongsToMany
    // {
    //     return $this->belongsToMany('App\Product', 'user_product');
    // }

    /******* Packages *******/



    /** Auth **/

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }

    /******* Helpers *******/



    /** START ALL Helpers **/

    /**
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === self::SUPER_ADMIN;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ADMIN;
    }

    /**
     * @return bool
     */
    public function isUser(): bool
    {
        return $this->role === self::USER;
    }

    /**
     * @return bool
     */
    public function isRegister(): bool
    {
        return $this->role === self::REGISTER;
    }

    /**
     * @return bool
     */
    public function isSouth(): bool
    {
        return $this->role === self::SOUTH;
    }

    /**
     * @return bool
     */
    public function isWorker(): bool
    {
        return $this->role === self::WORKER;
    }

    public function changeRoleToRegister()
    {
        //if($role_status !== )

    }

    public function changeRoleToSouth()
    {

    }

    public function changeRoleToWorker()
    {

    }

    /** END ALL Helpers **/

    /**START BLOG Helpers **/

    /** END BLOG Helpers **/

    /**START SHOPS Helpers **/

    /** END SHOPS Helpers **/

    /**START MAXAPI Helpers **/

    /** END MAXAPI Helpers **/

    /**START MAX2 Helpers **/

    /** END MAX2 Helpers **/   

    

    /********* CRUD **********/

    public function createUser($data)
    {
        $user = new User();
        $user->role = $data['role'];
        $user->last_name = $data['last_name'];
        $user->first_name = $data['first_name'];
        $user->by_father_name = $data['by_father_name'];
        $user->code_name = $data['code_name'];
        $user->gender = $data['gender'];
        $user->birth_date = $data['birth_date'];
        $user->birth_place = $data['by_fbirth_place'];
        $user->nationality = $data['nationality'];
        $user->role_status = $data['role_status'];
        $user->passport = $data['passport'];
        $user->passport_valid_from = $data['passport_valid_from'];
        $user->passport_valid_to = $data['passport_valid_to'];
        $user->work_perm_start = $data['work_perm_start'];
        $user->phone = $data['phone'];
        //$user->phone_with_viber = $data['phone_with_viber'];
        $user->phone_with_talegram = $data['phone_with_talegram'];
        $user->bank_account = $data['bank_account'];
        $user->bank_account_other = $data['bank_account_other'];
        $user->contragent_id = $data['contragent_id'];
        $user->email = $data['email'];
        $user->image = $data['image'];
        if (isset($data['social_id'])) {
            $user->social_id = $data['social_id'];
            //$user->social_google_id = $data['social_google_id'];
        } else {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        
        return $user;
    }

    public function generateToken()
    {
        $this->api_token = Str::random(60);
        $this->save();
        return $this->api_token;
    }    

    /******* CRUD Admin *******/

    

    public function makeAdmin()
    {
        $this->is_admin = 1;
        $this->save();
    }

    public function makeNormal()
    {
        $this->is_admin = 0;
        $this->save();
    }

    public function toggleAdmin($value)
    {
        if($value == null)
        {
            return $this->makeNormal();
        }

        return $this->makeAdmin();
    }

    public function ban()
    {
        $this->bun_status = User::IS_BANNED;
        $this->save();
    }

    public function unban()
    {
        $this->bun_status = User::IS_ACTIVE;
        $this->save();
    }

    public function toggleBan($value)
    {
        if($value == null)
        {
            return $this->unban();
        }

        return $this->ban();
    }




    /**START ALL CRUD **/

    /** END ALL CRUD **/

    /**START BLOG CRUD **/

    public static function add($fields)
    {
        $user = new static;
        $user->fill($fields);
        $user->save();

        return $user;
    }

    public function edit($fields)
    {
        $this->fill($fields); //name,email
        
        $this->save();
    }

    public function generatePassword($password)
    {
        if($password != null)
        {
            $this->password = bcrypt($password);
            $this->save();
        }
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
        $this->avatar = $filename;
        $this->save();
    }

    public function removeAvatar()
    {
        if($this->avatar != null)
        {
            Storage::delete('uploads/' . $this->avatar);
        }
    }

    public function getImage()
    {
        if($this->avatar == null)
        {
            return '/img/no-image.png';
        }

        return '/uploads/' . $this->avatar;
    }

    public static function getSearchList(Request $request)
    {
        $search = $request->get('search');        
        $search = $search ? '%' . $search . '%' : null;
       
        return User::where('first_name', 'LIKE', '%'.$search.'%')
            ->orWhere('last_name', 'LIKE', '%'.$search.'%')
            ->orWhere('by_father_name', 'LIKE', '%'.$search.'%')->get();
    } 

    /** END BLOG CRUD **/

    /**START SHOPS CRUD **/

    /** END SHOPS CRUD **/

    /**START MAXAPI CRUD **/

    public function getImageUrl($image): string
    {//*maxapi+shop
        return url('static/avatars/'.$image);
    }

    /** END MAXAPI CRUD **/

    /**START MAX2 CRUD **/

     public static function getRoleList(Request $request)
    {
       $users = User::all();
       $super_admin = User::where('role','super_admin')->get()->count();
       $admin = User::where('role','admin')->get()->count();
       $user = User::where('role','user')->get()->count();
       $register = User::where('role','register')->get()->count();
       $south = User::where('role','south')->get()->count();
       $worker = User::where('role','worker')->get()->count();
       
        
        return $user = collect(['super_admin' =>  $super_admin, 'admin' => $admin, 'user' => $user, 'register' =>  $register, 'south' => $south, 'worker' => $worker]);
    }

    /** END MAX2 CRUD **/ 
    
    

    /******* CRUD User*******/



    /** START ALL CRUD User **/    

    /** END ALL CRUD User  **/

    /**START BLOG CRUD User  **/

    public static function getSearchCommentList(Request $request)
    {//*
        $search = $request->get('search');        
        $search = $search ? '%' . $search . '%' : null;
       
        return User::where('first_name', 'LIKE', '%'.$search.'%')
            ->orWhere('last_name', 'LIKE', '%'.$search.'%')
            ->orWhere('by_father_name', 'LIKE', '%'.$search.'%')->get();
    }

    /** END BLOG CRUD User  **/

    /**START SHOPS CRUD User  **/

    /** END SHOPS CRUD User  **/

    /**START MAXAPI CRUD User  **/

    /** END MAXAPI CRUD User  **/

    /**START MAX2 CRUD User  **/

    /** END MAX2 CRUD User  **/ 


    /******* GettersSeters *******/
    


    /** START ALL GETSET **/  

    /**
     * Accessor for Worker::FullName
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return trim($this->last_name . ' ' . $this->first_name . ' ' . $this->by_father_name);
    }

    /**
     * Accessor for Worker::Nationality as string
     *
     * @param string $value
     * @return string
     */
    public function getNationalityAttribute($value)
    {
        if (is_null($value)) {
            return $this->nationality_custom;
        } else {
            return $value;
        }
    }

    /**
     * Mutator for Worker::nationality as string
     *
     * @param string $str
     * @return void
     */
    public function setNationalityAttribute($str)
    {
        $is_standard = in_array($str, self::NATIONALITIES);
        if ($is_standard) {
            $this->attributes['nationality'] = $str;
            $this->attributes['nationality_custom'] = null;
        } else {
            $this->attributes['nationality'] = null;
            $this->attributes['nationality_custom'] = $str;
        }
    }   

    /** END ALL GETSET  **/

    /**START BLOG GETSET  **/

    /** END BLOG GETSET  **/

    /**START SHOPS GETSET  **/

    /** END SHOPS GETSET  **/

    /**START MAXAPI GETSET  **/

    /** END MAXAPI GETSET  **/

    /**START MAX2 GETSET  **/

    /** END MAX2 GETSET  **/
    /******* Helpers *******/
    /******* Relations *******/
    /******* Properties *******/
    /******* CRUD *******/    
    /******* GettersSeters *******/
    /******* ConstantsScopes *******/ 
    


    /** START ALL **/    

    /** END ALL  **/

    /**START BLOG  **/

    /** END BLOG  **/

    /**START SHOPS  **/

    /** END SHOPS  **/

    /**START MAXAPI  **/

    /** END MAXAPI  **/

    /**START MAX2  **/

    /** END MAX2  **/ 
}    
