{{-- Users Count --}}
<div class="col-lg-3 col-6">
    <div class="small-box bg-success">

        <div class="inner">
            <h3>{{ $usersCount }}</h3>

            <p>Usuarios registrados</p>
        </div>

        <div class="icon">
            <i class="fas fa-users"></i>
        </div>

        @can('Listar Usuarios', Auth::user())
            <a href="{{ route('admin.users.index') }}" class="small-box-footer">
                Más información <i class="fas fa-arrow-circle-right"></i>
            </a>
        @else
            <span class="small-box-footer"><br></span>
        @endcan

    </div>
</div>
{{-- ./Users Count --}}

{{-- Roles Count --}}
<div class="col-lg-3 col-6">
    <div class="small-box bg-info">

        <div class="inner">
            <h3>{{ $rolesCount }}</h3>

            <p>Roles registrados</p>
        </div>

        <div class="icon">
            <i class="fas fa-user-secret"></i>
        </div>

        @can('Listar Roles', Auth::user())
            <a href="{{ route('admin.roles.index') }}" class="small-box-footer">
                Más información <i class="fas fa-arrow-circle-right"></i>
            </a>
        @else
            <span class="small-box-footer"><br></span>
        @endcan

    </div>
</div>
{{-- ./Roles Count --}}

{{-- Permissions Count --}}
<div class="col-lg-3 col-6">
    <div class="small-box bg-warning">

        <div class="inner">
            <h3>{{ $permissionsCount }}</h3>

            <p>Permisos registrados</p>
        </div>

        <div class="icon">
            <i class="fas fa-key"></i>
        </div>

        @can('Listar Permisos', Auth::user())
            <a href="{{ route('admin.permissions.index') }}" class="small-box-footer">
                Más información <i class="fas fa-arrow-circle-right"></i>
            </a>
        @else
            <span class="small-box-footer"><br></span>
        @endcan

    </div>
</div>
{{-- ./Permissions Count --}}
