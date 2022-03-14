<?php

namespace Tests\Feature\Admin\PermissionController;

use Tests\TestCase;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeletePermissionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Total permissions created by seeder
     *
     * @var int
     */
    protected $totalBasePermissions;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->seed(['PermissionSeeder']);

        $this->totalBasePermissions = Permission::count();
    }

    /**
     * @test
     */
    public function guest_cannot_delete_permissions()
    {
        $this->delete(route('admin.permissions.destroy', rand()))->assertRedirect('login');
        $this->delete(route('admin.permissions.clean'))->assertRedirect('login');
    }

    /**
     * @test
     */
    public function unauthorized_user_cannot_delete_permissions()
    {
        $this->signIn();

        $permission = Permission::factory()->create();

        $this->delete(route('admin.permissions.destroy', $permission))->assertForbidden();
        $this->delete(route('admin.permissions.clean'))->assertForbidden();
    }

    /**
     * @test
     */
    public function authorized_user_can_delete_a_permission()
    {
        $user = $this->signInWithPermissionsTo(['admin.permissions.destroy']);

        $permission = Permission::factory()->create(['level' => $user->level]);

        $this->delete(route('admin.permissions.destroy', $permission))
            ->assertRedirect(route('admin.permissions.index'));

        $this->assertDatabaseCount('permissions', $this->totalBasePermissions);

        $this->assertDatabaseMissing('permissions', $permission->toArray());
    }

    /**
     * @test
     */
    public function authorized_user_can_clean_permissions()
    {
        $this->signInWithPermissionsTo(['admin.permissions.clean']);

        Permission::factory(10)->create();

        $this->assertNotEquals(0, Permission::getUnnecessaryPermissions()->count());

        $this->delete(route('admin.permissions.clean'))
            ->assertRedirect(route('admin.permissions.index'));

        $this->assertEquals(0, Permission::getUnnecessaryPermissions()->count());

        $this->assertDatabaseCount('permissions', $this->totalBasePermissions);
    }
}
