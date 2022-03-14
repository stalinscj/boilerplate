<?php

namespace Tests\Feature\Admin\RoleController;

use Tests\TestCase;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteRoleTest extends TestCase
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
    public function guest_cannot_delete_roles()
    {
        $this->delete(route('admin.roles.destroy', rand()))->assertRedirect('login');
    }

    /**
     * @test
     */
    public function unauthorized_user_cannot_delete_roles()
    {
        $this->signIn();

        $role = Role::factory()->create();

        $this->delete(route('admin.roles.destroy', $role))->assertForbidden();
    }

    /**
     * @test
     */
    public function authorized_user_can_delete_a_role()
    {
        $user = $this->signInWithPermissionsTo(['admin.roles.destroy']);

        $role = Role::factory()->create(['level' => $user->level]);

        $this->delete(route('admin.roles.destroy', $role))
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.roles.index'));

        $this->assertDatabaseCount('roles', 0);
    }
}
