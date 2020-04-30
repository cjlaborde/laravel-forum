<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // When
        'App\Events\ThreadReceivedNewReply' => [
            //Then
            'App\Listeners\NotifyMentionedUser',
            'App\Listeners\NotifySubscribers'
        ],

        //        Registered::class => [
        //            'App\Listeners\SendEmailConfirmationRequest'
        ////            SendEmailVerificationNotification::class,
        //        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
