@extends('layouts.app')

{{-- Estilos específicos para esta vista --}}
<link rel="stylesheet" href="{{ asset('css/indexcomponentes.css') }}">

<style>
    /* ============================================================
       Table responsiveness y scroll horizontal para pantallas pequeñas
       ============================================================ */
    .table-wrapper {
        overflow-x: auto !important;
        /* Permitir scroll horizontal si la tabla es más ancha que el contenedor */
        overflow-y: hidden;
        /* Evitar scroll vertical */
    }

    /* ============================================================
       Asegurar que la tabla tenga un ancho mínimo para disparar scroll
       ============================================================ */
    .components-table {
        min-width: 1000px;
        /* Ajustar según la cantidad de columnas o contenido */
    }

    /* ============================================================
       Columna "Opciones" / "Componentes Inactivos" más ancha
       ============================================================ */
    .column-actions {
        width: 250px !important;
        /* Mayor ancho para cabecera larga */
        white-space: normal !important;
        /* Permitir que el texto se divida en varias líneas */
        vertical-align: top;
        /* Alinear contenido al inicio de la celda */
    }

    /* ============================================================
       Mejorar legibilidad de la lista de componentes
       ============================================================ */
    .componentes {
        font-size: 0.9rem !important;
        /* Tamaño de texto ligeramente más grande */
        line-height: 1.4;
        /* Altura de línea cómoda para leer */
    }

    .componentes ul {
        list-style-type: disc;
        /* Viñetas tipo disco */
        padding-left: 20px !important;
        /* Separación izquierda de la lista */
        margin-top: 5px;
        /* Espacio superior */
        text-align: left;
        /* Alinear texto a la izquierda */
    }

    .componentes li {
        margin-bottom: 2px;
        /* Espacio entre elementos de la lista */
    }

    /* ============================================================
       Columna Dirección con truncado y tooltip
       ============================================================ */
    .column-direccion {
        max-width: 150px;
        /* Ancho máximo de la columna */
        white-space: nowrap;
        /* Evitar salto de línea */
        overflow: hidden;
        /* Ocultar el contenido que exceda */
        text-overflow: ellipsis;
        /* Mostrar "..." si se corta el texto */
        cursor: help;
        /* Indicar al usuario que hay información adicional */
    }
</style>

