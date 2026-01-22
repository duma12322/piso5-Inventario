@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/indexcomponentes.css') }}?v={{ time() }}">

@section('content')
<div class="components-container">

    <!-- =========================
             Header de la sección
             ========================= -->
    <div class="components-header">
        <div class="header-content">
            <!-- Título y descripción -->
            <div class="title-section">
                <h1>Listado de Componentes Opcionales</h1>
                <p>Gestión de componentes opcionales del sistema</p>
            </div>

            <!-- Estadísticas rápidas -->
            <div class="header-stats">
                <div class="stat-card">
                    <i class="fas fa-plug"></i>
                    <span class="stat-number">{{ $opcionales->count() }}</span>
                    <span class="stat-label">Opcionales</span>
                </div>
            </div>
        </div>

        <!-- Botón para agregar nuevo componente opcional -->
        <a href="{{ route('componentesOpcionales.create') }}" class="btn-add-component">
            <i class="fas fa-plus-circle"></i>
            Agregar Opcional
        </a>
    </div>

    <!-- =========================
             Buscador simple
             ========================= -->
    <div class="simple-search-container">
        <form action="{{ route('componentesOpcionales.index') }}" method="GET" class="simple-search-form">
            <div class="search-wrapper">
                <input type="text" name="search" class="search-input"
                    placeholder="Buscar opcionales (tipo, marca, modelo, capacidad)..."
                    value="{{ request('search') }}" aria-label="Buscar opcionales">

                <button type="submit" class="search-button">
                    <i class="fas fa-search"></i>
                </button>

                <!-- Botón para limpiar búsqueda -->
                @if(request('search'))
                <a href="{{ route('componentesOpcionales.index') }}" class="clear-button" title="Limpiar búsqueda">
                    <i class="fas fa-times"></i>
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- =========================
             Tabla de componentes opcionales
             ========================= -->
    <div class="table-wrapper">
        <table class="components-table">
            <thead>
                <tr>
                    <th class="column-equipo">Equipo</th>
                    <th class="column-type">Tipo Opcional</th>
                    <th class="column-brand">Marca</th>
                    <th class="column-model">Modelo</th>
                    <th class="column-status">Estado</th>
                    <th class="column-actions"><i class="fas fa-cogs"></i> Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($opcionales as $o)
                <tr class="component-row">
                    <!-- Equipo asociado -->
                    <td class="component-equipo">
                        <div class="equipo-info">
                            <i class="fas fa-laptop"></i>
                            <span>{{ isset($o->equipo) && trim($o->equipo->marca) ? $o->equipo->marca : 'S/E' }}</span>
                        </div>
                    </td>

                    <!-- Tipo de opcional -->
                    <td class="component-type">
                        <span class="type-badge type-{{ Str::slug($o->tipo_opcional) }}">
                            <i class="fas {{ getComponentIcon($o->tipo_opcional) }}"></i>
                            {{ trim($o->tipo_opcional) ?: 'S/T' }}
                        </span>
                    </td>

                    <!-- Marca -->
                    <td class="component-brand">
                        {{ trim($o->marca) ?: 'S/M' }}
                    </td>

                    <!-- Modelo -->
                    <td class="component-model">
                        {{ trim($o->modelo) ?: 'S/M' }}
                    </td>

                    <!-- Estado -->
                    @php
                    $statusText = trim($o->estado) ?: 'Desconocido';
                    $statusSlug = Str::slug($statusText);

                    // Lógica para abreviar textos largos
                    $displayText = $statusText;
                    if (strtolower($statusText) === 'buen funcionamiento') {
                    $displayText = 'Buen Func.';
                    }
                    @endphp
                    <td class="component-status">
                        <span class="status-badge status-{{ $statusSlug }}" title="{{ $statusText }}">
                            {{ $displayText }}
                        </span>
                    </td>

                    <!-- Acciones: editar / eliminar -->
                    <td class="component-actions">
                        <div class="action-buttons">
                            <!-- Editar -->
                            <a href="{{ route('componentesOpcionales.edit', $o->id_opcional) }}"
                                class="btn-action btn-edit" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>

                            <!-- Eliminar -->
                            <form action="{{ route('componentesOpcionales.destroy', $o->id_opcional) }}" method="POST"
                                class="delete-form"
                                onsubmit="return confirm('¿Está seguro de eliminar este componente opcional?')">
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
                <!-- Estado vacío -->
                <tr class="no-components">
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-plug"></i>
                            <h3>No hay componentes opcionales registrados</h3>
                            <a href="{{ route('componentesOpcionales.create') }}" class="btn-empty-state">
                                <i class="fas fa-plus"></i>
                                Agregar Primer Opcional
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    @if($opcionales->hasPages())
    <div class="pagination-container">
        {{ $opcionales->appends(request()->query())->links() }}
    </div>
    @endif
