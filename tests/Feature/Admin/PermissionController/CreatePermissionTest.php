<?php

namespace Tests\Feature\Admin\PermissionController;

use Tests\TestCase;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreatePermissionTest extends TestCase
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
    public function guest_cannot_create_permissions()
    {
        $this->get(route('admin.permissions.create'))->assertRedirect('login');
        $this->post(route('admin.permissions.store'))->assertRedirect('login');
    }

    /**
     * @test
     */
    public function unauthorized_user_cannot_create_permissions()
    {
        $this->signIn();

        $this->get(route('admin.permissions.create'))->assertForbidden();
        $this->post(route('admin.permissions.store'))->assertForbidden();
    }

    /**
     * @test
     */
    public function authorized_user_can_create_a_permission()
    {
        $user = $this->signInWithPermissionsTo(['admin.permissions.create', 'admin.permissions.store']);

        $this->get(route('admin.permissions.create'))
            ->assertSuccessful()
            ->assertViewIs('admin.permissions.create');

        $attributes = Permission::factory()->raw(['level' => $user->level]);

        $this->post(route('admin.permissions.store'), $attributes)
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.permissions.index'));

        $this->assertDatabaseCount('permissions', $this->totalBasePermissions + 1);

        $this->assertDatabaseHas('permissions', $attributes);
    }
}
