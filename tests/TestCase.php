<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function signIn($user = null)
    {
        $user = $user ?: create('App\User');

        $this->actingAs($user);

        return $this;
    }

    protected function signInAdmin($admin = null)
    {
        $admin = $admin ?: create('App\User');

        config(['forum.administrators' => [ $admin->email ]]);

        $this->actingAs($admin);

        return $this;
    }

}
