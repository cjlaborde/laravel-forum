<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReplyTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function it_has_an_owner()
    {
//        $reply = factory('App\Reply')->create();
        $reply = create('App\Reply');

        $this->assertInstanceOf('App\User', $reply->owner);
    }

    /** @test */
    function it_knows_if_it_was_just_published()
    {
        $reply = create('App\Reply');

        $this->assertTrue($reply->wasJustPublished());

        $reply->created_at = Carbon::now()->subMonth();

        $this->assertFalse($reply->wasJustPublished());

    }

    /** @test */
    function it_can_detect_all_mentioned_users_in_the_body()
    {
        $reply = new \App\Reply([
            'body' => '@JaneDoe wants to talk to @JohnDoe'
        ]);

        $this->assertEquals(['JaneDoe', 'JohnDoe'], $reply->mentionedUsers());
    }

    /** @test */
    function it_wraps_mentioned_usernames_in_the_body_within_anchor_tags()
    {
        $reply = new \App\Reply([
            'body' => 'Hello @Jane-Doe.'
        ]);

        $this->assertEquals(
            'Hello <a href="/profiles/Jane-Doe">@Jane-Doe</a>.',
            $reply->body
        );
    }

    /** @test */
    function it_knows_if_it_is_the_best_reply()
    {
        $reply = create('App\Reply');

        $this->assertFalse($reply->isBest());

        $reply->thread->update(['best_reply_id' => $reply->id]);

        $this->assertTrue($reply->fresh()->isBest());
    }

    /** @test */
    function a_reply_body_is_sanitized_automatically()
    {
        $reply = make('App\Reply', ['body' => '<script>alert("bad")</script><p>This is okay.</p>']);
//        dd($reply->body);

        $this->assertEquals("<p>This is okay.</p>", $reply->body);
    }


    /** @test */
    function a_reply_knows_the_total_xp_earned()
    {
        $this->signIn();
        $reply = create('App\Reply'); // 2 points for creating the reply.
        $this->assertEquals(2, $reply->xp);
        $reply->thread->markBestReply($reply); // 50 points for best.
        $this->assertEquals(52, $reply->xp);
        $this->post(route('replies.favorite', $reply)); // 5 points for favoriting.
        $this->assertEquals(57, $reply->xp);
    }

    /** @test */
    public function it_generates_the_correct_path_for_a_paginated_thread()
    {
        // Given we have a thread
        $thread = create('App\Thread');

        // And that thread has three-replies
        $replies = create('App\Reply', ['thread_id' => $thread->id], 3);

        // And we are paginating 1 per page
        config(['forum.pagination.perPage' => 2]);

        // If we generate the path for the last reply (3rd one)
//        dd($replies->last()->path());

        // It should include ?page=3 in the path.
        $this->assertEquals(
            $thread->path() . '?page=1#reply-1',
            $replies->first()->path()
        );

        $this->assertEquals(
            $thread->path() . '?page=1#reply-2',
            $replies[1]->path()
        );

        $this->assertEquals(
            $thread->path() . '?page=2#reply-3',
            $replies->last()->path()
        );
    }
}
