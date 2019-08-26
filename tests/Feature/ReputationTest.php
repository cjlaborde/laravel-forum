<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReputationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_earns_points_when_they_create_a_thread()
    {
        $thread = create('App\Thread');


        # grab user associated with that thread.
        $this->assertEquals(10, $thread->creator->reputation);


    }

    /** @test */
    public function a_user_earns_points_when_they_reply_to_a_thread()
    {
        # given we have tread
        $thread = create('App\Thread');

        # add reply
        $reply = $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'Here is a reply.'
        ]);

        $this->assertEquals(2, $reply->owner->reputation);
    }

    /** @test */
    public function a_user_earns_points_when_their_reply_is_market_as_best()
    {
        # given we have tread
        $thread = create('App\Thread');

        # add reply so gets 2 points here as well
        $reply = $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'Here is a reply.'
        ]);

        # make sure user that added best reply gets reputation

        $thread->markBestReply($reply);

        $this->assertEquals(52, $reply->owner->reputation);
    }
}
