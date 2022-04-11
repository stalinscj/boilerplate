@extends('admin.layouts.app')

@section('title', 'Usuarios')

@section('title-header', 'Crear Usuario')

@section('content')
  @include('admin.users._form', ['method' => 'POST', 'action' => 'store'])
@endsection
