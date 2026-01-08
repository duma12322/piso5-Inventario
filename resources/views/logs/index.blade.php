@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/indexcomponentes.css') }}?v={{ time() }}">
@section('content')
    <div class="components-container">
        <div class="components-header">
            <div class="header-content">
                <div class="title-section">
                    <h1>Registro de Logs</h1>
                    <p>Historial de actividad del sistema</p>
                </div>
                <div class="header-stats">
                    <div class="stat-card">
                        <i class="fas fa-history"></i>
                        <span class="stat-number">{{ $logs->total() }}</span>
                        <span class="stat-label">Registros</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Buscador (Adapted) --}}
        <div class="filter-section"
            style="background: white; padding: 20px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <form method="GET" action="{{ route('logs.index') }}" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="search_usuario" class="form-control" placeholder="Buscar por Usuario..."
                        value="{{ request('search_usuario') }}"
                        style="border: 1px solid #e2e8f0; padding: 0.6rem; border-radius: 0.375rem;">
                </div>
                <div class="col-md-4">
                    <input type="text" name="search_accion" class="form-control" placeholder="Buscar por Acción..."
                        value="{{ request('search_accion') }}"
                        style="border: 1px solid #e2e8f0; padding: 0.6rem; border-radius: 0.375rem;">
                </div>
                <div class="col-md-3">
                    <input type="text" name="search_fecha" class="form-control" placeholder="Fecha (dd/mm/yyyy)..."
                        value="{{ request('search_fecha') }}"
                        style="border: 1px solid #e2e8f0; padding: 0.6rem; border-radius: 0.375rem;">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100"
                        style="background-color: var(--primary-color); border: none; padding: 0.6rem; color: white; border-radius: 0.375rem;">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>
            </form>
        </div>

        <div class="table-wrapper">
            <table class="components-table table-compact">
                <thead>
                    <tr>
                        <th class="column-brand">Usuario</th>
                        <th class="column-model">Acción</th>
                        <th class="column-date">Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $index => $log)
                        <tr class="component-row">
                            <td class="component-equipo">
                                <div class="equipo-info">
                                    <i class="fas fa-user"></i>
                                    <span>{{ $log->usuario ?? 'Sistema' }}</span>
                                </div>
                            </td>
                            <td class="component-model">
                                {{ $log->accion }}
                            </td>
                            <td class="component-date" style="color: #4a5568; font-weight: 500;">
                                <i class="far fa-clock" style="margin-right: 5px; color: #718096;"></i>
                                {{ \Carbon\Carbon::parse($log->fecha)->format('d/m/Y H:i:s') }}
                            </td>
                        </tr>
                    @empty
                        <tr class="no-components">
                            <td colspan="4">
                                <div class="empty-state">
                                    <i class="fas fa-history"></i>
                                    <h3>No hay registros de actividad</h3>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($logs->hasPages())
            <div class="pagination-container">
                {{ $logs->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection