<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Permission;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->seed(['PermissionSeeder']);
    }

    /**
     * @test
     */
    public function can_return_unnecesary_permissions()
    {
        Route::name('private.route')
            ->get('private-route', fn() => true)
            ->middleware('access');

        Route::name('public-route', fn() => true)
            ->get('public.route');

        Permission::factory()
            ->create(['route_name' => 'private.route']);

        $unnecessaryPermission = Permission::factory()
            ->create(['route_name' => 'public.route']);

        $unnecessaryPermissions = Permission::getUnnecessaryPermissions();

        $this->assertIsIterable($unnecessaryPermissions);
        $this->assertCount(1, $unnecessaryPermissions);
        $this->assertTrue($unnecessaryPermission->is($unnecessaryPermissions->first()));
    }

    /**
     * @test
     */
    public function can_returns_all_private_routes_without_any_permission()
    {
        Route::name('private.route')
            ->get('private-route', fn() => true)
            ->middleware('access');

        Route::name('public-route', fn() => true)
            ->get('public.route');

        $privateRoutesWithoutPermission = Permission::getPrivateRoutesWithoutPermission();

        $this->assertIsIterable($privateRoutesWithoutPermission);
        $this->assertCount(1, $privateRoutesWithoutPermission);
        $this->assertEquals('private.route', $privateRoutesWithoutPermission->first()->getName());
    }

    /**
     * @test
     */
    public function can_returns_the_total_permissions_notifications()
    {
        Route::name('private.route1')
            ->get('private-route1', fn() => true)
            ->middleware('access');

        Route::name('private.route2')
            ->get('private-route2', fn() => true)
            ->middleware('access');

        Route::name('public-route1', fn() => true)
            ->get('public.route1');

        Route::name('public-route2', fn() => true)
            ->get('public.route2');

        Permission::factory(2)
            ->sequence(
                ['route_name' => 'private.route1'],
                ['route_name' => 'public.route1']
            )
            ->create();

        $this->assertEquals(2, Permission::getTotalPermissionsNotifications());
    }

    /**
     * @test
     * */
    public function can_convert_permissions_to_collection()
    {
        $permissions = Permission::factory(3)->create();

        $mix = [
            intval($permissions[0]->id),
            $permissions[1]->name,
            $permissions[2],
            null,
            ''
        ];

        $collection = Permission::convertPermissionsToCollection(...$mix);

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertCount(3, $collection);
        $this->assertTrue($permissions[0]->is($collection[0]));
        $this->assertTrue($permissions[1]->is($collection[1]));
        $this->assertTrue($permissions[2]->is($collection[2]));
    }
}
