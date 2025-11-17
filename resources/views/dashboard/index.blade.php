@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

@section('content')
<div class="container">

    <h1 class="mb-4 text-center">🏠     Bienvenido</h1>
    <p class="text-center">Bienvenido, {{ $usuario->name ?? $usuario->usuario }}.</p>

    {{-- Totales Generales --}}
    <div class="row mb-4 justify-content-center text-center">

        {{-- Usuarios --}}
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card shadow-sm p-3">
                <h5>Usuarios</h5>
                <p class="fs-4 fw-bold">{{ $totalUsuarios }}</p>
                <a href="{{ route('usuarios.index') }}" class="btn btn-primary btn-sm">Ver</a>
            </div>
        </div>

        {{-- Equipos --}}
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card shadow-sm p-3">
                <h5>Equipos</h5>
                <p class="fs-4 fw-bold">{{ $totalEquipos }}</p>
                <a href="{{ route('equipos.index') }}" class="btn btn-primary btn-sm">Ver</a>
            </div>
        </div>

        {{-- Direcciones --}}
        @if(Auth::user()->rol === 'Administrador')
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card shadow-sm p-3">
                <h5>Direcciones</h5>
                <p class="fs-4 fw-bold">{{ $totalDirecciones }}</p>
                <a href="{{ route('direcciones.index') }}" class="btn btn-primary btn-sm">Ver</a>
            </div>
        </div>
        @endif
    </div>


    {{-- ============================
        GRÁFICAS DE ESTADOS
    ============================ --}}
    <div class="row justify-content-center">

        {{-- Estado Funcional --}}
        <div class="col-md-4">
            <div class="card p-3 mb-3 shadow-sm">

                <h5 class="text-center">Estado Funcional de Equipos</h5>

                @php
                    $total = array_sum($estadoFuncional);
                @endphp

                <div class="chart-container">
                    @foreach($estadoFuncional as $estado => $count)
                        @php
                            $percent = $total > 0 ? round(($count / $total) * 100, 1) : 0;
                            $color = $estado === 'Operativo' ? '#28a745' :
                                     ($estado === 'Regular' ? '#ffc107' : '#dc3545');
                        @endphp

                        <div class="donut"
                            style="--percent: {{ $percent }};
                                   --color: {{ $color }};"
                            data-value="{{ $percent }}">
                        </div>

                        <p>
                            <span class="legend-box" style="background: {{ $color }}"></span>
                            {{ $estado }} ({{ $count }})
                        </p>
                    @endforeach
                </div>

                <a href="{{ route('estado-funcional.pdf') }}" class="btn btn-secondary btn-sm mt-2" target="_blank">📄 PDF</a>
            </div>
        </div>


        {{-- Estado Tecnológico --}}
        <div class="col-md-4">
            <div class="card p-3 mb-3 shadow-sm">

                <h5 class="text-center">Estado Tecnológico de Equipos</h5>

                @php
                    $total = array_sum($estadoTecnologico);
                @endphp

                <div class="chart-container">
                    @foreach($estadoTecnologico as $estado => $count)
                        @php
                            $percent = $total > 0 ? round(($count / $total) * 100, 1) : 0;
                            $color = $estado === 'Actualizado' ? '#007bff' :
                                     ($estado === 'Obsoleto' ? '#6c757d' : '#ffc107');
                        @endphp

                        <div class="donut"
                            style="--percent: {{ $percent }};
                                   --color: {{ $color }};"
                            data-value="{{ $percent }}">
                        </div>

                        <p>
                            <span class="legend-box" style="background: {{ $color }}"></span>
                            {{ $estado }} ({{ $count }})
                        </p>
                    @endforeach
                </div>

                <a href="{{ route('estado-tecnologico.pdf') }}" class="btn btn-secondary btn-sm mt-2" target="_blank">📄 PDF</a>
            </div>
        </div>


        {{-- Estado Gabinete --}}
        <div class="col-md-4">
            <div class="card p-3 mb-3 shadow-sm">

                <h5 class="text-center">Estado Físico de Gabinetes</h5>

                @php
                    $total = array_sum($estadoGabinete);
                @endphp

                <div class="chart-container">
                    @foreach($estadoGabinete as $estado => $count)
                        @php
                            $percent = $total > 0 ? round(($count / $total) * 100, 1) : 0;
                            $color = $estado === 'Bueno' ? '#28a745' :
                                     ($estado === 'Dañado' ? '#dc3545' : '#ffc107');
                        @endphp

                        <div class="donut"
                            style="--percent: {{ $percent }};
                                   --color: {{ $color }};"
                            data-value="{{ $percent }}">
                        </div>

                        <p>
                            <span class="legend-box" style="background: {{ $color }}"></span>
                            {{ $estado }} ({{ $count }})
                        </p>
                    @endforeach
                </div>

                <a href="{{ route('estado-gabinete.pdf') }}" class="btn btn-secondary btn-sm mt-2" target="_blank">📄 PDF</a>
            </div>
        </div>
    </div>

</div>
@endsection
