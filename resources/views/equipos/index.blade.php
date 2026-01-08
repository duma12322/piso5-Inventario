@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/equiposprin.css') }}?v={{ time() }}">

@section('content')
    <div class="container">
        <!-- Sección del encabezado -->
        <div class="header-section">
            <h1 class="page-title">Lista de Equipos</h1>
            <div class="header-buttons">
                <a href="{{ route('equipos.create') }}" class="btn-header-add">
                    <i class="fas fa-plus-circle"></i> Agregar Equipo
                </a>
                <a href="{{ route('equipos.pdfGlobal', request()->query()) }}" class="btn-header-pdf" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                        style="width: 20px; height: 20px; color: white;">
                        <path
                            d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625Z" />
                        <path
                            d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z" />
                    </svg>
                    GENERAR PDF
                </a>
                <a href="{{ route('excel.equipos.global', request()->query()) }}" class="btn-header-pdf" style="background-color: #107c41; margin-left: 10px;" target="_blank">
                    <i class="fas fa-file-excel" style="margin-right: 5px;"></i>
                    GENERAR EXCEL
                </a>
            </div>
        </div>

        <!-- BUSCADOR SIMPLE - Solo input y botón -->
        <div class="simple-search-container">
            <form action="{{ route('equipos.index') }}" method="GET" class="simple-search-form">
                <div class="search-wrapper">
                    <input type="text" name="search" class="search-input" placeholder="Buscar equipos..."
                        value="{{ request('search') }}" aria-label="Buscar equipos">
                    <button type="submit" class="search-button">
                        <i class="fas fa-search"></i>
                    </button>
                    @if(request('search'))
                        <a href="{{ route('equipos.index') }}" class="clear-button" title="Limpiar búsqueda">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Contenedor de la tabla -->
        <div class="table-container">
            <table class="equipos-table">
                <thead>
                    <tr>
                        <th>Nº Bien</th>
                        <th>MC</th>
                        <th>Modelo</th>
                        <th class="column-direccion">Dirección</th>
                        <th>División</th>
                        <th>COORD.</th>
                        <th>Estado Funcional</th>
                        <th>Estado Tecnológico</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($equipos as $e)
                        <tr>
                            <td class="numero_bien">{{ trim($e->numero_bien) ?: 'S/I' }}</td>
                            <td>{{ trim($e->marca) ?: 'S/M' }}</td>
                            <td>{{ trim($e->modelo) ?: 'S/M' }}</td>
                            <td class="column-direccion" title="{{ trim($e->direccion->nombre_direccion ?? '') }}">
                                {{ trim($e->direccion->nombre_direccion ?? '') ?: 'N/A' }}
                            </td>
                            <td>{{ trim($e->division->nombre_division ?? '') ?: 'N/A' }}</td>
                            <td>{{ trim($e->coordinacion->nombre_coordinacion ?? '') ?: 'N/A' }}</td>
                            <td>
                                @php
                                    $estadoFuncional = trim($e->estado_funcional) ?: 'desconocido';
                                    $estadoFuncionalLower = strtolower(str_replace(' ', '-', $estadoFuncional));
                                    // Abbreviate "Buen Funcionamiento"
                                    $displayEstado = $estadoFuncional === 'Buen Funcionamiento' ? 'Buen Func.' : ($estadoFuncional == 'desconocido' ? 'Desconocido' : $estadoFuncional);
                                @endphp
                                <span class="status-badge status-{{ $estadoFuncionalLower }}" title="{{ $estadoFuncional }}">
                                    {{ $displayEstado }}
                                </span>
                            </td>
                            <td>
                                @php
                                    if (is_array($e->estado_tecnologico)) {
                                        $estadoTec = $e->estado_tecnologico['estado'] ?? 'desconocido';
                                    } else {
                                        $estadoTec = trim($e->estado_tecnologico) ?: 'desconocido';
                                    }
                                    $estadoTecLower = strtolower(str_replace(' ', '-', $estadoTec));
                                @endphp
                                <span class="status-badge status-{{ $estadoTecLower }}">
                                    {{ $estadoTec == 'desconocido' ? 'Desconocido' : $estadoTec }}
                                </span>
                            </td>
                            <td>

                                <div class="action-toggle-container text-center">
                                    <button class="btn btn-sm toggle-actions-btn"
                                        onclick="toggleActions('actions-{{ $e->id_equipo }}')" title="Ver Acciones"
                                        style="color: #dc2626; background: none; border: none; padding: 0.25rem;">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>

                                    <div id="actions-{{ $e->id_equipo }}"
                                        class="inline-actions d-none mt-2 justify-content-center gap-2"
                                        style="animation: fadeIn 0.3s ease-out;">
                                        <a href="{{ route('equipos.edit', $e->id_equipo) }}" class="btn-action btn-edit"
                                            title="Editar"
                                            style="width: 32px; height: 32px; display: inline-flex; justify-content: center; align-items: center; border-radius: 8px; background: #eff6ff; color: #2563eb;">
                                            <i class="fas fa-edit" style="font-size: 0.8rem;"></i>
                                        </a>

                                        <a href="{{ route('componentes.porEquipo', $e->id_equipo) }}"
                                            class="btn-action btn-view" title="Ver Componentes"
                                            style="width: 32px; height: 32px; display: inline-flex; justify-content: center; align-items: center; border-radius: 8px; background: #ecfdf5; color: #059669;">
                                            <i class="fas fa-list" style="font-size: 0.8rem;"></i>
                                        </a>

                                        <a href="{{ route('equipos.pdf', $e->id_equipo) }}" class="btn-action btn-pdf"
                                            target="_blank" title="GENERAR PDF"
                                            style="width: 32px; height: 32px; display: inline-flex; justify-content: center; align-items: center; border-radius: 8px; background: #fef2f2; color: #dc2626;">
                                            <i class="fas fa-file-pdf" style="font-size: 0.8rem;"></i>
                                        </a>

                                        <a href="{{ route('excel.equipos.single', $e->id_equipo) }}" class="btn-action btn-excel"
                                            target="_blank" title="GENERAR EXCEL"
                                            style="width: 32px; height: 32px; display: inline-flex; justify-content: center; align-items: center; border-radius: 8px; background: #ecfdf5; color: #107c41;">
                                            <i class="fas fa-file-excel" style="font-size: 0.8rem;"></i>
                                        </a>

                                        <form action="{{ route('equipos.destroy', $e->id_equipo) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete" title="Eliminar"
                                                onclick="return confirm('¿Estás seguro de eliminar este equipo?')"
                                                style="width: 32px; height: 32px; display: inline-flex; justify-content: center; align-items: center; border-radius: 8px; background: #f3f4f6; color: #4b5563; border: none;">
                                                <i class="fas fa-trash" style="font-size: 0.8rem;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="no-data">
                                <i class="fas fa-info-circle"></i>
                                @if(request('search'))
                                    No se encontraron equipos para "{{ request('search') }}"
                                @else
                                    No hay equipos registrados.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>


        </div>

        <!-- Paginación -->
        @if($equipos->hasPages())
            <div class="pagination-container">
                {{ $equipos->appends(request()->query())->links() }}
            </div>
        @endif
    </div>


@endsection

@section('scripts')
    <script>
        function toggleActions(id) {
            const el = document.getElementById(id);
            const allActions = document.querySelectorAll('.inline-actions');

            // Cerrar otros abiertos (opcional, pero más limpio)
            allActions.forEach(action => {
                if (action.id !== id) {
                    action.classList.add('d-none');
                    action.classList.remove('d-flex');
                }
            });

            // Toggle actual
            if (el.classList.contains('d-none')) {
                el.classList.remove('d-none');
                el.classList.add('d-flex');
            } else {
                el.classList.add('d-none');
                el.classList.remove('d-flex');
            }
        }
    </script>
@endsection