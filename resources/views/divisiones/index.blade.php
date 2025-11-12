@extends('layouts.app')

@section('title', 'Divisiones')

@section('content')
<div class="container mt-4">
    <h3>Divisiones</h3>
    <a href="{{ route('divisiones.create') }}" class="btn btn-success mb-2">Agregar División</a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Dirección</th>
                <th>Nombre División</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($divisiones as $division)
            <tr>
                <td>{{ $division->id_division }}</td>
                <td>{{ $division->direccion->nombre_direccion ?? '' }}</td>
                <td>{{ $division->nombre_division }}</td>
                <td>
                    <a href="{{ route('divisiones.edit', $division->id_division) }}" class="btn btn-primary btn-sm">Editar</a>
                    <form action="{{ route('divisiones.destroy', $division->id_division) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('¿Eliminar división?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection