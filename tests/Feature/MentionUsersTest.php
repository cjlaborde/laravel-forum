<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MentionUsersTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function mentioned_users_in_a_reply_notified()
    {
        $this->withoutExceptionHandling();
        // Given I have a user. JohnDoe, who is signed in.
        $john = create('App\User', ['username' => 'JohnDoe']);
        $this->signIn($john);
        // And another use, JaneDoe.
        $jane = create('App\User', ['username' => 'JaneDoe']);
        $this->signIn($jane);


        //If we have a thread
        $thread = create('App\Thread');

        // And JohnDoe replies and mentions @JaneDoe
        $reply = make('App\Reply', [
            'body' => 'Hey @JaneDoe look at this out. @whodoesnotexist'
        ]);

        $this->json('post', $thread->path() . '/replies', $reply->toArray());

        // Then, JaneDoe should be notified.
        $this->assertCount(1, $jane->notifications);
    }
//
//    /** @test */
//    public function mentioned_users_in_a_reply_are_notified()
//    {
//        $this->withoutExceptionHandling();
//        // Given we have a user, JohnDoe, who is signed in.
//        $john = create('App\User', ['username' => 'JohnDoe']);
//        $this->signIn($john);
//        // And we also have a user, JaneDoe.
//        $jane = create('App\User', ['username' => 'JaneDoe']);
//        // If we have a thread
//        $thread = create('App\Thread');
//        // And JohnDoe replies to that thread and mentions @JaneDoe.
//        $reply = make('App\Reply', [
//            'body' => 'Hey @JaneDoe check this out.'
//        ]);
//        $this->json('post', $thread->path() . '/replies', $reply->toArray());
//        // Then @JaneDoe should receive a notification.
//        $this->assertCount(1, $jane->notifications);
//        $this->assertEquals(
//            "JohnDoe mentioned you in \"{$thread->title}\"",
//            $jane->notifications->first()->data['message']
//        );
//    }

    /** @test */
    function it_can_fetch_all_mentioned_users_starting_with_the_given_characters()
    {
        create('App\User', ['username' => 'johndoe']);
        create('App\User', ['username' => 'johndoe2']);
        create('App\User', ['username' => 'janedoe']);
        $results = $this->json('GET', '/api/users', ['username' => 'john']);
        $this->assertCount(2, $results->json());
    }

}
