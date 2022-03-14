<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use RealRashid\SweetAlert\Facades\Alert;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $roles = Role::where('level', '>=', auth()->user()->level)->get();

        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        $role = new Role();

        return view('admin.roles.create', compact('role'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\RoleRequest  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(RoleRequest $request)
    {
        Role::create($request->validated());

        Alert::toast('Rol creado exitosamente', 'success');

        return redirect()->route('admin.roles.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Role $role)
    {
        return view('admin.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(Role $role)
    {
        $this->authorize('manage', $role);

        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\RoleRequest  $request
     * @param  \App\Models\Role  $role
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(RoleRequest $request, Role $role)
    {
        $this->authorize('manage', $role);

        $role->update($request->validated());

        app()['cache']->forget('spatie.permission.cache');

        Alert::toast('Rol editado exitosamente', 'success');

        return redirect()->route('admin.roles.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Role $role)
    {
        $this->authorize('manage', $role);

        $role->delete();

        app()['cache']->forget('spatie.permission.cache');

        Alert::toast('Rol eliminado exitosamente', 'success');

        return redirect()->route('admin.roles.index');
    }
}
