@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Listado de Componentes Opcionales</h3>
    <a href="{{ route('componentesOpcionales.create') }}" class="btn btn-success mb-2">Agregar Componente Opcional</a>

    <table class="table table-bordered table-hover">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Equipo</th>
                <th>Modelo</th>
                <th>Tipo</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($opcionales as $o)
            <tr>
                <td>{{ trim($o->id_opcional) ?: 'S/I' }}</td>
                <td>{{ trim($o->equipo->marca) ?: 'S/E' }}</td>
                <td>{{ trim($o->equipo->modelo) ?: 'S/M' }}</td>
                <td>{{ trim($o->tipo_opcional) ?: 'S/T' }}</td>
                <td>{{ trim($o->marca) ?: 'S/M' }}</td>
                <td>{{ trim($o->modelo) ?: 'S/M' }}</td>
                <td>{{ trim($o->estado) ?: 'Desconocido' }}</td>
                <td>
                    <a href="{{ route('componentesOpcionales.edit', $o->id_opcional) }}" class="btn btn-primary btn-sm">Editar</a>

                    <form action="{{ route('componentesOpcionales.destroy', $o->id_opcional) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar componente opcional?')">
                            Eliminar
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>

    </table>
</div>
@endsection