@extends('admin.layouts.app')

@section('title', 'Usuarios')

@section('page-title', 'Usuarios')

@section('content')

<div class="row container">
    <div class="col-xl-6">
        <div class="card">

            <div class="card-header">
                <h3 class="card-title">Usuario {{ $user->name }}</h3>
                <a href="{{ URL::previous() }}" class="btn btn-sm btn-secondary float-right">Regresar</a>
            </div>

            <div class="card-body">
                <strong><i class="fas fa-envelope mr-1"></i> Email</strong>
                <p class="text-muted">
                    {{ $user->email }}
                </p>
                <hr>
                <strong><i class="fas fa-user-secret mr-1"></i> Roles</strong>
                <p class="text-muted">
                    {{ implode(', ', $user->getRoleNames()->toArray() ) }}
                </p>
                <hr>
                <strong><i class="fas fa-clock mr-1"></i> Fecha de Creación</strong>
                <p class="text-muted">
                    {{ $user->created_at->format(config('app.datetime_format')) }}
                </p>
                <hr>
            </div>

        </div>
    </div>
</div>

@endsection
