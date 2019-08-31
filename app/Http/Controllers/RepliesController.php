<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Notifications\YouWereMentioned;
use App\Reply;
use App\Thread;
use App\User;

use Illuminate\Support\Facades\Gate;

class RepliesController extends Controller
{
    /**
     * RepliesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }
    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(config('forum.pagination.perPage'));
    }

    /**
     * @param integer $channelId
     * @param Thread $thread
     * @param CreatePostRequest $form
     * @return  \Illuminate\Http\RedirectResponse
     */
    public function store($channelId, Thread $thread, CreatePostRequest $form)
    {
        if ($thread->locked) {
            return response('Thread is locked', 422);
        }
            # 3 add the reply
            return $thread->addReply([
                'body' => request('body'),
                'user_id' => auth()->id()
            ])->load('owner');
    }
    /**
     * @param Reply $reply
     * @param Spam $spam
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Reply $reply)
    {
        # do not allow not signed in users to update a reply.
        $this->authorize('update', $reply);

        request()->validate(['body' => 'required|spamfree']);

        $reply->update(request(['body']));
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

//        if ($reply->user_id != auth()->id()) {
//            return response([], 403);
//        }

        $reply->delete();

        if (request()->expectsJson()) {
            return response(['status' => 'Reply deleted']);
        }
        return back();
    }
}
