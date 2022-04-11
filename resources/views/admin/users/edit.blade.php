@extends('admin.layouts.app')

@section('title', 'Usuarios')

@section('title-header', 'Editar Usuario')

@section('content')
  @include('admin.users._form', ['method' => 'PUT', 'action' => 'update'])
@endsection
