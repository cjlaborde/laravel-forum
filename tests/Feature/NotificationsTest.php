<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;

class NotificationsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp () : void
    {
        parent::setUp();

        # we sign in in all tests

        $this->signIn();
    }

    /** @test */
    function a_notification_is_prepared_when_a_subscribed_thread_receives_a_new_reply_that_is_not_by_the_current_user()
    {
        # have a thread
       $thread = create('App\Thread')->subscribe();

//       dd($thread);

        $this->assertCount(0, auth()->user()->notifications);

//        // Then, each time a new reply is left... we should get 1 notification
        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'Some reply here'
        ]);

//        // A notification should be prepared for the user.

        $this->assertCount(0, auth()->user()->fresh()->notifications);

        # now if we have a reply from someone else.
        $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'Some reply here'
        ]);

        # then that should trigger notification
        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }

    /** @test */
    function a_user_can_fetch_their_unread_notifications()
    {
//        # created thread then subscribed user to the thread.
//        $thread = create('App\Thread')->subscribe();
//
//        $thread->addReply([
//            'user_id' => create('App\User')->id,
//            'body' => 'Some reply here'
//        ]);
        # Replaced by below Factor from UserFactory.php
        create(DatabaseNotification::class);

//        $user = auth()->user();

        //        dd($response);

        $this->assertCount(
            1,
            $this->getJson("/profiles/" . auth()->user()->name . "/notifications")->json()
        );
    }

//    /** @test */
//    function a_user_mark_a_notification_as_read()
//    {
//        create(DatabaseNotification::class);
//
//        tap(auth()->user(), function ($user) {
//
//        $this->assertCount(1, $user->unreadNotifications);
//
//            # delete notification when you read notification with the proper API request
//        $this->delete("/profiles/{$user->name}/notifications/" . $user->unreadNotifications->first()->id);
////        $this->delete("/profiles/{$user->name}/notifications/" . $user->unreadNotifications->first()->id);
//
//        $this->assertCount(0, $user->fresh()->unreadNotifications);
//        });
//
//    }
}
