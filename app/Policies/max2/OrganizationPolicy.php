<?php

namespace App\Policies;

use Auth;
use App\User;
use App\Vacancy;
use App\Organization;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Request;

class OrganizationPolicy
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
        if($user->role == 'admin' ||  $user->role == 'worker'){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function indexStats(User $user)
    {
        if($user->role == 'admin'){

            return true;
        }

        return false;
    }

    public function show(User $user)
    {
        if ($user->role === 'employer' && $user->id === $user->organization->creator_id){

            return true;

        } else {

            return false;
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if($user->role == 'employer' && $user->id === $user->organization->creator_id){
            return $user->organization->id > 0;
        }
    }

    /** 
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function update(User $user)
    {
        if($user->role == 'employer' && $user->id === $user->organization->creator_id){
        
        return true;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function delete(User $user)
    {
        
        if($user->role == 'employer' && $user->id === $user->organization->creator_id){
        
            return true;
        }
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
