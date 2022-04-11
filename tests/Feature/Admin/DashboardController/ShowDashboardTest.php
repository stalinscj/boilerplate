<?php

namespace Tests\Feature\Admin\DashboardController;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowDashboardTest extends TestCase
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
    public function a_guest_cannot_access_to_dashboard()
    {
        $this->get(route('admin.dashboard'))->assertRedirect('login');
    }

    /**
     * @test
     */
    public function an_unauthorized_user_cannot_access_to_dashboard()
    {
        $this->signIn();

        $this->get(route('admin.dashboard'))->assertForbidden();
    }

    /**
     * @test
     */
    public function authorized_user_can_see_the_dashboard()
    {
        $this->signInWithPermissionsTo('admin.dashboard');

        $usersCount       = rand(2, 5);
        $rolesCount       = rand(1, 5);
        $permissionsCount = Permission::count();

        User::factory($usersCount - 1)->create();
        Role::factory($rolesCount)->create();

        $this->get(route('admin.dashboard'))
            ->assertSuccessful()
            ->assertViewIs('admin.dashboard.index')
            ->assertSeeTextInOrder([
                $usersCount,
                'Usuarios registrados',
                $rolesCount,
                'Roles registrados',
                $permissionsCount,
                'Permisos registrados',
            ]);
    }
}
