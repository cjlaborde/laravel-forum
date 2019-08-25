<?php
namespace Tests\Feature;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
class LockThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function non_administrators_may_not_lock_threads()
    {
        $this->withExceptionHandling();
        $this->signIn();

        # thread that belong to logged in user.
        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        // hit endpont, that will update the "locked" attribute to true for the thread.

        $this->post(route('locked-threads.store', $thread))->assertStatus(403); # if user that is not admin use this part it will show error page.

        $this->assertFalse(!! $thread->fresh()->locked);
    }

    /** @test */
    function administrators_can_lock_threads()
    {
        // login to create an admin. John = Admin in User.php isAdmin function
        $this->signIn(factory('App\User')->states('administrator')->create());

        # thread that belong to logged in user.
        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->post(route('locked-threads.store', $thread));

        $this->assertTrue($thread->fresh()->locked, 'Failed asserting that the thread was locked.');
    }

    /** @test */
    function administrators_can_unlock_threads()
    {
        // login to create an admin. John = Admin in User.php isAdmin function
        $this->signIn(factory('App\User')->states('administrator')->create());

        # thread that belong to logged in user.
        $thread = create('App\Thread', ['user_id' => auth()->id(), 'locked' => true ]);

        $this->delete(route('locked-threads.store', $thread));

        $this->assertFalse($thread->fresh()->locked, 'Failed asserting that the thread was unlocked.');
    }

    /** @test */
    public function once_locked_a_thread_may_not_receive_new_replies()
    {
//        $this->withoutExceptionHandling();
        $this->signIn();

        $thread = create('App\Thread', ['locked' => true]);

//        $thread->lock();

        # should not work if thread locked
        $this->post($thread->path() . '/replies', [
            'body' => 'Foobar',
            'user_id' => auth()->id()
        ])->assertStatus(422);
    }
}
