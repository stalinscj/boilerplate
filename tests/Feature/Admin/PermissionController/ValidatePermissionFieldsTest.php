<?php

namespace Tests\Feature\Admin\PermissionController;

use Tests\TestCase;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ValidatePermissionFieldsTest extends TestCase
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
    public function the_name_field_is_required_to_create_or_update_a_permission()
    {
        $this->signInWithPermissionsTo(['admin.permissions.store', 'admin.permissions.update']);

        $attributes = Permission::factory()->raw(['name' => '']);

        $this->post(route('admin.permissions.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'name',
            trans('validation.required', ['attribute' => 'name'])
        );

        $this->assertDatabaseCount('permissions', $this->totalBasePermissions);

        session()->forget('errors');

        $permission = Permission::factory()->create();

        $this->put(route('admin.permissions.update', $permission), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'name',
            trans('validation.required', ['attribute' => 'name'])
        );
    }

    /**
     * @test
     */
    public function the_name_field_must_be_a_string_to_create_or_update_a_permission()
    {
        $this->signInWithPermissionsTo(['admin.permissions.store', 'admin.permissions.update']);

        $attributes = Permission::factory()->raw(['name' => 123456]);

        $this->post(route('admin.permissions.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'name',
            trans('validation.string', ['attribute' => 'name'])
        );

        $this->assertDatabaseCount('permissions', $this->totalBasePermissions);

        session()->forget('errors');

        $permission = Permission::factory()->create();

        $this->put(route('admin.permissions.update', $permission), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'name',
            trans('validation.string', ['attribute' => 'name'])
        );
    }

    /**
     * @test
     */
    public function the_name_field_must_not_be_greater_than_45_chars_to_create_or_update_a_permission()
    {
        $this->signInWithPermissionsTo(['admin.permissions.store', 'admin.permissions.update']);

        $attributes = Permission::factory()->raw(['name' => Str::random(46)]);

        $this->post(route('admin.permissions.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'name',
            trans('validation.max.string', ['attribute' => 'name', 'max' => 45])
        );

        $this->assertDatabaseCount('permissions', $this->totalBasePermissions);

        session()->forget('errors');

        $permission = Permission::factory()->create();

        $this->put(route('admin.permissions.update', $permission), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'name',
            trans('validation.max.string', ['attribute' => 'name', 'max' => 45])
        );
    }

    /**
     * @test
     */
    public function the_name_field_must_be_unique_to_create_or_update_a_permission()
    {
        $this->signInWithPermissionsTo(['admin.permissions.store', 'admin.permissions.update']);

        $permission = Permission::factory()->create();

        $attributes = Permission::factory()->raw(['name' => $permission->name]);

        $this->post(route('admin.permissions.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'name',
            trans('validation.unique', ['attribute' => 'name'])
        );

        $this->assertDatabaseCount('permissions', $this->totalBasePermissions + 1);

        session()->forget('errors');

        $permissionB = Permission::factory()->create();

        $this->put(route('admin.permissions.update', $permissionB), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'name',
            trans('validation.unique', ['attribute' => 'name'])
        );
    }

    /**
     * @test
     */
    public function the_route_name_field_is_required_to_create_or_update_a_permission()
    {
        $this->signInWithPermissionsTo(['admin.permissions.store', 'admin.permissions.update']);

        $attributes = Permission::factory()->raw(['route_name' => '']);

        $this->post(route('admin.permissions.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'route_name',
            trans('validation.required', ['attribute' => 'route name'])
        );

        $this->assertDatabaseCount('permissions', $this->totalBasePermissions);

        session()->forget('errors');

        $permission = Permission::factory()->create();

        $this->put(route('admin.permissions.update', $permission), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'route_name',
            trans('validation.required', ['attribute' => 'route name'])
        );
    }

    /**
     * @test
     */
    public function the_route_name_field_must_be_a_string_to_create_or_update_a_permission()
    {
        $this->signInWithPermissionsTo(['admin.permissions.store', 'admin.permissions.update']);

        $attributes = Permission::factory()->raw(['route_name' => 123456]);

        $this->post(route('admin.permissions.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'route_name',
            trans('validation.string', ['attribute' => 'route name'])
        );

        $this->assertDatabaseCount('permissions', $this->totalBasePermissions);

        session()->forget('errors');

        $permission = Permission::factory()->create();

        $this->put(route('admin.permissions.update', $permission), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'route_name',
            trans('validation.string', ['attribute' => 'route name'])
        );
    }

    /**
     * @test
     */
    public function the_route_name_field_must_not_be_greater_than_45_chars_to_create_or_update_a_permission()
    {
        $this->signInWithPermissionsTo(['admin.permissions.store', 'admin.permissions.update']);

        $attributes = Permission::factory()->raw(['route_name' => Str::random(46)]);

        $this->post(route('admin.permissions.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'route_name',
            trans('validation.max.string', ['attribute' => 'route name', 'max' => 45])
        );

        $this->assertDatabaseCount('permissions', $this->totalBasePermissions);

        session()->forget('errors');

        $permission = Permission::factory()->create();

        $this->put(route('admin.permissions.update', $permission), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'route_name',
            trans('validation.max.string', ['attribute' => 'route name', 'max' => 45])
        );
    }

    /**
     * @test
     */
    public function the_route_name_field_must_be_unique_to_create_or_update_a_permission()
    {
        $this->signInWithPermissionsTo(['admin.permissions.store', 'admin.permissions.update']);

        $permission = Permission::factory()->create();

        $attributes = Permission::factory()->raw(['route_name' => $permission->route_name]);

        $this->post(route('admin.permissions.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'route_name',
            trans('validation.unique', ['attribute' => 'route name'])
        );

        $this->assertDatabaseCount('permissions', $this->totalBasePermissions + 1);

        session()->forget('errors');

        $permissionB = Permission::factory()->create();

        $this->put(route('admin.permissions.update', $permissionB), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'route_name',
            trans('validation.unique', ['attribute' => 'route name'])
        );
    }

    /**
     * @test
     */
    public function the_level_field_is_required_to_create_or_update_a_permission()
    {
        $this->signInWithPermissionsTo(['admin.permissions.store', 'admin.permissions.update']);

        $attributes = Permission::factory()->raw(['level' => '']);

        $this->post(route('admin.permissions.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'level',
            trans('validation.required', ['attribute' => 'level'])
        );

        $this->assertDatabaseCount('permissions', $this->totalBasePermissions);

        session()->forget('errors');

        $permission = Permission::factory()->create();

        $this->put(route('admin.permissions.update', $permission), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'level',
            trans('validation.required', ['attribute' => 'level'])
        );
    }

    /**
     * @test
     */
    public function the_level_field_must_be_an_integer_to_create_or_update_a_permission()
    {
        $this->signInWithPermissionsTo(['admin.permissions.store', 'admin.permissions.update']);

        $attributes = Permission::factory()->raw(['level' => 'NaN']);

        $this->post(route('admin.permissions.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'level',
            trans('validation.integer', ['attribute' => 'level'])
        );

        $this->assertDatabaseCount('permissions', $this->totalBasePermissions);

        session()->forget('errors');

        $permission = Permission::factory()->create();

        $this->put(route('admin.permissions.update', $permission), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'level',
            trans('validation.integer', ['attribute' => 'level'])
        );
    }

    /**
     * @test
     */
    public function the_level_field_must_not_be_less_than_user_role_level_to_create_or_update_a_permission()
    {
        $user = $this->signInWithPermissionsTo(['admin.permissions.store', 'admin.permissions.update']);

        $role = Role::factory()->create(['level' => 5]);

        $user->assignRole($role);

        $attributes = Permission::factory()->raw(['level' => 4]);

        $this->post(route('admin.permissions.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'level',
            trans('validation.min.numeric', ['attribute' => 'level', 'min' => 5])
        );

        $this->assertDatabaseCount('permissions', $this->totalBasePermissions);

        session()->forget('errors');

        $permission = Permission::factory()->create();

        $this->put(route('admin.permissions.update', $permission), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'level',
            trans('validation.min.numeric', ['attribute' => 'level', 'min' => 5])
        );
    }

    /**
     * @test
     */
    public function the_group_field_is_required_to_create_or_update_a_permission()
    {
        $this->signInWithPermissionsTo(['admin.permissions.store', 'admin.permissions.update']);

        $attributes = Permission::factory()->raw(['group' => '']);

        $this->post(route('admin.permissions.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'group',
            trans('validation.required', ['attribute' => 'group'])
        );

        $this->assertDatabaseCount('permissions', $this->totalBasePermissions);

        session()->forget('errors');

        $permission = Permission::factory()->create();

        $this->put(route('admin.permissions.update', $permission), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'group',
            trans('validation.required', ['attribute' => 'group'])
        );
    }

    /**
     * @test
     */
    public function the_group_field_must_be_a_string_to_create_or_update_a_permission()
    {
        $this->signInWithPermissionsTo(['admin.permissions.store', 'admin.permissions.update']);

        $attributes = Permission::factory()->raw(['group' => 123456]);

        $this->post(route('admin.permissions.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'group',
            trans('validation.string', ['attribute' => 'group'])
        );

        $this->assertDatabaseCount('permissions', $this->totalBasePermissions);

        session()->forget('errors');

        $permission = Permission::factory()->create();

        $this->put(route('admin.permissions.update', $permission), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'group',
            trans('validation.string', ['attribute' => 'group'])
        );
    }

    /**
     * @test
     */
    public function the_group_field_must_be_greater_than_2_chars_to_create_or_update_a_permission()
    {
        $this->signInWithPermissionsTo(['admin.permissions.store', 'admin.permissions.update']);

        $attributes = Permission::factory()->raw(['group' => 'ab']);

        $this->post(route('admin.permissions.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'group',
            trans('validation.min.string', ['attribute' => 'group', 'min' => 3])
        );

        $this->assertDatabaseCount('permissions', $this->totalBasePermissions);

        session()->forget('errors');

        $permission = Permission::factory()->create();

        $this->put(route('admin.permissions.update', $permission), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'group',
            trans('validation.min.string', ['attribute' => 'group', 'min' => 3])
        );
    }

    /**
     * @test
     */
    public function the_group_field_must_not_be_greater_than_20_chars_to_create_or_update_a_permission()
    {
        $this->signInWithPermissionsTo(['admin.permissions.store', 'admin.permissions.update']);

        $attributes = Permission::factory()->raw(['group' => Str::random(21)]);

        $this->post(route('admin.permissions.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'group',
            trans('validation.max.string', ['attribute' => 'group', 'max' => 20])
        );

        $this->assertDatabaseCount('permissions', $this->totalBasePermissions);

        session()->forget('errors');

        $permission = Permission::factory()->create();

        $this->put(route('admin.permissions.update', $permission), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'group',
            trans('validation.max.string', ['attribute' => 'group', 'max' => 20])
        );
    }
}
