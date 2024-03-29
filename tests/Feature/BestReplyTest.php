<?php

namespace Tests\Feature;

use Tests\TestCase;

use Illuminate\Foundation\Testing\DatabaseMigrations;

class BestReplyTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_thread_creator_may_mark_any_reply_as_the_best_reply()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        # create thread and associate it with signed in user.
        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $replies = create('App\Reply', ['thread_id' => $thread->id], 2);

        # by default not the best one
        $this->assertFalse($replies[1]->isBest());

        # which reply should be mark as best reply give me the second reply id ($replies[1]->id) mark is as best reply
        $this->postJson(route('best-replies.store', [$replies[1]->id]));

//        dd($replies[1]->fresh()->isBest());

        $this->assertTrue($replies[1]->fresh()->isBest());
    }

    /** @test */
    public function only_the_thread_creator_may_mark_a_reply_as_best()
    {
        $this->withExceptionHandling();
        $this->signIn();
        $thread = create(\App\Thread::class, ['user_id' => auth()->id()]);
        $replies = create(\App\Reply::class, ['thread_id' => $thread->id], 2);
        $this->signIn(create(\App\User::class));
        $this->postJson(route('best-replies.store', [$replies[1]->id]))->assertStatus(403);
        $this->assertFalse($replies[1]->fresh()->isBest());
    }


    /** @test */
    function if_a_best_reply_is_deleted_then_the_thread_is_properly_updated_to_reflect_that()
    {
        $this->signIn();

        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $reply->thread->markBestReply($reply);

        // When we delete the reply.
        $this->deleteJson(route('replies.destroy', $reply->id));

        // Then the thread should be updated.
        $this->assertNull($reply->thread->fresh()->best_reply_id);
    }



}
