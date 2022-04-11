<?php

namespace Tests\Feature\UserController;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RestoreUserTest extends TestCase
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
    public function guest_cannot_restore_users()
    {
        $this->post(route('admin.users.restore', rand()))->assertRedirect('login');
    }

    /**
     * @test
     */
    public function unauthorized_user_cannot_restore_users()
    {
        $this->signIn();

        $user = User::factory()->create();

        $user->delete();

        $this->post(route('admin.users.restore', $user))->assertForbidden();
    }

    /**
     * @test
     */
    public function authorized_user_can_restore_an_user()
    {
        $this->signInWithPermissionsTo('admin.users.restore');

        $user = User::factory()->create();

        $user->delete();
        $this->assertSoftDeleted('users',  ['id' => $user->id]);

        $this->post(route('admin.users.restore', $user))
            ->assertRedirect(route('admin.users.index'));

        $this->assertEquals($user->id, User::find($user->id)->id ?? null);
    }
}
