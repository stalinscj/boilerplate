<?php

namespace App\Http\Responses\Admin;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Contracts\Support\Responsable;

class DashboardResponse implements Responsable
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        return view('admin.dashboard.index', [
            'usersCount'       => $this->getUsersCount(),
            'rolesCount'       => $this->getRolesCount(),
            'permissionsCount' => $this->getPermissionsCount(),
        ]);
    }

    /**
     * Returns the users count
     *
     * @return int
     */
    public function getUsersCount()
    {
        return User::count();
    }

    /**
     * Returns the roles count
     *
     * @return int
     */
    public function getRolesCount()
    {
        return Role::count();
    }

    /**
     * Returns the roles count
     *
     * @return int
     */
    public function getPermissionsCount()
    {
        return Permission::count();
    }
}
