<?php

namespace Tests\Feature\UserController;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ValidateUserFieldsTest extends TestCase
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
    public function the_name_field_is_required_to_create_and_update_an_user()
    {
        $this->signInWithPermissionsTo(['admin.users.store', 'admin.users.update']);

        $attributes = User::factory()->raw(['name' => '']);

        $this->post(route('admin.users.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'name',
            trans('validation.required', ['attribute' => 'name'])
        );

        $this->assertDatabaseCount('users', 1);

        session()->forget('errors');

        $user = User::factory()->create();

        $this->put(route('admin.users.update', $user), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'name',
            trans('validation.required', ['attribute' => 'name'])
        );
    }

    /**
     * @test
     */
    public function the_name_field_must_be_a_string_to_create_and_update_an_user()
    {
        $this->signInWithPermissionsTo(['admin.users.store', 'admin.users.update']);

        $attributes = User::factory()->raw(['name' => 123456]);

        $this->post(route('admin.users.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'name',
            trans('validation.string', ['attribute' => 'name'])
        );

        $this->assertDatabaseCount('users', 1);

        session()->forget('errors');

        $user = User::factory()->create();

        $this->put(route('admin.users.update', $user), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'name',
            trans('validation.string', ['attribute' => 'name'])
        );
    }

    /**
     * @test
     */
    public function the_name_field_must_not_be_greater_than_60_chars_to_create_and_update_an_user()
    {
        $this->signInWithPermissionsTo(['admin.users.store', 'admin.users.update']);

        $attributes = User::factory()->raw(['name' => Str::random(61)]);

        $this->post(route('admin.users.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'name',
            trans('validation.max.string', ['attribute' => 'name', 'max' => 60])
        );

        $this->assertDatabaseCount('users', 1);

        session()->forget('errors');

        $user = User::factory()->create();

        $this->put(route('admin.users.update', $user), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'name',
            trans('validation.max.string', ['attribute' => 'name', 'max' => 60])
        );
    }

    /**
     * @test
     */
    public function the_email_field_is_required_to_create_and_update_an_user()
    {
        $this->signInWithPermissionsTo(['admin.users.store', 'admin.users.update']);

        $attributes = User::factory()->raw(['email' => '']);

        $this->post(route('admin.users.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'email',
            trans('validation.required', ['attribute' => 'email'])
        );

        $this->assertDatabaseCount('users', 1);

        session()->forget('errors');

        $user = User::factory()->create();

        $this->put(route('admin.users.update', $user), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'email',
            trans('validation.required', ['attribute' => 'email'])
        );
    }

    /**
     * @test
     */
    public function the_email_field_must_be_a_string_to_create_and_update_an_user()
    {
        $this->signInWithPermissionsTo(['admin.users.store', 'admin.users.update']);

        $attributes = User::factory()->raw(['email' => 123456]);

        $this->post(route('admin.users.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'email',
            trans('validation.string', ['attribute' => 'email'])
        );

        $this->assertDatabaseCount('users', 1);

        session()->forget('errors');

        $user = User::factory()->create();

        $this->put(route('admin.users.update', $user), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'email',
            trans('validation.string', ['attribute' => 'email'])
        );
    }

    /**
     * @test
     */
    public function the_email_field_must_not_be_greater_than_100_chars_to_create_and_update_an_user()
    {
        $this->signInWithPermissionsTo(['admin.users.store', 'admin.users.update']);

        $attributes = User::factory()->raw(['email' => Str::random(101)]);

        $this->post(route('admin.users.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'email',
            trans('validation.max.string', ['attribute' => 'email', 'max' => 100]),
            1
        );

        $this->assertDatabaseCount('users', 1);

        session()->forget('errors');

        $user = User::factory()->create();

        $this->put(route('admin.users.update', $user), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'email',
            trans('validation.max.string', ['attribute' => 'email', 'max' => 100]),
            1
        );
    }

    /**
     * @test
     */
    public function the_email_field_must_be_a_valid_email_to_create_and_update_an_user()
    {
        $this->signInWithPermissionsTo(['admin.users.store', 'admin.users.update']);

        $attributes = User::factory()->raw(['email' => 'invalid@email']);

        $this->post(route('admin.users.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'email',
            trans('validation.email', ['attribute' => 'email'])
        );

        $this->assertDatabaseCount('users', 1);

        session()->forget('errors');

        $user = User::factory()->create();

        $this->put(route('admin.users.update', $user), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'email',
            trans('validation.email', ['attribute' => 'email'])
        );
    }

    /**
     * @test
     */
    public function the_email_field_must_be_unique_to_create_and_update_an_user()
    {
        $user = $this->signInWithPermissionsTo(['admin.users.store', 'admin.users.update']);

        $attributes = User::factory()->raw(['email' => $user->email]);

        $this->post(route('admin.users.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'email',
            trans('validation.unique', ['attribute' => 'email'])
        );

        $this->assertDatabaseCount('users', 1);

        session()->forget('errors');

        $user = User::factory()->create();

        $this->put(route('admin.users.update', $user), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'email',
            trans('validation.unique', ['attribute' => 'email'])
        );
    }

    /**
     * @test
     */
    public function the_password_field_is_required_to_create_an_user()
    {
        $this->signInWithPermissionsTo(['admin.users.store']);

        $attributes = User::factory()->raw(['password' => '']);

        $this->post(route('admin.users.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'password',
            trans('validation.required', ['attribute' => 'password'])
        );

        $this->assertDatabaseCount('users', 1);
    }

    /**
     * @test
     */
    public function the_password_field_is_required_to_update_an_user_only_if_update_password_is_sent()
    {
        $this->signInWithPermissionsTo('admin.users.update');

        $user = User::factory()->create();

        $attributes = User::factory()->raw(['password' => '', 'update_password' => true]);

        $this->put(route('admin.users.update', $user), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'password',
            trans('validation.required', ['attribute' => 'password'])
        );

        session()->forget('errors');

        $attributes = User::factory()->raw(['password' => '']);

        $this->put(route('admin.users.update', $user), $attributes)
            ->assertSessionDoesntHaveErrors('password');

    }

    /**
     * @test
     */
    public function the_password_field_must_be_a_string_to_create_and_update_an_user()
    {
        $this->signInWithPermissionsTo(['admin.users.store', 'admin.users.update']);

        $attributes = User::factory()->raw(['password' => 123456]);

        $this->post(route('admin.users.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'password',
            trans('validation.string', ['attribute' => 'password'])
        );

        $this->assertDatabaseCount('users', 1);

        session()->forget('errors');

        $user = User::factory()->create();

        $attributes['update_password'] = true;

        $this->put(route('admin.users.update', $user), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'password',
            trans('validation.string', ['attribute' => 'password'])
        );
    }

    /**
     * @test
     */
    public function the_password_field_must_not_be_less_than_8_chars_to_create_and_update_an_user()
    {
        $this->signInWithPermissionsTo(['admin.users.store', 'admin.users.update']);

        $attributes = User::factory()->raw(['password' => Str::random(7)]);

        $this->post(route('admin.users.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'password',
            trans('validation.min.string', ['attribute' => 'password', 'min' => 8])
        );

        $this->assertDatabaseCount('users', 1);

        session()->forget('errors');

        $user = User::factory()->create();

        $attributes['update_password'] = true;

        $this->put(route('admin.users.update', $user), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'password',
            trans('validation.min.string', ['attribute' => 'password', 'min' => 8])
        );
    }

    /**
     * @test
     */
    public function the_password_field_must_be_a_confirmed_to_create_and_update_an_user()
    {
        $this->signInWithPermissionsTo(['admin.users.store', 'admin.users.update']);

        $attributes = User::factory()->raw(['password' => 'password']);

        $this->post(route('admin.users.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'password',
            trans('validation.confirmed', ['attribute' => 'password'])
        );

        $this->assertDatabaseCount('users', 1);

        session()->forget('errors');

        $user = User::factory()->create();

        $attributes['update_password'] = true;

        $this->put(route('admin.users.update', $user), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'password',
            trans('validation.confirmed', ['attribute' => 'password'])
        );
    }

    /**
     * @test
     */
    public function the_roles_field_is_optional_to_create_and_update_an_user()
    {
        $this->signInWithPermissionsTo(['admin.users.store', 'admin.users.update']);

        $attributes = User::factory()->raw([
            'password'              => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->post(route('admin.users.store'), $attributes)
            ->assertSessionDoesntHaveErrors('roles');


        session()->forget('errors');

        $user = User::factory()->create();

        $this->put(route('admin.users.update', $user), $attributes)
            ->assertSessionDoesntHaveErrors('roles');

        $this->assertDatabaseHas('users', Arr::only($attributes, ['name', 'email']));
    }

    /**
     * @test
     */
    public function the_roles_field_must_be_in_allowed_roles_to_create_and_update_an_user()
    {
        $this->signInWithPermissionsTo(['admin.users.store', 'admin.users.update']);

        $roles = Role::factory(3)->create()->pluck('id');

        $attributes = User::factory()->raw([
            'roles' => $roles->toArray()
        ]);

        $this->post(route('admin.users.store'), $attributes);

        $this->assertDatabaseCount('users', 1);

        $this->assertSessionHasErrorKeyValue(
            'roles.0',
            trans('validation.in', ['attribute' => 'roles.0'])
        );

        session()->forget('errors');

        $user = User::factory()->create();

        $this->put(route('admin.users.update', $user), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'roles.0',
            trans('validation.in', ['attribute' => 'roles.0'])
        );
    }


}
