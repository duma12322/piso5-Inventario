@extends('layouts.app')

@section('title', 'Agregar Componente')

@section('content')
<link rel="stylesheet" href="{{ asset('css/createagregarcomponente.css') }}">

<div class="animated-background">
    <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
        <div class="shape shape-5"></div>
    </div>
</div>

<div class="component-form-container">
    <!-- Form Header -->
    <header class="form-header">
        <div class="header-content">
            <div class="header-icon-container">
                <i class="fas fa-microchip header-icon"></i>
            </div>
            <div class="header-text">
                <h1>Agregar Componente</h1>
                <p>Configura las especificaciones del nuevo hardware</p>
            </div>
        </div>
        <div class="header-actions">
            @if(!empty($porEquipo) && $porEquipo && !empty($id_equipo))
            <a href="{{ route('componentes.porEquipo', $id_equipo) }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            @else
            <a href="{{ route('componentes.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            @endif
        </div>
    </header>

    @if($errors->any())
    <div class="alert-container mb-4">
        @foreach($errors->all() as $error)
        <div class="alert alert-danger fade show" role="alert" style="border-radius: var(--radius-md); box-shadow: var(--shadow-sm);">
            <i class="fas fa-exclamation-circle me-2"></i> {{ $error }}
        </div>
        @endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('componentes.store') }}" class="premium-form">
        @csrf
        @if($porEquipo ?? false)
        <input type="hidden" name="porEquipo" value="1">
        <input
            type="hidden"
            id="id_equipo_hidden"
            name="id_equipo"
            value="{{ old('id_equipo', $equipoSeleccionado->id_equipo) }}"
            data-existentes='@json($componentesExistentes ?? [])'>

        @endif

        <div class="form-step active">
            <div class="step-header">
                <div class="step-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div class="step-title">
                    <h3>Información General</h3>
                    <p>Selecciona el equipo y tipo de componente</p>
                </div>
            </div>

            <div class="form-grid">
                <!-- Selección del equipo -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-laptop"></i> Equipo
                    </label>
                    @if(isset($porEquipo) && $porEquipo)
                    <div class="selected-equipo">
                        <div class="equipo-icon">
                            <i class="fas fa-desktop"></i>
                        </div>
                        <div class="equipo-info">
                            <span class="equipo-name">{{ $equipoSeleccionado->marca }} {{ $equipoSeleccionado->modelo }}</span>
                            <span class="equipo-status">Equipo Seleccionado</span>
                        </div>
                    </div>
                    <input type="hidden" name="id_equipo" value="{{ old('id_equipo', $equipoSeleccionado->id_equipo) }}">
                    @else
                    <select id="id_equipo" name="id_equipo" class="form-select" required>
                        <option value="">Seleccione un equipo</option>
                        @foreach ($equipos as $e)
                        <option value="{{ $e->id_equipo }}" {{ old('id_equipo', $equipoSeleccionado->id_equipo ?? '') == $e->id_equipo ? 'selected' : '' }}>
                            {{ $e->marca }} {{ $e->modelo }}
                        </option>
                        @endforeach
                    </select>
                    @endif
                </div>

                <!-- Tipo de componente -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-tags"></i> Tipo de Componente
                    </label>
                    <select id="tipo_componente" name="tipo_componente" class="form-select" required>
                        <option value="">Seleccione un tipo</option>
                        @foreach([
                        'Tarjeta Madre',
                        'Memoria RAM',
                        'Procesador',
                        'Fuente de Poder',
                        'Disco Duro',
                        'Tarjeta Grafica',
                        'Tarjeta Red',
                        'Unidad Optica',
                        'Fan Cooler'
                        ] as $tipo)
                        <option value="{{ $tipo }}" {{ old('tipo_componente') == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
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
                            <h4>Detalles de la Tarjeta Madre</h4>
                            <p>Especificaciones técnicas de la placa base</p>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-industry"></i> Marca / Fabricante</label>
                            <input type="text" name="marca_tarjeta_madre" class="form-input" placeholder="Ej: Biostar, ASUS, MSI" value="{{ old('marca_tarjeta_madre') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-barcode"></i> Modelo</label>
                            <input type="text" name="modelo_tarjeta_madre" class="form-input" placeholder="Ej. B450M-A" value="{{ old('modelo_tarjeta_madre') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-plug"></i> Socket</label>
                            <input type="text" name="socket_tarjeta_madre" class="form-input" placeholder="Ej. AM4, LGA1700" value="{{ old('socket_tarjeta_madre') }}">
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-memory"></i> Cantidad de Slots RAM</label>
                            <input type="number" name="cantidad_slot_memoria" class="form-input" placeholder="2 o 4" value="{{ old('cantidad_slot_memoria') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-microchip"></i> Tipo RAM</label>
                            <input type="text" name="tipo_tarjeta_madre" class="form-input" placeholder="DDR4, DDR5" value="{{ old('tipo_tarjeta_madre') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-tachometer-alt"></i> Memoria Máxima (GB)</label>
                            <input type="text" name="memoria_maxima" class="form-input" value="{{ old('memoria_maxima') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-wave-square"></i> Frecuencias de Memoria (MHz)</label>
                        <div class="checkbox-grid">
                            @php
                            $opcionesFrecuencias = [
                            'DDR' => [200, 266, 333, 400],
                            'DDR2' => [400, 533, 667, 800, 1066],
                            'DDR3' => [800, 1066, 1333, 1600, 1866, 2133, 2400],
                            'DDR4' => [2133, 2400, 2666, 2800, 2933, 3000, 3200, 3466, 3600, 3733, 4000, 4266],
                            'DDR5' => [4800, 5200, 5600, 6000, 6400, 6800, 7200, 7600, 8000, 8400]
                            ];
                            @endphp
                            @foreach($opcionesFrecuencias as $tipo => $frecs)
                            <div class="checkbox-group-wrapper">
                                <span class="group-label"><strong>{{ $tipo }}</strong></span>
                                <div class="checkbox-options">
                                    @foreach($frecs as $freq)
                                    <label class="checkbox-item">
                                        <input type="checkbox" name="frecuencias_memoria[]" value="{{ $freq }}" {{ is_array(old('frecuencias_memoria')) && in_array($freq, old('frecuencias_memoria')) ? 'checked' : '' }}>
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
                            @foreach (['ISA', 'AGP', 'PCI', 'PCI-X', 'AMR/CNR', 'PCIe x1', 'PCIe x2', 'PCIe x4', 'PCIe x8', 'PCIe x12', 'PCIe x16', 'PCIe x32', 'Mini PCIe', 'M.2 (Key M)', 'M.2 (Key E)', 'Thunderbolt header', 'OCP', 'CXL'] as $ranura)
                            <label class="checkbox-item">
                                <input type="checkbox" name="ranuras_expansion[]" value="{{ $ranura }}" {{ is_array(old('ranuras_expansion')) && in_array($ranura, old('ranuras_expansion')) ? 'checked' : '' }}>
                                <span>{{ $ranura }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Conectores de alimentación --}}
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-plug"></i> Conectores de alimentación</label>
                        <div class="checkbox-grid compact">
                            @foreach (['ATX 24 pines', 'ATX 20 pines', 'EPS 4 pines', 'EPS 8 pines', 'EPS 4+4 pines', '6 pines PCIe', '8 pines PCIe', '6+2 pines PCIe', '12VHPWR (PCIe 5.0)', '4 pines Molex', 'SATA Power', 'Berg (Floppy)'] as $c)
                            <label class="checkbox-item">
                                <input type="checkbox" name="conectores_alimentacion[]" value="{{ $c }}" {{ is_array(old('conectores_alimentacion')) && in_array($c, old('conectores_alimentacion')) ? 'checked' : '' }}>
                                <span>{{ $c }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Puertos internos --}}
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-folder-open"></i> Puertos internos</label>
                        <div class="checkbox-grid compact">
                            @foreach (['SATA', 'M.2', 'U.2', 'IDE (PATA)', 'PCIe x1/x4/x16', 'USB 2.0 header', 'USB 3.0 header', 'Audio HD header', 'TPM header', 'Fan header (3/4 pines)', 'RGB/ARGB header', 'Paralelo (LPT)', 'Serial (COM)', 'FireWire (IEEE 1394)', 'Game/MIDI', 'Chassis Intrusion', 'Thunderbolt header'] as $p)
                            <label class="checkbox-item">
                                <input type="checkbox" name="puertos_internos[]" value="{{ $p }}" {{ is_array(old('puertos_internos')) && in_array($p, old('puertos_internos')) ? 'checked' : '' }}>
                                <span>{{ $p }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Puertos externos --}}
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-external-link-alt"></i> Puertos externos (Panel I/O)</label>
                        <div class="checkbox-grid compact">
                            @foreach (['HDMI', 'DisplayPort', 'Mini DisplayPort', 'DVI', 'VGA', 'USB 2.0', 'USB 3.0/3.1 Gen1', 'USB 3.2 Gen2', 'USB-C', 'RJ-45 Ethernet', 'RJ-11', 'Jack 3.5 mm', 'S/PDIF', 'PS/2', 'Thunderbolt 3/4', 'eSATA', 'FireWire'] as $p)
                            <label class="checkbox-item">
                                <input type="checkbox" name="puertos_externos[]" value="{{ $p }}" {{ is_array(old('puertos_externos')) && in_array($p, old('puertos_externos')) ? 'checked' : '' }}>
                                <span>{{ $p }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-hdd"></i> BIOS / UEFI</label>
                            <input type="text" name="bios_uefi" class="form-input" placeholder="Ej: AMI UEFI" value="{{ old('bios_uefi') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-calendar-alt"></i> Año de instalación</label>
                            <input type="number" name="fecha_instalacion" class="form-input" min="2000" max="{{ date('Y') }}" value="{{ old('fecha_instalacion') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-thermometer-half"></i> Estado</label>
                            <select name="estado_tarjeta_madre" class="form-select">
                                @foreach(['Buen Funcionamiento', 'Operativo', 'Sin Funcionar'] as $estado)
                                <option value="{{ $estado }}" {{ old('estado_tarjeta_madre') == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-align-left"></i> Detalles Adicionales</label>
                        <textarea name="detalles_tarjeta_madre" class="form-textarea" placeholder="Información técnica relevante">{{ old('detalles_tarjeta_madre') }}</textarea>
                    </div>
                </div>

                {{-- Memoria RAM --}}
                <div id="memoria_ram_campos" class="component-section" style="display:none;">
                    <div class="component-header">
                        <div class="component-icon"><i class="fas fa-memory"></i></div>
                        <div class="component-title">
                            <h4>Memoria RAM</h4>
                            <p>Detalles del módulo de memoria</p>
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-industry"></i> Marca</label>
                            <input type="text" name="marca_memoria" class="form-input" placeholder="Ej: Corsair, Kingston" value="{{ old('marca_memoria') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-microchip"></i> Tipo</label>
                            <input type="text" name="tipo_ram" class="form-input" placeholder="Ej: DDR4, DDR5" value="{{ old('tipo_ram') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-list-ol"></i> Slot RAM</label>
                            <input type="text" name="slot_memoria" class="form-input" placeholder="Ej: Slot 1" value="{{ old('slot_memoria') }}">
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-database"></i> Capacidad</label>
                            <input type="text" name="capacidad_ram" class="form-input" placeholder="Ej: 8GB, 16GB" value="{{ old('capacidad_ram') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-tachometer-alt"></i> Frecuencia</label>
                            <input type="text" name="frecuencia_ram" class="form-input" placeholder="Ej: 3200MHz" value="{{ old('frecuencia_ram') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-thermometer-half"></i> Estado</label>
                            <select name="estado_memoria" class="form-select">
                                @foreach(['Buen Funcionamiento', 'Operativo', 'Sin Funcionar'] as $estado)
                                <option value="{{ $estado }}" {{ old('estado_memoria') == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-align-left"></i> Detalles</label>
                        <textarea name="detalles_ram" class="form-textarea" placeholder="Información adicional">{{ old('detalles_ram') }}</textarea>
                    </div>
                </div>

                {{-- Procesador --}}
                <div id="procesador_campos" class="component-section" style="display:none;">
                    <div class="component-header">
                        <div class="component-icon"><i class="fas fa-brain"></i></div>
                        <div class="component-title">
                            <h4>Procesador</h4>
                            <p>Especificaciones de la CPU</p>
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-industry"></i> Marca</label>
                            <input type="text" name="marca_procesador" class="form-input" placeholder="Ej. Intel, AMD" value="{{ old('marca_procesador') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-barcode"></i> Modelo</label>
                            <input type="text" name="modelo_procesador" class="form-input" placeholder="Ej. Core i7-13700K" value="{{ old('modelo_procesador') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-microchip"></i> Arquitectura</label>
                            <input type="text" name="arquitectura_procesador" class="form-input" placeholder="Ej. x64" value="{{ old('arquitectura_procesador') }}">
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-layer-group"></i> Núcleos</label>
                            <input type="number" name="nucleos" class="form-input" value="{{ old('nucleos') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-tachometer-alt"></i> Frecuencia (GHz)</label>
                            <input type="text" name="frecuencia_procesador" class="form-input" value="{{ old('frecuencia_procesador') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-plug"></i> Socket</label>
                            <input type="text" name="socket_procesador" class="form-input" value="{{ old('socket_procesador') }}">
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-bolt"></i> Consumo (W)</label>
                            <input type="text" name="consumo_procesador" class="form-input" value="{{ old('consumo_procesador') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-thermometer-half"></i> Estado</label>
                            <select name="estado_procesador" class="form-select">
                                @foreach(['Buen Funcionamiento', 'Operativo', 'Sin Funcionar'] as $estado)
                                <option value="{{ $estado }}" {{ old('estado_procesador') == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-align-left"></i> Detalles</label>
                        <textarea name="detalles_procesador" class="form-textarea">{{ old('detalles_procesador') }}</textarea>
                    </div>
                </div>

                {{-- Fuente de Poder --}}
                <div id="fuente_poder_campos" class="component-section" style="display:none;">
                    <div class="component-header">
                        <div class="component-icon"><i class="fas fa-bolt"></i></div>
                        <div class="component-title">
                            <h4>Fuente de Poder</h4>
                            <p>Especificaciones de alimentación</p>
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-industry"></i> Marca</label>
                            <input type="text" name="marca_fuente" class="form-input" value="{{ old('marca_fuente') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-barcode"></i> Modelo</label>
                            <input type="text" name="modelo_fuente" class="form-input" value="{{ old('modelo_fuente') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-bolt"></i> Potencia</label>
                            <input type="text" name="potencia" class="form-input" placeholder="600W" value="{{ old('potencia') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-plug"></i> Voltajes de salida</label>
                        <div class="checkbox-grid compact">
                            @foreach (['+12V', '+5V', '+3.3V', '-12V', '+5VSB', '19V DC', '12V DC', '5V', '+1.8V', '+3.0V', '+1.2V', '+2.5V', '+24V'] as $v)
                            <label class="checkbox-item">
                                <input type="checkbox" name="voltajes_fuente[]" value="{{ $v }}" {{ is_array(old('voltajes_fuente')) && in_array($v, old('voltajes_fuente')) ? 'checked' : '' }}>
                                <span>{{ $v }}</span>
                            </label>
                            @endforeach
                        </div>
                        <input type="text" name="voltaje_otro" class="form-input mt-2" placeholder="Otro voltaje" value="{{ old('voltaje_otro') }}">
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-thermometer-half"></i> Estado</label>
                            <select name="estado_fuente" class="form-select">
                                @foreach(['Buen Funcionamiento', 'Operativo', 'Sin Funcionar'] as $estado)
                                <option value="{{ $estado }}">{{ $estado }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-align-left"></i> Detalles</label>
                        <textarea name="detalles_fuente" class="form-textarea"></textarea>
                    </div>
                </div>

                {{-- Disco Duro --}}
                <div id="disco_duro_campos" class="component-section" style="display:none;">
                    <div class="component-header">
                        <div class="component-icon"><i class="fas fa-hdd"></i></div>
                        <div class="component-title">
                            <h4>Disco Duro / Almacenamiento</h4>
                            <p>Detalles de la unidad de almacenamiento</p>
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-industry"></i> Marca</label>
                            <input type="text" name="marca_disco" class="form-input" value="{{ old('marca_disco') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-database"></i> Tipo</label>
                            <select name="tipo_disco" class="form-select">
                                <option value="">Seleccione</option>
                                <option value="HDD">HDD</option>
                                <option value="SSD">SSD</option>
                                <option value="NVMe">NVMe</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-memory"></i> Capacidad</label>
                            <input type="text" name="capacidad_disco" class="form-input" placeholder="Ej: 1TB" value="{{ old('capacidad_disco') }}">
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-thermometer-half"></i> Estado</label>
                            <select name="estado_disco" class="form-select">
                                @foreach(['Buen Funcionamiento', 'Operativo', 'Sin Funcionar'] as $estado)
                                <option value="{{ $estado }}">{{ $estado }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-align-left"></i> Detalles</label>
                        <textarea name="detalles_disco" class="form-textarea"></textarea>
                    </div>
                </div>

                {{-- Tarjeta Grafica --}}
                <div id="tarjeta_grafica_campos" class="component-section" style="display:none;">
                    <div class="component-header">
                        <div class="component-icon"><i class="fas fa-video"></i></div>
                        <div class="component-title">
                            <h4>Tarjeta Gráfica</h4>
                            <p>Especificaciones de video</p>
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-industry"></i> Marca</label>
                            <input type="text" name="marca_tarjeta_grafica" class="form-input" value="{{ old('marca_tarjeta_grafica') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-barcode"></i> Modelo</label>
                            <input type="text" name="modelo_tarjeta_grafica" class="form-input" value="{{ old('modelo_tarjeta_grafica') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-database"></i> Capacidad</label>
                            <input type="text" name="capacidad_tarjeta_grafica" class="form-input" value="{{ old('capacidad_tarjeta_grafica') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-desktop"></i> Salidas de video</label>
                        <div class="checkbox-grid compact">
                            @foreach (['VGA', 'HDMI', 'DVI', 'DisplayPort'] as $s)
                            <label class="checkbox-item">
                                <input type="checkbox" name="salidas_video[]" value="{{ $s }}" {{ is_array(old('salidas_video')) && in_array($s, old('salidas_video')) ? 'checked' : '' }}>
                                <span>{{ $s }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-thermometer-half"></i> Estado</label>
                            <select name="estado_tarjeta_grafica" class="form-select">
                                @foreach(['Buen Funcionamiento', 'Operativo', 'Sin Funcionar'] as $estado)
                                <option value="{{ $estado }}">{{ $estado }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-align-left"></i> Detalles</label>
                        <textarea name="detalles_tarjeta_grafica" class="form-textarea"></textarea>
                    </div>
                </div>

                {{-- Tarjeta Network --}}
                <div id="tarjeta_red_campos" class="component-section" style="display:none;">
                    <div class="component-header">
                        <div class="component-icon"><i class="fas fa-network-wired"></i></div>
                        <div class="component-title">
                            <h4>Tarjeta de Red</h4>
                            <p>Especificaciones de conectividad</p>
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-industry"></i> Marca</label>
                            <input type="text" name="marca_tarjeta_red" class="form-input" value="{{ old('marca_tarjeta_red') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-barcode"></i> Modelo</label>
                            <input type="text" name="modelo_tarjeta_red" class="form-input" value="{{ old('modelo_tarjeta_red') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-tachometer-alt"></i> Velocidad</label>
                            <input type="text" name="velocidad_transferencia" class="form-input" value="{{ old('velocidad_transferencia') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-wifi"></i> Tipo</label>
                        <div class="checkbox-grid compact">
                            @foreach (['Ethernet (LAN)', 'Wi-Fi', 'Bluetooth'] as $t)
                            <label class="checkbox-item">
                                <input type="checkbox" name="tipo_tarjeta_red[]" value="{{ $t }}" {{ is_array(old('tipo_tarjeta_red')) && in_array($t, old('tipo_tarjeta_red')) ? 'checked' : '' }}>
                                <span>{{ $t }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-thermometer-half"></i> Estado</label>
                            <select name="estado_tarjeta_red" class="form-select">
                                @foreach(['Buen Funcionamiento', 'Operativo', 'Sin Funcionar'] as $estado)
                                <option value="{{ $estado }}">{{ $estado }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-align-left"></i> Detalles</label>
                        <textarea name="detalles_tarjeta_red" class="form-textarea"></textarea>
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
                            <label class="form-label"><i class="fas fa-industry"></i> Marca</label>
                            <input type="text" name="marca_unidad" class="form-input" value="{{ old('marca_unidad') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-layer-group"></i> Tipo de Unidad</label>
                            <select name="tipo_unidad" class="form-select">
                                @foreach(['CD-ROM', 'CD-RW', 'DVD-ROM', 'DVD-RW', 'Blu-ray ROM', 'Blu-ray RW'] as $tipo)
                                <option value="{{ $tipo }}">{{ $tipo }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-disc"></i> Discos Soportados</label>
                        <div class="checkbox-grid compact">
                            @foreach (['CD', 'DVD', 'Blu-ray'] as $d)
                            <label class="checkbox-item">
                                <input type="checkbox" name="tipos_discos[]" value="{{ $d }}" {{ is_array(old('tipos_discos')) && in_array($d, old('tipos_discos')) ? 'checked' : '' }}>
                                <span>{{ $d }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-thermometer-half"></i> Estado</label>
                            <select name="estado_unidad" class="form-select">
                                @foreach(['Buen Funcionamiento', 'Operativo', 'Sin Funcionar'] as $estado)
                                <option value="{{ $estado }}">{{ $estado }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-align-left"></i> Detalles</label>
                        <textarea name="detalles_unidad" class="form-textarea"></textarea>
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
                            <label class="form-label"><i class="fas fa-industry"></i> Marca</label>
                            <input type="text" name="marca_fan" class="form-input" value="{{ old('marca_fan') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-wind"></i> Tipo</label>
                            <input type="text" name="tipo_fan" class="form-input" placeholder="Aire, Líquido" value="{{ old('tipo_fan') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-bolt"></i> Consumo (W)</label>
                            <input type="text" name="consumo_fan" class="form-input" value="{{ old('consumo_fan') }}">
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-map-marker-alt"></i> Ubicación</label>
                            <input type="text" name="ubicacion" class="form-input" value="{{ old('ubicacion') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-thermometer-half"></i> Estado</label>
                            <select name="estado_fan" class="form-select">
                                @foreach(['Buen Funcionamiento', 'Operativo', 'Sin Funcionar'] as $estado)
                                <option value="{{ $estado }}">{{ $estado }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-align-left"></i> Detalles</label>
                        <textarea name="detalles_fan" class="form-textarea"></textarea>
                    </div>
                </div>
            </div>

            <div class="form-actions mt-4">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Guardar Componente
                </button>
            </div>
        </div>
    </form>
</div>

@endsection

@section('scripts')
<style>
    :root {
        --primary: #da0606;
        --primary-light: #ff4d4d;
        --gradient-primary: linear-gradient(135deg, #da0606 0%, #b70909 100%);
    }

    .premium-form {
        margin-top: 1rem;
    }

    .btn-submit {
        width: 100%;
        padding: 1.25rem;
        background: var(--gradient-primary);
        color: white;
        border: none;
        border-radius: var(--radius-lg);
        font-size: 1.2rem;
        font-weight: 700;
        cursor: pointer;
        transition: var(--transition);
        box-shadow: var(--shadow-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
    }

    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-xl);
        filter: brightness(1.1);
    }

    .checkbox-group-wrapper {
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: rgba(102, 126, 234, 0.03);
        border-radius: var(--radius-md);
        border: 1px solid rgba(102, 126, 234, 0.1);
    }

    .group-label {
        display: block;
        margin-bottom: 1rem;
        color: var(--primary);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .checkbox-options {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
        gap: 0.75rem;
    }

    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.85rem;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: var(--radius-sm);
        transition: var(--transition);
        background: white;
        border: 1px solid var(--gray-200);
    }

    .checkbox-item:hover {
        background: rgba(102, 126, 234, 0.05);
        border-color: var(--primary);
    }

    .checkbox-item input {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .alert-container {
        animation: fadeIn 0.5s ease-out;
    }

    @media (max-width: 768px) {
        .form-header {
            height: auto;
            flex-direction: column;
            padding: 1.5rem;
            text-align: center;
        }

        .header-content {
            flex-direction: column;
        }

        .header-icon-container {
            width: 60px;
            height: 60px;
        }

        .header-icon {
            font-size: 2.5rem;
        }

        .header-text h1 {
            font-size: 1.8rem;
        }
    }
</style>
<script src="{{ asset('js/componente1.js') }}"></script>
<script src="{{ asset('js/unicos.js') }}"></script>
<script src="{{ asset('js/unidad.js') }}"></script>
<script src="{{ asset('js/tipoRam.js') }}"></script>
@endsection