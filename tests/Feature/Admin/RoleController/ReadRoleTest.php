<?php

namespace Tests\Feature\Admin\RoleController;

use Tests\TestCase;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadRoleTest extends TestCase
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
    public function guest_cannot_read_roles()
    {
        $this->get(route('admin.roles.index'))->assertRedirect('login');
        $this->get(route('admin.roles.show', rand()))->assertRedirect('login');
    }

    /**
     * @test
     */
    public function unauthorized_user_cannot_read_roles()
    {
        $this->signIn();

        $role = Role::factory()->create();

        $this->get(route('admin.roles.index'))->assertForbidden();
        $this->get(route('admin.roles.show', $role))->assertForbidden();
    }

    /**
     * @test
     */
    public function authorized_user_can_read_the_role_list()
    {
        $user = $this->signInWithPermissionsTo(['admin.roles.index']);

        $roles = Role::factory(5)->create(['level' => $user->level]);

        $response = $this->get(route('admin.roles.index'))
            ->assertSuccessful()
            ->assertViewIs('admin.roles.index');

        foreach ($roles as $role) {
            $response->assertSeeText($role->name)
                ->assertSeeText($role->level);
        }
    }

    /**
     * @test
     */
    public function authorized_user_can_read_the_details_of_a_role()
    {
        $this->signInWithPermissionsTo(['admin.roles.show']);

        $role = Role::factory()->create();

        $this->get(route('admin.roles.show', $role))
            ->assertSuccessful()
            ->assertViewIs('admin.roles.show')
            ->assertSeeText($role->name)
            ->assertSeeText($role->level);
    }
}
