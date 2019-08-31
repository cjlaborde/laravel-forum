<?php
namespace Tests\Feature;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
class ProfilesTest extends TestCase
{
    use DatabaseMigrations;
    /** @test */
    function a_user_has_a_profile()
    {
        $user = create('App\User');
        $this->get("/profiles/{$user->name}")
            ->assertSee($user->name);
    }
}
