<?php

namespace Tests\Unit\Http\Middleware;

use Tests\TestCase;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AccessMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function users_without_permissions_can_access_to_routes_that_no_needs_permissions()
    {
        Route::get('unprotected-route', fn() => true);

        $this->get('unprotected-route')
            ->assertStatus(200);

        $this->signIn();

        $this->get('unprotected-route')
            ->assertStatus(200);
    }

    /**
     * @test
     */
    public function users_without_permissions_cannot_access_to_routes_that_needs_permissions()
    {
        Route::get('protected-route', fn() => true)
            ->middleware('access');

        $this->get('protected-route')
            ->assertStatus(403);

        $this->signIn();

        $this->get('protected-route')
            ->assertStatus(403);
    }

    /**
     * @test
     */
    public function users_with_permissions_can_access_to_routes_that_needs_permissions()
    {
        Route::get('protected-route', fn() => true)
            ->name('protected-route')
            ->middleware('access');

        $permission = Permission::factory()->createOne(['route_name' => 'protected-route']);

        $user = User::factory()->createOne()
            ->givePermissionTo($permission);

        $this->actingAs($user)
            ->get('protected-route')
            ->assertStatus(200);
    }
}
