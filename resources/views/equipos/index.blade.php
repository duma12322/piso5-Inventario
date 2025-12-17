@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/equiposprin.css') }}">

@section('content')
<div class="container">
    <!-- Sección del encabezado -->
    <div class="header-section">
        <h1 class="page-title">Lista de Equipos</h1>
        <div class="header-buttons">
            <a href="{{ route('equipos.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Agregar Equipo
            </a>
            <a href="{{ route('equipos.pdfGlobal', request()->query()) }}" class="btn btn-success" target="_blank">
                <i class="fas fa-file-pdf"></i> GENERAR PDF
            </a>
        </div>
    </div>

    <!-- BUSCADOR SIMPLE - Solo input y botón -->
    <div class="simple-search-container">
        <form action="{{ route('equipos.index') }}" method="GET" class="simple-search-form">
            <div class="search-wrapper">
                <input type="text" 
                       name="search" 
                       class="search-input" 
                       placeholder="Buscar equipos..." 
                       value="{{ request('search') }}"
                       aria-label="Buscar equipos">
                <button type="submit" class="search-button">
                    <i class="fas fa-search"></i>
                </button>
                @if(request('search'))
                <a href="{{ route('equipos.index') }}" class="clear-button" title="Limpiar búsqueda">
                    <i class="fas fa-times"></i>
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Contenedor de la tabla -->
    <div class="table-container">
        <table class="equipos-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nº Bien</th>
                    <th>MC</th>
                    <th>Modelo</th>
                    <th>Dirección</th>
                    <th>División</th>
                    <th>COORD.</th>
                    <th>Estado Funcional</th>
                    <th>Estado Tecnológico</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($equipos as $e)
                <tr>
                    <td class="equipo-id">{{ trim($e->id_equipo) ?: 'S/I' }}</td>
                    <td class="numero_bien">{{ trim($e->numero_bien) ?: 'S/I' }}</td>
                    <td>{{ trim($e->marca) ?: 'S/M' }}</td>
                    <td>{{ trim($e->modelo) ?: 'S/M' }}</td>
                    <td>{{ trim($e->direccion->nombre_direccion ?? '') ?: 'N/A' }}</td>
                    <td>{{ trim($e->division->nombre_division ?? '') ?: 'N/A' }}</td>
                    <td>{{ trim($e->coordinacion->nombre_coordinacion ?? '') ?: 'N/A' }}</td>
                    <td>
                        @php
                        $estadoFuncional = trim($e->estado_funcional) ?: 'desconocido';
                        $estadoFuncionalLower = strtolower(str_replace(' ', '-', $estadoFuncional));
                        @endphp
                        <span class="status-badge status-{{ $estadoFuncionalLower }}">
                            {{ $estadoFuncional == 'desconocido' ? 'Desconocido' : $e->estado_funcional }}
                        </span>
                    </td>
                    <td>
                        @php
                        if (is_array($e->estado_tecnologico)) {
                            $estadoTec = $e->estado_tecnologico['estado'] ?? 'desconocido';
                        } else {
                            $estadoTec = trim($e->estado_tecnologico) ?: 'desconocido';
                        }
                        $estadoTecLower = strtolower(str_replace(' ', '-', $estadoTec));
                        @endphp
                        <span class="status-badge status-{{ $estadoTecLower }}">
                            {{ $estadoTec == 'desconocido' ? 'Desconocido' : $estadoTec }}
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

                            <a href="{{ route('equipos.pdf', $e->id_equipo) }}" class="btn-action btn-pdf" title="GENERAR PDF">
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
                    <td colspan="10" class="no-data">
                        <i class="fas fa-info-circle"></i>
                        @if(request('search'))
                            No se encontraron equipos para "{{ request('search') }}"
                        @else
                            No hay equipos registrados.
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <!-- Paginación -->
        @if($equipos->hasPages())
        <div class="pagination-wrapper">
            {{ $equipos->links() }}
        </div>
        @endif
    </div>
</div>


@endsection