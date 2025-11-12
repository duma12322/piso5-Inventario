@extends('layouts.app')

@section('title', 'Coordinaciones')

@section('content')
<div class="container mt-4">
    <h3>Coordinaciones</h3>
    <a href="{{ route('coordinaciones.create') }}" class="btn btn-success mb-2">Agregar Coordinación</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>División</th>
                <th>Coordinación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($coordinaciones as $c)
            <tr>
                <td>{{ $c->id_coordinacion }}</td>
                <td>{{ $c->division->nombre_division ?? '' }}</td>
                <td>{{ $c->nombre_coordinacion }}</td>
                <td>
                    <a href="{{ route('coordinaciones.edit', $c->id_coordinacion) }}" class="btn btn-primary btn-sm">Editar</a>
                    <form action="{{ route('coordinaciones.destroy', $c->id_coordinacion) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar coordinación?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection