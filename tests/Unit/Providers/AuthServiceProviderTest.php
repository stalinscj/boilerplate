<?php

namespace Tests\Unit\Providers;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthServiceProviderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function grant_all_to_super_admin()
    {
        Role::factory()
            ->createOne(['name' => config('permission.super_admin.name')]);

        Route::get('forbidden', fn() => Gate::authorize('forbidden'));

        $user = User::factory()->create();

        $this->signIn($user);

        $this->get('forbidden')
            ->assertStatus(403);

        $user->assignRole(config('permission.super_admin.name'));

        $this->get('forbidden')
            ->assertStatus(200);
    }
}
