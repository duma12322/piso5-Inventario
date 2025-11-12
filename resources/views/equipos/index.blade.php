@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">Lista de Equipos</h3>
    <a href="{{ route('equipos.create') }}" class="btn btn-success mb-3">Agregar Equipo</a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Dirección</th>
                <th>División</th>
                <th>Coordinación</th>
                <th>Estado Funcional</th>
                <th>Estado Tecnológico</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($equipos as $e)
            <tr>
                <td>{{ trim($e->id_equipo) ?: 'S/I' }}</td>
                <td>{{ trim($e->marca) ?: 'S/M' }}</td>
                <td>{{ trim($e->modelo) ?: 'S/M' }}</td>
                <td>{{ trim($e->direccion->nombre_direccion ?? '') ?: 'N/A' }}</td>
                <td>{{ trim($e->division->nombre_division ?? '') ?: 'N/A' }}</td>
                <td>{{ trim($e->coordinacion->nombre_coordinacion ?? '') ?: 'N/A' }}</td>
                <td>{{ trim($e->estado_funcional) ?: 'Desconocido' }}</td>
                <td>{{ is_array($e->estado_tecnologico) ? ($e->estado_tecnologico['estado'] ?? 'Desconocido') : ($e->estado_tecnologico ?: 'Desconocido') }}</td>
                <td>
                    <a href="{{ route('equipos.edit', $e->id_equipo) }}" class="btn btn-primary btn-sm">Editar</a>

                    <form action="{{ route('equipos.destroy', $e->id_equipo) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('¿Eliminar este equipo?')">Eliminar</button>
                    </form>

                    <a href="{{ route('componentes.porEquipo', $e->id_equipo) }}" class="btn btn-info btn-sm">Ver Componentes</a>

                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">No hay equipos registrados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection