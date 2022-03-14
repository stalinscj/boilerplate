<?php

namespace Tests\Feature\Admin\PermissionController;

use Tests\TestCase;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdatePermissionTest extends TestCase
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
    public function guest_cannot_update_permissions()
    {
        $this->get(route('admin.permissions.edit', rand()))->assertRedirect('login');
        $this->put(route('admin.permissions.update', rand()))->assertRedirect('login');
    }

    /**
     * @test
     */
    public function unauthorized_user_cannot_update_permissions()
    {
        $this->signIn();

        $permission = Permission::factory()->create();

        $this->get(route('admin.permissions.edit', $permission))->assertForbidden();
        $this->put(route('admin.permissions.update', $permission))->assertForbidden();
    }

    /**
     * @test
     */
    public function authorized_user_can_update_a_permission()
    {
        $user = $this->signInWithPermissionsTo(['admin.permissions.edit', 'admin.permissions.update']);

        $permission = Permission::factory()->create(['level' => $user->level]);

        $this->get(route('admin.permissions.edit', $permission))
            ->assertSuccessful()
            ->assertViewIs('admin.permissions.edit');

        $attributes = Permission::factory()->raw(['level' => $user->level]);

        $this->put(route('admin.permissions.update', $permission), $attributes)
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.permissions.index'));

        $this->assertDatabaseHas('permissions', $attributes);
    }
}
