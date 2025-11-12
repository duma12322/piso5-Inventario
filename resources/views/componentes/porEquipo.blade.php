@extends('layouts.app')

@section('title', 'Componentes del Equipo')

@section('content')
<div class="container mt-4">
    <h3>Componentes del Equipo</h3>
    <a href="{{ route('equipos.index') }}" class="btn btn-secondary mb-2">Volver a Equipos</a>

    <!-- Botones de agregar componentes -->
    <div class="mb-3">
        <a href="{{ route('componentes.createPorEquipo', ['id_equipo' => $id_equipo]) }}" class="btn btn-primary">Agregar Componente</a>
    </div>

    <!-- Componentes principales -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Capacidad</th>
                <th>Estado</th>
                <th>Ranuras</th>
                <th>Puertos E/S</th>
                <th>Bios/Uefi</th>
                <th>Conectores de alimentación</th>
                <th>Acciones</th> <!-- Nueva columna -->
            </tr>
        </thead>
        <tbody>
            @forelse ($componentes as $c)
            <tr>
                <td>{{ $c->id_componente ?? 'Sin ID' }}</td>
                <td>{{ $c->tipo_componente ?? 'Sin tipo' }}</td>
                <td>{{ $c->marca ?? 'Sin marca' }}</td>
                <td>{{ $c->modelo ?? 'Sin modelo' }}</td>
                <td>{{ $c->capacidad ?? 'Sin capacidad' }}</td>
                <td>{{ $c->estado ?? 'Sin estado' }}</td>
                <td>
                    @php $ranuras = !empty($c->ranuras_expansion) ? explode(',', $c->ranuras_expansion) : []; @endphp
                    @forelse($ranuras as $ranura)
                    <span class="badge bg-success me-1">{{ $ranura }}</span>
                    @empty
                    Sin info
                    @endforelse
                </td>
                <td>
                    @php
                    $puertos = array_merge(
                    !empty($c->puertos_internos) ? explode(',', $c->puertos_internos) : [],
                    !empty($c->puertos_externos) ? explode(',', $c->puertos_externos) : []
                    );
                    @endphp
                    @forelse($puertos as $puerto)
                    <span class="badge bg-primary me-1">{{ $puerto }}</span>
                    @empty
                    Sin info
                    @endforelse
                </td>
                <td>{{ $c->bios_uefi ?? 'Sin info' }}</td>
                <td>
                    @php $conectores = !empty($c->conectores_alimentacion) ? explode(',', $c->conectores_alimentacion) : []; @endphp
                    @forelse($conectores as $conector)
                    <span class="badge bg-success me-1">{{ $conector }}</span>
                    @empty
                    Sin info
                    @endforelse
                </td>

                <!-- Acciones -->
                <td>
                    <a href="{{ route('componentes.editPorEquipo', $c->id_componente) }}" class="btn btn-warning btn-sm mb-1">✏️ Editar</a>

                    <form action="{{ route('componentes.destroy', $c->id_componente) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Desea eliminar este componente?');">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="porEquipo" value="1">
                        <input type="hidden" name="id_equipo" value="{{ $id_equipo }}">
                        <button type="submit" class="btn btn-danger btn-sm">🗑️ Eliminar</button>
                    </form>
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="11" class="text-center">No hay componentes disponibles</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Botón para mostrar opcionales -->
    <button id="toggleOpcionales" class="btn btn-info mb-2">Permitir ver Componentes Opcionales</button>

    <!-- Tabla de opcionales -->
    <div id="opcionales" style="display:none;">
        <h5>Componentes Opcionales</h5>

        <!-- Botón agregar opcional -->
        <div class="mb-3">
            <a href="{{ route('componentesOpcionales.createPorEquipo', ['id_equipo' => $id_equipo]) }}" class="btn btn-success">➕ Agregar Opcional</a>
        </div>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Capacidad</th>
                    <th>Frecuencia</th>
                    <th>Tipo</th>
                    <th>Consumo</th>
                    <th>Estado</th>
                    <th>Detalles</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($opcionales as $o)
                <tr>
                    <td>{{ trim($o->id_opcional) ?: 'Sin ID' }}</td>
                    <td>{{ trim($o->tipo_opcional) ?: 'Sin tipo' }}</td>
                    <td>{{ trim($o->marca) ?: 'Sin marca' }}</td>
                    <td>{{ trim($o->modelo) ?: 'Sin modelo' }}</td>
                    <td>{{ trim($o->capacidad) ?: 'Sin capacidad' }}</td>
                    <td>{{ trim($o->frecuencia) ?: 'Sin frecuencia' }}</td>
                    <td>{{ trim($o->tipo) ?: 'Sin tipo' }}</td>
                    <td>{{ trim($o->consumo) ?: 'Sin consumo' }}</td>
                    <td>{{ trim($o->estado) ?: 'Sin estado' }}</td>
                    <td>{{ trim($o->detalles) ?: 'Sin detalles' }}</td>
                    <!-- Acciones -->
                    <td>
                        <a href="{{ route('componentesOpcionales.editPorEquipo', $o->id_opcional) }}" class="btn btn-warning btn-sm mb-1">✏️ Editar</a>

                        <form action="{{ route('componentesOpcionales.destroy', $o->id_opcional) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Desea eliminar este componente opcional?');">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="porEquipo" value="1">
                            <input type="hidden" name="id_equipo" value="{{ $id_equipo }}">
                            <button type="submit" class="btn btn-danger btn-sm">🗑️ Eliminar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="text-center">No hay componentes opcionales disponibles</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/porEquipo.js') }}"></script>
@endsection