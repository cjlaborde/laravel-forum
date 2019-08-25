<?php

namespace App;

use Illuminate\Support\Facades\Redis;

class Trending
{
    public function get()
    {
//        0, 4 only grab the top 5
//        return array_map('json_decode', Redis::zrevrange('trending_threads', 0, 4));
        return array_map('json_decode', Redis::zrevrange($this->cacheKey(), 0, 4));
    }

    public function push($thread)
    {
        # throw it at reddis with json without need to do database query.
//        Redis::zincrby('trending_threads', 1, json_encode([
        Redis::zincrby($this->cacheKey(), 1, json_encode([
            'title' => $thread->title,
            'path' => $thread->path()
        ]));
    }

    # useful so that you have different cache for testing and serve otherwise you delete all everytime you test.
    public function cacheKey()
    {
        return app()->environment('testing') ? 'testing_trending_threads' : 'trending_threads';
    }

    public function reset()
    {
        //        Redis::del('testing_trending_threads');
        Redis::del($this->cacheKey());
    }
}
