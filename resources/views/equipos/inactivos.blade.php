@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4 text-danger">💀 Equipos con Componentes Inactivos</h1>

    {{-- Contenedor de datos para JS --}}
    <div id="app-data"
        data-direcciones='@json($direcciones)'
        data-divisiones='@json($divisiones)'
        data-coordinaciones='@json($coordinaciones)'>
    </div>

    {{-- Filtros --}}
    <form method="GET" action="{{ route('equipos.inactivos') }}" class="mb-4 row g-3">
        <div class="col-md-3">
            <label for="id_direccion" class="form-label">Dirección</label>
            <select name="id_direccion" id="id_direccion" class="form-select">
                <option value="">Todas</option>
                @foreach ($direcciones as $direccion)
                <option value="{{ $direccion->id_direccion }}"
                    {{ request('id_direccion') == $direccion->id_direccion ? 'selected' : '' }}>
                    {{ $direccion->nombre_direccion ?? $direccion->nombre }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label for="id_division" class="form-label">División</label>
            <select name="id_division" id="id_division" class="form-select" disabled>
                <option value="">Todas</option>
            </select>
        </div>

        <div class="col-md-3">
            <label for="id_coordinacion" class="form-label">Coordinación</label>
            <select name="id_coordinacion" id="id_coordinacion" class="form-select" disabled>
                <option value="">Todas</option>
            </select>
        </div>

        <div class="col-md-3 align-self-end">
            <button type="submit" class="btn btn-danger w-100">🔍 Filtrar</button>
        </div>
    </form>

    {{-- Tabla --}}
    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Equipo</th>
                    <th>Dirección</th>
                    <th>División</th>
                    <th>Coordinación</th>
                    <th>Componentes Inactivos</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($equipos as $equipo)
                <tr>
                    <td>{{ $equipo->id_equipo }}</td>
                    <td>{{ $equipo->marca }} {{ $equipo->modelo }}</td>
                    <td>{{ $equipo->direccion->nombre_direccion ?? '—' }}</td>
                    <td>{{ $equipo->division->nombre_division ?? '—' }}</td>
                    <td>{{ $equipo->coordinacion->nombre_coordinacion ?? '—' }}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-danger mb-2 toggle-btn"
                            type="button"
                            data-target="comp{{ $equipo->id_equipo }}">
                            Ver Componentes
                        </button>

                        <div class="componentes d-none" id="comp{{ $equipo->id_equipo }}">

                            {{-- COMPONENTES PRINCIPALES --}}
                            <ul class="mb-1">
                                @foreach ($equipo->componentes as $comp)
                                @if ($comp->estado === 'Inactivo' || $comp->estadoElim === 'Inactivo')
                                <li>🧩 {{ $comp->tipo_componente }} ({{ $comp->marca }}) - Inactivo</li>
                                @endif
                                @endforeach
                            </ul>

                            {{-- OPCIONALES --}}
                            <strong>Componentes Opcionales:</strong>
                            <ul>
                                @foreach ($equipo->componentes as $comp)
                                @foreach ($comp->componentesOpcionales as $op)
                                @if ($op->estadoElim === 'Inactivo')
                                <li>
                                    ⚙️ {{ $op->tipo_opcional }}
                                    ({{ $op->marca }} {{ $op->modelo }})
                                    - Inactivo
                                </li>
                                @endif
                                @endforeach
                                @endforeach
                            </ul>

                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">
                        No hay equipos con componentes inactivos.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/inactivos2.js') }}"></script>
<script src="{{ asset('js/inactivos.js') }}"></script>
@endsection