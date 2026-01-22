@extends('layouts.app')

{{-- Importar estilos específicos para la página de Coordinaciones --}}
<link rel="stylesheet" href="{{ asset('css/indexcomponentes.css') }}">

{{-- Título de la página --}}
@section('title', 'Coordinaciones')

@section('content')
{{-- Contenedor principal de Coordinaciones --}}
<div class="components-container">

    {{-- Header de la sección con título, descripción y estadísticas --}}
    <div class="components-header">
        <div class="header-content">

            {{-- Sección de título y descripción --}}
            <div class="title-section">
                <h1>Listado de Coordinaciones</h1>
                <p>Gestión de las coordinaciones</p>
            </div>

            {{-- Estadísticas rápidas --}}
            <div class="header-stats">
                <div class="stat-card">
                    <i class="fas fa-network-wired"></i>
                    {{-- Número total de coordinaciones --}}
                    <span class="stat-number">{{ $coordinaciones->count() }}</span>
                    <span class="stat-label">Coordinaciones</span>
                </div>
            </div>
        </div>

        {{-- Botón para agregar una nueva coordinación --}}
        <a href="{{ route('coordinaciones.create') }}" class="btn-add-component">
            <i class="fas fa-plus-circle"></i>
            Agregar Coordinación
        </a>
    </div>

    {{-- Tabla de Coordinaciones --}}
    <div class="table-wrapper">
        <table class="components-table">
            <thead>
                <tr>
                    <th class="column-brand">División</th>
                    <th class="column-model">Coordinación</th>
                    <th class="column-actions">Acciones</th>
                </tr>
            </thead>
            <tbody>
                {{-- Iterar sobre todas las coordinaciones --}}
                @forelse ($coordinaciones as $c)
                <tr class="component-row">
                    {{-- Mostrar nombre de la división o 'S/D' si no existe --}}
                    <td class="component-brand">
                        {{ $c->division->nombre_division ?? 'S/D' }}
                    </td>

                    {{-- Nombre de la coordinación --}}
                    <td class="component-model">
                        {{ $c->nombre_coordinacion }}
                    </td>

                    {{-- Acciones: Editar y Eliminar --}}
                    <td class="component-actions">
                        <div class="action-buttons">

                            {{-- Botón para editar coordinación --}}
                            <a href="{{ route('coordinaciones.edit', $c->id_coordinacion) }}"
                                class="btn-action btn-edit" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>

                            {{-- Formulario para eliminar coordinación --}}
                            <form action="{{ route('coordinaciones.destroy', $c->id_coordinacion) }}" method="POST"
                                class="delete-form" onsubmit="return confirm('¿Eliminar coordinación?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" title="Eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>
                @empty
                {{-- Estado vacío cuando no hay coordinaciones --}}
                <tr class="no-components">
                    <td colspan="4">
                        <div class="empty-state">
                            <i class="fas fa-network-wired"></i>
                            <h3>No hay coordinaciones registradas</h3>
                            {{-- Botón para agregar la primera coordinación --}}
                            <a href="{{ route('coordinaciones.create') }}" class="btn-empty-state">
                                <i class="fas fa-plus"></i>
                                Agregar Primera Coordinación
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación de la lista de coordinaciones --}}
    <div class="pagination-container">
        {{ $coordinaciones->appends(request()->query())->links() }}
    </div>
</div>
@endsection