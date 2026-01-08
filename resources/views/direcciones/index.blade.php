@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/indexcomponentes.css') }}">
<link rel="stylesheet" href="{{ asset('css/direccionesprin.css') }}">
@section('title', 'Direcciones')

@section('content')
    <div class="direcciones-container">
        <!-- Header Section -->
        <div class="direcciones-header">
            <div class="header-content">
                <div class="header-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="header-text">
                    <h1>Direcciones</h1>
                    <p>Gestión de direcciones del sistema</p>
                </div>
            </div>

            <div class="header-actions">
                <a href="{{ route('direcciones.create') }}" class="btn-add">
                    <i class="fas fa-plus"></i> Agregar Dirección
                </a>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $direcciones->count() }}</h3>
                    <p>Direcciones Registradas</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $direcciones->count() }}</h3>
                    <p>Total Registros</p>
                </div>
            </div>
        </div>

        <!-- Tabla de Direcciones -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="direcciones-table">
                    <thead>
                        <tr>
                            <th class="text-start">Nombre de la Dirección</th>
                            <!-- <th class="text-center">Estado</th> -->
                            <th class="column-actions">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($direcciones as $direccion)
                            <tr class="component-row" data-id="{{ $direccion->id_direccion }}">
                                <td class="text-start">
                                    <div class="direccion-info justify-content-start">
                                        <div class="direccion-icon">
                                            <i class="fas fa-building"></i>
                                        </div>
                                        <div class="direccion-details text-start">
                                            <strong>{{ $direccion->nombre_direccion }}</strong>
                                            @if($direccion->descripcion)
                                                <p class="direccion-description">{{ $direccion->descripcion }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- </td>
                                                        <td class="text-center">
                                                            @if(isset($direccion->estado) && $direccion->estado == 'inactivo')
                                                            <span class="badge badge-inactive">Inactiva</span>
                                                            @else
                                                            <span class="badge badge-active">Activa</span>
                                                            @endif
                                                        </td> -->
                                <td class="component-actions">
                                    <div class="action-buttons">
                                        <a href="{{ route('direcciones.edit', $direccion->id_direccion) }}"
                                            class="btn-action btn-edit" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form action="{{ route('direcciones.destroy', $direccion->id_direccion) }}"
                                            method="POST" class="delete-form" onsubmit="return confirm('¿Eliminar dirección?')">
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
                            <tr>
                                <td colspan="4" class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <h4>No hay direcciones registradas</h4>
                                    <p>Comienza agregando una nueva dirección</p>
                                    <a href="{{ route('direcciones.create') }}" class="btn-add-empty">
                                        <i class="fas fa-plus"></i> Agregar Primera Dirección
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if($direcciones->hasPages())
                <div class="pagination-container">
                    {{ $direcciones->appends(request()->query())->links() }}
                </div>
            @endif
        </div>

        <!-- Modal de Confirmación -->
        <div id="confirmModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3><i class="fas fa-exclamation-triangle"></i> Confirmar Eliminación</h3>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro de eliminar la dirección <strong id="direccion-name"></strong>?</p>
                    <p class="text-warning">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Cancelar</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-confirm">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Modal de confirmación
            const modal = document.getElementById('confirmModal');
            const deleteButtons = document.querySelectorAll('.delete-confirm');
            const direccionName = document.getElementById('direccion-name');
            const deleteForm = document.getElementById('deleteForm');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const direccionId = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');

                    // Actualizar modal
                    direccionName.textContent = name;
                    deleteForm.action = `/direcciones/${direccionId}`;

                    // Mostrar modal
                    modal.style.display = 'flex';
                });
            });

            // Cerrar modal al hacer clic fuera
            window.addEventListener('click', function (event) {
                if (event.target === modal) {
                    closeModal();
                }
            });

            // Animaciones para filas
            const rows = document.querySelectorAll('.direccion-row');
            rows.forEach((row, index) => {
                row.style.animationDelay = `${index * 0.05}s`;
                row.classList.add('animate-fade-in');
            });

            // Tooltips
            const tooltipElements = document.querySelectorAll('[data-tooltip]');
            tooltipElements.forEach(element => {
                element.addEventListener('mouseenter', function (e) {
                    const tooltip = document.createElement('div');
                    tooltip.className = 'tooltip';
                    tooltip.textContent = this.getAttribute('data-tooltip');
                    document.body.appendChild(tooltip);

                    const rect = this.getBoundingClientRect();
                    tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
                    tooltip.style.top = rect.top - tooltip.offsetHeight - 10 + 'px';

                    this.tooltipElement = tooltip;
                });

                element.addEventListener('mouseleave', function () {
                    if (this.tooltipElement) {
                        this.tooltipElement.remove();
                        this.tooltipElement = null;
                    }
                });
            });
        });

        function closeModal() {
            document.getElementById('confirmModal').style.display = 'none';
        }
    </script>
@endsection