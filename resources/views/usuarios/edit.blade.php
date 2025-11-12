@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Editar Usuario</h3>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('usuarios.update', ['usuario' => $usuario->id_usuario]) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Usuario</label>
            <input type="text" name="usuario" class="form-control" value="{{ old('usuario', $usuario->usuario) }}" required>
        </div>
        <div class="form-group mt-2">
            <label>Rol</label>
            <select name="rol" class="form-control" required>
                <option value="Administrador" {{ old('rol', $usuario->rol) == 'Administrador' ? 'selected' : '' }}>Administrador</option>
                <option value="Usuario" {{ old('rol', $usuario->rol) == 'Usuario' ? 'selected' : '' }}>Usuario</option>
            </select>
        </div>
        <div class="form-group mt-2">
            <label>Nueva Contraseña (opcional)</label>
            <input type="password" name="password" class="form-control" placeholder="Dejar vacío para no cambiarla">
        </div>
        <div class="form-group mt-2">
            <label>Contraseña Actual (obligatoria para editar)</label>
            <input type="password" name="password_actual" class="form-control" required>
        </div>

        <div class="form-group mt-3 d-flex justify-content-start gap-2">
            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary mt-3">← Volver</a>
            <button type="submit" class="btn btn-primary mt-3">Actualizar</button>
        </div>

    </form>
</div>
@endsection