@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Agregar Usuario</h3>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('usuarios.store') }}">
        @csrf
        <div class="form-group">
            <label>Usuario</label>
            <input type="text" name="usuario" class="form-control" value="{{ old('usuario') }}" required>
            @error('usuario')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group mt-2">
            <label>Contraseña</label>
            <input type="password" name="password" class="form-control" required>
            @error('password')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group mt-2">
            <label>Rol</label>
            <select name="rol" class="form-control" required>
                <option value="Administrador" {{ old('rol')=='Administrador' ? 'selected' : '' }}>Administrador</option>
                <option value="Usuario" {{ old('rol')=='Usuario' ? 'selected' : '' }}>Usuario</option>
            </select>
            @error('rol')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group mt-3 d-flex justify-content-start gap-2">
            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary mt-2">← Volver</a>
            <button class="btn btn-success mt-2">Guardar</button>
        </div>
    </form>
</div>
@endsection