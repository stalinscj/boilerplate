<?php

namespace Tests\Feature\Admin\RoleController;

use Tests\TestCase;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateRoleTest extends TestCase
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
    public function guest_cannot_update_roles()
    {
        $this->get(route('admin.roles.edit', rand()))->assertRedirect('login');
        $this->put(route('admin.roles.update', rand()))->assertRedirect('login');
    }

    /**
     * @test
     */
    public function unauthorized_user_cannot_update_roles()
    {
        $this->signIn();

        $role = Role::factory()->create();

        $this->get(route('admin.roles.edit', $role))->assertForbidden();
        $this->put(route('admin.roles.update', $role))->assertForbidden();
    }

    /**
     * @test
     */
    public function authorized_user_can_update_a_role()
    {
        $user = $this->signInWithPermissionsTo(['admin.roles.edit', 'admin.roles.update']);

        $role = Role::factory()->create(['level' => $user->level]);

        $this->get(route('admin.roles.edit', $role))
            ->assertSuccessful()
            ->assertViewIs('admin.roles.edit');

        $attributes = Role::factory()->raw(['level' => $user->level]);

        $this->put(route('admin.roles.update', $role), $attributes)
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.roles.index'));

        $this->assertDatabaseHas('roles', $attributes);
    }
}
