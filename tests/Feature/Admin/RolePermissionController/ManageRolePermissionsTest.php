<?php

namespace Tests\Feature\Admin\RolePermissionController;

use Tests\TestCase;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageRolePermissionsTest extends TestCase
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
    public function guest_cannot_manage_role_permissions()
    {
        $this->get(route('admin.roles.permissions.create', rand()))->assertRedirect('login');
        $this->post(route('admin.roles.permissions.store', rand()))->assertRedirect('login');
    }

    /**
     * @test
     */
    public function unauthorized_user_cannot_manage_role_permissions()
    {
        $this->signIn();

        $role = Role::factory()->create();

        $this->get(route('admin.roles.permissions.create', $role))->assertForbidden();
        $this->post(route('admin.roles.permissions.store', $role))->assertForbidden();
    }

    /**
     * @test
     */
    public function authorized_user_can_manage_role_permissions()
    {
        $user = $this->signInWithPermissionsTo(['admin.roles.permissions.create', 'admin.roles.permissions.store']);

        $role = Role::factory()->create(['level' => $user->level]);
        $permissions = Permission::factory(3)->create(['level' => $user->level]);

        $role->givePermissionTo($permissions->first());

        $this->get(route('admin.roles.permissions.create', $role))
            ->assertSuccessful()
            ->assertViewIs('admin.roles_permissions.create');

        $attributes = [
            'permissions' => $permissions->take(2)->pluck('id')
        ];

        $this->post(route('admin.roles.permissions.store', $role), $attributes)
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.roles.index'));

        $this->assertDatabaseCount('role_has_permissions', 2);

        foreach ($permissions->take(2) as $permission) {
            $this->assertDatabaseHas('role_has_permissions',
                [
                    'role_id'       => $role->id,
                    'permission_id' => $permission->id,
                ]
            );
        }
    }

    /**
     * @test
     */
    public function authorized_user_cannot_assign_permissions_with_levels_lower_than_his_own()
    {
        $user = $this->signInWithPermissionsTo(['admin.roles.permissions.store']);

        $role = Role::factory()->create(['level' => $user->level]);
        $permission = Permission::factory()->create(['level' => 0]);

        $attributes = [
            'permissions' => [$permission->id]
        ];

        $this->post(route('admin.roles.permissions.store', $role), $attributes);

        $this->assertDatabaseCount('role_has_permissions', 0);
    }
}
