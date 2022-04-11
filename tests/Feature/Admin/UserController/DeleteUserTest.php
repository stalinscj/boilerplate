<?php

namespace Tests\Feature\UserController;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteUserTest extends TestCase
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
    public function guest_cannot_delete_users()
    {
        $this->delete(route('admin.users.destroy', rand()))->assertRedirect('login');
    }

    /**
     * @test
     */
    public function unauthorized_user_cannot_delete_users()
    {
        $this->signIn();

        $user = User::factory()->create();

        $this->delete(route('admin.users.destroy', $user))->assertForbidden();
    }

    /**
     * @test
     */
    public function authorized_user_can_delete_users()
    {
        $this->signInWithPermissionsTo('admin.users.destroy');

        $user = User::factory()->create();

        $this->delete(route('admin.users.destroy', $user))
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseCount('users', 1);
    }
}
