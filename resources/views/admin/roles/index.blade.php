@extends('admin.layouts.app')

@section('title', 'Roles')

@section('title-header', 'Roles')

@push('css')
    {!! Helper::dataTablesCSS() !!}
@endpush

@section('content')
<!-- Default box -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Roles</h3>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-sm float-right">Crear</a>
    </div>
    <div class="card-body">
        <table id="table" class="table table-sm table-bordered table-hover w-100" data-order='[[1,"asc"], [0,"asc"]]'>
            <thead>
                <tr>
                    <th >Nombre</th>
                    <th >Nivel</th>
                    <th data-sortable="false">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                    <tr>
                        <td><a href="{{ route('admin.roles.show', $role) }}">{{ $role->name }}</a></td>
                        <td>{{ $role->level }}</td>
                        <td>
                            <a href="{{ route('admin.roles.edit', $role) }}"
                                class="btn btn-sm btn-primary" title="Editar">
                                <i class="fa fa-edit"></i>
                            </a>
                            @if ($role->name!=config('app.role_super_admin.name'))
                                <a href="#" class="btn btn-sm btn-danger confirm-delete" form-target="delete-form-{{ $role->id }}"
                                    title="Eliminar" onclick="event.preventDefault();" >
                                    <i class="fa fa-times"></i>
                                </a>
                                <form id="delete-form-{{ $role->id }}" action="{{ route('admin.roles.destroy', $role) }}"
                                    method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- /.card -->
@endsection

@push('js')
    {!! Helper::dataTablesJS() !!}

    {!! Helper::dataTables('#table') !!}

    {!! Helper::swalConfirm('.confirm-delete') !!}
@endpush
