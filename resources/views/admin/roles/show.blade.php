@extends('admin.layouts.app')

@section('title', 'Roles')

@section('title-header', 'Roles')

@push('css')
    {!! Helper::dataTablesCSS() !!}
@endpush

@section('content')

<div class="row container">

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Rol {{ $role->name }}</h3>
                <a href="{{ URL::previous() }}" class="btn btn-sm btn-secondary float-right">Regresar</a>
            </div>
            <div class="card-body">
                <strong><i class="fas fa-arrows-alt-v mr-1"></i> Nivel</strong>
                <p class="text-muted">
                    {{ $role->level }}
                </p>
                <hr>
            </div>
        </div>
    </div>

    <div class="col-xl-12">
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">Permisos del Rol {{ $role->name }}</h3>
            </div>
            <div class="card-body">
                <table id="table" class="table table-sm table-bordered table-hover w-100">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Nivel</th>
                            <th>Grupo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($role->permissions as $permission)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.permissions.show', $permission) }}">
                                        {{ $permission->name }}
                                    </a>
                                </td>
                                <td>{{ $permission->level }}</td>
                                <td>{{ $permission->group }}</td>
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

    {!! Helper::dataTables('#table') !!}
@endpush
