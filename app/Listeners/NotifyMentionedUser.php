<?php

namespace App\Listeners;

use App\Events\ThreadReceivedNewReply;
use App\Notifications\YouWereMentioned;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyMentionedUser
{
    public function handle(ThreadReceivedNewReply $event)
    {
        User::whereIn('username', $event->reply->mentionedUsers())
            ->get()
           ->each(function ($user) use ($event) {
               $user->notify(new YouWereMentioned($event->reply));
           });
    }

    /*

    public function handle(ThreadReceivedNewReply $event)
    {
//        dd('caught'); # check if method been caught by unit test.
        // Inspect the body of the reply for username mentions.
        # use regular expresion to find @usernameHere
        # preg_match will find all users while preg_match only the first one found.
//        preg_match_all('/\@([^\s\ ]+)/', $event->reply->body,$matches);
        //            dd($matches);
        // And then for each mentioned user, notify them.

        # 1) collect array of mentioned users.
        collect($event->reply->mentionedUsers())
        //#====================================================================
    All this Replaced by ->get()   !!!!!!!!!!!!
    //#====================================================================
            # 2) map over that array
            ->map(function ($name) {
                # 3) return user instance by performing a database query.
                return User::where('name', $name)->first();
            })
            # 4) filter out any null responses
            ->filter()
            # 5) for each actual users we notify them
        //#====================================================================
            ->each(function ($user) use ($event) {
                $user->notify(new YouWereMentioned($event->reply));
            });
    }
    */
}
