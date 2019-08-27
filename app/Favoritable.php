<?php

namespace App;

trait Favoritable
{
    protected static function bootFavoritable()
    {
        static::deleting(function ($model) {
            $model->favorites->each->delete();
        });
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    public function favorite()
    {
        $attributes = ['user_id' => auth()->id()];
        // if we don't find any matches in database then we use next command to apply new one.
        if (! $this->favorites()->where($attributes)->exists()) {
//            dd('hit');
            Reputation::award(auth()->user(), Reputation::REPLY_FAVORITED);
//            dd(auth()->user()->fresh()->reputation);
            return $this->favorites()->create($attributes);
        }
    }

    public function unfavorite()
    {
        $attributes = ['user_id' => auth()->id()];

        // SQL query find all favorites that match these attributes and delete them.
//        $this->favorites()->where($attributes)->delete();

        // Get me a collection of favorites, filter over that collections and for each one going to delete the favorite
        //
        $this->favorites()->where($attributes)->get()->each->delete();

        Reputation::reduce(auth()->user(), Reputation::REPLY_FAVORITED);
    }

    public function isFavorited()
    {
        return (bool) $this->favorites->where('user_id', auth()->id())->count();
    }

    public function getIsFavoritedAttribute() // $reply->isFavorited
    {
        return $this->isFavorited();
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }
}
