@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

@section('content')
<div class="container">

    <h1 class="mb-4 text-center">🏠 Bienvenido</h1>
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

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card shadow-sm p-3">
                <h5>Equipos</h5>
                <p class="fs-4 fw-bold">{{ $totalEquipos }}</p>
                <a href="{{ route('equipos.index') }}" class="btn btn-primary btn-sm">Ver</a>
            </div>
        </div>

        <<<<<<< HEAD======={{-- Direcciones --}}>>>>>>> 95ce691c2b11f5bdbef9d0eca07b581d68aa1250
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

    <<<<<<< HEAD
        {{-- Estados con gráficas --}}
        <div class="row justify-content-center">
        @foreach([
        ['id'=>'chartFuncional','titulo'=>'Estado Funcional de Equipos','data'=>$estadoFuncional,'pdf'=>'estado-funcional.pdf'],
        ['id'=>'chartTecnologico','titulo'=>'Estado Tecnológico de Equipos','data'=>$estadoTecnologico,'pdf'=>'estado-tecnologico.pdf'],
        ['id'=>'chartGabinete','titulo'=>'Estado Físico de Gabinetes','data'=>$estadoGabinete,'pdf'=>'estado-gabinete.pdf']
        ] as $chart)
        <div class="col-md-4">
            <div class="card p-3 mb-3 shadow-sm">
                <h5>{{ $chart['titulo'] }}</h5>
                <ul class="list-group mb-2">
                    @foreach($chart['data'] as $estado => $count)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $estado }}
                        <span class="badge bg-secondary rounded-pill">{{ $count }}</span>
                    </li>
                    @endforeach
                </ul>
                <canvas id="{{ $chart['id'] }}"
                    data-labels='@json(array_keys($chart["data"]))'
                    data-data='@json(array_values($chart["data"]))'></canvas>
                <a href="{{ url($chart['pdf']) }}" class="btn btn-secondary btn-sm mt-2" target="_blank">📄 Generar PDF</a>
                =======

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
                            >>>>>>> 95ce691c2b11f5bdbef9d0eca07b581d68aa1250
                        </div>
                    </div>
                    @endforeach
                </div>

            </div>
            @endsection
            <<<<<<< HEAD

                @section('scripts')
                <script src="https://cdn.jsdelivr.net/npm/chart.js">
                </script>
                <script src="{{ asset('js/sidebar.js') }}"></script>
                <script src="{{ asset('js/graficas.js') }}"></script>
                @endsection
                =======
                >>>>>>> 95ce691c2b11f5bdbef9d0eca07b581d68aa1250