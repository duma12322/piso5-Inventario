@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}?v={{ time() }}">

@section('content')
<div class="container">

    {{-- Header con animación --}}
    <div class="dashboard-header text-center mb-5">
        <h1 class="welcome-title">Bienvenido, {{ $usuario->usuario }}</h1>
    </div>

    {{-- Tarjetas de Totales con animaciones --}}

    <div class="row mb-5 justify-content-center text-center">
        @if(Auth::user()->rol === 'Administrador')
        {{-- Usuarios --}}
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="stats-card card-1 animate-card">
                <div class="card-icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="stats-icon">
                        <path
                            d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z" />
                    </svg>
                </div>
                <div class="metric-value">{{ $totalUsuarios }}</div>
                <div class="metric-label">Usuarios</div>
                <a href="{{ route('usuarios.index') }}" class="stats-card__action">
                    Ver detalles →
                </a>
            </div>
        </div>
        @endif

        {{-- Equipos --}}
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="stats-card card-2 animate-card">
                <div class="card-icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="stats-icon">
                        <path fill-rule="evenodd"
                            d="M2.25 5.25a3 3 0 0 1 3-3h13.5a3 3 0 0 1 3 3V15a3 3 0 0 1-3 3h-3v.257c0 .597.237 1.17.659 1.591l.621.622a.75.75 0 0 1-.53 1.28h-9a.75.75 0 0 1-.53-1.28l.621-.622a2.25 2.25 0 0 0 .659-1.59V18h-3a3 3 0 0 1-3-3V5.25Zm1.5 0v7.5a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5v-7.5a1.5 1.5 0 0 0-1.5-1.5H5.25a1.5 1.5 0 0 0-1.5 1.5Z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="metric-value">{{ $totalEquipos }}</div>
                <div class="metric-label">Equipos</div>
                <a href="{{ route('equipos.index') }}" class="stats-card__action">
                    Ver detalles →
                </a>
            </div>
        </div>

        {{-- Direcciones --}}
        @if(Auth::user()->rol === 'Administrador')
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="stats-card card-3 animate-card">
                <div class="card-icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="stats-icon">
                        <path fill-rule="evenodd"
                            d="M8.161 2.58a1.875 1.875 0 0 1 1.678 0l4.993 2.498c.106.052.23.052.336 0l3.869-1.935A1.875 1.875 0 0 1 21.75 4.82v12.485c0 .71-.401 1.36-1.037 1.677l-4.875 2.437a1.875 1.875 0 0 1-1.676 0l-4.994-2.497a.375.375 0 0 0-.336 0l-3.868 1.935A1.875 1.875 0 0 1 2.25 19.18V6.695c0-.71.401-1.36 1.036-1.677l4.875-2.437ZM9 6a.75.75 0 0 1 .75.75V15a.75.75 0 0 1-1.5 0V6.75A.75.75 0 0 1 9 6Zm6.75 3a.75.75 0 0 0-1.5 0v8.25a.75.75 0 0 0 1.5 0V9Z"
                            clip-rule="evenodd" />
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

    {{-- Sección de Gráficas --}}
    <div class="charts-section">
        <h2 class="section-title text-center mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                style="width: 35px; height: 35px; color: var(--primary-color); vertical-align: -7px; margin-right: 8px;">
                <path fill-rule="evenodd"
                    d="M2.25 13.5a8.25 8.25 0 0 1 8.25-8.25.75.75 0 0 1 .75.75v6.75H18a.75.75 0 0 1 .75.75 8.25 8.25 0 0 1-16.5 0Z"
                    clip-rule="evenodd" />
                <path fill-rule="evenodd"
                    d="M12.75 3a.75.75 0 0 1 .75-.75 8.25 8.25 0 0 1 8.25 8.25.75.75 0 0 1-.75.75h-7.5a.75.75 0 0 1-.75-.75V3Z"
                    clip-rule="evenodd" />
            </svg>
            Estadísticas en Tiempo Real
        </h2>

        <div class="row justify-content-center">
            {{-- Estado Funcional --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="chart-card animate-card">
                    <div class="chart-header">
                        <h5> Estado Funcional</h5>
                    </div>

                    @php
                    // Ordenar: Buen Funcionamiento primero
                    $order = ['Buen Funcionamiento', 'Operativo', 'Sin Funcionar'];
                    uksort($estadoFuncional, function($key1, $key2) use ($order) {
                    $pos1 = array_search($key1, $order);
                    $pos2 = array_search($key2, $order);
                    return ($pos1 === false ? 999 : $pos1) - ($pos2 === false ? 999 : $pos2);
                    });

                    $total = array_sum($estadoFuncional);
                    $cumulative = 0;
                    @endphp

                    <div class="chart-wrapper">
                        <div class="donut-chart-container">
                            <svg width="100%" height="100%" viewBox="0 0 42 42" class="donut-svg">
                                <circle class="donut-hole" cx="21" cy="21" r="15.91549430918954" fill="#fff"></circle>
                                <circle class="donut-ring" cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="#d2d3d4" stroke-width="5"></circle>

                                @foreach($estadoFuncional as $estado => $count)
                                @php
                                $percent = $total > 0 ? round(($count / $total) * 100, 1) : 0;
                                $color = $estado === 'Buen Funcionamiento' ? '#2ed604ff' :
                                ($estado === 'Operativo' ? '#ffc107' : '#eb071eff');
                                $offset = 100 - $cumulative + 25; // Start at top (adjust as needed)
                                // Improved offset logic for SVG:
                                // SVG stroke draws clockwise. To stack, we use standard dashoffset logic.
                                // DashOffset = 25 (Top 12 o'clock) - Cumulative.
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
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            style="width: 20px; height: 20px; color: white; margin-right: 8px; vertical-align: text-bottom;">
                            <path
                                d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625Z" />
                            <path
                                d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z" />
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

            {{-- Estado Tecnológico --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="chart-card animate-card">
                    <div class="chart-header">
                        <h5> Estado Tecnológico</h5>
                    </div>

                    @php
                    $total = array_sum($estadoTecnologico);
                    $cumulative = 0;
                    @endphp

                    <div class="chart-wrapper">
                        <div class="donut-chart-container">
                            <svg width="100%" height="100%" viewBox="0 0 42 42" class="donut-svg">
                                <circle class="donut-hole" cx="21" cy="21" r="15.91549430918954" fill="#fff"></circle>
                                <circle class="donut-ring" cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="#d2d3d4" stroke-width="5"></circle>

                                @foreach($estadoTecnologico as $estado => $count)
                                @php
                                $percent = $total > 0 ? round(($count / $total) * 100, 1) : 0;
                                // Actualizable -> Amarillo (#ffc107), Obsoleto -> Rojo (#fa0606ff)
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

                            <div class="donut-center" data-original-total="{{ $total }}" data-original-label="Total">
                                <span class="donut-total">{{ $total }}</span>
                                <small>Total</small>
                            </div>
                        </div>
                    </div>

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

                    <a href="{{ route('estado-tecnologico.pdf') }}" class="btn-download" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            style="width: 20px; height: 20px; color: white; margin-right: 8px; vertical-align: text-bottom;">
                            <path
                                d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625Z" />
                            <path
                                d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z" />
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

            {{-- Estado Gabinete --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="chart-card animate-card">
                    <div class="chart-header">
                        <h5> Estado Físico de Gabinetes</h5>
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
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            style="width: 20px; height: 20px; color: white; margin-right: 8px; vertical-align: text-bottom;">
                            <path
                                d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625Z" />
                            <path
                                d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z" />
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

        // Interacción Hover Donut (SVG)
        const segments = document.querySelectorAll('.donut-segment-svg');

        segments.forEach(segment => {
            segment.addEventListener('mouseenter', function() {
                // Visual Effect
                this.setAttribute('stroke-width', '6'); // Make slightly thicker

                const container = this.closest('.donut-chart-container');
                const center = container.querySelector('.donut-center');
                const totalSpan = center.querySelector('.donut-total');
                const labelSmall = center.querySelector('small');

                const percent = this.getAttribute('data-percent');
                const label = this.getAttribute('data-label');
                const color = this.style.getPropertyValue('--color').trim();

                totalSpan.textContent = percent + '%';
                totalSpan.style.color = color;
                labelSmall.textContent = label;
            });

            segment.addEventListener('mouseleave', function() {
                // revert visual effect
                this.setAttribute('stroke-width', '5');

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