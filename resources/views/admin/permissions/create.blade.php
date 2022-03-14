@extends('admin.layouts.app')

@section('title', 'Permisos')

@section('title-header', 'Crear Permiso')

@section('content')
    @include('admin.permissions._form', ['permission' => $permission, 'method' => 'POST', 'action' => 'store'])
@endsection
