<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can manage the per$permission.
     *
     * @param  \App\Http\Modules\User\User  $user
     * @param  \App\Models\Permission  $permission
     *
     * @return bool
     */
    public function manage(User $user, Permission $permission)
    {
        return $permission->level >= $user->level;
    }
}
