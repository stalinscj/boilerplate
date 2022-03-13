<?php

namespace Tests;

use App\Models\User;
use App\Models\Permission;
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

    /**
     * Returns a User with Permissions for routes names after sign in
     *
     * @param  array|string  $permissions
     * @param  \App\Models\User|null  $user
     * @return \App\Models\User
     */
    protected function signInWithPermissionsTo($routesNames, $user = null)
    {
        $user = $this->signIn($user);
        $permissions = Permission::whereIn('route_name', collect($routesNames))->get();

        $user->givePermissionTo($permissions);

        return $user;
    }
}
