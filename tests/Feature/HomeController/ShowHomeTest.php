<?php

namespace Tests\Feature\HomeController;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowHomeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function guest_cannot_access_to_home()
    {
        $this->get(route('home'))->assertRedirect('login');
    }

    /**
     * @test
     */
    public function user_can_access_to_home()
    {
        $this->signIn();

        $this->get(route('home'))
            ->assertSuccessful()
            ->assertViewIs('home.index');
    }
}
