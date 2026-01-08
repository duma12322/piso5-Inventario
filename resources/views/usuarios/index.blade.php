@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/indexcomponentes.css') }}">
@section('content')
    <div class="components-container">
        <div class="components-header">
            <div class="header-content">
                <div class="title-section">
                    <h1>Gestión de Usuarios</h1>
                    <p>Administración de usuarios y roles del sistema</p>
                </div>
                <div class="header-stats">
                    <div class="stat-card">
                        <i class="fas fa-users"></i>
                        <span class="stat-number">{{ $usuarios->count() }}</span>
                        <span class="stat-label">Usuarios</span>
                    </div>
                </div>
            </div>
            <a href="{{ route('usuarios.create') }}" class="btn-add-component">
                <i class="fas fa-user-plus"></i>
                Agregar Usuario
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success"
                style="background-color: #c6f6d5; border-color: #9ae6b4; color: #22543d; padding: 1rem; margin-bottom: 1rem; border-radius: 0.375rem;">
                {{ session('success') }}
            </div>
        @endif

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
                    @forelse ($usuarios as $u)
                        <tr class="component-row">
                            <td class="component-id" style="font-weight: 600; color: #2d3748;">
                                <div class="equipo-info">
                                    <i class="fas fa-user-circle"></i>
                                    <span>{{ $u->usuario }}</span>
                                </div>
                            </td>
                            <td class="component-model text-center">
                                <span class="status-badge"
                                    style="{{ $u->rol === 'Administrador' ? 'background-color: #fed7d7; color: #c53030;' : 'background-color: #ebf8ff; color: #2b6cb0;' }}">
                                    {{ $u->rol }}
                                </span>
                            </td>
                            <td class="component-actions">
                                <div class="action-buttons">
                                    <a href="{{ route('usuarios.edit', ['usuario' => $u->id_usuario]) }}"
                                        class="btn-action btn-edit" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('usuarios.destroy', ['usuario' => $u->id_usuario]) }}" method="POST"
                                        class="delete-form" onsubmit="return confirm('¿Eliminar usuario?')">
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

        @if($usuarios->hasPages())
            <div class="pagination-container">
                {{ $usuarios->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection