@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">🏠 Dashboard</h1>
    <p>Bienvenido, {{ $usuario->name ?? $usuario->usuario }}.</p>

    {{-- Fila de totales generales --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center p-3 mb-3">
                <h5>Usuarios</h5>
                <p>{{ $totalUsuarios }}</p>
                <a href="{{ route('usuarios.index') }}" class="btn btn-primary btn-sm">Ver</a>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center p-3 mb-3">
                <h5>Equipos</h5>
                <p>{{ $totalEquipos }}</p>
                <a href="{{ route('equipos.index') }}" class="btn btn-primary btn-sm">Ver</a>
            </div>
        </div>
    </div>

    {{-- Fila de estados --}}
    <div class="row">
        <div class="col-md-4">
            <div class="card p-3 mb-3">
                <h5>Estado Funcional de Equipos</h5>
                <ul class="list-group mb-2">
                    @foreach($estadoFuncional as $estado => $count)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $estado }}
                        <span class="badge bg-success rounded-pill">{{ $count }}</span>
                    </li>
                    @endforeach
                </ul>
                <a href="{{ route('estado-funcional.pdf') }}" class="btn btn-secondary btn-sm" target="_blank">
                    📄 Generar PDF
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3 mb-3">
                <h5>Estado Tecnológico de Equipos</h5>
                <ul class="list-group mb-2">
                    @foreach($estadoTecnologico as $estado => $count)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $estado }}
                        <span class="badge bg-warning rounded-pill">{{ $count }}</span>
                    </li>
                    @endforeach
                </ul>
                <a href="{{ route('estado-tecnologico.pdf') }}" class="btn btn-secondary btn-sm" target="_blank">
                    📄 Generar PDF
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3 mb-3">
                <h5>Estado Físico de Gabinetes</h5>
                <ul class="list-group mb-2">
                    @foreach($estadoGabinete as $estado => $count)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $estado }}
                        <span class="badge bg-primary rounded-pill">{{ $count }}</span>
                    </li>
                    @endforeach
                </ul>
                <a href="{{ route('estado-gabinete.pdf') }}" class="btn btn-secondary btn-sm" target="_blank">
                    📄 Generar PDF
                </a>
            </div>
        </div>
    </div>
</div>
@endsection