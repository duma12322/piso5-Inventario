@extends('layouts.app')

@section('title', 'Editar Componente')

@section('content')
<!-- Carga del CSS específico para la vista de creación/edición de componentes -->
<link rel="stylesheet" href="{{ asset('css/createagregarcomponente.css') }}">

<!-- Fondo animado con formas flotantes -->
<div class="animated-background">
    <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
        <div class="shape shape-5"></div>
    </div>
</div>

<!-- Contenedor principal del formulario -->
<div class="component-form-container">

    <!-- Header del formulario -->
    <header class="form-header">
        <div class="header-content">
            <!-- Icono de edición -->
            <div class="header-icon-container">
                <i class="fas fa-edit header-icon"></i>
            </div>

            <!-- Texto del header: título y descripción -->
            <div class="header-text">
                <h1>Editar Componente</h1>
                <p>Actualizar especificaciones del hardware - ID: {{ $componente->id_componente }}</p>
            </div>
        </div>

        <!-- Botón de volver atrás -->
        <div class="header-actions">
            @if(!empty($porEquipo) && $porEquipo && !empty($id_equipo))
            <!-- Si el componente pertenece a un equipo específico -->
            <a href="{{ route('componentes.porEquipo', $id_equipo) }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            @else
            <!-- Si es listado general -->
            <a href="{{ route('componentes.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            @endif
        </div>
    </header>

    <!-- Muestra de errores de validación -->
    @if($errors->any())
    <div class="alert-container mb-4">
        @foreach($errors->all() as $error)
        <div class="alert alert-danger fade show" role="alert" style="border-radius: var(--radius-md);">
            <i class="fas fa-exclamation-circle me-2"></i> {{ $error }}
        </div>
        @endforeach
    </div>
    @endif

    <!-- Formulario principal para editar componente -->
    <form method="POST" action="{{ route('componentes.update', $componente->id_componente) }}" class="premium-form">
        @csrf
        @method('PUT') <!-- Método PUT para actualización -->

        <!-- Si la edición proviene de un equipo específico, se envían campos ocultos -->
        @if(isset($porEquipo) && $porEquipo)
        <input type="hidden" name="porEquipo" value="1">
        <input type="hidden" name="id_equipo" value="{{ $componente->id_equipo }}">
        @endif

        <!-- Paso del formulario: Configuración Base -->
        <div class="form-step active">
            <div class="step-header">
                <div class="step-icon">
                    <i class="fas fa-cog"></i>
                </div>
                <div class="step-title">
                    <h3>Configuración Base</h3>
                    <p>Revisa el equipo asociado y el tipo de hardware</p>
                </div>
            </div>

            <!-- Grid de formulario con campos principales -->
            <div class="form-grid">
                <!-- Selección de equipo asociado al componente -->
                <div class="form-group">
                    <label class="form-label"><i class="fas fa-laptop"></i> Equipo</label>
                    <select name="id_equipo" class="form-select" required>
                        <option value="">Seleccione un equipo</option>
                        @foreach ($equipos as $e)
                        <option value="{{ $e->id_equipo }}"
                            {{ $e->id_equipo == old('id_equipo', $componente->id_equipo) ? 'selected' : '' }}>
                            {{ $e->marca }} {{ $e->modelo }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Selección del tipo de componente -->
                <div class="form-group">
                    <label class="form-label"><i class="fas fa-tags"></i> Tipo de Componente</label>
                    <select id="tipo_componente" name="tipo_componente" class="form-select" required>
                        <option value="">Seleccione un tipo</option>
                        @foreach ($tiposComponentes as $tipo)
                        <option value="{{ $tipo }}"
                            {{ old('tipo_componente', $componente->tipo_componente) == $tipo ? 'selected' : '' }}>
                            {{ $tipo }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="component-sections">
                {{-- Tarjeta Madre --}}
                <div id="tarjeta_madre_campos" class="component-section" style="display:none;">
                    <div class="component-header">
                        <div class="component-icon"><i class="fas fa-microchip"></i></div>
                        <div class="component-title">
                            <h4>Tarjeta Madre</h4>
                            <p>Actualizar especificaciones de la placa base</p>
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-industry"></i> Marca / Fabricante</label>
                            <input type="text" name="marca_tarjeta_madre" class="form-input"
                                value="{{ old('marca_tarjeta_madre', $componente->marca_tarjeta_madre ?? $componente->marca) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-barcode"></i> Modelo</label>
                            <input type="text" name="modelo_tarjeta_madre" class="form-input"
                                value="{{ old('modelo_tarjeta_madre', $componente->modelo) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-plug"></i> Socket</label>
                            <input type="text" name="socket_tarjeta_madre" class="form-input"
                                value="{{ old('socket_tarjeta_madre', $componente->socket) }}">
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-memory"></i> Slots RAM</label>
                            <input type="text" name="cantidad_slot_memoria" class="form-input"
                                value="{{ old('cantidad_slot_memoria', $componente->cantidad_slot_memoria) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-tachometer-alt"></i> Memoria Máxima (GB)</label>
                            <input type="text" name="memoria_maxima" class="form-input"
                                value="{{ old('memoria_maxima', $componente->memoria_maxima) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-layer-group"></i> Tipo RAM</label>
                            <input type="text" name="tipo_tarjeta_madre" class="form-input"
                                value="{{ old('tipo_tarjeta_madre', $componente->tipo) }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-wave-square"></i> Frecuencias de Memoria</label>
                        <div class="checkbox-grid">
                            @php
                            $opcionesFrecuencias = [
                            'DDR' => [200, 266, 333, 400],
                            'DDR2' => [400, 533, 667, 800, 1066],
                            'DDR3' => [800, 1066, 1333, 1600, 1866, 2133, 2400],
                            'DDR4' => [2133, 2400, 2666, 2800, 2933, 3000, 3200, 3466, 3600, 3733, 4000, 4266],
                            'DDR5' => [4800, 5200, 5600, 6000, 6400, 6800, 7200, 7600, 8000, 8400]
                            ];
                            $seleccionadasFreq = isset($componente->frecuencias_memoria) ? array_map('trim', explode(',', $componente->frecuencias_memoria)) : [];
                            @endphp
                            @foreach($opcionesFrecuencias as $tipo => $frecs)
                            <div class="checkbox-group-wrapper">
                                <span class="group-label"><strong>{{ $tipo }}</strong></span>
                                <div class="checkbox-options">
                                    @foreach($frecs as $freq)
                                    <label class="checkbox-item">
                                        <input type="checkbox" name="frecuencias_memoria[]" value="{{ $freq }}" {{ in_array($freq, $seleccionadasFreq) ? 'checked' : '' }}>
                                        <span>{{ $freq }} MHz</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-expand-arrows-alt"></i> Ranuras de expansión</label>
                        <div class="checkbox-grid compact">
                            @php
                            $opcionesRanuras = ['ISA', 'AGP', 'PCI', 'PCI-X', 'AMR/CNR', 'PCIe x1', 'PCIe x2', 'PCIe x4', 'PCIe x8', 'PCIe x12', 'PCIe x16', 'PCIe x32', 'Mini PCIe', 'M.2 (Key M)', 'M.2 (Key E)', 'Thunderbolt header', 'OCP', 'CXL'];
                            $valRan = old('ranuras_expansion', $componente->ranuras_expansion ?? '');
                            $selRan = is_array($valRan) ? $valRan : array_map('trim', explode(',', $valRan));
                            @endphp
                            @foreach($opcionesRanuras as $ranura)
                            <label class="checkbox-item">
                                <input type="checkbox" name="ranuras_expansion[]" value="{{ $ranura }}" {{ in_array($ranura, $selRan) ? 'checked' : '' }}>
                                <span>{{ $ranura }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Puertos internos --}}
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-folder-open"></i> Puertos internos</label>
                        <div class="checkbox-grid compact">
                            @php
                            $puertosInternos = ['SATA', 'M.2', 'U.2', 'IDE (PATA)', 'USB 2.0 header', 'USB 3.0 header', 'Audio HD header', 'TPM header', 'Fan header (3/4 pines)', 'RGB/ARGB header', 'Paralelo (LPT)', 'Serial (COM)', 'FireWire (IEEE 1394)', 'Game/MIDI', 'Chassis Intrusion (Detector)', 'Thunderbolt header', 'Panel frontal (power/reset/LEDs)'];
                            $valInt = old('puertos_internos', $componente->puertos_internos ?? '');
                            $selInt = is_array($valInt) ? $valInt : array_map('trim', explode(',', $valInt));
                            @endphp
                            @foreach ($puertosInternos as $puerto)
                            <label class="checkbox-item">
                                <input type="checkbox" name="puertos_internos[]" value="{{ $puerto }}" {{ in_array($puerto, $selInt) ? 'checked' : '' }}>
                                <span>{{ $puerto }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">BIOS/UEFI</label>
                            <input type="text" name="bios_uefi" class="form-input"
                                value="{{ old('bios_uefi', $componente->bios_uefi) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Año</label>
                            <input type="number" name="fecha_instalacion" class="form-input"
                                value="{{ old('fecha_instalacion', $componente->fecha_instalacion) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Estado</label>
                            <select name="estado_tarjeta_madre" class="form-select">
                                @foreach (['Buen Funcionamiento', 'Operativo', 'Sin Funcionar'] as $est)
                                <option value="{{ $est }}" {{ old('estado_tarjeta_madre', $componente->estado_tarjeta_madre ?? $componente->estado) == $est ? 'selected' : '' }}>{{ $est }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- RAM --}}
                <div id="memoria_ram_campos" class="component-section" style="display:none;">
                    <div class="component-header">
                        <div class="component-icon"><i class="fas fa-memory"></i></div>
                        <div class="component-title">
                            <h4>Memoria RAM</h4>
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Marca</label>
                            <input type="text" name="marca_memoria" class="form-input"
                                value="{{ old('marca_memoria', $componente->marca_memoria ?? $componente->marca) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tipo</label>
                            <input type="text" name="tipo_ram" class="form-input"
                                value="{{ old('tipo_ram', $componente->tipo_ram ?? $componente->tipo) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Capacidad</label>
                            <input type="text" name="capacidad_ram" class="form-input"
                                value="{{ old('capacidad_ram', $componente->capacidad_ram ?? $componente->capacidad) }}">
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Slot</label>
                            <input type="text" name="slot_memoria" class="form-input"
                                value="{{ old('slot_memoria', $componente->slot_memoria) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Estado</label>
                            <select name="estado_memoria" class="form-select">
                                @foreach (['Buen Funcionamiento', 'Operativo', 'Sin Funcionar'] as $est)
                                <option value="{{ $est }}" {{ old('estado_memoria', $componente->estado_memoria ?? $componente->estado) == $est ? 'selected' : '' }}>{{ $est }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Procesador --}}
                <div id="procesador_campos" class="component-section" style="display:none;">
                    <div class="component-header">
                        <div class="component-icon"><i class="fas fa-brain"></i></div>
                        <div class="component-title">
                            <h4>Procesador</h4>
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Marca</label>
                            <input type="text" name="marca_procesador" class="form-input"
                                value="{{ old('marca_procesador', $componente->marca_procesador ?? $componente->marca) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Modelo</label>
                            <input type="text" name="modelo_procesador" class="form-input"
                                value="{{ old('modelo_procesador', $componente->modelo_procesador ?? $componente->modelo) }}">
                        </div>
                    </div>
                </div>

                {{-- Disco --}}
                <div id="disco_duro_campos" class="component-section" style="display:none;">
                    <div class="component-header">
                        <div class="component-icon"><i class="fas fa-hdd"></i></div>
                        <div class="component-title">
                            <h4>Disco Duro</h4>
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Marca</label>
                            <input type="text" name="marca_disco" class="form-input"
                                value="{{ old('marca_disco', $componente->marca_disco ?? $componente->marca) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tipo</label>
                            <select name="tipo_disco" class="form-select">
                                @foreach(['HDD', 'SSD', 'NVMe'] as $td)
                                <option value="{{ $td }}" {{ old('tipo_disco', $componente->tipo_disco ?? $componente->tipo) == $td ? 'selected' : '' }}>{{ $td }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Capacidad</label>
                            <input type="text" name="capacidad_disco" class="form-input"
                                value="{{ old('capacidad_disco', $componente->capacidad_disco ?? $componente->capacidad) }}">
                        </div>
                    </div>
                </div>

                {{-- Tarjeta Grafica --}}
                <div id="tarjeta_grafica_campos" class="component-section" style="display:none;">
                    <div class="component-header">
                        <div class="component-icon"><i class="fas fa-hdd"></i></div>
                        <div class="component-title">
                            <h4>Tarjeta Gráfica</h4>
                            <p>Especificaciones de video</p>
                        </div>
                    </div>

                    <div class="form-grid">
                        {{-- Marca --}}
                        <div class="form-group">
                            <label class="form-label">Marca</label>
                            <input type="text" name="marca_tarjeta_grafica" class="form-input"
                                value="{{ old('marca_tarjeta_grafica', $componente->marca_tarjeta_grafica ?? $componente->marca) }}">
                        </div>

                        {{-- Modelo --}}
                        <div class="form-group">
                            <label class="form-label">Modelo</label>
                            <input type="text" name="modelo_tarjeta_grafica" class="form-input"
                                value="{{ old('modelo_tarjeta_grafica', $componente->modelo_tarjeta_grafica ?? $componente->modelo) }}">
                        </div>

                        {{-- Capacidad --}}
                        <div class="form-group">
                            <label class="form-label">Capacidad</label>
                            <input type="text" name="capacidad_tarjeta_grafica" class="form-input"
                                value="{{ old('capacidad_tarjeta_grafica', $componente->capacidad_tarjeta_grafica ?? $componente->capacidad) }}">
                        </div>

                        {{-- Salidas de video --}}
                        <div class="form-group">
                            <label class="form-label">Salidas de video</label>
                            <div class="checkbox-grid compact">
                                @php
                                // Convertimos a array si viene como string desde la BD
                                $salidasGuardadas = $componente->salidas_video
                                ? array_map('trim', explode(',', $componente->salidas_video))
                                : [];

                                // Prioridad a old() si hubo error de validación
                                $salidasOld = old('salidas_video', $salidasGuardadas);
                                @endphp

                                @foreach (['VGA', 'HDMI', 'DVI', 'DisplayPort'] as $s)
                                <label class="checkbox-item">
                                    <input type="checkbox" name="salidas_video[]" value="{{ $s }}"
                                        {{ in_array($s, $salidasOld) ? 'checked' : '' }}>
                                    <span>{{ $s }}</span>
                                </label>
                                @endforeach

                            </div>
                        </div>

                        {{-- Estado --}}
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-thermometer-half"></i> Estado</label>
                            <select name="estado_tarjeta_grafica" class="form-select">
                                @php
                                $estadoOld = old('estado_tarjeta_grafica', $componente->estado_tarjeta_grafica ?? $componente->estado ?? '');
                                @endphp
                                @foreach(['Buen Funcionamiento', 'Operativo', 'Sin Funcionar'] as $estado)
                                <option value="{{ $estado }}" {{ $estadoOld == $estado ? 'selected' : '' }}>
                                    {{ $estado }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Detalles --}}
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-align-left"></i> Detalles</label>
                            <textarea name="detalles_tarjeta_grafica" class="form-textarea">{{ old('detalles_tarjeta_grafica', $componente->detalles_tarjeta_grafica ?? $componente->detalles ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Tarjeta Network --}}
                <div id="tarjeta_red_campos" class="component-section" style="display:none;">
                    <div class="component-header">
                        <div class="component-icon"><i class="fas fa-hdd"></i></div>
                        <div class="component-title">
                            <h4>Tarjeta de Red</h4>
                            <p>Especificaciones de conectividad</p>
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Marca</label>
                            <input type="text" name="marca_tarjeta_red" class="form-input"
                                value="{{ old('marca_tarjeta_red', $componente->marca_tarjeta_red ?? $componente->marca) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Modelo</label>
                            <input type="text" name="modelo_tarjeta_red" class="form-input"
                                value="{{ old('modelo_tarjeta_red', $componente->modelo_tarjeta_red ?? $componente->modelo) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Velocidad</label>
                            <input type="text" name="velocidad_transferencia" class="form-input"
                                value="{{ old('velocidad_transferencia', $componente->velocidad_transferencia ?? $componente->modelo) }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-wifi"></i> Tipo</label>
                        <div class="checkbox-grid compact">
                            @php
                            // Convertimos a array si viene como string de BD
                            $tiposGuardados = isset($componente->tipo) && $componente->tipo
                            ? array_map('trim', explode(',', $componente->tipo))
                            : [];

                            // Prioridad a old() si hubo error de validación
                            $tiposOld = old('tipo_tarjeta_red', $tiposGuardados);
                            @endphp

                            @foreach (['Ethernet (LAN)', 'Wi-Fi', 'Bluetooth'] as $t)
                            <label class="checkbox-item">
                                <input type="checkbox" name="tipo_tarjeta_red[]" value="{{ $t }}"
                                    {{ in_array($t, $tiposOld) ? 'checked' : '' }}>
                                <span>{{ $t }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-thermometer-half"></i> Estado</label>
                            <select name="estado_tarjeta_red" class="form-select">
                                @php
                                $estadoOld = old('estado_tarjeta_red', $componente->estado ?? '');
                                @endphp
                                @foreach(['Buen Funcionamiento', 'Operativo', 'Sin Funcionar'] as $estado)
                                <option value="{{ $estado }}" {{ $estadoOld == $estado ? 'selected' : '' }}>
                                    {{ $estado }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-align-left"></i> Detalles</label>
                        <textarea name="detalles_tarjeta_red" class="form-textarea">{{ old('detalles_tarjeta_red', $componente->detalles_tarjeta_red ?? $componente->detalles ?? '') }}</textarea>
                    </div>
                </div>

                {{-- Unidad Optica --}}
                <div id="unidad_optica_campos" class="component-section" style="display:none;">
                    <div class="component-header">
                        <div class="component-icon"><i class="fas fa-compact-disc"></i></div>
                        <div class="component-title">
                            <h4>Unidad Óptica</h4>
                            <p>Detalles de lectora/quemadora</p>
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Marca</label>
                            <input type="text" name="marca_unidad" class="form-input"
                                value="{{ old('marca_unidad', $componente->marca_unidad ?? $componente->marca) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-layer-group"></i> Tipo de Unidad</label>
                            <select name="tipo_unidad" class="form-select">
                                @php
                                $tipoOld = old('tipo_unidad', $componente->tipo_unidad ?? $componente->tipo ?? '');
                                @endphp
                                @foreach(['CD-ROM', 'CD-RW', 'DVD-ROM', 'DVD-RW', 'Blu-ray ROM', 'Blu-ray RW'] as $tipo)
                                <option value="{{ $tipo }}" {{ $tipoOld == $tipo ? 'selected' : '' }}>
                                    {{ $tipo }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-disc"></i> Discos Soportados</label>
                        <div class="checkbox-grid compact">
                            @php
                            // Convertimos a array si viene como string desde la BD
                            $discosGuardados = isset($componente->tipos_discos) && $componente->tipos_discos
                            ? array_map('trim', explode(',', $componente->tipos_discos))
                            : [];

                            // Prioridad a old() si hubo error de validación
                            $discosOld = old('tipos_discos', $discosGuardados);
                            @endphp

                            @foreach (['CD', 'DVD', 'Blu-ray'] as $d)
                            <label class="checkbox-item">
                                <input type="checkbox" name="tipos_discos[]" value="{{ $d }}"
                                    {{ in_array($d, $discosOld) ? 'checked' : '' }}>
                                <span>{{ $d }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-thermometer-half"></i> Estado</label>
                            <select name="estado_unidad" class="form-select">
                                @php
                                $estadoOld = old('estado_unidad', $componente->estado ?? '');
                                @endphp
                                @foreach(['Buen Funcionamiento', 'Operativo', 'Sin Funcionar'] as $estado)
                                <option value="{{ $estado }}" {{ $estadoOld == $estado ? 'selected' : '' }}>
                                    {{ $estado }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-align-left"></i> Detalles</label>
                        <textarea name="detalles_unidad" class="form-textarea">{{ old('detalles_unidad', $componente->detalles_unidad ?? $componente->detalles ?? '') }}</textarea>
                    </div>
                </div>

                {{-- Fan Cooler --}}
                <div id="fan_cooler_campos" class="component-section" style="display:none;">
                    <div class="component-header">
                        <div class="component-icon"><i class="fas fa-fan"></i></div>
                        <div class="component-title">
                            <h4>Fan Cooler</h4>
                            <p>Especificaciones de enfriamiento</p>
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Marca</label>
                            <input type="text" name="marca_fan" class="form-input"
                                value="{{ old('marca_fan', $componente->marca_fan ?? $componente->marca) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tipo</label>
                            <input type="text" name="tipo_fan" class="form-input"
                                value="{{ old('tipo_fan', $componente->tipo_fan ?? $componente->tipo) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Consumo (W)</label>
                            <input type="text" name="consumo_fan" class="form-input"
                                value="{{ old('consumo_fan', $componente->consumo_fan ?? $componente->consumo) }}">
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Ubicacion</label>
                            <input type="text" name="ubicacion" class="form-input"
                                value="{{ old('ubicacion', $componente->ubicacion ?? $componente->consumo) }}">
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label"><i class="fas fa-thermometer-half"></i> Estado</label>
                                <select name="estado_fan" class="form-select">
                                    @php
                                    $estadoOld = old('estado_fan', $componente->estado ?? '');
                                    @endphp
                                    @foreach(['Buen Funcionamiento', 'Operativo', 'Sin Funcionar'] as $estado)
                                    <option value="{{ $estado }}" {{ $estadoOld == $estado ? 'selected' : '' }}>
                                        {{ $estado }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-align-left"></i> Detalles</label>
                        <textarea name="detalles_fan" class="form-textarea">{{ old('detalles_fan', $componente->detalles_fan ?? $componente->detalles ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<!-- Sección de Blade para scripts y estilos que se insertará en el layout donde haya @yield('scripts') -->

<style>
    /* ===============================
       VARIABLES CSS GLOBALES
       =============================== */
    :root {
        --primary: #da0606;
        /* Color principal (rojo oscuro) */
        --primary-light: #ff4d4d;
        /* Color principal más claro */
        --gradient-primary: linear-gradient(135deg, #da0606 0%, #b70909 100%);
        /* Gradiente principal para botones */
    }

    /* ===============================
       BOTÓN DE ENVÍO
       =============================== */
    .btn-submit {
        width: 100%;
        /* Ocupa todo el ancho del contenedor */
        padding: 1.25rem;
        /* Espaciado interno amplio */
        background: var(--gradient-primary);
        /* Fondo con gradiente */
        color: white;
        /* Texto blanco */
        border: none;
        /* Sin borde */
        border-radius: var(--radius-lg);
        /* Bordes redondeados, usa variable global */
        font-size: 1.2rem;
        /* Tamaño de fuente */
        font-weight: 700;
        /* Negrita */
        cursor: pointer;
        /* Cursor tipo puntero */
        transition: var(--transition);
        /* Transición suave definida globalmente */
        box-shadow: var(--shadow-primary);
        /* Sombra inicial */
        display: flex;
        /* Flexbox para alinear iconos y texto */
        align-items: center;
        /* Centrado vertical */
        justify-content: center;
        /* Centrado horizontal */
        gap: 0.75rem;
        /* Espacio entre icono y texto */
    }

    .btn-submit:hover {
        transform: translateY(-3px);
        /* Efecto de "levantar" el botón */
        box-shadow: var(--shadow-xl);
        /* Sombra más pronunciada al pasar el mouse */
    }

    /* ===============================
       GRUPOS DE CHECKBOX
       =============================== */
    .checkbox-group-wrapper {
        margin-bottom: 1rem;
        /* Separación inferior */
        padding: 1rem;
        /* Espaciado interno */
        background: rgba(102, 126, 234, 0.05);
        /* Fondo azul muy suave */
        border-radius: var(--radius-md);
        /* Bordes ligeramente redondeados */
    }

    .group-label {
        color: var(--primary);
        /* Texto en color principal */
        font-size: 0.85rem;
        /* Tamaño de fuente reducido */
        text-transform: uppercase;
        /* Mayúsculas */
        display: block;
        /* Mostrar como bloque */
        margin-bottom: 0.5rem;
        /* Separación inferior */
    }

    .checkbox-options {
        display: grid;
        /* Grid para distribuir los checkboxes */
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        /* Columnas automáticas, mínimo 100px cada una */
        gap: 0.5rem;
        /* Espacio entre checkboxes */
    }

    .checkbox-item {
        display: flex;
        /* Flexbox para alinear checkbox y texto */
        align-items: center;
        /* Centrado vertical */
        gap: 0.5rem;
        /* Espacio entre input y label */
        font-size: 0.8rem;
        /* Fuente pequeña */
    }
</style>

<!-- ===============================
     SCRIPTS JS DEL FORMULARIO
     =============================== -->
<script src="{{ asset('js/componente1.js') }}"></script> <!-- Lógica general de componentes -->
<script src="{{ asset('js/unicos.js') }}"></script> <!-- Manejo de campos únicos o especiales -->
<script src="{{ asset('js/unidad.js') }}"></script> <!-- Funciones relacionadas a la Unidad Óptica -->
<script src="{{ asset('js/tipoRam.js') }}"></script> <!-- Manejo de tipos de memoria RAM -->

@endsection