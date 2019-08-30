<?php

namespace App\Providers;

use App\Trending;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @param Trending $trending
     * @return void
     */
    public function boot(Trending $trending)
    {
//        \View::composer('*', function($view)
//        {
//            $channels = \Cache::rememberForever('channel', function () {
//                return Channel::all();
//            });
        ////            var_dump('querying');
//            $view->with('channels', $channels);
//        });

        \Schema::defaultStringLength(191);

        \Validator::extend('spamfree', 'App\Rules\SpamFree@passes');
    }
}

//\View::share('channels', Channel::all());

//    public function boot()
//    {
//
////        \View::composer('threads.create', function($view)
//        # * means share variable with all views.
//        \View::composer('*', function($view)
//        {
//            # with this if we switch back to the view can replace App\Channel::all() To $channels
//            $view->with('channels', Channel::all());
//        });
//    }
//}
