<?php 

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    } 

    public function before($user, $ability)
    {
        if ($ability !== 'create'){
            if ($user->role === 'admin') {
                return true;
            }
        }
    }   

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function index(User $user)
    {
        if($user->role == 'admin'){
            return true;
        }
        return false;
    }

    public function indexStats(User $user)
    {
        if($user->role == 'admin'){
            return true;
        }
        return false;
    }

    public function show(User $user)
    {
        return $user->id === \Auth::user()->id;
    }
    
    /** 
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $current_user
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->id === \Auth::user()->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $current_user
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->id === \Auth::user()->id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function restore(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function forceDelete(User $user, User $model)
    {
        //
    }
}
