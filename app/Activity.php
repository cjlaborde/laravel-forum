<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $guarded = [];

    protected $appends = ['favoritedModel'];

    public function getFavoritedModelAttribute()
    {
        $favoritedModel = null;
        if ($this->subject_type === Favorite::class) {
            $subject = $this->subject()->firstOrFail();
            if ($subject->favorited_type == Reply::class) {
                $favoritedModel = Reply::find($subject->favorited_id);
            }
        }
        return $favoritedModel;
    }


    public function subject()
    {
        # figures out what the appropiate relationship is for that activity
        return $this->morphTo();
    }

    public static function feed($user, $take = 50)
    {
        return static::where('user_id', $user->id)
            ->latest()
            ->with('subject')
            ->take($take)
            ->get()
            ->groupBy(function ($activity) {
            return $activity->created_at->format('Y-m-d');
        });
    }

    /**
     * Fetch an activity feed for the given user.
     *
     * @param  User $user
     * @param  int  $take
     *
     * @return \Illuminate\Database\Eloquent\Collection;
     */
    public static function paginatedFeed($user)
    {
        return static::where('user_id', $user->id)
            ->latest()
            ->with('subject');
    }
}


