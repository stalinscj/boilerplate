<?php

namespace Tests\Unit\Http\Responsables;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use App\Http\Responses\Admin\DashboardResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardResponseTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function should_get_the_users_count()
    {
        $usersCount = rand(1, 5);

        User::factory($usersCount)->create();

        $dashboard = new DashboardResponse();

        $this->assertEquals($usersCount, $dashboard->getUsersCount());
    }

    /**
     * @test
     */
    public function should_get_the_roles_count()
    {
        $rolesCount = rand(1, 5);

        Role::factory($rolesCount)->create();

        $dashboard = new DashboardResponse();

        $this->assertEquals($rolesCount, $dashboard->getRolesCount());
    }

    /**
     * @test
     */
    public function should_get_the_permissions_count()
    {
        $permissionsCount = rand(1, 5);

        Permission::factory($permissionsCount)->create();

        $dashboard = new DashboardResponse();

        $this->assertEquals($permissionsCount, $dashboard->getPermissionsCount());
    }
}
