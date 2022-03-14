<form action="{{ route("admin.roles.$action", $role) }}" method="POST">
    @csrf
    @method($method)
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Nombre*</label>
                        <input type="text" name="name" class="form-control" id="name" required
                            placeholder="Nombre del Rol"
                            value="{{ old('name', $role->name) }}">
                        @error('name')
                            <span class="error invalid-feedback" style="display:inline">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="level">Nivel*</label>
                        <input type="number" name="level" class="form-control" id="level" required
                            placeholder="Nivel del Rol"
                            value="{{ old('level', $role->level) }}"
                            min="{{ Auth::user()->roles->min('level') }}">
                        @error('level')
                            <span class="error invalid-feedback" style="display:inline">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group float-right">
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-sm btn-secondary">Regresar</a>
                        <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
                    </div>
                </div>
                <div class="card-footer">
                    * Campo obligatorio
                </div>
            </div>
        </div>
    </div>
</form>
