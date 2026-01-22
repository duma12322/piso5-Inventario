{{-- Extiende el layout principal de la aplicación --}}
@extends('layouts.app')

{{-- Hoja de estilos específica para la vista de componentes/usuarios --}}
<link rel="stylesheet" href="{{ asset('css/indexcomponentes.css') }}">

{{-- Inicio de la sección content --}}
@section('content')

<div class="components-container">

    {{-- ================= ENCABEZADO ================= --}}
    <div class="components-header">
        <div class="header-content">

            {{-- Título y descripción --}}
            <div class="title-section">
                <h1>Gestión de Usuarios</h1>
                <p>Administración de usuarios y roles del sistema</p>
            </div>

            {{-- Estadísticas rápidas --}}
            <div class="header-stats">
                <div class="stat-card">
                    <i class="fas fa-users"></i>
                    {{-- Total de usuarios --}}
                    <span class="stat-number">{{ $usuarios->count() }}</span>
                    <span class="stat-label">Usuarios</span>
                </div>
            </div>
        </div>

        {{-- Botón para crear un nuevo usuario --}}
        <a href="{{ route('usuarios.create') }}" class="btn-add-component">
            <i class="fas fa-user-plus"></i>
            Agregar Usuario
        </a>
    </div>

    {{-- ================= MENSAJE DE ÉXITO ================= --}}
    @if(session('success'))
    <div class="alert alert-success"
        style="background-color: #c6f6d5; border-color: #9ae6b4; color: #22543d; padding: 1rem; margin-bottom: 1rem; border-radius: 0.375rem;">
        {{ session('success') }}
    </div>
    @endif

    {{-- ================= TABLA DE USUARIOS ================= --}}
    <div class="table-wrapper">
        <table class="components-table">
            <thead>
                <tr>
                    <th class="column-id">Usuario</th>
                    <th class="column-model text-center">Rol</th>
                    <th class="column-actions">Acciones</th>
                </tr>
            </thead>
            <tbody>

                {{-- Iteración de usuarios --}}
                @forelse ($usuarios as $u)
                <tr class="component-row">

                    {{-- Columna Usuario --}}
                    <td class="component-id" style="font-weight: 600; color: #2d3748;">
                        <div class="equipo-info">
                            <i class="fas fa-user-circle"></i>
                            <span>{{ $u->usuario }}</span>
                        </div>
                    </td>

                    {{-- Columna Rol --}}
                    <td class="component-model text-center">
                        <span class="status-badge"
                            {{-- Estilo condicional según el rol --}}
                            style="{{ 
                                    $u->rol === 'Administrador'
                                        ? 'background-color: #fed7d7; color: #c53030;'
                                        : 'background-color: #ebf8ff; color: #2b6cb0;'
                                }}">
                            {{ $u->rol }}
                        </span>
                    </td>

                    {{-- Acciones --}}
                    <td class="component-actions">
                        <div class="action-buttons">

                            {{-- Botón editar --}}
                            <a href="{{ route('usuarios.edit', ['usuario' => $u->id_usuario]) }}"
                                class="btn-action btn-edit" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>

                            {{-- Formulario eliminar --}}
                            <form action="{{ route('usuarios.destroy', ['usuario' => $u->id_usuario]) }}"
                                method="POST"
                                class="delete-form"
                                onsubmit="return confirm('¿Eliminar usuario?')">

                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn-action btn-delete" title="Eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                {{-- Estado vacío --}}
                @empty
                <tr class="no-components">
                    <td colspan="3">
                        <div class="empty-state">
                            <i class="fas fa-users-slash"></i>
                            <h3>No hay usuarios registrados</h3>
                            <a href="{{ route('usuarios.create') }}" class="btn-empty-state">
                                <i class="fas fa-user-plus"></i>
                                Agregar Primer Usuario
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>
    </div>

    {{-- ================= PAGINACIÓN ================= --}}
    @if($usuarios->hasPages())
    <div class="pagination-container">
        {{ $usuarios->appends(request()->query())->links() }}
    </div>
    @endif

</div>

{{-- Fin de la sección content --}}
@endsection