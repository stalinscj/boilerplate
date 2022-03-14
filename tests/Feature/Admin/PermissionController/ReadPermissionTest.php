<?php

namespace Tests\Feature\Admin\PermissionController;

use Tests\TestCase;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadPermissionTest extends TestCase
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
    public function guest_cannot_read_permissions()
    {
        $this->get(route('admin.permissions.index'))->assertRedirect('login');
        $this->get(route('admin.permissions.show', rand()))->assertRedirect('login');
    }

    /**
     * @test
     */
    public function unauthorized_user_cannot_read_permissions()
    {
        $this->signIn();

        $permission = Permission::factory()->create();

        $this->get(route('admin.permissions.index'))->assertForbidden();
        $this->get(route('admin.permissions.show', $permission))->assertForbidden();
    }

    /**
     * @test
     */
    public function authorized_user_can_read_the_permission_list()
    {
        $user = $this->signInWithPermissionsTo(['admin.permissions.index']);

        $permissions = Permission::factory(5)->create(['level' => $user->level]);

        $this->get(route('admin.permissions.index'))
            ->assertSuccessful()
            ->assertViewIs('admin.permissions.index');

        $response = $this->getJson(
                route('admin.permissions.index', ['start' => 0, 'length' => 5]),
                ['X-Requested-With' => 'XMLHttpRequest']
            )
            ->assertOk();

        foreach ($permissions as $index => $permission) {
            $this->assertEquals($permission->name,       $response->json("data.$index.name"));
            $this->assertEquals($permission->route_name, $response->json("data.$index.route_name"));
            $this->assertEquals($permission->level,      $response->json("data.$index.level"));
            $this->assertEquals($permission->group,      $response->json("data.$index.group"));
        }
    }

    /**
     * @test
     */
    public function authorized_user_can_read_the_details_of_a_permission()
    {
        $this->signInWithPermissionsTo(['admin.permissions.show']);

        $permission = Permission::factory()->create();

        $this->get(route('admin.permissions.show', $permission))
            ->assertSuccessful()
            ->assertViewIs('admin.permissions.show')
            ->assertSeeText($permission->name)
            ->assertSeeText($permission->route_name)
            ->assertSeeText($permission->level)
            ->assertSeeText($permission->group);
    }
}
