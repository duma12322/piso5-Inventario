@extends('layouts.app')

<!-- Incluimos estilos CSS adicionales para la vista de Direcciones -->
<link rel="stylesheet" href="{{ asset('css/indexcomponentes.css') }}">
<link rel="stylesheet" href="{{ asset('css/direccionesprin.css') }}">

@section('title', 'Direcciones')

@section('content')
<div class="direcciones-container">
    <!-- ================================
             Header de la sección
             Muestra icono, título, descripción y botón de agregar
             ================================ -->
    <div class="direcciones-header">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-building"></i> <!-- Icono de edificio -->
            </div>
            <div class="header-text">
                <h1>Direcciones</h1>
                <p>Gestión de direcciones del sistema</p>
            </div>
        </div>

        <div class="header-actions">
            <!-- Botón para agregar una nueva dirección -->
            <a href="{{ route('direcciones.create') }}" class="btn-add">
                <i class="fas fa-plus"></i> Agregar Dirección
            </a>
        </div>
    </div>

    <!-- ================================
             Estadísticas de Direcciones
             ================================ -->
    <div class="stats-container">
        <!-- Total de direcciones registradas -->
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-building"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $direcciones->count() }}</h3>
                <p>Direcciones Registradas</p>
            </div>
        </div>

        <!-- Total de registros (puede coincidir con el conteo anterior) -->
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

    <!-- ================================
             Tabla de Direcciones
             ================================ -->
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
                    <!-- Recorremos las direcciones existentes -->
                    @forelse ($direcciones as $direccion)
                    <tr class="component-row" data-id="{{ $direccion->id_direccion }}">
                        <td class="text-start">
                            <div class="direccion-info justify-content-start">
                                <div class="direccion-icon">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div class="direccion-details text-start">
                                    <strong>{{ $direccion->nombre_direccion }}</strong>
                                    <!-- Mostrar descripción si existe -->
                                    @if($direccion->descripcion)
                                    <p class="direccion-description">{{ $direccion->descripcion }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="component-actions">
                            <div class="action-buttons">
                                <!-- Botón para editar la dirección -->
                                <a href="{{ route('direcciones.edit', $direccion->id_direccion) }}"
                                    class="btn-action btn-edit" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- Formulario para eliminar la dirección -->
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
                    <!-- Estado vacío si no hay direcciones -->
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

        <!-- ================================
                 Paginación de la tabla
                 ================================ -->
        @if($direcciones->hasPages())
        <div class="pagination-container">
            {{ $direcciones->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

    <!-- ================================
             Modal de confirmación de eliminación
             ================================ -->
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
    document.addEventListener('DOMContentLoaded', function() {
        // ================================
        // Elementos principales del modal de confirmación
        // ================================
        const modal = document.getElementById('confirmModal'); // Contenedor del modal
        const deleteButtons = document.querySelectorAll('.delete-confirm'); // Botones que disparan el modal
        const direccionName = document.getElementById('direccion-name'); // Span donde se mostrará el nombre de la dirección
        const deleteForm = document.getElementById('deleteForm'); // Formulario que ejecutará la eliminación

        // ================================
        // Abrir modal al hacer clic en un botón de eliminar
        // ================================
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Obtener ID y nombre de la dirección desde los atributos data
                const direccionId = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');

                // Actualizar contenido del modal
                direccionName.textContent = name; // Mostrar el nombre de la dirección en el modal
                deleteForm.action = `/direcciones/${direccionId}`; // Configurar la acción del formulario con la URL correcta

                // Mostrar modal
                modal.style.display = 'flex';
            });
        });

        // ================================
        // Cerrar modal al hacer clic fuera del contenido
        // ================================
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                closeModal(); // Llamar función para cerrar modal
            }
        });

        // ================================
        // Animaciones para filas de direcciones
        // ================================
        const rows = document.querySelectorAll('.direccion-row'); // Selecciona todas las filas de direcciones
        rows.forEach((row, index) => {
            row.style.animationDelay = `${index * 0.05}s`; // Retraso progresivo por fila para animación
            row.classList.add('animate-fade-in'); // Agrega clase de animación
        });

        // ================================
        // Tooltips personalizados
        // ================================
        const tooltipElements = document.querySelectorAll('[data-tooltip]'); // Elementos que tengan atributo data-tooltip
        tooltipElements.forEach(element => {
            // Mostrar tooltip al pasar el mouse
            element.addEventListener('mouseenter', function(e) {
                const tooltip = document.createElement('div');
                tooltip.className = 'tooltip'; // Clase de estilos para tooltip
                tooltip.textContent = this.getAttribute('data-tooltip'); // Contenido del tooltip
                document.body.appendChild(tooltip); // Agregar tooltip al DOM

                // Posicionar tooltip centrado horizontalmente sobre el elemento
                const rect = this.getBoundingClientRect();
                tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
                tooltip.style.top = rect.top - tooltip.offsetHeight - 10 + 'px'; // 10px por encima del elemento

                this.tooltipElement = tooltip; // Guardar referencia para eliminar después
            });

            // Eliminar tooltip al retirar el mouse
            element.addEventListener('mouseleave', function() {
                if (this.tooltipElement) {
                    this.tooltipElement.remove(); // Remover del DOM
                    this.tooltipElement = null; // Limpiar referencia
                }
            });
        });
    });

    // ================================
    // Función para cerrar el modal
    // ================================
    function closeModal() {
        document.getElementById('confirmModal').style.display = 'none'; // Oculta el modal
    }
</script>
@endsection