</div>

@php
/**
* Función getComponentIcon
* ------------------------
* Devuelve un ícono de Font Awesome según el tipo de componente opcional.
*
* @param string $type Tipo de componente opcional
* @return string Clase del icono de Font Awesome
*
* Ejemplo:
* 'Webcam' -> 'fa-camera'
* 'Disco Duro' -> 'fa-hdd'
* 'Desconocido' -> 'fa-puzzle-piece'
*/
function getComponentIcon($type)
{
$icons = [
'Webcam' => 'fa-camera',
'Scanner' => 'fa-print',
'Parlantes' => 'fa-volume-up',
'Microfono' => 'fa-microphone',
'UPS' => 'fa-battery-full',
'Memoria Ram' => 'fa-memory',
'Disco Duro' => 'fa-hdd',
'Fan Cooler' => 'fa-fan',
'Tarjeta Grafica' => 'fa-video',
'Tarjeta de Red' => 'fa-network-wired',
'Tarjeta WiFi' => 'fa-wifi',
'Tarjeta de Sonido' => 'fa-music',
'default' => 'fa-puzzle-piece'
];

// Recorre cada clave y verifica si el tipo contiene la palabra clave
foreach ($icons as $key => $icon) {
if (str_contains(strtolower($type), strtolower($key))) {
return $icon;
}
}

// Si no se encuentra coincidencia, retorna el icono por defecto
return $icons['default'];
}
@endphp

<style>
    /* =========================
       Tabla responsive
       ========================= */
    .table-wrapper {
        overflow-x: auto;
        /* Permite scroll horizontal en dispositivos pequeños */
        -webkit-overflow-scrolling: touch;
        /* Suaviza scroll en iOS */
    }

    /* =========================
       Status Badges
       ========================= */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        /* Espacio entre ícono y texto */
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    /* -------------------------
       Estados Verdes (Bueno / Activo)
       ------------------------- */
    .status-buen-funcionamiento,
    .status-activo,
    .status-nuevo,
    .status-bueno,
    .status-optimo {
        background-color: #d1fae5;
        color: #059669;
        border: 1px solid #a7f3d0;
    }

    /* -------------------------
       Estados Amarillos (Precaución / Regular)
       ------------------------- */
    .status-operativo,
    .status-mantenimiento,
    .status-actualizable,
    .status-regular,
    .status-en-reparacion {
        background-color: #fef3c7;
        color: #d97706;
        border: 1px solid #fde68a;
    }

    /* -------------------------
       Estados Rojos (Daño / Inactivo)
       ------------------------- */
    .status-sin-funcionar,
    .status-danado,
    .status-inactivo,
    .status-obsoleto,
    .status-desactualizado {
        background-color: #fee2e2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }

    /* -------------------------
       Estado por defecto (Desconocido)
       ------------------------- */
    .status-desconocido,
    .status-default {
        background-color: #f3f4f6;
        color: #4b5563;
        border: 1px solid #e5e7eb;
    }

    /* =========================
       Type Badges (Tipología de opcional)
       ========================= */
    .type-badge {
        background: #eef2ff;
        color: #4f46e5;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 500;
    }
</style>
@endsection