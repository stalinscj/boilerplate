@extends('admin.layouts.app')

@section('title', 'Permisos')

@section('title-header', 'Editar Permiso')

@section('content')
    @include('admin.permissions._form', ['permission' => $permission, 'method' => 'PUT', 'action' => 'update'])
@endsection
