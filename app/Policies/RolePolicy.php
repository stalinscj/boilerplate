<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can manage the role.
     *
     * @param  \App\Http\Modules\User\User  $user
     * @param  \App\Models\Role  $role
     *
     * @return bool
     */
    public function manage(User $user, Role $role)
    {
        return $role->level >= $user->level;
    }
}
