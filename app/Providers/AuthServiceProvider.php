<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\Permission' => 'App\Policies\PermissionPolicy',
        'App\Models\Role'       => 'App\Policies\RolePolicy',
        'App\Models\User'       => 'App\Policies\UserPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $this->grantAllToSuperAdmin();
    }

    /**
     * Implicitly grant "Super Admin" role all permissions
     *
     * @return void
     */
    public function grantAllToSuperAdmin()
    {
        Gate::before(function ($user, $ability) {
            return $user->hasRole(config('permission.super_admin.name')) ? true : null;
        });
    }
}
