@extends('admin.layouts.app')

@section('title', 'Permisos')

@section('title-header', 'Permisos')

@push('css')
    {!! Helper::dataTablesCSS() !!}
@endpush

@section('content')

<div class="row container">

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Permiso {{ $permission->name }}</h3>
                <a href="{{ URL::previous() }}" class="btn btn-sm btn-secondary float-right">Regresar</a>
            </div>
            <div class="card-body">
                <strong><i class="fa fa-globe mr-1"></i> Nombre de Ruta</strong>
                <p class="text-muted">
                    {{ $permission->route_name }}
                </p>
                <hr>
                <strong><i class="fas fa-arrows-alt-v mr-1"></i> Nivel</strong>
                <p class="text-muted">
                    {{ $permission->level }}
                </p>
                <hr>
                <strong><i class="fa fa-users mr-1"></i> Group</strong>
                <p class="text-muted">
                    {{ $permission->group }}
                </p>
                <hr>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">Roles con Permiso {{ $permission->name }}</h3>
            </div>
            <div class="card-body">
                <table id="table" class="table table-sm table-bordered table-hover w-100">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Nivel</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permission->roles as $role)
                            <tr>
                                <td><a href="{{ route('admin.roles.show', $role) }}">{{ $role->name }}</a></td>
                                <td>{{ $role->level }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection

@push('js')
    {!! Helper::dataTablesJS() !!}

    {!! Helper::dataTables("#table") !!}
@endpush
