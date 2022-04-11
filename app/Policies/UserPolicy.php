<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can manage the user.
     *
     * @param  \App\Http\Modules\User\User  $user
     * @param  \App\Models\User  $userToManage
     *
     * @return mixed
     */
    public function manage(User $user, User $userToManage)
    {
        return $userToManage->level >= $user->level;
    }
}
