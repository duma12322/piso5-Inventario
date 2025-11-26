@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/indexcomponentes.css') }}">
@section('content')
<div class="components-container">
    <!-- Header Section -->
    <div class="components-header">
        <div class="header-content">
            <div class="title-section">
                <!-- <i class="fas fa-microchip header-icon"></i> -->
                <h1>Listado de Componentes</h1>
                <p>Gestión de componentes del sistema</p>
            </div>
            <div class="header-stats">
                <div class="stat-card">
                    <i class="fas fa-cubes"></i>
                    <span class="stat-number">{{ $componentes->count() }}</span>
                    <span class="stat-label">Componentes</span>
                </div>
            </div>
        </div>
        
        <a href="{{ route('componentes.create') }}" class="btn-add-component">
            <i class="fas fa-plus-circle"></i>
            Agregar Componente
        </a>
    </div>

    <!-- Components Table -->
    <div class="table-wrapper">
        <table class="components-table">
            <thead>
                <tr>
                    <th class="column-id">
                        ID
                    </th>
                    <th class="column-equipo">
                        Equipo
                    </th>
                    <th class="column-type">
                        Tipo
                    </th>
                    <th class="column-brand">
                        Marca
                    </th>
                    <th class="column-model">
                        Modelo
                    </th>
                    <th class="column-status">
                        Estado
                    </th>
                    <th class="column-actions">
                        <i class="fas fa-cogs"></i>
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($componentes as $componente)
                <tr class="component-row" data-component-id="{{ $componente->id_componente }}">
                    <td class="component-id">
                        <span class="id-badge">{{ trim($componente->id_componente) ?: 'S/I' }}</span>
                    </td>
                    <td class="component-equipo">
                        <div class="equipo-info">
                            <i class="fas fa-laptop"></i>
                            <span>{{ trim($componente->equipo->marca) ?: 'S/E' }}</span>
                        </div>
                    </td>
                    <td class="component-type">
                        <span class="type-badge type-{{ Str::slug($componente->tipo_componente) }}">
                            <i class="fas {{ getComponentIcon($componente->tipo_componente) }}"></i>
                            {{ trim($componente->tipo_componente) ?: 'S/T' }}
                        </span>
                    </td>
                    <td class="component-brand">
                        {{ trim($componente->marca) ?: 'S/M' }}
                    </td>
                    <td class="component-model">
                        {{ trim($componente->modelo) ?: 'S/M' }}
                    </td>
                    <td class="component-status">
                        <span class="status-badge status-{{ Str::slug($componente->estado) }}">
                            <i class="fas {{($componente->estado) }}"></i>
                            {{ trim($componente->estado) ?: 'Desconocido' }}
                        </span>
                    </td>
                    <td class="component-actions">
                        <div class="action-buttons">
                            <a href="{{ route('componentes.edit', $componente->id_componente) }}" 
                               class="btn-action btn-edit" 
                               title="Editar componente">
                                <i class="fas fa-edit"></i>
                                <span class="tooltip">Editar</span>
                            </a>
                            
                            <form action="{{ route('componentes.destroy', $componente->id_componente) }}" 
                                  method="POST" 
                                  class="delete-form"
                                  onsubmit="return confirm('¿Estás seguro de eliminar este componente?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" title="Eliminar componente">
                                    <i class="fas fa-trash-alt"></i>
                                    <span class="tooltip">Eliminar</span>
                                </button>
                            </form>
                            
                            <!-- <button class="btn-action btn-info" title="Ver detalles" onclick="showComponentDetails({{ $componente->id_componente }})">
                                <i class="fas fa-info-circle"></i>
                                <span class="tooltip">Detalles</span>
                            </button>
                        </div> -->
                    </td>
                </tr>
                @empty
                <tr class="no-components">
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <h3>No hay componentes registrados</h3>
                            <p>Comienza agregando el primer componente al sistema</p>
                            <a href="{{ route('componentes.create') }}" class="btn-empty-state">
                                <i class="fas fa-plus"></i>
                                Agregar Primer Componente
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($componentes->hasPages())
    <div class="pagination-container">
        {{ $componentes->links() }}
    </div>
    @endif
</div>

<!-- Modal para detalles del componente -->
<div id="componentModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Detalles del Componente</h3>
            <span class="close-modal">&times;</span>
        </div>
        <div class="modal-body">
            <!-- Los detalles se cargarán aquí via AJAX -->
        </div>
    </div>
</div>

<!-- Helper PHP functions (deberías agregar esto en tu AppServiceProvider o helper) -->

@php
    function getComponentIcon($type) {
        $icons = [
            'CPU' => 'fa-microchip',
            'RAM' => 'fa-memory',
            'Disco Duro' => 'fa-hdd',
            'Tarjeta Madre' => 'fa-microchip',
            'Fuente de Poder' => 'fa-plug',
            'Monitor' => 'fa-desktop',
            'Teclado' => 'fa-keyboard',
            'Mouse' => 'fa-mouse',
            'Impresora' => 'fa-print',
            'Tarjeta de Video' => 'fa-video',
            'default' => 'fa-puzzle-piece'
        ];
        
        foreach ($icons as $key => $icon) {
            if (str_contains(strtolower($type), strtolower($key))) {
                return $icon;
            }
        }
        return $icons['default'];
    }
    
    function getStatusIcon($status) {
        $icons = [
            'Activo' => 'fa-check-circle',
            'Operativo' => 'fa-play-circle',
            'Buen funcionamiento'=> 'fa-play-circle',
            'Inactivo' => 'fa-pause-circle',
            'Dañado' => 'fa-times-circle',
            'Mantenimiento' => 'fa-tools',
            'default' => 'fa-question-circle'
        ];
        
        foreach ($icons as $key => $icon) {
            if (str_contains(strtolower($status), strtolower($key))) {
                return $icon;
            }
        }
        return $icons['default'];
    }
@endphp

@endsection

@section('scripts')
<script>
function showComponentDetails(componentId) {
    // Aquí puedes implementar una llamada AJAX para obtener los detalles del componente
    alert('Detalles del componente ID: ' + componentId);
    // En una implementación real, esto cargaría un modal con los detalles
}

// Modal functionality
const modal = document.getElementById('componentModal');
const closeModal = document.querySelector('.close-modal');

closeModal.addEventListener('click', function() {
    modal.style.display = 'none';
});

window.addEventListener('click', function(event) {
    if (event.target === modal) {
        modal.style.display = 'none';
    }
});
</script>
@endsection