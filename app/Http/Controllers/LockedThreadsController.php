<?php

namespace App\Http\Controllers;

use App\Thread;

class LockedThreadsController extends Controller
{
    public function store(Thread $thread)
    {
        $thread->update(['locked' => true]);
//        $thread->lock();
    }

    public function destroy(Thread $thread)
    {
        $thread->update(['locked' => false]);
//        $thread->unlock();
    }
}
