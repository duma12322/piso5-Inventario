@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/indexcomponentes.css') }}">
@section('title', 'Divisiones')

@section('content')
    <div class="components-container">
        <div class="components-header">
            <div class="header-content">
                <div class="title-section">
                    <h1>Listado de Divisiones</h1>
                    <p>Gestión de las divisiones</p>
                </div>
                <div class="header-stats">
                    <div class="stat-card">
                        <i class="fas fa-building"></i>
                        <span class="stat-number">{{ $divisiones->count() }}</span>
                        <span class="stat-label">Divisiones</span>
                    </div>
                </div>
            </div>
            <a href="{{ route('divisiones.create') }}" class="btn-add-component">
                <i class="fas fa-plus-circle"></i>
                Agregar División
            </a>
        </div>

        <div class="table-wrapper">
            <table class="components-table">
                <thead>
                    <tr>
                        <th class="column-brand">Dirección</th>
                        <th class="column-model">Nombre División</th>
                        <th class="column-actions">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($divisiones as $division)
                        <tr class="component-row">
                            <td class="component-brand">
                                {{ $division->direccion->nombre_direccion ?? 'S/D' }}
                            </td>
                            <td class="component-model">
                                {{ $division->nombre_division }}
                            </td>
                            <td class="component-actions">
                                <div class="action-buttons">
                                    <a href="{{ route('divisiones.edit', $division->id_division) }}" class="btn-action btn-edit"
                                        title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
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
                        <tr class="no-components">
                            <td colspan="4">
                                <div class="empty-state">
                                    <i class="fas fa-building"></i>
                                    <h3>No hay divisiones registradas</h3>
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
        <div class="pagination-container">
            {{ $divisiones->appends(request()->query())->links() }}
        </div>
    </div>
@endsection