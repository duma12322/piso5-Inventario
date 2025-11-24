@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/equiposprin.css') }}">
@section('content')
<div class="container">
    <div class="header-section">
        <h1 class="page-title">Lista de Equipos</h1>
        <a href="{{ route('equipos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Agregar Equipo
        </a>
    </div>

    <div class="table-container">
        <table class="equipos-table">
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
                    <td class="equipo-id">{{ trim($e->id_equipo) ?: 'S/I' }}</td>
                    <td>{{ trim($e->marca) ?: 'S/M' }}</td>
                    <td>{{ trim($e->modelo) ?: 'S/M' }}</td>
                    <td>{{ trim($e->direccion->nombre_direccion ?? '') ?: 'N/A' }}</td>
                    <td>{{ trim($e->division->nombre_division ?? '') ?: 'N/A' }}</td>
                    <td>{{ trim($e->coordinacion->nombre_coordinacion ?? '') ?: 'N/A' }}</td>
                    <td>
                        <span class="status-badge status-{{ strtolower(trim($e->estado_funcional) ?: 'desconocido') }}">
                            {{ trim($e->estado_funcional) ?: 'Desconocido' }}
                        </span>
                    </td>
                    <td>
                        @php
                            $estadoTec = is_array($e->estado_tecnologico) ? ($e->estado_tecnologico['estado'] ?? 'Desconocido') : ($e->estado_tecnologico ?: 'Desconocido');
                        @endphp
                        <span class="status-badge status-{{ strtolower($estadoTec) }}">
                            {{ $estadoTec }}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('equipos.edit', $e->id_equipo) }}" class="btn-action btn-edit" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            <a href="{{ route('componentes.porEquipo', $e->id_equipo) }}" class="btn-action btn-view" title="Ver Componentes">
                                <i class="fas fa-list"></i>
                            </a>
                            
                            <a href="{{ route('equipos.pdf', $e->id_equipo) }}" class="btn-action btn-pdf" title="Generar PDF">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                            
                            <form action="{{ route('equipos.destroy', $e->id_equipo) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar este equipo?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="no-data">
                        <i class="fas fa-info-circle"></i>
                        No hay equipos registrados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection