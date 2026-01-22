@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}?v={{ time() }}">

@section('content')
<div class="container">

    {{-- =========================
        Header del Dashboard
        ========================= --}}
    <div class="dashboard-header text-center mb-5">
        <h1 class="welcome-title">Bienvenido, {{ $usuario->usuario }}</h1>
    </div>

    {{-- =========================
        Tarjetas de Totales (Usuarios, Equipos, Direcciones)
        Con animaciones y enlaces a detalles
        ========================= --}}
    <div class="row mb-5 justify-content-center text-center">

        {{-- Usuarios (Solo visible para Administrador) --}}
        @if(Auth::user()->rol === 'Administrador')
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="stats-card card-1 animate-card">
                {{-- Icono de la tarjeta --}}
                <div class="card-icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="stats-icon">
                        <path d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 ..." />
                    </svg>
                </div>

                {{-- Valor y etiqueta de la métrica --}}
                <div class="metric-value">{{ $totalUsuarios }}</div>
                <div class="metric-label">Usuarios</div>

                {{-- Enlace a la lista de usuarios --}}
                <a href="{{ route('usuarios.index') }}" class="stats-card__action">
                    Ver detalles →
                </a>
            </div>
        </div>
        @endif

        {{-- Equipos (Visible para todos) --}}
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="stats-card card-2 animate-card">
                <div class="card-icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="stats-icon">
                        <path fill-rule="evenodd" d="M2.25 5.25a3 3 0 0 1 3-3h13.5..." />
                    </svg>
                </div>
                <div class="metric-value">{{ $totalEquipos }}</div>
                <div class="metric-label">Equipos</div>
                <a href="{{ route('equipos.index') }}" class="stats-card__action">
                    Ver detalles →
                </a>
            </div>
        </div>

        {{-- Direcciones (Solo Administrador) --}}
        @if(Auth::user()->rol === 'Administrador')
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="stats-card card-3 animate-card">
                <div class="card-icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="stats-icon">
                        <path fill-rule="evenodd" d="M8.161 2.58a1.875 1.875 0 0 1 1.678 0l4.993 2.498..." />
                    </svg>
                </div>
                <div class="metric-value">{{ $totalDirecciones }}</div>
                <div class="metric-label">Direcciones</div>
                <a href="{{ route('direcciones.index') }}" class="stats-card__action">
                    Ver detalles →
                </a>
            </div>
        </div>
        @endif
    </div>

    {{-- =========================
        Sección de Gráficas
        Estadísticas en tiempo real
        ========================= --}}
    <div class="charts-section">
        <h2 class="section-title text-center mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                style="width: 35px; height: 35px; color: var(--primary-color); vertical-align: -7px; margin-right: 8px;">
                <path fill-rule="evenodd" d="M2.25 13.5a8.25 8.25 0 0 1 8.25-8.25..." />
                <path fill-rule="evenodd" d="M12.75 3a.75.75 0 0 1 .75-.75..." />
            </svg>
            Estadísticas en Tiempo Real
        </h2>

        <div class="row justify-content-center">

            {{-- Gráfica de Estado Funcional --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="chart-card animate-card">
                    <div class="chart-header">
                        <h5>Estado Funcional</h5>
                    </div>

                    @php
                    // =========================
                    // Ordenar el estado funcional: Buen Funcionamiento → Operativo → Sin Funcionar
                    // =========================
                    $order = ['Buen Funcionamiento', 'Operativo', 'Sin Funcionar'];
                    uksort($estadoFuncional, function($key1, $key2) use ($order) {
                    $pos1 = array_search($key1, $order);
                    $pos2 = array_search($key2, $order);
                    return ($pos1 === false ? 999 : $pos1) - ($pos2 === false ? 999 : $pos2);
                    });

                    $total = array_sum($estadoFuncional);
                    $cumulative = 0;
                    @endphp

                    {{-- Contenedor SVG del donut chart --}}
                    <div class="chart-wrapper">
                        <div class="donut-chart-container">
                            <svg width="100%" height="100%" viewBox="0 0 42 42" class="donut-svg">
                                {{-- Fondo del donut --}}
                                <circle class="donut-hole" cx="21" cy="21" r="15.91549430918954" fill="#fff"></circle>
                                <circle class="donut-ring" cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="#d2d3d4" stroke-width="5"></circle>

                                {{-- Segmentar los estados funcionales --}}
                                @foreach($estadoFuncional as $estado => $count)
                                @php
                                $percent = $total > 0 ? round(($count / $total) * 100, 1) : 0;
                                $color = $estado === 'Buen Funcionamiento' ? '#2ed604ff' :
                                ($estado === 'Operativo' ? '#ffc107' : '#eb071eff');
                                $svgOffset = 25 - $cumulative;
                                $cumulative += $percent;
                                @endphp

                                <circle class="donut-segment-svg" cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="{{ $color }}" stroke-width="5"
                                    stroke-dasharray="{{ $percent }} {{ 100 - $percent }}"
                                    stroke-dashoffset="{{ $svgOffset }}"
                                    data-percent="{{ $percent }}"
                                    data-label="{{ $estado }}"
                                    style="--color: {{ $color }}; cursor: pointer; transition: stroke-width 0.3s;">
                                    <title>{{ $estado }}: {{ $percent }}%</title>
                                </circle>
                                @endforeach
                            </svg>

                            {{-- Centro del donut con total --}}
                            <div class="donut-center" data-original-total="{{ $total }}" data-original-label="Total">
                                <span class="donut-total">{{ $total }}</span>
                                <small>Total</small>
                            </div>
                        </div>
                    </div>

                    {{-- Leyenda --}}
                    <div class="chart-legend">
                        @foreach($estadoFuncional as $estado => $count)
                        @php
                        $color = $estado === 'Buen Funcionamiento' ? '#1ed106ff' :
                        ($estado === 'Operativo' ? '#ffee04ff' : '#ff0707ff');
                        @endphp
                        <div class="legend-item">
                            <span class="legend-color" style="background: {{ $color }}"></span>
                            <span class="legend-label">{{ $estado }}</span>
                            <span class="legend-count">({{ $count }})</span>
                        </div>
                        @endforeach
                    </div>

                    {{-- Botones de descarga --}}
                    <a href="{{ route('estado-funcional.pdf') }}" class="btn-download" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            style="width: 20px; height: 20px; color: white; margin-right: 8px; vertical-align: text-bottom;">
                            <path d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75..." />
                        </svg>
                        Descargar PDF
                    </a>
                    <a href="{{ route('excel.estado-funcional') }}" class="btn-download"
                        style="background-color: #107c41; margin-left: 10px;" target="_blank">
                        <i class="fas fa-file-excel" style="margin-right: 8px; color: white;"></i>
                        Descargar Excel
                    </a>
                </div>
            </div>

            {{-- =========================
    Estado Tecnológico
    Gráfica tipo donut mostrando la proporción de equipos
    Nuevo / Actualizable / Obsoleto
    ========================= --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="chart-card animate-card">
                    <div class="chart-header">
                        <h5>Estado Tecnológico</h5>
                    </div>

                    @php
                    // Total de equipos y acumulado para los segmentos SVG
                    $total = array_sum($estadoTecnologico);
                    $cumulative = 0;
                    @endphp

                    <div class="chart-wrapper">
                        <div class="donut-chart-container">
                            <svg width="100%" height="100%" viewBox="0 0 42 42" class="donut-svg">
                                {{-- Fondo del donut --}}
                                <circle class="donut-hole" cx="21" cy="21" r="15.91549430918954" fill="#fff"></circle>
                                <circle class="donut-ring" cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="#d2d3d4" stroke-width="5"></circle>

                                {{-- Segmentos dinámicos según estado tecnológico --}}
                                @foreach($estadoTecnologico as $estado => $count)
                                @php
                                $percent = $total > 0 ? round(($count / $total) * 100, 1) : 0;
                                // Definir color según estado
                                $color = $estado === 'Nuevo' ? '#4bf508ff' :
                                ($estado === 'Actualizable' ? '#ffc107' :
                                ($estado === 'Obsoleto' ? '#fa0606ff' : '#0651f1ff'));

                                $svgOffset = 25 - $cumulative;
                                $cumulative += $percent;
                                @endphp

                                <circle class="donut-segment-svg" cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="{{ $color }}" stroke-width="5"
                                    stroke-dasharray="{{ $percent }} {{ 100 - $percent }}"
                                    stroke-dashoffset="{{ $svgOffset }}"
                                    data-percent="{{ $percent }}"
                                    data-label="{{ $estado }}"
                                    style="--color: {{ $color }}; cursor: pointer; transition: stroke-width 0.3s;">
                                    <title>{{ $estado }}: {{ $percent }}%</title>
                                </circle>
                                @endforeach
                            </svg>

                            {{-- Centro del donut con total --}}
                            <div class="donut-center" data-original-total="{{ $total }}" data-original-label="Total">
                                <span class="donut-total">{{ $total }}</span>
                                <small>Total</small>
                            </div>
                        </div>
                    </div>

                    {{-- Leyenda de colores --}}
                    <div class="chart-legend">
                        @foreach($estadoTecnologico as $estado => $count)
                        @php
                        $color = $estado === 'Nuevo' ? '#39dd08ff' :
                        ($estado === 'Actualizable' ? '#ffc107' :
                        ($estado === 'Obsoleto' ? '#fa0606ff' : '#ffc107'));
                        @endphp
                        <div class="legend-item">
                            <span class="legend-color" style="background: {{ $color }}"></span>
                            <span class="legend-label">{{ $estado }}</span>
                            <span class="legend-count">({{ $count }})</span>
                        </div>
                        @endforeach
                    </div>

                    {{-- Botones de descarga --}}
                    <a href="{{ route('estado-tecnologico.pdf') }}" class="btn-download" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            style="width: 20px; height: 20px; color: white; margin-right: 8px; vertical-align: text-bottom;">
                            <path d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25..." />
                        </svg>
                        Descargar PDF
                    </a>
                    <a href="{{ route('excel.estado-tecnologico') }}" class="btn-download"
                        style="background-color: #107c41; margin-left: 10px;" target="_blank">
                        <i class="fas fa-file-excel" style="margin-right: 8px; color: white;"></i>
                        Descargar Excel
                    </a>
                </div>
            </div>

            {{-- =========================
    Estado Físico de Gabinetes
    Gráfica tipo donut mostrando la condición de los gabinetes
    Nuevo / Dañado / Otro
    ========================= --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="chart-card animate-card">
                    <div class="chart-header">
                        <h5>Estado Físico de Gabinetes</h5>
                    </div>

                    @php
                    $total = array_sum($estadoGabinete);
                    $cumulative = 0;
                    @endphp

                    <div class="chart-wrapper">
                        <div class="donut-chart-container">
                            <svg width="100%" height="100%" viewBox="0 0 42 42" class="donut-svg">
                                <circle class="donut-hole" cx="21" cy="21" r="15.91549430918954" fill="#fff"></circle>
                                <circle class="donut-ring" cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="#d2d3d4" stroke-width="5"></circle>

                                @foreach($estadoGabinete as $estado => $count)
                                @php
                                $percent = $total > 0 ? round(($count / $total) * 100, 1) : 0;
                                $color = $estado === 'Nuevo' ? '#54f708ff' :
                                ($estado === 'Dañado' ? '#d40202ff' : '#ffc107');

                                $svgOffset = 25 - $cumulative;
                                $cumulative += $percent;
                                @endphp

                                <circle class="donut-segment-svg" cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="{{ $color }}" stroke-width="5"
                                    stroke-dasharray="{{ $percent }} {{ 100 - $percent }}"
                                    stroke-dashoffset="{{ $svgOffset }}"
                                    data-percent="{{ $percent }}"
                                    data-label="{{ $estado }}"
                                    style="--color: {{ $color }}; cursor: pointer; transition: stroke-width 0.3s;">
                                    <title>{{ $estado }}: {{ $percent }}%</title>
                                </circle>
                                @endforeach
                            </svg>

                            <div class="donut-center" data-original-total="{{ $total }}" data-original-label="Total">
                                <span class="donut-total">{{ $total }}</span>
                                <small>Total</small>
                            </div>
                        </div>
                    </div>

                    {{-- Leyenda de colores --}}
                    <div class="chart-legend">
                        @foreach($estadoGabinete as $estado => $count)
                        @php
                        $color = $estado === 'Nuevo' ? '#28a745' :
                        ($estado === 'Dañado' ? '#ce0606ff' : '#ffc107');
                        @endphp
                        <div class="legend-item">
                            <span class="legend-color" style="background: {{ $color }}"></span>
                            <span class="legend-label">{{ $estado }}</span>
                            <span class="legend-count">({{ $count }})</span>
                        </div>
                        @endforeach
                    </div>

                    {{-- Botones de descarga --}}
                    <a href="{{ route('estado-gabinete.pdf') }}" class="btn-download" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            style="width: 20px; height: 20px; color: white; margin-right: 8px; vertical-align: text-bottom;">
                            <path d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25..." />
                        </svg>
                        Descargar PDF
                    </a>
                    <a href="{{ route('excel.estado-gabinete') }}" class="btn-download"
                        style="background-color: #107c41; margin-left: 10px;" target="_blank">
                        <i class="fas fa-file-excel" style="margin-right: 8px; color: white;"></i>
                        Descargar Excel
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // ===============================
    // Animación de tarjetas y gráficos
    // ===============================

    // Se ejecuta cuando todo el DOM ha sido cargado
    document.addEventListener('DOMContentLoaded', function() {

        // -------------------------------
        // Animación de tarjetas al hacer scroll
        // -------------------------------
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                // Cuando la tarjeta entra en el viewport, se ejecuta la animación
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                }
            });
        });

        // Selecciona todas las tarjetas con clase 'animate-card' y las observa
        document.querySelectorAll('.animate-card').forEach(card => {
            observer.observe(card);
        });

        // -------------------------------
        // Interacción Hover sobre gráficos tipo Donut (SVG)
        // -------------------------------
        const segments = document.querySelectorAll('.donut-segment-svg');

        segments.forEach(segment => {

            // Cuando el mouse entra en un segmento
            segment.addEventListener('mouseenter', function() {
                // Engrosar segmento para efecto visual
                this.setAttribute('stroke-width', '6');

                // Obtener contenedor del donut y elementos del centro
                const container = this.closest('.donut-chart-container');
                const center = container.querySelector('.donut-center');
                const totalSpan = center.querySelector('.donut-total');
                const labelSmall = center.querySelector('small');

                // Obtener datos del segmento
                const percent = this.getAttribute('data-percent');
                const label = this.getAttribute('data-label');
                const color = this.style.getPropertyValue('--color').trim();

                // Actualizar el centro del donut con información del segmento
                totalSpan.textContent = percent + '%';
                totalSpan.style.color = color;
                labelSmall.textContent = label;
            });

            // Cuando el mouse sale del segmento
            segment.addEventListener('mouseleave', function() {
                // Revertir grosor original
                this.setAttribute('stroke-width', '5');

                // Restaurar valores originales del centro del donut
                const container = this.closest('.donut-chart-container');
                const center = container.querySelector('.donut-center');
                const totalSpan = center.querySelector('.donut-total');
                const labelSmall = center.querySelector('small');

                const originalTotal = center.getAttribute('data-original-total');
                const originalLabel = center.getAttribute('data-original-label');

                totalSpan.textContent = originalTotal;
                totalSpan.style.color = '';
                labelSmall.textContent = originalLabel;
            });

        });

    });
</script>
@endsection