@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/equiposinactivosblade.css') }}">

@section('content')
<div class="container">
    <h1 class="mb-4 text-danger"> Equipos con Componentes Inactivos</h1>

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
            <select name="id_direccion" id="id_direccion" class="form-select"
                data-selected="{{ request('id_direccion') }}">
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
            <select name="id_division" id="id_division" class="form-select" disabled
                data-selected="{{ request('id_division') }}">
                <option value="">Todas</option>
            </select>
        </div>

        <div class="col-md-3">
            <label for="id_coordinacion" class="form-label">Coordinación</label>
            <select name="id_coordinacion" id="id_coordinacion" class="form-select" disabled
                data-selected="{{ request('id_coordinacion') }}">
                <option value="">Todas</option>
            </select>
        </div>

        <div class="col-md-3 align-self-end">
            <button type="submit" class="btn btn-danger w-100">  Filtrar</button>
        </div>
    </form>

    {{-- BOTÓN PDF UNA SOLA VEZ --}}
    <div class="mb-3">
        <a href="{{ route('pdf.equipos.inactivos', [
            'id_direccion' => request('id_direccion'),
            'id_division' => request('id_division'),
            'id_coordinacion' => request('id_coordinacion')
        ]) }}" class="btn btn-danger">
             Exportar PDF
        </a>
    </div>

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
                        {{-- Botón Componentes Principales --}}
                        <button class="btn btn-sm btn-outline-danger mb-2 toggle-btn"
                            type="button"
                            data-target="comp{{ $equipo->id_equipo }}">
                            Ver Componentes
                        </button>

                        {{-- Botón Componentes Opcionales --}}
                        <button class="btn btn-sm btn-outline-warning mb-2 toggle-btn"
                            type="button"
                            data-target="opc{{ $equipo->id_equipo }}">
                            Ver Componentes Opcionales
                        </button>

                        {{-- Lista Componentes Principales --}}
                        <div class="componentes d-none" id="comp{{ $equipo->id_equipo }}">
                            <ul class="mb-1">
                                @foreach ($equipo->componentes_inactivos as $comp)
                                <li> {{ $comp->tipo_componente }} ({{ $comp->marca }}) - Inactivo</li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- Lista Componentes Opcionales --}}
                        <div class="componentes d-none" id="opc{{ $equipo->id_equipo }}">
                            <strong>Componentes Opcionales:</strong>
                            <ul>
                                @foreach ($equipo->opcionales_inactivos as $op)
                                <li> {{ $op->tipo_opcional }} ({{ $op->marca }} {{ $op->modelo }}) - Inactivo</li>
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