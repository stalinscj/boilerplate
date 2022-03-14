<?php

namespace Tests\Feature\Admin\RoleController;

use Tests\TestCase;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateRoleTest extends TestCase
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
    public function guest_cannot_create_roles()
    {
        $this->get(route('admin.roles.create'))->assertRedirect('login');
        $this->post(route('admin.roles.store'))->assertRedirect('login');
    }

    /**
     * @test
     */
    public function unauthorized_user_cannot_create_roles()
    {
        $this->signIn();

        $this->get(route('admin.roles.create'))->assertForbidden();
        $this->post(route('admin.roles.store'))->assertForbidden();
    }

    /**
     * @test
     */
    public function authorized_user_can_create_a_role()
    {
        $user = $this->signInWithPermissionsTo(['admin.roles.create', 'admin.roles.store']);

        $this->get(route('admin.roles.create'))
            ->assertSuccessful()
            ->assertViewIs('admin.roles.create');

        $attributes = Role::factory()->raw(['level' => $user->level]);

        $this->post(route('admin.roles.store'), $attributes)
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.roles.index'));

        $this->assertDatabaseCount('roles', 1);

        $this->assertDatabaseHas('roles', $attributes);
    }
}
