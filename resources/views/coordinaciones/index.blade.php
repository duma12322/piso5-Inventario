@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/indexcomponentes.css') }}">
@section('title', 'Coordinaciones')

@section('content')
    <div class="components-container">
        <div class="components-header">
            <div class="header-content">
                <div class="title-section">
                    <h1>Listado de Coordinaciones</h1>
                    <p>Gestión de las coordinaciones</p>
                </div>
                <div class="header-stats">
                    <div class="stat-card">
                        <i class="fas fa-network-wired"></i>
                        <span class="stat-number">{{ $coordinaciones->count() }}</span>
                        <span class="stat-label">Coordinaciones</span>
                    </div>
                </div>
            </div>
            <a href="{{ route('coordinaciones.create') }}" class="btn-add-component">
                <i class="fas fa-plus-circle"></i>
                Agregar Coordinación
            </a>
        </div>

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
                    @forelse ($coordinaciones as $c)
                        <tr class="component-row">
                            <td class="component-brand">
                                {{ $c->division->nombre_division ?? 'S/D' }}
                            </td>
                            <td class="component-model">
                                {{ $c->nombre_coordinacion }}
                            </td>
                            <td class="component-actions">
                                <div class="action-buttons">
                                    <a href="{{ route('coordinaciones.edit', $c->id_coordinacion) }}"
                                        class="btn-action btn-edit" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
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
                        <tr class="no-components">
                            <td colspan="4">
                                <div class="empty-state">
                                    <i class="fas fa-network-wired"></i>
                                    <h3>No hay coordinaciones registradas</h3>
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
        <div class="pagination-container">
            {{ $coordinaciones->appends(request()->query())->links() }}
        </div>
    </div>
@endsection