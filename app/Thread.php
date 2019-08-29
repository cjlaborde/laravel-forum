<?php

namespace App;

use App\Events\ThreadReceivedNewReply;
use App\Filters\ThreadFilters;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Thread extends Model
{
    use RecordsActivity, Searchable;

    protected $guarded = [];

    protected $with = ['creator', 'channel'];

    # appends to the Json in threadscontroller.php
//    protected $appends = ['isSubscribedTo'];

    protected $casts = [
        'locked' => 'boolean',
        'pinned' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();

        # since we store it as column no longer needed here.
//        # globalScore is query automatically applied to all queries
//        static::addGlobalScope('replyCount', function ($builder) {
//            $builder->withCount('replies');
//        });

        # delete also associated replies when you delete thread.
        # for each reply in a thread I want to delete reply.
        static::deleting(function ($thread) {
            $thread->replies->each->delete();
//           $thread->replies->each(function ($reply) {
//                $reply->delete();
//           });
            Reputation::reduce($thread->creator, Reputation::THREAD_WAS_PUBLISHED);
        });

        # when thread created will immediately create the slug do it on created event.
        static::created(function ($thread) {
            $thread->update(['slug' => $thread->title]);

            Reputation::award($thread->creator, Reputation::THREAD_WAS_PUBLISHED);

            // $thread->creator->increment('reputation', Reputation::THREAD_WAS_PUBLISHED);
        });

    }

    public function path()
    {
//        return '/threads/' . $this->channel()->slug . '/' . $this->id;
        return "/threads/{$this->channel->slug}/{$this->slug}";
    }

    public function replies()
    {
        # shows in Json in (ThreadsController@show) every reply with favorites_count
//        return $this->hasMany(Reply::class)->withCount('favorites');
        return $this->hasMany(Reply::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class)->withoutGlobalScope('active');
    }

    public function addReply($reply)
    {
        # 1) Make the core to send a reply.
        $reply = $this->replies()->create($reply);

        # 2) Make an announcement.
        event(new ThreadReceivedNewReply($reply));

        return $reply;
    }

//    public function lock()
//    {
//        $this->update(['locked' => true]);
//    }
//
//    public function unlock()
//    {
//        $this->update(['locked' => false]);
//    }

    public function scopeFilter($query, ThreadFilters $filters)
    {
        return $filters->apply($query);
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            # use userId if it provided otherwise fallback to checking the authenticated user.
            'user_id' => $userId ?: auth()->id()
        ]);

        return $this;

    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();
    }

    public function subscriptions()
    {
        # what is our relationship here
        return $this->hasMany(ThreadSubscription::class);
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists(); # check if record exist
    }

    public function hasUpdatesFor($user)
    {
        $key = $user->visitedThreadCacheKey($this);

        return $this->updated_at > cache($key);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    # remove dangerous code like <script> with executable code.
    public function getBodyAttribute($body)
    {
//        return $body;
        return \Purify::clean($body);
    }

    public function setSlugAttribute($value)
    {

        # if slug exists then create new slug.
        if (static::whereSlug($slug = str_slug($value))->exists()) {
            $slug = "{$slug}-{$this->id}";
        }
//        #mutator
//        # check to see if slug is already in database
//        if (static::whereSlug($slug = str_slug($value))->exists()) {
//            #if so increment it foo-title ---> foo-title-2
//            $slug = $this->incrementSlug($slug);
//        }
//        var_dump($slug);
//
//        # otherwise just post it

        $this->attributes['slug'] = $slug;
    }

    public function markBestReply(Reply $reply)
    {
        $this->update(['best_reply_id' => $reply->id]);
        # replaced by above
//        $this->best_reply_id = $reply->id;
//        $this->save();

# award creator of the actual reply not the person giving best reply
        // $reply->owner->increment('reputation', 50);
        Reputation::award($reply->owner, Reputation::BEST_REPLY_AWARDED);

    }

    # generate->overwrite this method
    public function toSearchableArray()
    {
//        return ['title' => $this->title];
        # keep all results in algolia but also add a path method from app/Thread
        # remember to use php artisan scout:import "App\Thread" to update json results
        return $this->toArray() + ['path' => $this->path()];
//            + ['avatar_path' => $user->getAvatarPathAttribute()];
    }
}









//    protected function incrementSlug($slug, $count = 2)
//    {
//        $original = $slug; // foo-title-2
//        # store original slug then increment it.
//
//        # if slug exists then create new slug.
//        while (static::whereSlug($slug)->exists()) {
//            $slug = "{$original}-" . $count++;
//        }
//
//        return $slug;
//    }





/*
public function incrementSlug($slug)
{
//        \Thread::whereTitle('Help Me')->latest('id')->value('slug');

    # find all records in database that have this title. We will find the most recent id which will be the highest id
    $max = static::whereTitle($this->title)->latest('id')->value('slug'); // foo-title-5 foo-title

    # [-1] Example ------->    'laracasts'[-1] => "s"
    # /(\d+)$/ look for number at end of string example help-2
    if (is_numeric($max[-1])) {
        return preg_replace_callback('/(\d+)$/', function ($matches) {
            // $matches[1]
            # replace foo-title-5 with foo-title-6
            return $matches[1] + 1;
        }, $max);
    }

    # if end not numberic then turn foo-title -> foo title-2
    return "{$slug}-2";
}
*/
//    public function hasUpdatesFor($user = null)
//    {
//        $user = $user ?: auth()->user();
//
//        # Look in the cache for the proper key.
//        # compare that carbon instance with the $thread->updated_at
//        # if updated_at is more than what I have in cache. It means a reply has been left since last time visitor used this page.
//        # Cache key for redis cache
//        # users.50.visits.1 === key below.
//        $key = $user->visitedThreadCacheKey($this);
//        return $this->updated_at > cache($key);
//    }


/*
 * Used for redis

    public function visits()
    {
        return new Visits($this);
    }
 */

//}
