<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Returns a User after sign in
     *
     * @param  \App\Models\User|null $user
     * @return \App\Models\User
     */
    protected function signIn(User $user = null)
    {
        $user = $user ?? User::factory()->createOne();

        $this->actingAs($user);

        return $user;
    }
}
