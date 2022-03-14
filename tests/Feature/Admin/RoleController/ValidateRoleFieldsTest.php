<?php

namespace Tests\Feature\Admin\RoleController;

use Tests\TestCase;
use App\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ValidateRoleFieldsTest extends TestCase
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
    public function the_name_field_is_required_to_create_and_update_a_role()
    {
        $this->signInWithPermissionsTo(['admin.roles.store', 'admin.roles.update']);

        $attributes = Role::factory()->raw(['name' => '']);

        $this->post(route('admin.roles.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'name',
            trans('validation.required', ['attribute' => 'name'])
        );

        $this->assertDatabaseCount('roles', 0);

        session()->forget('errors');

        $role = Role::factory()->create();

        $this->put(route('admin.roles.update', $role), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'name',
            trans('validation.required', ['attribute' => 'name'])
        );
    }

    /**
     * @test
     */
    public function the_name_field_must_be_a_string_to_create_and_update_a_role()
    {
        $this->signInWithPermissionsTo(['admin.roles.store', 'admin.roles.update']);

        $attributes = Role::factory()->raw(['name' => 123456]);

        $this->post(route('admin.roles.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'name',
            trans('validation.string', ['attribute' => 'name'])
        );

        $this->assertDatabaseCount('roles', 0);

        session()->forget('errors');

        $role = Role::factory()->create();

        $this->put(route('admin.roles.update', $role), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'name',
            trans('validation.string', ['attribute' => 'name'])
        );
    }

    /**
     * @test
     */
    public function the_name_field_must_only_contain_letters_numbers_and_dashes_to_create_and_update_a_role()
    {
        $this->signInWithPermissionsTo(['admin.roles.store', 'admin.roles.update']);

        $attributes = Role::factory()->raw(['name' => 'Not < Allowed']);

        $this->post(route('admin.roles.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'name',
            trans('validation.alpha_dash', ['attribute' => 'name'])
        );

        $this->assertDatabaseCount('roles', 0);

        session()->forget('errors');

        $role = Role::factory()->create();

        $this->put(route('admin.roles.update', $role), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'name',
            trans('validation.alpha_dash', ['attribute' => 'name'])
        );
    }

    /**
     * @test
     */
    public function the_name_field_must_not_be_greater_than_30_chars_to_create_and_update_a_role()
    {
        $this->signInWithPermissionsTo(['admin.roles.store', 'admin.roles.update']);

        $attributes = Role::factory()->raw(['name' => Str::random(31)]);

        $this->post(route('admin.roles.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'name',
            trans('validation.max.string', ['attribute' => 'name', 'max' => 30])
        );

        $this->assertDatabaseCount('roles', 0);

        session()->forget('errors');

        $role = Role::factory()->create();

        $this->put(route('admin.roles.update', $role), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'name',
            trans('validation.max.string', ['attribute' => 'name', 'max' => 30])
        );
    }

    /**
     * @test
     */
    public function the_name_field_must_be_unique_to_create_and_update_a_role()
    {
        $this->signInWithPermissionsTo(['admin.roles.store', 'admin.roles.update']);

        $role = Role::factory()->create();

        $attributes = Role::factory()->raw(['name' => $role->name]);

        $this->post(route('admin.roles.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'name',
            trans('validation.unique', ['attribute' => 'name'])
        );

        $this->assertDatabaseCount('roles', 1);

        session()->forget('errors');

        $roleB = Role::factory()->create();

        $this->put(route('admin.roles.update', $roleB), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'name',
            trans('validation.unique', ['attribute' => 'name'])
        );
    }

    /**
     * @test
     */
    public function the_level_field_is_required_to_create_and_update_a_role()
    {
        $this->signInWithPermissionsTo(['admin.roles.store', 'admin.roles.update']);

        $attributes = Role::factory()->raw(['level' => '']);

        $this->post(route('admin.roles.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'level',
            trans('validation.required', ['attribute' => 'level'])
        );

        $this->assertDatabaseCount('roles', 0);

        session()->forget('errors');

        $role = Role::factory()->create();

        $this->put(route('admin.roles.update', $role), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'level',
            trans('validation.required', ['attribute' => 'level'])
        );
    }

    /**
     * @test
     */
    public function the_level_field_must_be_an_integer_to_create_and_update_a_role()
    {
        $this->signInWithPermissionsTo(['admin.roles.store', 'admin.roles.update']);

        $attributes = Role::factory()->raw(['level' => 'NaN']);

        $this->post(route('admin.roles.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'level',
            trans('validation.integer', ['attribute' => 'level'])
        );

        $this->assertDatabaseCount('roles', 0);

        session()->forget('errors');

        $role = Role::factory()->create();

        $this->put(route('admin.roles.update', $role), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'level',
            trans('validation.integer', ['attribute' => 'level'])
        );
    }

    /**
     * @test
     */
    public function the_level_field_must_not_be_less_than_user_role_level_to_create_and_update_a_role()
    {
        $user = $this->signInWithPermissionsTo(['admin.roles.store', 'admin.roles.update']);

        $role = Role::factory()->create(['level' => 5]);

        $user->assignRole($role);

        $attributes = Role::factory()->raw(['level' => 4]);

        $this->post(route('admin.roles.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'level',
            trans('validation.min.numeric', ['attribute' => 'level', 'min' => 5])
        );

        $this->assertDatabaseCount('roles', 1);

        session()->forget('errors');

        $this->put(route('admin.roles.update', $role), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'level',
            trans('validation.min.numeric', ['attribute' => 'level', 'min' => 5])
        );
    }
}
