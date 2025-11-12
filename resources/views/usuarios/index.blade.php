@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Gestión de Usuarios</h3>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('usuarios.create') }}" class="btn btn-success mb-3">Agregar Usuario</a>

    <table class="table table-bordered table-hover">
        <thead class="thead-dark">
            <tr>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $u)
            <tr>
                <td>{{ $u->usuario }}</td>
                <td>{{ $u->rol }}</td>
                <td>
                    <a href="{{ route('usuarios.edit', ['usuario' => $u->id_usuario]) }}" class="btn btn-primary btn-sm">Editar</a>

                    <form action="{{ route('usuarios.destroy', ['usuario' => $u->id_usuario]) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar usuario?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                    </form>

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection