@extends('admin.layouts.app')

@section('title', 'Roles')

@section('title-header', 'Crear Rol')

@section('content')
    @include('admin.roles._form', ['role' => $role, 'method' => 'POST', 'action' => 'store'])
@endsection
