<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Trending;

class SearchController extends Controller
{
    public function show(Trending $trending)
    {
        if (request()->expectsJson()) {
            // return threads directly
            return Thread::search(request('q'))->paginate(25);
        }
        // piggy back on the Threads index() from ThreadsController
        return view('threads.search', [
            'trending' => $trending->get()
        ]);
    }
}

/*
public function show(Trending $trending)
{
    $search = request('q');

//        return Thread::search($search)->get();
    $threads = Thread::search($search)->paginate(25);

    if (request()->expectsJson()) {
        # return threads directly
        return $threads;
    }

    # piggy back on the Threads index() from ThreadsController
    return view('threads.search', [
        'threads' => $threads,
        'trending' => $trending->get()
    ]);
}
*/
