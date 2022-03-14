@extends('admin.layouts.app')

@section('title', 'Permisos')

@section('title-header', 'Permisos')

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
</div>

@endsection
