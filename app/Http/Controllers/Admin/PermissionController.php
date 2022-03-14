<?php

namespace App\Http\Controllers\Admin;

use App\Support\Helper;
use App\Models\Permission;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\Admin\PermissionRequest;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Support\Renderable|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if (request()->ajax()) {

            $permissions = Permission::where('level', '>=', auth()->user()->level);

            return datatables()
                ->eloquent($permissions)
                ->addColumn('show_link', '{{ route("admin.permissions.show", $id) }}')
                ->addColumn('is_private_route', fn($permission) => Helper::isPrivateRoute($permission->route_name) ? 'Si' : 'No')
                ->addColumn('actions', 'admin.permissions._actions')
                ->rawColumns(['actions'])
                ->toJson();
        }

        $unnecessaryPermissions = Permission::getUnnecessaryPermissions();
        $privateRoutesWithoutPermission = Permission::getPrivateRoutesWithoutPermission();

        return view('admin.permissions.index', compact(
            'unnecessaryPermissions',
            'privateRoutesWithoutPermission'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        $permission = new Permission();
        $privateRoutesWithoutPermission = Permission::getPrivateRoutesWithoutPermission();
        $permissionsGroups = Permission::all()
            ->groupBy('group')
            ->keys();

        return view('admin.permissions.create', compact(
            'permission',
            'permissionsGroups',
            'privateRoutesWithoutPermission'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\PermissionRequest  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PermissionRequest $request)
    {
        Permission::create($request->validated());

        Alert::toast('Permiso creado exitosamente', 'success');

        return redirect()->route('admin.permissions.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Permission $permission)
    {
        return view('admin.permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(Permission $permission)
    {
        $this->authorize('manage', $permission);

        $privateRoutesWithoutPermission = Permission::getPrivateRoutesWithoutPermission();
        $permissionsGroups = Permission::all()
            ->groupBy('group')
            ->keys();

        return view('admin.permissions.edit', compact(
            'permission',
            'permissionsGroups',
            'privateRoutesWithoutPermission'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\PermissionRequest  $request
     * @param  \App\Models\Permission  $permission
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PermissionRequest $request, Permission $permission)
    {
        $this->authorize('manage', $permission);

        $permission->update($request->validated());

        app()['cache']->forget('spatie.permission.cache');

        Alert::toast('Permiso editado exitosamente', 'success');

        return redirect()->route('admin.permissions.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Permission  $permission
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Permission $permission)
    {
        $this->authorize('manage', $permission);

        $permission->delete();

        app()['cache']->forget('spatie.permission.cache');

        Alert::toast('Permiso eliminado exitosamente', 'success');

        return redirect()->route('admin.permissions.index');
    }

    /**
     * Remove all unused resources from storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clean()
    {
        Permission::getUnnecessaryPermissions()
            ->each(function ($permission) {
                $permission->delete();
            });

        app()['cache']->forget('spatie.permission.cache');

        Alert::toast('Permisos innecesarios eliminados exitosamente', 'success');

        return redirect()->route('admin.permissions.index');
    }
}
