@extends('admin.layouts.app')

@section('title', 'Permisos')

@section('title-header', 'Permisos')

@push('css')
    {!! Helper::dataTablesCSS() !!}
@endpush

@section('content')
<!-- Default box -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Permisos</h3>
        <div class="float-right">
            @if ($privateRoutesWithoutPermission->count())
                <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal"
                    data-target="#modal-private-routes-without-permission">
                    Mostrar Rutas Privadas sin Permisos ({{ $privateRoutesWithoutPermission->count() }})
                </button>
            @endif
            @if ($unnecessaryPermissions->count())
                <a href="#" onclick="event.preventDefault(); document.getElementById('clean-permissions-form').submit();"
                    class="btn btn-danger btn-sm">
                    Eliminar Permisos Innecesarios ({{ $unnecessaryPermissions->count() }})
                </a>
                <form id="clean-permissions-form" action="{{ route('admin.permissions.clean') }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            @endif


            <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary btn-sm">Crear</a>
        </div>
    </div>
    <div class="card-body">
        <table id="table" class="table table-sm table-bordered table-hover w-100" data-order='[[2, "asc"], [0, "asc"]]'>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Nombre de la Ruta</th>
                    <th>Nivel</th>
                    <th>Grupo</th>
                    <th>Necesario</th>
                    <th data-sortable="false">Acciones</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

{{-- Modal with private routes without permission --}}
<div class="modal fade" id="modal-private-routes-without-permission">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Rutas Privadas sin Permiso</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <div class="row">
                    @foreach ($privateRoutesWithoutPermission as $route)
                        <div class="col-4">
                            <p>{{ $route->getName() }}</p>
                        </div>
                    @endforeach
                </div>
			</div>
			<div class="modal-footer justify-content-rigth">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
{{-- /.modal --}}

@endsection

@php
    $route = route('admin.permissions.index');
    $options = <<<JAVASCRIPT
        {
            ajax: "$route",
            procesing: true,
            serverSide: true,
            columns: [
                {
                    data: 'name',
                    render: (data, type, row) => `<a href='\${row.show_link}'>\${data}</a>`
                },
                {data: 'route_name'},
                {data: 'level'},
                {data: 'group'},
                {data: 'is_private_route'},
                {data: 'actions'},
            ],
        }
JAVASCRIPT;
@endphp

@push('js')
    {!! Helper::dataTablesJS() !!}

    {!! Helper::dataTables("#table", $options) !!}

    {!! Helper::swalConfirm('.confirm-delete') !!}
@endpush
