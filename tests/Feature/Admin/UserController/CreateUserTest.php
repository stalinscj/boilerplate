<?php

namespace Tests\Feature\UserController;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateUserTest extends TestCase
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
    public function guest_cannot_create_users()
    {
        $this->get(route('admin.users.create'))->assertRedirect('login');
        $this->post(route('admin.users.store'))->assertRedirect('login');
    }

    /**
     * @test
     */
    public function unauthorized_user_cannot_create_users()
    {
        $this->signIn();

        $this->get(route('admin.users.create'))->assertForbidden();
        $this->post(route('admin.users.store'))->assertForbidden();
    }

    /**
     * @test
     */
    public function authorized_user_can_create_users()
    {
        $this->signInWithPermissionsTo(['admin.users.create', 'admin.users.store']);

        $this->get(route('admin.users.create'))
            ->assertSuccessful()
            ->assertViewIs('admin.users.create');

        $attributes = User::factory()->raw([
            'password'              => 'password',
            'password_confirmation' => 'password'
        ]);

        $this->post(route('admin.users.store'), $attributes)
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseCount('users', 2);

        $this->assertDatabaseHas('users', Arr::only($attributes, ['name', 'email']));

        $this->assertTrue(Hash::check($attributes['password'], User::latest('id')->first()->password));
    }

    /**
     * @test
     */
    public function authorized_user_can_create_users_with_roles()
    {
        $user = $this->signInWithPermissionsTo('admin.users.store');

        $roles = Role::factory(3)
            ->create(['level' => $user->level])
            ->pluck('id');

        $attributes = User::factory()->raw([
            'password'              => 'password',
            'password_confirmation' => 'password',
            'roles'                 => $roles->toArray(),
        ]);

        $this->post(route('admin.users.store'), $attributes)
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.users.index'));

        $rolesAssigned = User::latest('id')
            ->first()
            ->roles
            ->pluck('id');

        $this->assertEquals($roles, $rolesAssigned);
    }
}
