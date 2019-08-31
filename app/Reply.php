<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favoritable, RecordsActivity;
    # used to project from error
    # dd [body] to fillable property to allow mass assignment
    protected $guarded = [];

//    We want to use this relationship for every single query.
//Now every query for a reply will automatically fetch the favorites
    protected $with = ['owner', 'favorites'];

//    append custom attributes to the json
    protected $appends = ['favoritesCount', 'isFavorited', 'isBest', 'xp'];

    protected static function boot()
    {
        parent::boot();
        # we increment replies count every time a reply is created on thread.
        static::created(function ($reply) {
            $reply->thread->increment('replies_count');

            // $reply->owner->increment('reputation', 2);
            Reputation::award($reply->owner, Reputation::REPLY_POSTED);
        });
        # when we delete we remove 1 from the count.
        static::deleted(function ($reply) {
            # at the application level but we going to do it on database level instead
//            if ($reply->isBest()) {
//                $reply->thread->update(['best_reply_id' => null]);
//            }
//            dd('here'); check if test is hitting this
//            dd($reply->thread->replies_count);   # 1
            $reply->thread->decrement('replies_count');

            Reputation::reduce($reply->owner, Reputation::REPLY_POSTED);
//            dd($reply->thread->replies_count);  # 0
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function wasJustPublished()
    {
        return $this->created_at->gt(Carbon::now()->subMinute());
    }

    public function mentionedUsers()
    {
        preg_match_all('/@([\w\-]+)/', $this->body, $matches);

        # return matches minus the @ symbol
        return $matches[1];
    }

    public function path()
    {
        $perPage = config('forum.pagination.perPage'); // we want  2 per page

        // Determine the position of reply
//        dd($this->thread->replies()->pluck('id')->search($this->id));

        # we have to account for 0 index which would be +1
        $replyPosition = $this->thread->replies()->pluck('id')->search($this->id) + 1; // position is 5

        // ceil to make 2 /5 = 2.5 ceil push it to page 3
        // ceil($replyPosition / $perPage); // should be 3 / 1 = 3

//        dd($replyPosition);

        $page = ceil($replyPosition / $perPage);

        return $this->thread->path() . "?page={$page}#reply-{$this->id}";
    }

    public function setBodyAttribute($body)
    {
        #   https://regexr.com/
        # 0 = give me everything in match '/@([^\s]+)/'  #------> @JaneDoe
        # $1 = give only what is between brackets ([^\s]+) exclude @ #------> JaneDoe
//        $this->attributes['body'] = preg_replace('/@([^\s\.]+)/', '<a href="/profiles/$1">$0</a>', $body); // Hey @JaneDoe
        $this->attributes['body'] = preg_replace(
            '/@([\w\-]+)/',
            '<a href="/profiles/$1">$0</a>',
            $body
        );
    }

    public function isBest()
    {
        return $this->thread->best_reply_id == $this->id;
    }

    public function getIsBestAttribute()
    {
        return $this->isBest();
    }

    public function getXpAttribute()
    {
        $xp = config('forum.reputation.reply_posted');
        if ($this->isBest()) {
            $xp += config('forum.reputation.best_reply_awarded');
        }
        return $xp += $this->favorites()->count() * config('forum.reputation.reply_favorited');
    }
    public function getBodyAttribute($body)
    {
//        return $body;
        return \Purify::clean($body);
    }
}
