<?php

namespace App\Policies;

use App\Reply;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

// remember to Register Policy in AuthServiceProvider
// need to active in App/Providers/AuthServiceProvider to work.
class ReplyPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Reply $reply)
    {
        return $reply->user_id == $user->id;
    }

    public function create(User $user)
    {
        if (! $lastReply = $user->fresh()->lastReply) {
            return true;
        }

        // if no last reply then return true there is no problem.
        return ! $lastReply->wasJustPublished();
    }
}
