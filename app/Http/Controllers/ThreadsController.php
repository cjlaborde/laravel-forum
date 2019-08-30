<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Inspections\Spam;
use App\Rules\Recaptcha;
use App\Thread;
use App\Filters\ThreadFilters;
use Illuminate\Validation\Rule;
use App\Trending;
use Illuminate\Http\Request;


class ThreadsController extends Controller
{
    /**
     * Create a new ThreadsController instance.
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Channel $channel
     * @param ThreadFilters $filters
     * @param Trending $trending
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel, ThreadFilters $filters)
    {
        $threads = $this->getThreads($channel, $filters);

        if (request()->wantsJson()) {
            return $threads;
        }

//        $trending->get();


//        return view('threads.index', compact('threads', 'trending'));
        return view('threads.index', [
            'threads' => $threads,
            'channel' => $channel
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create', [
            'channels' => Channel::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Recaptcha $recaptcha
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Recaptcha $recaptcha)
    {
        # see the Json response when creating thread used for debugging
//        dd(request()->all());


//        dd(auth()->user()->confirmed); // null to fix go to create_user_table and add confirmed boolean

        # if not confirmed you need to redirect

//        if (! auth()->user()->confirmed) {
//            return redirect('/threads')->with('flash', 'You must first confirm your email address.');
//        }

//        dd(request()->all());
        # SpamFree comes from App\Rules\SpamFree.php

//        dd($request->all());

        #  1 Validate
        request()->validate([
            'title' => 'required|spamfree',
            'body' => 'required|spamfree',
//            'channel_id' => 'required|exists:channels,id',
            'channel_id' => [
              'required',
              Rule::exists('channels', 'id')->where(function ($query) {
                  $query->where('archived', false);
              })
            ],
//            'g-recaptcha-response' => ['required', $recaptcha]
        ]);

//        dd('where never even get to this point. this used for debugging');
        # Create
        $thread = Thread::create([
            'user_id' => auth()->id(),
            'channel_id' => request('channel_id'),
            'title' => request('title'),
            'body' => request('body')
        ]);

        if (request()->wantsJson()) {
            return response($thread, 201);
        }
        # Redirect
        return redirect($thread->path())
            ->with('flash', 'Your thread has been published!');
    }

    /**
     * Display the specified resource.
     *
     * @param $channel
     * @param \App\Thread $thread
     * @param Trending $trending
     * @return \Illuminate\Http\Response
     */

    public function show($channel, Thread $thread, Trending $trending)
    {
        # Return json
//        return $thread;

        if (auth()->check()) {
            auth()->user()->read($thread);
        }

        $trending->push($thread);

//        $thread->visits()->record(); # redis
        $thread->increment('visits');

        return view('threads.show', compact('thread'));
    }
//     * Display the specified resource.
//     *
//     * @param  integer     $channelId
//     * @param  \App\Thread $thread
//     * @return \Illuminate\Http\Response
//     */
//    public function show($channel, Thread $thread)
//    {
////        return $thread; #show Json on page
////        dd($thread->isSubscribedTo); # true or false if subscribed
//
////        return $thread;
//
//        # appends to the Json remember to add it to thread
////        return $thread->append('isSubscribedTo');
//
//        #show Json on page
////        return $thread->load('replies'); # show replies as well
////        return Thread::withCount('replies')->first(); # returns replies_count as well
//
//        # get JSOn all replies with owners favorites as well.
////        return $thread->load('replies.favorites')->load('replies.owner');
//        # show json with only replies
////        return $thread->replies;
//
////        return $thread->replies();
//
//        // Record that the user visited this page.
//        $key = sprintf("users.%s.visits.%s", auth()->id(), $thread->id);
//
//        # store new key in the cache using key and current time.
//        cache()->forever($key, Carbon::now());
//
//        // record a timestamp when they visited page.
//
//
//
//        return view('threads.show', compact('thread'));
//    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread $thread
     * @return \Illuminate\Http\Response
     */

    #Accept Channel and Thread
    public function update($channel, Thread $thread)
    {
        // authorization
        $this->authorize('update', $thread);

        // validation
        $thread->update(request()->validate([
            'title' => 'required|spamfree',
            'body' => 'required|spamfree'
        ]));

        return $thread;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy($channel, Thread $thread)
    {
        # update corresponse to the function at Policies update

        # 1) Authorize Request
        $this->authorize('update', $thread);

        # if the thread user_id is not same as the logged in user abort.
//        if ($thread->user_id != auth()->id()) {
//            abort(403, 'You do not have permission to do this.');
//        }

        # 2) Delete
        $thread->delete();

        # 3) Return a response.
        if (request()->wantsJson()) {
            return response([], 204);
        }

        return redirect('/threads');

    }



    /**
     * @param Channel $channel
     * @param ThreadFilters $filters
     * @return mixed
     */
    private function getThreads(Channel $channel, ThreadFilters $filters)
    {
//        $threads = Thread::latest()->filter($filters);

        $threads = Thread::orderBy('pinned', 'DESC')
            ->latest()
            ->filter($filters);
        # it did not replace the default latests that why popularity filter from ThreadFilters Do not work. so remove latests
//        $threads = Thread::filter($filters);

        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }

//        dd($threads->toSql()); # output the SQL that been constructed. # http://localhost:8000/threads?popular=1

//        return $threads->get();
        return $threads->paginate(25);
    }

}
