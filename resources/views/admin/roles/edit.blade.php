@extends('admin.layouts.app')

@section('title', 'Roles')

@section('title-header', 'Editar Rol')

@section('content')
    @include('admin.roles._form', ['role' => $role, 'method' => 'PUT', 'action' => 'update'])
@endsection
