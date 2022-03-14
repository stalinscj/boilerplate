@extends('admin.layouts.app')

@section('title', 'Permisos de Roles')

@section('title-header')
  Permisos del Rol {{ "'$role->name' (Nivel {$role->level})" }}
@endsection

@section('content')

<form action="{{ route('admin.roles.permissions.store', $role) }}" method="POST">
  @csrf
  <div class="row">
    @foreach ($permissionsGrouped as $permissionGroup => $permissions)
      @if ($permissions->max('level') >= Auth::user()->level)
        <div class="col-md-4">
          <div class="card card-secondary">
            <div class="card-header">
              <h3 class="card-title">{{ $permissionGroup }}</h3>
            </div>
            <div class="card-body">
              <div class="form-group">
                @foreach ($permissions as $permission)
                  @if ($permission->level >= Auth::user()->level)
                    <div class="custom-control custom-checkbox">
                      <input class="custom-control-input" type="checkbox"
                        id="permission-{{ $permission->id }}"
                        name="permissions[]"
                        value="{{ $permission->id }}"
                        {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }} >
                      <label for="permission-{{ $permission->id }}" class="custom-control-label">
                        {{ "$permission->name (Nivel {$permission->level})"  }}
                      </label>
                    </div>
                  @endif
                @endforeach
              </div>
            </div>
          </div>
        </div>
      @endif
    @endforeach
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card card-default">
        <div class="card-header text-right">
          <a href="{{ route('admin.roles.index') }}" class="btn btn-sm btn-secondary">Regresar</a>
          <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
        </div>
      </div>
    </div>
  </div>
</form>

@endsection
