<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

// need to active in App/Providers/AuthServiceProvider to work.
class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the given profile.
     *
     * @param  \App\User  $signedInUser
     * @param  \App\User  $user
     * @return bool
     */
    public function update(User $signedInUser, User $user)
    {
//        return if id the same
        return $signedInUser->id === $user->id;
    }
}
