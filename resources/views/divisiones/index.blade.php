@extends('layouts.app')

{{-- ============================================================
     Incluye el CSS principal para la tabla de componentes
     ============================================================ --}}
<link rel="stylesheet" href="{{ asset('css/indexcomponentes.css') }}">

@section('title', 'Divisiones') {{-- Título de la página --}}

@section('content')
<div class="components-container">
    {{-- ============================================================
             Header de la sección de divisiones
             ============================================================ --}}
    <div class="components-header">
        <div class="header-content">
            {{-- Título principal y descripción --}}
            <div class="title-section">
                <h1>Listado de Divisiones</h1>
                <p>Gestión de las divisiones</p>
            </div>

            {{-- Estadísticas rápidas --}}
            <div class="header-stats">
                <div class="stat-card">
                    <i class="fas fa-building"></i>
                    {{-- Número total de divisiones --}}
                    <span class="stat-number">{{ $divisiones->count() }}</span>
                    <span class="stat-label">Divisiones</span>
                </div>
            </div>
        </div>

        {{-- Botón para agregar una nueva división --}}
        <a href="{{ route('divisiones.create') }}" class="btn-add-component">
            <i class="fas fa-plus-circle"></i>
            Agregar División
        </a>
    </div>

    {{-- ============================================================
             Tabla con el listado de divisiones
             ============================================================ --}}
    <div class="table-wrapper">
        <table class="components-table">
            <thead>
                <tr>
                    <th class="column-brand">Dirección</th> {{-- Dirección asociada a la división --}}
                    <th class="column-model">Nombre División</th> {{-- Nombre de la división --}}
                    <th class="column-actions">Acciones</th> {{-- Botones de acción --}}
                </tr>
            </thead>
            <tbody>
                {{-- Iteración sobre todas las divisiones --}}
                @forelse ($divisiones as $division)
                <tr class="component-row">
                    {{-- Muestra la dirección de la división o 'S/D' si no existe --}}
                    <td class="component-brand">
                        {{ $division->direccion->nombre_direccion ?? 'S/D' }}
                    </td>

                    {{-- Muestra el nombre de la división --}}
                    <td class="component-model">
                        {{ $division->nombre_division }}
                    </td>

                    {{-- Acciones: Editar y Eliminar --}}
                    <td class="component-actions">
                        <div class="action-buttons">
                            {{-- Botón de edición --}}
                            <a href="{{ route('divisiones.edit', $division->id_division) }}" class="btn-action btn-edit"
                                title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>

                            {{-- Formulario de eliminación --}}
                            <form action="{{ route('divisiones.destroy', $division->id_division) }}" method="POST"
                                class="delete-form" onsubmit="return confirm('¿Eliminar división?')">
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
                {{-- Estado vacío si no hay divisiones registradas --}}
                <tr class="no-components">
                    <td colspan="4">
                        <div class="empty-state">
                            <i class="fas fa-building"></i>
                            <h3>No hay divisiones registradas</h3>
                            {{-- Botón para agregar la primera división --}}
                            <a href="{{ route('divisiones.create') }}" class="btn-empty-state">
                                <i class="fas fa-plus"></i>
                                Agregar Primera División
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ============================================================
             Paginación de la tabla
             ============================================================ --}}
    <div class="pagination-container">
        {{ $divisiones->appends(request()->query())->links() }}
    </div>
</div>
@endsection