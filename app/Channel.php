<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $guarded = [];

    public function getRouteKeyName()
    {
        // we want laravel to use slug instead of primary id key so we override getRouteKeyName
        return 'slug';
    }

    public function threads()
    {
        return $this->hasMany(Thread::class);
    }
}
