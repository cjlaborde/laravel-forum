<?php

namespace Tests\Feature;

use App\Reputation;
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
        $this->assertEquals(Reputation::THREAD_WAS_PUBLISHED, $thread->creator->reputation);
        // $this->assertEquals(10, $thread->creator->reputation);

    }

    /** @test */
    public function a_user_lose_points_when_they_delete_a_thread()
    {
        # sig in phpstorm will write $this->signIn();
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        # grab user associated with that thread.
        $this->assertEquals(Reputation::THREAD_WAS_PUBLISHED, $thread->creator->reputation);
        // $this->assertEquals(10, $thread->creator->reputation);

        $this->delete($thread->path());
        # should lose 10 points for deleting thread.

        $this->assertEquals(0, $thread->creator->fresh()->reputation);
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

        $this->assertEquals(Reputation::REPLY_POSTED, $reply->owner->reputation);
    }

    /** @test */
    public function a_user_lose_points_when_their_reply_to_a_thread_is_deleted()
    {
        $this->signIn();

        # given we have tread
        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $this->assertEquals(Reputation::REPLY_POSTED, $reply->owner->reputation);

        $this->delete("/replies/{$reply->id}");
//
//        $this->assertEquals(0, $reply->owner->fresh()->reputation);
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

        $total = Reputation::REPLY_POSTED + Reputation::BEST_REPLY_AWARDED;

        $this->assertEquals($total, $reply->owner->reputation);
    }

    /** @test */
    public function a_user_earns_points_when_their_reply_is_favorited()
    {
        $this->signIn();
        # given we have tread
        $thread = create('App\Thread');

        # add reply so gets 2 points here as well
        $reply = $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'Some reply.'
        ]);

        # make sure user that added best reply gets reputation

        $this->post("/replies/{$reply->id}/favorites");

        $total = Reputation::REPLY_POSTED + Reputation::REPLY_FAVORITED;

        $this->assertEquals($total, $reply->owner->fresh()->reputation);
    }

    /** @test */
    public function a_user_loses_points_when_their_reply_is_unfavorited()
    {
        $this->signIn();

        # make sure user that added best reply gets reputation
       $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $this->post("/replies/{$reply->id}/favorites");

        $total = Reputation::REPLY_POSTED + Reputation::REPLY_FAVORITED;

        $this->assertEquals($total, $reply->owner->fresh()->reputation);

        $this->delete("/replies/{$reply->id}/favorites");

        $total = Reputation::REPLY_POSTED + Reputation::REPLY_FAVORITED - Reputation::REPLY_FAVORITED;

        $this->assertEquals($total, $reply->owner->fresh()->reputation);
    }
}
