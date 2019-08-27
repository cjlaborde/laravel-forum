<?php

namespace App\Policies;

use App\User;
use App\Thread;
use Illuminate\Auth\Access\HandlesAuthorization;

// need to active in App/Providers/AuthServiceProvider to work.
class ThreadPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the thread.
     *
     * @param  \App\User  $user
     * @param  \App\Thread  $thread
     * @return mixed
     */

    // Are you allowed to update?
    public function update(User $user, Thread $thread)
    {
//        checks if those 2 match up you are authorized
        return $thread->user_id == $user->id;
    }
}
