{{-- resources/views/componentes/index.blade.php --}}
@extends('layouts.app') {{-- Layout principal con <head> y menú --}}

@section('content')
<div class="container mt-4">
    <h3>Listado de Componentes</h3>

    <a href="{{ route('componentes.create') }}" class="btn btn-success mb-2">Agregar Componente</a>

    <table class="table table-bordered table-hover">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Equipo</th>
                <th>Tipo</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($componentes as $componente)
            <tr>
                <td>{{ trim($componente->id_componente) ?: 'S/I' }}</td>
                <td>{{ trim($componente->equipo->marca) ?: 'S/E' }}</td> {{-- Relación con equipo --}}
                <td>{{ trim($componente->tipo_componente) ?: 'S/T' }}</td>
                <td>{{ trim($componente->marca) ?: 'S/M' }}</td>
                <td>{{ trim($componente->modelo) ?: 'S/M' }}</td>
                <td>{{ trim($componente->estado) ?: 'Desconocido' }}</td>
                <td>
                    <a href="{{ route('componentes.edit', $componente->id_componente) }}" class="btn btn-primary btn-sm">Editar</a>
                    <form action="{{ route('componentes.destroy', $componente->id_componente) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Eliminar componente?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Eliminar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">No hay componentes registrados</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection