<?php

namespace Tests\Feature\UserController;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadUserTest extends TestCase
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
    public function guest_cannot_read_users()
    {
        $this->get(route('admin.users.index'))->assertRedirect('login');
        $this->get(route('admin.users.show', rand()))->assertRedirect('login');
    }

    /**
     * @test
     */
    public function unauthorized_user_cannot_read_users()
    {
        $this->signIn();

        $user = User::factory()->create();

        $this->get(route('admin.users.index'))->assertForbidden();
        $this->get(route('admin.users.show', $user))->assertForbidden();
    }

    /**
     * @test
     */
    public function authorized_user_can_read_the_users_list()
    {
        $users = User::factory(5)->create();

        $this->signInWithPermissionsTo(['admin.users.index']);

        $this->get(route('admin.users.index'))
            ->assertSuccessful()
            ->assertViewIs('admin.users.index');

        $response = $this->get(route('admin.users.index'))
            ->assertSuccessful()
            ->assertViewIs('admin.users.index');

        foreach ($users as $user) {
            $response->assertSeeText($user->name)
                ->assertSeeText($user->email)
                ->assertSeeText($user->created_at->format(config('app.datetime_format')));
        }
    }

    /**
     * @test
     */
    public function authorized_user_can_read_the_details_of_an_user()
    {
        $this->signInWithPermissionsTo('admin.users.show');

        $user = User::factory()->create();

        $this->get(route('admin.users.show', $user))
            ->assertSuccessful()
            ->assertViewIs('admin.users.show')
            ->assertSeeText($user->name)
            ->assertSeeText($user->email)
            ->assertSeeText($user->roles->implode('name', ', '));
    }
}
