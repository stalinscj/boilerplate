@extends('admin.layouts.app')

@section('title', 'Usuarios')

@section('title-header', 'Usuarios')

@push('css')
  {!! Helper::dataTablesCSS() !!}
@endpush

@section('content')
<!-- Default box -->
<div class="card">

  <div class="card-header">
    <h3 class="card-title">Usuarios</h3>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm float-right">Crear</a>
  </div>

  <div class="card-body">
    <table id="table" class="table table-sm table-bordered table-hover w-100">

      <thead>
        <tr>
          <th>Nombre</th>
          <th>Email</th>
          <th>Fecha de Creación</th>
          <th>Activo</th>
          <th data-sortable="false">Acciones</th>
        </tr>
      </thead>

      <tbody>
        @foreach($users as $user)
          <tr>
            <td><a href="{{ route('admin.users.show', $user) }}">{{ $user->name }}</a></td>
            <td>{{ $user->email }}</td>
            <td data-order="{{ $user->created_at->timestamp }}">
              {{ $user->created_at->format(config('app.datetime_format')) }}
            </td>
            <td>{{ $user->trashed() ? 'No' : 'Sí' }}</td>
            <td>
              @if(!$user->trashed())
                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-primary"
                  title="Editar">
                  <i class="fa fa-edit"></i>
                </a>
                <a href="#" class="btn btn-sm btn-danger confirm-delete" form-target="delete-form-{{ $user->id }}"
                  title="Eliminar" onclick="event.preventDefault();" >
                  <i class="fa fa-times"></i>
                </a>
                <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}"
                  method="POST" style="display: none;">
                  @csrf
                  @method('DELETE')
                </form>
              @else
                <a href="#" class="btn btn-sm btn-success" title="Restaurar"
                  onclick="event.preventDefault();
                  document.getElementById('restore-form-{{ $user->id }}').submit();">
                  <i class="fa fa-plus" aria-hidden="true"></i>
                </a>
                <form id="restore-form-{{ $user->id }}" action="{{ route('users.restore', $user) }}"
                  method="POST" style="display: none;">
                  @csrf
                </form>
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

</div>
@endsection

@push('js')
  {!! Helper::dataTablesJS() !!}

  {!! Helper::dataTables('#table') !!}

  {!! Helper::swalConfirm('.confirm-delete') !!}
@endpush
