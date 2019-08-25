<?php

namespace Tests\Unit;

use App\Notifications\ThreadWasUpdated;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function foo\func;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    public function setUp():void
    {
        parent::setUp();
        # create thread for each test
        $this->thread = create('App\Thread');
    }

    /** @test */
    function a_thread_has_a_path()
    {
        # give we have a thread
        $thread = create('App\Thread');

//        $this->assertEquals('/threads/' . $thread->channel->slug . '/' . $thread->id, $thread->path());
        $this->assertEquals(
            "/threads/{$thread->channel->slug}/{$thread->slug}", $thread->path()
        );
    }


    /**
     * A basic unit test example.
     *
     * @return void
     */
    /** @test */
    function a_thread_has_a_creator()
    {
        $this->assertInstanceOf('App\User', $this->thread->creator);
    }
    /** @test */
    public function a_thread_has_replies()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }


    /** @test */
    public function a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    /** @test */
    function a_thread_notifies_all_registered_subscribers_when_a_reply_is_added()
    {
//        # class for purpose of testing
        # fake the notification
        Notification::fake();

        $this->signIn()
            ->thread
            ->subscribe()
            ->addReply([
                'body' => 'Foobar',
                'user_id' => 1
        ]);

        # make sure notification was sent.
        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
    }

    /** @test */
    function a_thread_belongs_to_a_channel()
    {
       $thread = create('App\Thread');

        $this->assertInstanceOf('App\Channel', $thread->channel);
    }

    /** @test */
    function a_thread_can_be_subscribed_to()
    {
        // Given we have a thread
        $thread = create('App\Thread');

        // When the user subscribes to the thread.
        # give user id of 1
        $thread->subscribe($userId =  1);

        // Then we should be able to fetch all threads that the user has subscribed to
        $this->assertEquals(
            1,
            $thread->subscriptions()->where('user_id', $userId)->count()
        );
    }

    /** @test */
    function a_thread_can_be_unsubscribed_from()
    {
        // Given we have a thread
        $thread = create('App\Thread');

        // And a user who is subscribed to the thread.
        $thread->subscribe($userId =  1);

        $thread->unsubscribe($userId);

        $this->assertCount(0, $thread->subscriptions);

    }

    /** @test */
    function it_knows_if_the_authenticated_user_is_subscribed_to_it()
    {
        // Given we have a thread
        $thread = create('App\Thread');

        // And a user who is subscribed to the thread.
        $this->signIn();

        $this->assertFalse($thread->isSubscribedTo);

        $thread->subscribe();

        $this->assertTrue($thread->isSubscribedTo);

    }

    /** @test */
    function a_thread_can_check_if_the_authenticated_user_has_read_all_replies()
    {
        $this->signIn();
        $thread = create('App\Thread');
        # Tap basically adds auth()->user To all inside the parentesis
        tap(auth()->user(), function ($user) use ($thread) {
            $this->assertTrue($thread->hasUpdatesFor($user));
            $user->read($thread);
            // Simulate that the user visited the thread.
            $this->assertFalse($thread->hasUpdatesFor($user));
        });

        # simulate that the user is redis
        // Record that the user visited this page.
//        $key = sprintf("users.%s.visits.%s", auth()->id(), $thread->id);
    }

    /** @test */
    function a_thread_body_is_sanitized_automatically()
    {
        $thread = make('App\Thread', ['body' => '<script>alert("bad")</script><p>This is okay.</p>']);
//        dd($thread->body);

        $this->assertEquals("<p>This is okay.</p>", $thread->body);
    }
}

    /*
     * Only use when using Redis


    /** @test *    /
    function a_thread_records_each_visits()
    {
        $thread = make('App\Thread', ['id' => 1]);

//        Redis::del("threads.{$thread->id}.visits");

//        $thread->resetVisits(); refractored to class bellow
        $thread->visits()->reset();

        $this->assertSame(0, $thread->visits()->count()); // incr 100 to 101

        $thread->visits()->record();

        $this->assertEquals(1, $thread->visits()->count());// incr 100 to 101
//
//        $thread->recordVisit();
//
//        $this->assertEquals( 2, $thread->visits()); // 100
    }
    */

