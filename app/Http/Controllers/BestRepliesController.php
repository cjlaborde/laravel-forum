<?php

namespace App\Http\Controllers;

use App\Reply;
use Illuminate\Http\Request;

class BestRepliesController extends Controller
{
    public function store(Reply $reply)
    {
//        abort_if($reply->thread->user_id !== auth()->id(), 401);
        # using policies from ThreadPolicy.php
        $this->authorize('update', $reply->thread);

//        $reply->thread->update(['best_reply_id' => $reply->id]);
        $reply->thread->markBestReply($reply);
    }
}
