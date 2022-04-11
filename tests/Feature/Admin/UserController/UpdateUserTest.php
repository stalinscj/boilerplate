<?php

namespace Tests\Feature\UserController;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateUserTest extends TestCase
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
    public function guest_cannot_update_users()
    {
        $this->get(route('admin.users.edit', rand()))->assertRedirect('login');
        $this->put(route('admin.users.update', rand()))->assertRedirect('login');
    }

    /**
     * @test
     */
    public function unauthorized_user_cannot_update_users()
    {
        $this->signIn();

        $user = User::factory()->create();

        $this->get(route('admin.users.edit', $user))->assertForbidden();
        $this->put(route('admin.users.update', $user))->assertForbidden();
    }

    /**
     * @test
     */
    public function authorized_user_can_update_an_user()
    {
        $this->signInWithPermissionsTo(['admin.users.edit', 'admin.users.update']);

        $user = User::factory()->create();

        $this->get(route('admin.users.edit', $user))
            ->assertSuccessful()
            ->assertViewIs('admin.users.edit');

        $attributes = User::factory()->raw();

        $this->put(route('admin.users.update', $user), $attributes)
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseCount('users', 2);

        $this->assertDatabaseHas('users', Arr::only($attributes, ['name', 'email']));
    }

    /**
     * @test
     */
    public function authorized_user_can_update_the_roles_of_an_user()
    {
        $user = $this->signInWithPermissionsTo('admin.users.update')
            ->syncRoles(Role::factory()->create());

        $user = User::factory()->create();

        $roles = Role::factory(3)
            ->create(['level' => $user->level])
            ->pluck('id');

        $attributes = User::factory()
            ->raw(['roles' => $roles->toArray()]);

        $this->put(route('admin.users.update', $user), $attributes)
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.users.index'));

        $rolesAssigned = $user->roles()->pluck('id');

        $this->assertEquals($roles, $rolesAssigned);
    }
}
