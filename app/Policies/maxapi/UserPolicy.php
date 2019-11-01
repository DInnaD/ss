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

    /**
     * Determine whether the user can view the category.
     *
     * @param User $user
     * @return mixed
     */
    public function getSearchList(User $user)
    {
        if($user->role == 'admin'){

        return true;
        }
        return false;
    }


    /**
     * Determine whether the user can view the category.
     *
     * @param User $user
     * @return mixed
     */
    public function view(User $user)//history+profile+delProd in history + delPod in history all
    {

        return true;
    }

    /**
     * Determine whether the user can create categories.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isUser();
    }

    /**
     * Determine whether the user can update the category.
     *
     * @param User $user
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->isUser();
    }

    public function delete(User $user)
    {
        return $user->isAdmin();
    }
}
