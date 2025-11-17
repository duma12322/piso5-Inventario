@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">🏠 Dashboard</h1>
    <p>Bienvenido, {{ $usuario->name ?? $usuario->usuario }}.</p>

    {{-- Totales generales --}}
    <div class="row mb-4 justify-content-center text-center">
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
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/sidebar.js') }}"></script>
<script src="{{ asset('js/graficas.js') }}"></script>
@endsection