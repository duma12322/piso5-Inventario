@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/componentesopcionalesblade.css') }}">

@section('content')
<div class="container mt-4">
    <div class="header-container">
        <h3>Listado de Componentes Opcionales</h3>
        <a href="{{ route('componentesOpcionales.create') }}" class="btn btn-success mb-2">Agregar Componente Opcional</a>
    </div>

    <div class="table-container">
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Equipo</th>
                    <th>Modelo Equipo</th>
                    <th>Tipo Opcional</th>
                    <th>Marca Opcional</th>
                    <th>Modelo Opcional</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($opcionales as $o)
                <tr>
                    <td>{{ trim($o->id_opcional) ?: 'S/I' }}</td>
                    <td>{{ isset($o->equipo) && trim($o->equipo->marca) ? $o->equipo->marca : 'S/E' }}</td>
                    <td>{{ isset($o->equipo) && trim($o->equipo->modelo) ? $o->equipo->modelo : 'S/M' }}</td>
                    <td>{{ trim($o->tipo_opcional) ?: 'S/T' }}</td>
                    <td>{{ trim($o->marca) ?: 'S/M' }}</td>
                    <td>{{ trim($o->modelo) ?: 'S/M' }}</td>
                    <td>
                        <span class="estado-badge estado-{{ strtolower($o->estado ?? 'desconocido') }}">
                            {{ trim($o->estado) ?: 'Desconocido' }}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('componentesOpcionales.edit', $o->id_opcional) }}" class="btn btn-edit">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <form action="{{ route('componentesOpcionales.destroy', $o->id_opcional) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-delete" onclick="return confirm('¿Está seguro de eliminar este componente opcional?')">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center no-data">
                        No hay componentes opcionales registrados
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($opcionales->hasPages())
        <div class="pagination-container">
            {{ $opcionales->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal de confirmación -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Está seguro de que desea eliminar este componente opcional?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mejorar la confirmación de eliminación
    const deleteForms = document.querySelectorAll('.delete-form');
    
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('¿Está seguro de eliminar este componente opcional? Esta acción no se puede deshacer.')) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endsection