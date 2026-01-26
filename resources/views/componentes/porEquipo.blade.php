@extends('layouts.app')

<!-- ======================================================
     Estilos específicos para la página de componentes de equipo
     Cache busting con ?v={{ time() }} para evitar problemas de caché
====================================================== -->
<link rel="stylesheet" href="{{ asset('css/equiposprin.css') }}?v={{ time() }}">

@section('title', 'Componentes del Equipo')

@section('content')
<div class="container">

    {{-- ======================================================
             Sección Header
             Título de la página y botones de navegación:
             - Volver a la lista de equipos
             - Agregar componente al equipo actual
        ====================================================== --}}
    <div class="header-section">
        <h1 class="page-title">Componentes del Equipo</h1>
        <div class="header-buttons">
            <a href="{{ route('equipos.index') }}" class="btn-header-add" style="background: rgba(255,255,255,0.2);">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <a href="{{ route('componentes.createPorEquipo', ['id_equipo' => $id_equipo]) }}" class="btn-header-add">
                <i class="fas fa-plus-circle"></i> Agregar Componente
            </a>
        </div>
    </div>

    {{-- ======================================================
             Tabla de Componentes Principales
             Listado de componentes con sus detalles y acciones
        ====================================================== --}}
    <div class="section-title-wrapper" style="margin-bottom: 2rem;">
        <h2 style="color: #aa1414; font-weight: 700; border-left: 5px solid #aa1414; padding-left: 15px;">
            Componentes Principales
        </h2>
    </div>

    <div class="table-container">
        <table class="equipos-table">
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Capacidad</th>
                    <th>Estado</th>
                    <th>Detalles (Ranuras/Puertos/Conectores)</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($componentes as $c)
                <tr>
                    {{-- Tipo, Marca y Modelo --}}
                    <td>{{ $c->tipo_componente ?? 'S/T' }}</td>
                    <td>{{ $c->marca ?? 'S/M' }}</td>
                    <td>{{ $c->modelo ?? 'S/M' }}</td>

                    {{-- Capacidad --}}
                    <td>{{ $c->capacidad ?? 'S/C' }}</td>

                    {{-- Estado con badge de color dinámico --}}
                    @php
                    $estado = trim($c->estado ?? 'Sin estado');
                    $estadoClass = strtolower(str_replace(' ', '-', $estado));
                    @endphp
                    <td>
                        <span class="status-badge status-{{ $estadoClass }}">{{ $estado }}</span>
                    </td>

                    {{-- Detalles de ranuras, puertos y conectores --}}
                    @php
                    $ranuras = !empty($c->ranuras_expansion) ? explode(',', $c->ranuras_expansion) : [];
                    $puertos = array_merge(
                    !empty($c->puertos_internos) ? explode(',', $c->puertos_internos) : [],
                    !empty($c->puertos_externos) ? explode(',', $c->puertos_externos) : []
                    );
                    $conectores = !empty($c->conectores_alimentacion) ? explode(',', $c->conectores_alimentacion) : [];
                    @endphp
                    <td>
                        <div style="font-size: 0.8rem;">
                            @if(count($ranuras)) <strong>Ranuras:</strong> {{ implode(', ', $ranuras) }}<br> @endif
                            @if(count($puertos)) <strong>Puertos:</strong> {{ implode(', ', $puertos) }}<br> @endif
                            @if(count($conectores)) <strong>Conectores:</strong> {{ implode(', ', $conectores) }} @endif
                            @if(empty($ranuras) && empty($puertos) && empty($conectores))
                            <span style="color: #999;">Sin detalles extra</span>
                            @endif
                        </div>
                    </td>

                    {{-- Botones de acción: Editar y Eliminar --}}
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('componentes.editPorEquipo', $c->id_componente) }}"
                                class="btn-action btn-edit" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('componentes.destroy', $c->id_componente) }}" method="POST"
                                class="delete-form" onsubmit="return confirm('¿Desea eliminar este componente?');">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="porEquipo" value="1">
                                <input type="hidden" name="id_equipo" value="{{ $id_equipo }}">
                                <button type="submit" class="btn-action btn-delete" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                {{-- Mensaje cuando no hay componentes --}}
                <tr>
                    <td colspan="8" class="no-data">
                        <i class="fas fa-microchip"></i> No hay componentes registrados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ======================================================
             Tabla de Componentes Opcionales
             Incluye detalles como consumo, frecuencia y acciones
        ====================================================== --}}
    <div class="section-title-wrapper" style="margin: 3rem 0 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2 style="color: #aa1414; font-weight: 700; border-left: 5px solid #aa1414; padding-left: 15px;">
                Componentes Opcionales
            </h2>
            <a href="{{ route('componentesOpcionales.createPorEquipo', ['id_equipo' => $id_equipo]) }}"
                class="btn-header-add" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 10px 20px; font-size: 0.9rem;">
                <i class="fas fa-plus"></i> Agregar Opcional
            </a>
        </div>
    </div>

    <div class="table-container">
        <table class="equipos-table">
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Capacidad/Frec.</th>
                    <th>Consumo</th>
                    <th>Estado</th>
                    <th>Detalles</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($opcionales as $o)
                <tr>
                    {{-- Tipo, Marca y Modelo --}}
                    <td>{{ trim($o->tipo_opcional) ?: 'S/T' }}</td>
                    <td>{{ trim($o->marca) ?: 'S/M' }}</td>
                    <td>{{ trim($o->modelo) ?: 'S/M' }}</td>

                    {{-- Capacidad y Frecuencia --}}
                    <td>
                        {{ trim($o->capacidad) ?: '' }}
                        {{ trim($o->frecuencia) ? ' / '.trim($o->frecuencia) : '' }}
                        {{ (!trim($o->capacidad) && !trim($o->frecuencia)) ? 'S/D' : '' }}
                    </td>

                    {{-- Consumo --}}
                    <td>{{ trim($o->consumo) ?: 'S/C' }}</td>

                    {{-- Estado con badge de color dinámico --}}
                    @php
                    $estadoOpc = trim($o->estado) ?: 'Sin estado';
                    $estadoOpcClass = strtolower(str_replace(' ', '-', $estadoOpc));
                    @endphp
                    <td>
                        <span class="status-badge status-{{ $estadoOpcClass }}">{{ $estadoOpc }}</span>
                    </td>

                    {{-- Detalles adicionales --}}
                    <td>{{ trim($o->detalles) ?: '-' }}</td>

                    {{-- Botones de acción: Editar y Eliminar --}}
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('componentesOpcionales.editPorEquipo', $o->id_opcional) }}"
                                class="btn-action btn-edit" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('componentesOpcionales.destroy', $o->id_opcional) }}" method="POST"
                                class="delete-form" onsubmit="return confirm('¿Desea eliminar este componente opcional?');">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="porEquipo" value="1">
                                <input type="hidden" name="id_equipo" value="{{ $id_equipo }}">
                                <button type="submit" class="btn-action btn-delete" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                {{-- Mensaje cuando no hay componentes opcionales --}}
                <tr>
                    <td colspan="8" class="no-data">
                        <i class="fas fa-box-open"></i> No hay componentes opcionales.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection

{{-- ======================================================
     Scripts específicos para esta página
     JS de manejo por equipo
====================================================== --}}
@section('scripts')
<script src="{{ asset('js/porEquipo.js') }}"></script>
@endsection