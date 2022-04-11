<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users = User::withTrashed()
            ->get()
            ->filter(fn ($user) => $user->level >= auth()->user()->level);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        $user = new User();
        $roles = Role::where('level', '>=', auth()->user()->level)->get();

        return view('admin.users.create', compact('user', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\UserRequest  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserRequest $request)
    {
        $user = User::create($request->validated());
        $user->syncRoles($request->roles);
        $user->givePermissionTo(Permission::firstWhere('route_name', 'admin.dashboard'));
        $user->email_verified_at = now();
        $user->save();

        app()['cache']->forget('spatie.permission.cache');

        Alert::toast('Usuario creado exitosamente', 'success');

        return redirect()->route('admin.users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User $user
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(User $user)
    {
        $this->authorize('manage', $user);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User $user
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(User $user)
    {
        $this->authorize('manage', $user);

        $roles = Role::where('level', '>=', auth()->user()->level)->get();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\UserRequest  $request
     * @param  \App\Models\User $user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserRequest $request, User $user)
    {
        $this->authorize('manage', $user);

        $user->update($request->validated());

        $user->syncRoles($request->roles);

        app()['cache']->forget('spatie.permission.cache');

        Alert::toast('Usuario editado exitosamente', 'success');

        return redirect()->route('admin.users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User $user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        $this->authorize('manage', $user);

        $user->secureDelete();

        Alert::toast('Usuario deshabilitado exitosamente', 'error');

        return redirect()->route('admin.users.index');
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id)
    {
        $user = User::withTrashed()
            ->where('id', $id)
            ->firstOrFail();

        $this->authorize('manage', $user);

        $user->restore();

        Alert::toast('Usuario restaurado exitosamente', 'success');

        return redirect()->route('admin.users.index');
    }
}
