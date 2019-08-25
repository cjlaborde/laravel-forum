<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateThreadTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        //        $this->withoutExceptionHandling();
        $this->signIn();
    }

    /** @test */
    function a_thread_requires_a_title_and_body_to_be_updated()
    {
        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        # endpoint in web.php for update thread
        $this->patch($thread->path(), [
            'title' => 'Changed'
        ])->assertSessionHasErrors('body');

        $this->patch($thread->path(), [
            'body' => 'Changed'
        ])->assertSessionHasErrors('title');
    }

    /** @test */
    function unauthorized_users_may_not_update_threads()
    {
        # create thread with other user
        $thread = create('App\Thread', ['user_id' => create('App\User')->id]);

        # then try to update the other user post with your signIn account
        # endpoint in web.php for update thread
        # title date irrelevant so leave it empty [])
        $this->patch($thread->path(), [])->assertStatus(403);
    }

    /** @test */
    function a_thread_can_be_updated_by_its_creator()
    {
        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        # endpoint in web.php for update thread
        $this->patch($thread->path(), [
            'title' => 'Changed',
            'body' => 'Changed body.'
        ]);

        tap($thread->fresh(), function ($thread) {
            $this->assertEquals('Changed', $thread->title);
            $this->assertEquals('Changed body.', $thread->body);
        });
    }
}
