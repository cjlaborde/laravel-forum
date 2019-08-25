<?php

namespace Tests\Feature;

use Tests\TestCase;

use Illuminate\Foundation\Testing\DatabaseMigrations;

class FavoritesTest extends TestCase
{
    use DatabaseMigrations;


    /** @test */
    function guests_can_not_favorite_anything()
    {
        $this->post('replies/1/favorites')
            ->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_can_favorite_any_reply()
    {
        $this->signIn();
        // /replies/id/favorite
        $reply = create('App\Reply');

        // If I post to a "favorite" endpoint
        $this->post('replies/' . $reply->id . '/favorites');

//        dd(\App\Favorite::all());

        // It should be recorded in the database.
        $this->assertCount(1, $reply->favorites);
    }

    /** @test */
    public function an_authenticated_user_can_unfavorite_a_reply()
    {
        $this->signIn();
        // /replies/id/favorite
        $reply = create('App\Reply');

        $reply->favorite();

        # submit delete request
        $this->delete('replies/' . $reply->id . '/favorites');

//        dd(\App\Favorite::all());

        // It should be recorded in the database.
        # fresh() reset everything
        $this->assertCount(0, $reply->fresh()->favorites);
    }

    /** @test */
    function an_authenticated_user_may_only_favorite_a_reply_once()
    {
        $this->signIn();
        // /replies/id/favorite
        $reply = create('App\Reply');

        // If I post to a "favorite" endpoint
        try {
            $this->post('replies/' . $reply->id . '/favorites');
            $this->post('replies/' . $reply->id . '/favorites');
        } catch (\Exception $e) {
            $this->fail('Did not expect to insert the same record set twice.');
        }




//        dd(\App\Favorite::all()->toArray()); array of identical so we have to fix it.

        // It should be recorded in the database.
        $this->assertCount(1, $reply->favorites);

    }
}



