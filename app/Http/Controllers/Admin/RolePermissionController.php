<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class RolePermissionController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\Role $role
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(Role $role)
    {
        $this->authorize('manage', $role);

        $permissionsGrouped = Permission::where('level', '>=', auth()->user()->level)
            ->get()
            ->groupBy('group');

        return view('admin.roles_permissions.create', compact('role', 'permissionsGrouped'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Role $role)
    {
        $this->authorize('manage', $role);

        $userLevel = $request->user()->level;

        $newPermissions = Permission::where('level', '>=', $userLevel)
            ->whereIn('id', $request->permissions)
            ->get();

        $permissions = $role->getAllPermissions()
            ->where('level', '<', $userLevel)
            ->merge($newPermissions);

        $role->syncPermissions($permissions);

        Alert::toast('Los Permisos del Rol han sido guardados exitosamente', 'success');

        return redirect()->route('admin.roles.index');
    }
}