@section('content')
<div class="components-container">
    {{-- ============================================================
         Encabezado de la sección: Título + Descripción + Botones
         ============================================================ --}}
    <div class="components-header">
        <div class="header-content">
            <div class="title-section">
                <h1>Equipos con Componentes Inactivos</h1>
                <p>Gestión de equipos que poseen componentes inactivos</p>
            </div>

            {{-- Botón Exportar PDF --}}
            <a href="{{ route('pdf.equipos.inactivos', [
        'id_direccion' => request('id_direccion'),
        'id_division' => request('id_division'),
        'id_coordinacion' => request('id_coordinacion')
    ]) }}" class="btn-add-component" style="background: linear-gradient(135deg, #f70000, #660303);">
                <i class="fas fa-file-pdf"></i>
                Exportar Reporte
            </a>

            {{-- Botón Exportar Excel --}}
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

    {{-- ============================================================
         Sección de filtros: Dirección, División, Coordinación
         ============================================================ --}}
    <div class="filter-section"
        style="background: white; padding: 20px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <form method="GET" action="{{ route('equipos.inactivos') }}" class="row g-3">

            {{-- Filtro Dirección --}}
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

            {{-- Filtro División --}}
            <div class="col-md-3">
                <label for="id_division" class="form-label" style="font-weight: 600; color: #4a5568;">División</label>
                <select name="id_division" id="id_division" class="form-select" disabled
                    data-selected="{{ request('id_division') }}">
                    <option value="">Todas</option>
                </select>
            </div>

            {{-- Filtro Coordinación --}}
            <div class="col-md-3">
                <label for="id_coordinacion" class="form-label" style="font-weight: 600; color: #4a5568;">Coordinación</label>
                <select name="id_coordinacion" id="id_coordinacion" class="form-select" disabled
                    data-selected="{{ request('id_coordinacion') }}">
                    <option value="">Todas</option>
                </select>
            </div>

            {{-- Botón Filtrar --}}
            <div class="col-md-3 align-self-end">
                <button type="submit" class="btn btn-primary w-100"
                    style="background-color: var(--primary-color); border: none;">
                    <i class="fas fa-filter"></i> Filtrar
                </button>
            </div>
        </form>
    </div>

    {{-- ============================================================
         Contenedor con datos en JSON para JS (direcciones, divisiones, coordinaciones)
         ============================================================ --}}
    <div id="app-data" data-direcciones='@json($direcciones)' data-divisiones='@json($divisiones)'
        data-coordinaciones='@json($coordinaciones)'>
    </div>

    {{-- ============================================================
         Tabla de Equipos con Componentes Inactivos
         ============================================================ --}}
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

                    {{-- Columna Equipo: icono + marca y modelo --}}
                    <td class="component-equipo">
                        <div class="equipo-info">
                            <i class="fas fa-desktop"></i>
                            <span>{{ $equipo->marca }} {{ $equipo->modelo }}</span>
                        </div>
                    </td>

                    {{-- Columna Dirección con tooltip --}}
                    <td class="column-direccion" title="{{ $equipo->direccion->nombre_direccion ?? '' }}">
                        {{ $equipo->direccion->nombre_direccion ?? '—' }}
                    </td>

                    {{-- Columna División --}}
                    <td class="component-model">
                        {{ $equipo->division->nombre_division ?? '—' }}
                    </td>

                    {{-- Columna Coordinación --}}
                    <td class="component-model">
                        {{ $equipo->coordinacion->nombre_coordinacion ?? '—' }}
                    </td>

                    {{-- Columna Componentes Inactivos --}}
                    <td class="component-actions">
                        <div class="action-buttons" style="justify-content: flex-start; flex-direction: column; align-items: flex-start;">

                            {{-- Componentes Principales --}}
                            <div class="mb-2 w-100">
                                <div class="d-flex align-items-center mb-1">
                                    <button class="btn-action btn-delete toggle-btn me-2" type="button"
                                        title="Ver Componentes" data-target="comp{{ $equipo->id_equipo }}"
                                        style="width: 32px; height: 32px; font-size: 0.8rem;">
                                        <i class="fas fa-microchip"></i>
                                    </button>
                                    <span style="font-weight: 600; font-size: 0.85rem; color: #e53e3e;">Principales</span>
                                </div>

                                {{-- Lista de componentes inactivos principales (oculta inicialmente) --}}
                                <div class="componentes d-none" id="comp{{ $equipo->id_equipo }}" style="color: #c53030;">
                                    <ul class="pl-3 mb-0">
                                        @foreach ($equipo->componentes_inactivos as $comp)
                                        <li><strong>{{ $comp->tipo_componente }}</strong>: {{ $comp->marca }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            {{-- Componentes Opcionales --}}
                            @if($equipo->opcionales_inactivos->count() > 0)
                            <div class="w-100">
                                <div class="d-flex align-items-center mb-1">
                                    <button class="btn-action btn-edit toggle-btn me-2"
                                        style="background-color: #ecc94b; color: #744210; width: 32px; height: 32px; font-size: 0.8rem;"
                                        type="button" title="Ver Opcionales" data-target="opc{{ $equipo->id_equipo }}">
                                        <i class="fas fa-plus-circle"></i>
                                    </button>
                                    <span style="font-weight: 600; font-size: 0.85rem; color: #d69e2e;">Opcionales</span>
                                </div>

                                {{-- Lista de componentes inactivos opcionales (oculta inicialmente) --}}
                                <div class="componentes d-none" id="opc{{ $equipo->id_equipo }}" style="color: #b7791f;">
                                    <ul class="pl-3 mb-0">
                                        @foreach ($equipo->opcionales_inactivos as $op)
                                        <li><strong>{{ $op->tipo_opcional }}</strong>: {{ $op->marca }} {{ $op->modelo }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @endif

                        </div>
                    </td>

                </tr>
                @empty
                {{-- Estado vacío si no hay equipos con componentes inactivos --}}
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

    {{-- Paginación --}}
    <div class="pagination-container">
        {{ $equipos->appends(request()->query())->links() }}
    </div>
</div>
@endsection

@section('scripts')
{{-- Scripts para toggles de componentes inactivos --}}
<script src="{{ asset('js/inactivos2.js') }}"></script>
<script src="{{ asset('js/inactivos.js') }}"></script>
@endsection