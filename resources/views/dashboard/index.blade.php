@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

@section('content')
<div class="container">

    {{-- Header con animación --}}
    <div class="dashboard-header text-center mb-5">
        <h1 class="welcome-title"> Bienvenido</h1>
        <p class="welcome-subtitle"> Que nuevo tenemos hoy? <span class="user-name">{{ $usuario->name ?? $usuario->usuario }}</span> </p>
    </div>

    {{-- Tarjetas de Totales con animaciones --}}
    <div class="row mb-5 justify-content-center text-center">
        {{-- Usuarios --}}
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="stats-card card-1 animate-card">
                <div class="card-icon">👥</div>
                <h5>Usuarios</h5>
                <p class="stats-number">{{ $totalUsuarios }}</p>
                <a href="{{ route('usuarios.index') }}" class="btn btn-primary btn-sm card-btn">Ver Detalles</a>
            </div>
        </div>

        {{-- Equipos --}}
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="stats-card card-2 animate-card">
                <div class="card-icon">💻</div>
                <h5>Equipos</h5>
                <p class="stats-number">{{ $totalEquipos }}</p>
                <a href="{{ route('equipos.index') }}" class="btn btn-primary btn-sm card-btn">Ver Detalles</a>
            </div>
        </div>

        {{-- Direcciones --}}
        @if(Auth::user()->rol === 'Administrador')
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="stats-card card-3 animate-card">
                <div class="card-icon">🏢</div>
                <h5>Direcciones</h5>
                <p class="stats-number">{{ $totalDirecciones }}</p>
                <a href="{{ route('direcciones.index') }}" class="btn btn-primary btn-sm card-btn">Ver Detalles</a>
            </div>
        </div>
        @endif
    </div>

    {{-- Sección de Gráficas --}}
    <div class="charts-section">
        <h2 class="section-title text-center mb-4">📊 Estadísticas en Tiempo Real</h2>
        
        <div class="row justify-content-center">
            {{-- Estado Funcional --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="chart-card animate-card">
                    <div class="chart-header">
                        <h5> Estado Funcional</h5>
                    </div>
                    
                    @php
                    $total = array_sum($estadoFuncional);
                    $accumulatedPercent = 0;
                    $segmentCount = count($estadoFuncional);
                    $index = 0;
                    @endphp

                    <div class="chart-wrapper">
                        <div class="donut-chart">
                            @foreach($estadoFuncional as $estado => $count)
                            @php
                            $index++;
                            // Calcular porcentaje exacto sin redondear primero
                            $percentExact = $total > 0 ? ($count / $total) * 100 : 0;
                            
                            // Si es el último segmento, asegurar que llegue a 100%
                            if ($index === $segmentCount) {
                                $percent = 100 - $accumulatedPercent;
                            } else {
                                $percent = round($percentExact, 1);
                            }
                            
                            $startPercent = $accumulatedPercent;
                            $endPercent = $accumulatedPercent + $percent;
                            $accumulatedPercent = $endPercent;
                            $color = $estado === 'Buen Funcionamiento' ? '#2ed604ff' :
                            ($estado === 'Operativo' ? '#ffc107' : '#eb071eff');
                            $delay = $loop->index * 0.3;
                            @endphp

                            <div class="donut-segment" 
                                 style="--start-percent: {{ $startPercent }}; 
                                        --end-percent: {{ $endPercent }};
                                        --percent: {{ $percent }}; 
                                        --color: {{ $color }};
                                        --delay: {{ $delay }}s;"
                                 data-percent="{{ $percent }}"
                                 data-label="{{ $estado }}">
                                <div class="segment-tooltip">{{ $estado }}: {{ $percent }}%</div>
                            </div>
                            @endforeach
                            
                            <div class="donut-center">
                                <span class="donut-total">{{ $total }}</span>
                                <small>Total</small>
                            </div>
                        </div>
                    </div>

                    <div class="chart-legend">
                        @foreach($estadoFuncional as $estado => $count)
                        @php
                        $color = $estado === 'Buen Funcionamiento' ? '#1ed106ff' :
                        $color = $estado === 'Operativo' ? '#ffee04ff' :
                        ($estado === 'Sin Funcionar' ? '#ff0707ff' : '#000000ff');
                        @endphp
                        <div class="legend-item">
                            <span class="legend-color" style="background: {{ $color }}"></span>
                            <span class="legend-label">{{ $estado }}</span>
                            <span class="legend-count">({{ $count }})</span>
                        </div>
                        @endforeach
                    </div>

                    <a href="{{ route('estado-funcional.pdf') }}" class="btn-download" target="_blank">
                        📄 Descargar PDF
                    </a>
                </div>
            </div>

            {{-- Estado Tecnológico --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="chart-card animate-card">
                    <div class="chart-header">
                        <h5> Estado Tecnológico</h5>
                    </div>
                    
                    @php
                    $total = array_sum($estadoTecnologico);
                    $accumulatedPercent = 0;
                    $segmentCount = count($estadoTecnologico);
                    $index = 0;
                    @endphp

                    <div class="chart-wrapper">
                        <div class="donut-chart">
                            @foreach($estadoTecnologico as $estado => $count)
                            @php
                            $index++;
                            // Calcular porcentaje exacto sin redondear primero
                            $percentExact = $total > 0 ? ($count / $total) * 100 : 0;
                            
                            // Si es el último segmento, asegurar que llegue a 100%
                            if ($index === $segmentCount) {
                                $percent = 100 - $accumulatedPercent;
                            } else {
                                $percent = round($percentExact, 1);
                            }
                            
                            $startPercent = $accumulatedPercent;
                            $endPercent = $accumulatedPercent + $percent;
                            $accumulatedPercent = $endPercent;
                            $color = $estado === 'Nuevo' ? '#4bf508ff' :
                            ($estado === 'Actualizable' ? '#0651f1ff' : '#ffc107');
                            ($estado === 'Obsoleto' ? '#fa0606ff' : '#30ff07ff');
                            $delay = $loop->index * 0.3;
                            @endphp

                            <div class="donut-segment" 
                                 style="--start-percent: {{ $startPercent }}; 
                                        --end-percent: {{ $endPercent }};
                                        --percent: {{ $percent }}; 
                                        --color: {{ $color }};
                                        --delay: {{ $delay }}s;"
                                 data-percent="{{ $percent }}"
                                 data-label="{{ $estado }}">
                                <div class="segment-tooltip">{{ $estado }}: {{ $percent }}%</div>
                            </div>
                            @endforeach
                            
                            <div class="donut-center">
                                <span class="donut-total">{{ $total }}</span>
                                <small>Total</small>
                            </div>
                        </div>
                    </div>

                    <div class="chart-legend">
                        @foreach($estadoTecnologico as $estado => $count)
                        @php
                        $color = $estado === 'Nuevo' ? '#39dd08ff' :
                        ($estado === 'Actualizable' ? '#3818ecff' : '#ffc107');
                        @endphp
                        <div class="legend-item">
                            <span class="legend-color" style="background: {{ $color }}"></span>
                            <span class="legend-label">{{ $estado }}</span>
                            <span class="legend-count">({{ $count }})</span>
                        </div>
                        @endforeach
                    </div>

                    <a href="{{ route('estado-tecnologico.pdf') }}" class="btn-download" target="_blank">
                        📄 Descargar PDF
                    </a>
                </div>
            </div>

            {{-- Estado Gabinete --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="chart-card animate-card">
                    <div class="chart-header">
                        <h5> Estado Físico de Gabinetes</h5>
                    </div>
                    
                    @php
                    $total = array_sum($estadoGabinete);
                    $accumulatedPercent = 0;
                    $segmentCount = count($estadoGabinete);
                    $index = 0;
                    @endphp

                    <div class="chart-wrapper">
                        <div class="donut-chart">
                            @foreach($estadoGabinete as $estado => $count)
                            @php
                            $index++;
                            // Calcular porcentaje exacto sin redondear primero
                            $percentExact = $total > 0 ? ($count / $total) * 100 : 0;
                            
                            // Si es el último segmento, asegurar que llegue a 100%
                            if ($index === $segmentCount) {
                                $percent = 100 - $accumulatedPercent;
                            } else {
                                $percent = round($percentExact, 1);
                            }
                            
                            $startPercent = $accumulatedPercent;
                            $endPercent = $accumulatedPercent + $percent;
                            $accumulatedPercent = $endPercent;
                            $color = $estado === 'Nuevo' ? '#54f708ff' :
                            ($estado === 'Dañado' ? '#d40202ff' : '#ffc107');
                            $delay = $loop->index * 0.3;
                            @endphp

                            <div class="donut-segment" 
                                 style="--start-percent: {{ $startPercent }}; 
                                        --end-percent: {{ $endPercent }};
                                        --percent: {{ $percent }}; 
                                        --color: {{ $color }};
                                        --delay: {{ $delay }}s;"
                                 data-percent="{{ $percent }}"
                                 data-label="{{ $estado }}">
                                <div class="segment-tooltip">{{ $estado }}: {{ $percent }}%</div>
                            </div>
                            @endforeach
                            
                            <div class="donut-center">
                                <span class="donut-total">{{ $total }}</span>
                                <small>Total</small>
                            </div>
                        </div>
                    </div>

                    <div class="chart-legend">
                        @foreach($estadoGabinete as $estado => $count)
                        @php
                        $color = $estado == 'Nuevo' ? '#28a745' :
                        ($estado === 'Dañado' ? '#ce0606ff' : '#ffc107');
                        @endphp
                        <div class="legend-item">
                            <span class="legend-color" style="background: {{ $color }}"></span>
                            <span class="legend-label">{{ $estado }}</span>
                            <span class="legend-count">({{ $count }})</span>
                        </div>
                        @endforeach
                    </div>

                    <a href="{{ route('estado-gabinete.pdf') }}" class="btn-download" target="_blank">
                        📄 Descargar PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
// Animación para las tarjetas al hacer scroll
document.addEventListener('DOMContentLoaded', function() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animationPlayState = 'running';
            }
        });
    });

    document.querySelectorAll('.animate-card').forEach(card => {
        observer.observe(card);
    });
});
</script>
@endsection