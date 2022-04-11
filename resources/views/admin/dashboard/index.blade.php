@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('title-header', 'Dashboard')

@section('content')
<div class="container-fluid">
    {{-- Users, Roles and Permissions --}}
    <div class="row">
        @include('admin.dashboard._small_boxes')
    </div>
    {{-- ./EUsers, Roles and Permissions --}}
</div>
@endsection
