@extends('admin.layouts.app')

@section('title', 'Roles')

@section('title-header', 'Roles')

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
</div>

@endsection
