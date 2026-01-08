@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/indexcomponentes.css') }}">
<style>
    /* Fix requested by user: Table responsiveness and visibility */
    .table-wrapper {
        overflow-x: auto !important;
        overflow-y: hidden;
    }

    /* Ensure the table has a minimum width to trigger scrolling on small screens */
    .components-table {
        min-width: 1000px;
        /* Adjust based on column contents */
    }

    /* Fix for "Opciones" / "Componentes Inactivos" column visibility */
    .column-actions {
        width: 250px !important;
        /* Wider column for the long header */
        white-space: normal !important;
        /* Allow header text to wrap */
        vertical-align: top;
    }

    /* Improve readability of inactive components text */
    .componentes {
        font-size: 0.9rem !important;
        /* Slightly larger text */
        line-height: 1.4;
    }

    .componentes ul {
        list-style-type: disc;
        padding-left: 20px !important;
        margin-top: 5px;
        text-align: left;
    }

    .componentes li {
        margin-bottom: 2px;
    }

    /* Columna Dirección Truncada con Tooltip */
    .column-direccion {
        max-width: 150px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        cursor: help;
    }
</style>
@section('content')
    <div class="components-container">
        <div class="components-header">
            <div class="header-content">
                <div class="title-section">
                    <h1>Equipos con Componentes Inactivos</h1>
                    <p>Gestión de equipos que poseen componentes inactivos</p>
                </div>

                <a href="{{ route('pdf.equipos.inactivos', [
        'id_direccion' => request('id_direccion'),
        'id_division' => request('id_division'),
        'id_coordinacion' => request('id_coordinacion')
    ]) }}" class="btn-add-component" style="background: linear-gradient(135deg, #f70000, #660303);">
                    <i class="fas fa-file-pdf"></i>
                    Exportar Reporte
                </a>
                <a href="{{ route('excel.inactivos', [
        'id_direccion' => request('id_direccion'),
        'id_division' => request('id_division'),
        'id_coordinacion' => request('id_coordinacion')
    ]) }}" class="btn-add-component" style="background: linear-gradient(135deg, #107c41, #0c5c30); margin-left: 10px;">
                    <i class="fas fa-file-excel"></i>
                    Exportar Excel
                </a>
            </div>

        </div>

        {{-- Filtros (Adapted to match style but keep functionality) --}}
        <div class="filter-section"
            style="background: white; padding: 20px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <form method="GET" action="{{ route('equipos.inactivos') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="id_direccion" class="form-label" style="font-weight: 600; color: #4a5568;">Dirección</label>
                    <select name="id_direccion" id="id_direccion" class="form-select"
                        data-selected="{{ request('id_direccion') }}">
                        <option value="">Todas</option>
                        @foreach ($direcciones as $direccion)
                            <option value="{{ $direccion->id_direccion }}" {{ request('id_direccion') == $direccion->id_direccion ? 'selected' : '' }}>
                                {{ $direccion->nombre_direccion ?? $direccion->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="id_division" class="form-label" style="font-weight: 600; color: #4a5568;">División</label>
                    <select name="id_division" id="id_division" class="form-select" disabled
                        data-selected="{{ request('id_division') }}">
                        <option value="">Todas</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="id_coordinacion" class="form-label"
                        style="font-weight: 600; color: #4a5568;">Coordinación</label>
                    <select name="id_coordinacion" id="id_coordinacion" class="form-select" disabled
                        data-selected="{{ request('id_coordinacion') }}">
                        <option value="">Todas</option>
                    </select>
                </div>

                <div class="col-md-3 align-self-end">
                    <button type="submit" class="btn btn-primary w-100"
                        style="background-color: var(--primary-color); border: none;">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>

        <div id="app-data" data-direcciones='@json($direcciones)' data-divisiones='@json($divisiones)'
            data-coordinaciones='@json($coordinaciones)'>
        </div>

        <div class="table-wrapper">
            <table class="components-table">
                <thead>
                    <tr>
                        <th class="column-equipo">Equipo</th>
                        <th class="column-direccion">Dirección</th>
                        <th class="column-model">División</th>
                        <th class="column-model">Coordinación</th>
                        <th class="column-actions">Componentes Inactivos</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($equipos as $equipo)
                        <tr class="component-row">
                            <td class="component-equipo">
                                <div class="equipo-info">
                                    <i class="fas fa-desktop"></i>
                                    <span>{{ $equipo->marca }} {{ $equipo->modelo }}</span>
                                </div>
                            </td>
                            <td class="column-direccion" title="{{ $equipo->direccion->nombre_direccion ?? '' }}">
                                {{ $equipo->direccion->nombre_direccion ?? '—' }}
                            </td>
                            <td class="component-model">
                                {{ $equipo->division->nombre_division ?? '—' }}
                            </td>
                            <td class="component-model">
                                {{ $equipo->coordinacion->nombre_coordinacion ?? '—' }}
                            </td>
                            <td class="component-actions">
                                <div class="action-buttons"
                                    style="justify-content: flex-start; flex-direction: column; align-items: flex-start;">
                                    {{-- Botón Componentes Principales --}}
                                    <div class="mb-2 w-100">
                                        <div class="d-flex align-items-center mb-1">
                                            <button class="btn-action btn-delete toggle-btn me-2" type="button"
                                                title="Ver Componentes" data-target="comp{{ $equipo->id_equipo }}"
                                                style="width: 32px; height: 32px; font-size: 0.8rem;">
                                                <i class="fas fa-microchip"></i>
                                            </button>
                                            <span
                                                style="font-weight: 600; font-size: 0.85rem; color: #e53e3e;">Principales</span>
                                        </div>
                                        <div class="componentes d-none" id="comp{{ $equipo->id_equipo }}"
                                            style="color: #c53030;">
                                            <ul class="pl-3 mb-0">
                                                @foreach ($equipo->componentes_inactivos as $comp)
                                                    <li><strong>{{ $comp->tipo_componente }}</strong>: {{ $comp->marca }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>

                                    {{-- Botón Componentes Opcionales --}}
                                    @if($equipo->opcionales_inactivos->count() > 0)
                                        <div class="w-100">
                                            <div class="d-flex align-items-center mb-1">
                                                <button class="btn-action btn-edit toggle-btn me-2"
                                                    style="background-color: #ecc94b; color: #744210; width: 32px; height: 32px; font-size: 0.8rem;"
                                                    type="button" title="Ver Opcionales" data-target="opc{{ $equipo->id_equipo }}">
                                                    <i class="fas fa-plus-circle"></i>
                                                </button>
                                                <span
                                                    style="font-weight: 600; font-size: 0.85rem; color: #d69e2e;">Opcionales</span>
                                            </div>
                                            <div class="componentes d-none" id="opc{{ $equipo->id_equipo }}"
                                                style="color: #b7791f;">
                                                <ul class="pl-3 mb-0">
                                                    @foreach ($equipo->opcionales_inactivos as $op)
                                                        <li><strong>{{ $op->tipo_opcional }}</strong>: {{ $op->marca }}
                                                            {{ $op->modelo }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="no-components">
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fas fa-check-circle"></i>
                                    <h3>No hay equipos con componentes inactivos</h3>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination-container">
            {{ $equipos->appends(request()->query())->links() }}
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/inactivos2.js') }}"></script>
    <script src="{{ asset('js/inactivos.js') }}"></script>
@endsection