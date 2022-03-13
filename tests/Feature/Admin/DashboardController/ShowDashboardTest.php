<?php

namespace Tests\Feature\Admin\DashboardController;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowDashboardTest extends TestCase
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
    public function a_guest_cannot_access_to_dashboard()
    {
        $this->get(route('admin.dashboard'))->assertRedirect('login');
    }

    /**
     * @test
     */
    public function an_unauthorized_user_cannot_access_to_dashboard()
    {
        $this->signIn();

        $this->get(route('admin.dashboard'))->assertForbidden();
    }

    /**
     * @test
     */
    public function authorized_user_can_see_the_dashboard()
    {
        $this->signInWithPermissionsTo('admin.dashboard');

        $this->get(route('admin.dashboard'))
            ->assertSuccessful()
            ->assertViewIs('admin.dashboard.index');
    }
}
