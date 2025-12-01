@extends('layouts.app') {{-- Extiende tu layout principal --}}

@section('title', 'Agregar Componente')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
<link rel="stylesheet" href="{{ asset('css/componentes.create.css') }}">
@endsection


@section('content')
<div class="container mt-4">
    <h3>Agregar Componente</h3>
    <form method="POST" action="{{ route('componentes.store') }}">
        @csrf
        @if($porEquipo ?? false)
        <input type="hidden" name="porEquipo" value="1">
        <input type="hidden" name="id_equipo" value="{{ $equipoSeleccionado->id_equipo }}">
        @endif

        <!-- Selección del equipo -->
        <div class="form-grid">
            <div class="form-group">
                <label>Equipo</label>
                @if(isset($porEquipo) && $porEquipo)
                <input type="hidden" name="id_equipo" value="{{ old('id_equipo', $equipoSeleccionado->id_equipo) }}">
                <input type="text" class="form-control" value="{{ $equipoSeleccionado->marca }} {{ $equipoSeleccionado->modelo }}" readonly>
                @else
                <select id="id_equipo" name="id_equipo" class="form-control" required>
                    <option value="">Seleccione</option>
                    @foreach ($equipos as $e)
                    <option value="{{ $e->id_equipo }}"
                        {{ old('id_equipo', $equipoSeleccionado->id_equipo ?? '') == $e->id_equipo ? 'selected' : '' }}>
                        {{ $e->marca }} {{ $e->modelo }}
                    </option>
                    @endforeach
                </select>
                @endif
            </div>

            <!-- Tipo de componente -->
            <div class="form-group">
                <label>Tipo de Componente</label>
                <select id="tipo_componente" name="tipo_componente" class="form-control" required>
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

        {{-- Tarjeta Madre --}}
        <div id="tarjeta_madre_campos" style="display:none;">
            <div class="text-primary-wrapper">🔧 Tarjeta Madre</div>

            {{-- Marca / Fabricante --}}
            <div class="form-grid">
                <div class="form-group">
                    <label>Marca / Fabricante</label>
                    <input type="text" name="marca_tarjeta_madre" class="form-control" placeholder="Ej: Biostar, ASUS, Intel, Zotac, ASRock, MSI" value="{{ old('marca_tarjeta_madre') }}">
                </div>

                <div class="form-group">
                    <label>Modelo</label>
                    <input type="text" name="modelo_tarjeta_madre" class="form-control" placeholder="Ej. HP dc5800 SFF, B450M-A" value="{{ old('modelo_tarjeta_madre') }}">
                </div>

                <div class="form-group">
                    <label>Socket</label>
                    <input type="text" name="socket_tarjeta_madre" class="form-control" placeholder="Ej. LGA1700, AM5" value="{{ old('socket_tarjeta_madre') }}">
                </div>
            </div>

            <!-- Cantidad de ranuras -->
            <div class="form-grid">
                <div class="form-group">
                    <label>Cantidad de Slot RAM</label>
                    <input type="number" name="cantidad_slot_memoria" id="cantidad_slot_memoria" class="form-control" placeholder="Cantidad de Slot" value="{{ old('cantidad_slot_memoria') }}">
                </div>

                <!-- Memoria Máxima -->
                <div class="form-group">
                    <label for="memoria_maxima">Memoria Máxima (GB)</label>
                    <input type="text" name="memoria_maxima" class="form-control" min="1"
                        value="{{ old('memoria_maxima', $componente->memoria_maxima ?? '') }}">
                </div>

                <!-- Tipo de RAM -->
                <div class="form-group">
                    <label>Tipo RAM</label>
                    <input type="text" name="tipo_tarjeta_madre" class="form-control" placeholder="Ej. DDR3, DDR2" value="{{ old('tipo_tarjeta_madre') }}">
                </div>
            </div>
            <!-- Frecuencias de Memoria -->
            <div class="form-group">
                <label for="frecuencias_memoria">Frecuencias de Memoria (MHz)</label>
                <div class="checkbox-group">
                    <div class="form-check-container">
                        @php
                        $opcionesFrecuencias = [
                        'DDR' => [200, 266, 333, 400],
                        'DDR2' => [400, 533, 667, 800, 1066],
                        'DDR3' => [800, 1066, 1333, 1600, 1866, 2133, 2400],
                        'DDR4' => [2133, 2400, 2666, 2800, 2933, 3000, 3200, 3466, 3600, 3733, 4000, 4266],
                        'DDR5' => [4800, 5200, 5600, 6000, 6400, 6800, 7200, 7600, 8000, 8400]
                        ];

                        $seleccionadasFreq = isset($componente->frecuencias_memoria)
                        ? explode(',', $componente->frecuencias_memoria)
                        : [];
                        @endphp

                        @foreach($opcionesFrecuencias as $tipo => $frecs)
                        <div class="frecuencia-grupo" data-tipo="{{ $tipo }}">
                            <strong>{{ $tipo }}</strong>
                            <div class="form-check-container">
                                @foreach($frecs as $freq)
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="frecuencias_memoria[]" value="{{ $freq }}"
                                        {{ in_array($freq, $seleccionadasFreq) ? 'checked' : '' }}>
                                    {{ $freq }} MHz
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endforeach

                        <!-- Aquí mostramos el error si existe -->
                        @if($errors->has('frecuencias_memoria'))
                        <div class="alert alert-danger mt-2">
                            {{ $errors->first('frecuencias_memoria') }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Ranuras de expansión --}}
            <div class="form-group">
                <label>Ranuras de expansión</label>
                <div class="checkbox-group">
                    <div class="form-check-container">
                        @php
                        $opcionesRanuras = [
                        'ISA',
                        'AGP',
                        'PCI',
                        'PCI-X',
                        'AMR/CNR',
                        'PCIe x1',
                        'PCIe x2',
                        'PCIe x4',
                        'PCIe x8',
                        'PCIe x12',
                        'PCIe x16',
                        'PCIe x32',
                        'Mini PCIe',
                        'M.2 (Key M)',
                        'M.2 (Key E)',
                        'Thunderbolt header',
                        'OCP',
                        'CXL'
                        ];
                        $seleccionadas = old('ranuras_expansion', isset($componente) ? explode(',', $componente->ranuras_expansion) : []);
                        @endphp

                        @foreach($opcionesRanuras as $ranura)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="ranuras_expansion[]" value="{{ $ranura }}"
                                {{ in_array($ranura, $seleccionadas) ? 'checked' : '' }}>
                            <label class="form-check-label">{{ $ranura }}</label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Conectores de alimentación --}}
            <div class="form-group">
                <label>Conectores de alimentación</label>
                <div class="checkbox-group">
                    <div class="form-check-container">
                        @php
                        $conectores = [
                        'ATX 24 pines',
                        'ATX 20 pines',
                        'EPS 4 pines',
                        'EPS 8 pines',
                        'EPS 4+4 pines',
                        '6 pines PCIe',
                        '8 pines PCIe',
                        '6+2 pines PCIe',
                        '12VHPWR (PCIe 5.0)',
                        '4 pines Molex',
                        'SATA Power',
                        'Berg (Floppy)'
                        ];
                        $seleccionadosCon = old('conectores_alimentacion', isset($componente) ? explode(',', $componente->conectores_alimentacion) : []);
                        @endphp

                        @foreach ($conectores as $c)
                        <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" name="conectores_alimentacion[]" value="{{ $c }}"
                                {{ in_array($c, $seleccionadosCon) ? 'checked' : '' }}>
                            {{ $c }}
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Puertos internos --}}
            <div class="form-group">
                <label>Puertos internos E/S</label>
                <div class="checkbox-group">
                    <div class="form-check-container">
                        @php
                        $puertosInternos = [
                        'SATA',
                        'M.2',
                        'U.2',
                        'IDE (PATA)',
                        'USB 2.0 header',
                        'USB 3.0 header',
                        'Audio HD header',
                        'TPM header',
                        'Fan header (3/4 pines)',
                        'RGB/ARGB header',
                        'Paralelo (LPT)',
                        'Serial (COM)',
                        'FireWire (IEEE 1394)',
                        'Game/MIDI',
                        'Chassis Intrusion',
                        'Thunderbolt header',
                        'Audio frontal (AC’97/HD Audio)',
                        'Panel frontal (power/reset/LEDs)'
                        ];
                        $seleccionadosInt = old('puertos_internos', []);
                        @endphp
                        @foreach ($puertosInternos as $p)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="puertos_internos[]" value="{{ $p }}" {{ in_array($p, $seleccionadosInt) ? 'checked' : '' }}>
                            <label class="form-check-label">{{ $p }}</label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Puertos externos (Panel I/O) --}}
            <div class="form-group">
                <label>Puertos externos (Panel I/O)</label>
                <div class="checkbox-group">
                    <div class="form-check-container">
                        @php
                        $puertosExternos = [
                        'HDMI',
                        'DisplayPort',
                        'Mini DisplayPort',
                        'DVI',
                        'VGA',
                        'USB 2.0',
                        'USB 3.0/3.1',
                        'USB 3.2',
                        'USB-C',
                        'RJ-45 Ethernet',
                        'RJ-11',
                        'Jack 3.5 mm (Sonido)',
                        'Jack 3.5 mm (Audio)', 'S/PDIF', 'PS/2 (Teclado)',
                        'PS/2 (Mouse)', 'Thunderbolt 3/4', 'eSATA', 'FireWire', 'Puerto Serie', 'Puerto Paralelo'];
                        $seleccionadosExt = old('puertos_externos', []);
                        @endphp
                        @foreach ($puertosExternos as $p)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="puertos_externos[]" value="{{ $p }}" {{ in_array($p, $seleccionadosExt) ? 'checked' : '' }}>
                            <label class="form-check-label">{{ $p }}</label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- BIOS / UEFI --}}
            <div class="form-grid">
                <div class="form-group">
                    <label>BIOS / UEFI</label>
                    <input type="text" name="bios_uefi" class="form-control" placeholder="Ej: AMI UEFI" value="{{ old('bios_uefi') }}">
                </div>
                {{-- Año de instalación --}}
                <div class="form-group">
                    <label>Año de instalación</label>
                    <input type="number" name="fecha_instalacion" class="form-control" min="2000" max="{{ date('Y') }}" value="{{ old('fecha_instalacion') }}">
                </div>

                {{-- Estado --}}
                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado_tarjeta_madre" class="form-control">
                        @foreach(['Buen Funcionamiento','Operativo','Sin Funcionar'] as $estado)
                        <option value="{{ $estado }}" {{ old('estado_tarjeta_madre') == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            {{-- Detalles --}}
            <div class="form-group">
                <label>Detalles</label>
                <textarea name="detalles_tarjeta_madre" class="form-control" rows="5" placeholder="Información adicional del componente">{{ old('detalles_tarjeta_madre') }}</textarea>
            </div>
        </div>

        <!-- Memoria Ram -->
        <div id="memoria_ram_campos" style="display:none;">
            <div class="text-primary-wrapper">💾 Memoria RAM</div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Marca</label>
                    <input type="text" name="marca_memoria" class="form-control" placeholder="Ej: Corsair, Kingston, G. Skill, Crucial y ADATA"
                        value="{{ old('marca_memoria') }}">
                </div>

                <div class="form-group">
                    <label>Tipo</label>
                    <input type="text" name="tipo_ram" class="form-control"
                        placeholder="Ej: DDR4, DDR5"
                        value="{{ old('tipo_ram') }}">
                    @if($errors->has('tipo_ram'))
                    <div class="alert alert-danger mt-2">
                        {{ $errors->first('tipo_ram') }}
                    </div>
                    @endif
                </div>

                <div class="form-group">
                    <label>Capacidad</label>
                    <input type="text" name="capacidad_ram" class="form-control" placeholder="Ej: 8GB, 16GB"
                        value="{{ old('capacidad_ram') }}">
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Frecuencia</label>
                    <input type="text" name="frecuencia_ram" class="form-control" placeholder="Ej: 3200MHz"
                        value="{{ old('frecuencia_ram') }}">
                </div>

                <div class="form-group">
                    <label>Slot RAM</label>
                    <input type="text" name="slot_memoria" class="form-control" placeholder="En cuál Slot se ubica la RAM"
                        value="{{ old('slot_memoria') }}">

                    @if($errors->has('slot_memoria'))
                    <div class="alert alert-danger mt-2">
                        {{ $errors->first('slot_memoria') }}
                    </div>
                    @endif
                </div>

                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado_memoria" class="form-control">
                        <option value="Operativo" {{ old('estado_memoria') == 'Operativo' ? 'selected' : '' }}>Operativo</option>
                        <option value="Medio dañado" {{ old('estado_memoria') == 'Medio dañado' ? 'selected' : '' }}>Medio dañado</option>
                        <option value="Dañado" {{ old('estado_memoria') == 'Dañado' ? 'selected' : '' }}>Dañado</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Detalles</label>
                <textarea name="detalles_ram" class="form-control" rows="5" placeholder="Información adicional del componente">{{ old('detalles_ram') }}</textarea>
            </div>
        </div>

        <!-- Procesador -->
        <div id="procesador_campos" style="display:none;">
            <div class="text-primary-wrapper">🧠 Procesador</div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Marca</label>
                    <input type="text" name="marca_procesador" class="form-control" placeholder="Ej. Intel, AMD, Apple" value="{{ old('marca_procesador') }}">
                </div>

                <div class="form-group">
                    <label>Modelo</label>
                    <input type="text" name="modelo_procesador" class="form-control" placeholder="Ej. Core i7-13700K, Ryzen 5 7600, Apple M3 Max"
                        value="{{ old('modelo_procesador') }}">
                </div>

                <div class="form-group">
                    <label>Aquitectura</label>
                    <input type="text" name="arquitectura_procesador" class="form-control" placeholder="x64 o x86, ARM (32 o 64 bits)"
                        value="{{ old('arquitectura_procesador') }}">
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Núcleos</label>
                    <input type="number" name="nucleos" class="form-control" placeholder="Ej. 2, 4, 8"
                        value="{{ old('nucleos') }}">
                </div>

                <div class="form-group">
                    <label>Frecuencia (GHz)</label>
                    <input type="text" name="frecuencia_procesador" class="form-control" placeholder="Ej. 1.9, 3.6"
                        value="{{ old('frecuencia_procesador') }}">
                </div>

                <div class="form-group">
                    <label>Socket</label>
                    <input type="text" name="socket_procesador" class="form-control" placeholder="Ej. LGA1700, AM5"
                        value="{{ old('socket_procesador') }}">
                    @if($errors->has('socket'))
                    <div class="alert alert-danger">
                        {{ $errors->first('socket') }}
                    </div>
                    @endif
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Consumo eléctrico (W)</label>
                    <input type="text" name="consumo_procesador" class="form-control" placeholder="Ej. 65W, 125W,350W"
                        value="{{ old('consumo_procesador') }}">
                </div>
                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado_procesador" class="form-control">
                        <option value="Operativo" {{ old('estado_procesador') == 'Operativo' ? 'selected' : '' }}>Operativo</option>
                        <option value="Medio dañado" {{ old('estado_procesador') == 'Medio dañado' ? 'selected' : '' }}>Medio dañado</option>
                        <option value="Dañado" {{ old('estado_procesador') == 'Dañado' ? 'selected' : '' }}>Dañado</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Detalles</label>
                <textarea name="detalles_procesador" class="form-control" rows="5" placeholder="Información adicional del componente">{{ old('detalles_procesador') }}</textarea>
            </div>
        </div>

        <!-- Fuente de Poder -->
        <div id="fuente_poder_campos" style="display:none;">
            <div class="text-primary-wrapper">⚡ Fuente de Poder</div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Marca</label>
                    <input type="text" name="marca_fuente" class="form-control" placeholder="Ej: Corsair, Seasonic, Cooler Master, EVGA, MSI y Thermaltake">
                </div>

                <div class="form-group">
                    <label>Modelo</label>
                    <input type="text" name="modelo_fuente" class="form-control" placeholder="Ej: Corsair CV550, Cooler Master MWE 500, EVGA SuperNOVA 750 G6">
                </div>

                <div class="form-group">
                    <label>Potencia</label>
                    <input type="text" name="potencia" class="form-control" placeholder="Ej: 600W">
                </div>

                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado_fuente" class="form-control">
                        <option>Operativo</option>
                        <option>Medio dañado</option>
                        <option>Dañado</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Voltajes de salida</label>
                <div class="checkbox-group">
                    <div class="form-check-container">
                        @php
                        $opcionesVoltajes = [
                        '+12V' => 'voltaje_12v',
                        '+5V' => 'voltaje_5v',
                        '+3.3V' => 'voltaje_3_3v',
                        '-12V' => 'voltaje_neg12v',
                        '+5VSB' => 'voltaje_5vsb',
                        '19V DC' => 'voltaje_19v', // Laptops
                        '12V DC' => 'voltaje_12vmini', // MiniPC
                        '5V' => 'voltaje_5vmini', // MiniPC
                        '+1.8V' => 'voltaje_1_8v',
                        '+3.0V' => 'voltaje_3v',
                        '+1.2V' => 'voltaje_1_2v',
                        '+2.5V' => 'voltaje_2_5v',
                        '+24V' => 'voltaje_24v',
                        ];

                        // Voltajes previamente guardados (old input)
                        $voltajes = old('voltajes_fuente', $componente->voltajes_fuente ?? '');
                        $voltajesArray = $voltajes ? explode(',', $voltajes) : [];

                        // Separar "otro voltaje"
                        $voltajeOtro = '';
                        foreach ($voltajesArray as $key => $v) {
                        if (!array_key_exists($v, $opcionesVoltajes)) {
                        $voltajeOtro = $v;
                        unset($voltajesArray[$key]);
                        }
                        }
                        @endphp

                        @foreach($opcionesVoltajes as $v => $id)
                        <div class="form-check">
                            <input type="checkbox" name="voltajes_fuente[]" value="{{ $v }}" class="form-check-input" id="{{ $id }}" {{ in_array($v, $voltajesArray) ? 'checked' : '' }}>
                            <label class="form-check-label" for="{{ $id }}">
                                {{ $v }}
                                @if($v == '19V DC') (Laptop)@endif
                                @if($v == '12V DC' || $v == '5V') (MiniPC)@endif
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Detalles</label>
                <textarea name="detalles_fuente" class="form-control" rows="5" placeholder="Información adicional del componente"></textarea>
            </div>
        </div>

        <!-- Disco Duro -->
        <div id="disco_duro_campos" style="display:none;">
            <div class="text-primary-wrapper">💽 Disco Duro</div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Marca</label>
                    <input type="text" name="marca_disco" class="form-control" placeholder="Ej: Western Digital (WD), Seagate, Toshiba">
                </div>

                <div class="form-group">
                    <label>Tipo</label>
                    <select name="tipo_disco" class="form-control">
                        <option value="">Seleccione el tipo de disco</option>
                        <option value="HDD">HDD (Hard Disk Drive)</option>
                        <option value="SSD">SSD (Solid State Drive)</option>
                        <option value="SSHD">SSHD (Solid State Hybrid Drive)</option>
                        <option value="NVMe">NVMe (Non-Volatile Memory Express)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Capacidad</label>
                    <input type="text" name="capacidad_disco" class="form-control" placeholder="Ej: 1TB, 512GB">
                </div>

                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado_disco" class="form-control">
                        <option>Operativo</option>
                        <option>Medio dañado</option>
                        <option>Dañado</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Detalles</label>
                <textarea name="detalles_disco" class="form-control" rows="5" placeholder="Información adicional del componente"></textarea>
            </div>
        </div>

        <!-- Tarjeta Grafica Integarda -->
        <div id="tarjeta_grafica_campos" style="display:none;">
            <div class="text-primary-wrapper">🎮 Tarjeta Gráfica Integrada</div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Marca</label>
                    <input type="text" name="marca_tarjeta_grafica" class="form-control" placeholder="Ej: Intel UHD, AMD Radeon Vega, Apple GPU">
                </div>

                <div class="form-group">
                    <label>Modelo</label>
                    <input type="text" name="modelo_tarjeta_grafica" class="form-control" placeholder="Ej: Intel Iris Xe Graphics G7, AMD Radeon Vega 8">
                </div>

                <div class="form-group">
                    <label>Capacidad</label>
                    <input type="text" name="capacidad_tarjeta_grafica" class="form-control" placeholder="Ej: 256MB, 8GB, 16GB">
                </div>

                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado_tarjeta_grafica" class="form-control">
                        <option>Operativo</option>
                        <option>Medio dañado</option>
                        <option>Dañado</option>
                    </select>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Salidas de video</label>
                    <div class="checkbox-group">
                        <div class="form-check-container">
                            @php
                            $salidasSeleccionadas = old('salidas_video', $componente->salidas_video ?? '');
                            if (is_string($salidasSeleccionadas)) {
                            $salidasSeleccionadas = array_map('trim', explode(',', $salidasSeleccionadas));
                            }
                            @endphp

                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="salidas_video[]" value="VGA"
                                    {{ in_array('VGA', $salidasSeleccionadas ?? []) ? 'checked' : '' }}>
                                VGA
                            </label>

                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="salidas_video[]" value="HDMI"
                                    {{ in_array('HDMI', $salidasSeleccionadas ?? []) ? 'checked' : '' }}>
                                HDMI
                            </label>

                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="salidas_video[]" value="DVI"
                                    {{ in_array('DVI', $salidasSeleccionadas ?? []) ? 'checked' : '' }}>
                                DVI
                            </label>

                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="salidas_video[]" value="DisplayPort"
                                    {{ in_array('DisplayPort', $salidasSeleccionadas ?? []) ? 'checked' : '' }}>
                                DisplayPort
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Detalles</label>
                <textarea name="detalles_tarjeta_grafica" class="form-control" rows="5" placeholder="Información adicional del componente"></textarea>
            </div>
        </div>

        <!-- Tarjeta de Red -->
        <div id="tarjeta_red_campos" style="display:none;">
            <div class="text-primary-wrapper">🌐 Tarjeta de Red</div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Marca / Fabricante</label>
                    <input type="text" name="marca_tarjeta_red" class="form-control" placeholder="Ej: TP-Link, ASUS, Intel, Netgear, Cudy, StarTech">
                </div>

                <div class="form-group">
                    <label>Modelo</label>
                    <input type="text" name="modelo_tarjeta_red" class="form-control" placeholder="Ej: Intel I219-V, Realtek RTL8111H, Marvell AQtion AQC113C">
                </div>

                <div class="form-group">
                    <label>Velocidad de transferencia</label>
                    <input type="text" name="velocidad_transferencia" class="form-control" placeholder="Ej: 1Gbps, 100Mbps">
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Tipo</label>
                    <div class="checkbox-group">
                        <div class="form-check-container">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" name="tipo_tarjeta_red[]" value="Ethernet (LAN)">
                                    Ethernet (LAN)
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" name="tipo_tarjeta_red[]" value="Wi-Fi">
                                    Wi-Fi
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" name="tipo_tarjeta_red[]" value="Bluetooth">
                                    Bluetooth
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado_tarjeta_red" class="form-control">
                        <option>Operativo</option>
                        <option>Medio dañado</option>
                        <option>Dañado</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Detalles</label>
                <textarea name="detalles_tarjeta_red" class="form-control" rows="5" placeholder="Información adicional del componente"></textarea>
            </div>
        </div>

        <!-- Unidad Optica -->
        <div id="unidad_optica_campos" style="display:none;">
            <div class="text-primary-wrapper">📀 Unidad Óptica</div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Marca / Fabricante</label>
                    <input type="text" name="marca_unidad" class="form-control" placeholder="Ej: LG, ASUS, Pioneer, Lenovo" value="{{ old('marca_unidad', $componente->marca ?? '') }}">
                </div>

                <div class="form-group">
                    <label>Tipo de Unidad</label>
                    <select name="tipo_unidad" class="form-control">
                        @php
                        $tiposUnidad = ['CD-ROM','CD-RW','DVD-ROM','DVD-RW','Blu-ray ROM','Blu-ray RW'];
                        $selectedTipo = old('tipo_unidad', $componente->tipo ?? '');
                        @endphp
                        @foreach($tiposUnidad as $tipo)
                        <option value="{{ $tipo }}" @if($tipo==$selectedTipo) selected @endif>{{ $tipo }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Tipos de discos soportados</label>
                    <div class="checkbox-group">
                        <div class="form-check-container">
                            @php
                            $discos = ['CD','DVD','Blu-ray'];
                            $selectedDiscos = old('tipos_discos', isset($componente->tipos_discos) ? explode(',', $componente->tipos_discos) : []);
                            @endphp
                            @foreach($discos as $index => $d)
                            <div class="form-check">
                                <input type="checkbox"
                                    class="form-check-input"
                                    id="disco_{{ $index }}"
                                    name="tipos_discos[]"
                                    value="{{ $d }}"
                                    @if(in_array($d, $selectedDiscos)) checked @endif>
                                <label class="form-check-label" for="disco_{{ $index }}">{{ $d }}</label>
                            </div>
                            @endforeach

                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado_unidad" class="form-control">
                        <option value="Operativo" @if(old('estado_unidad', $componente->estado ?? '') == 'Operativo') selected @endif>Operativo</option>
                        <option value="Medio dañado" @if(old('estado_unidad', $componente->estado ?? '') == 'Medio dañado') selected @endif>Medio dañado</option>
                        <option value="Dañado" @if(old('estado_unidad', $componente->estado ?? '') == 'Dañado') selected @endif>Dañado</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Detalles</label>
                <textarea name="detalles_unidad" class="form-control" rows="5" placeholder="Información adicional del componente">{{ old('detalles_unidad', $componente->detalles ?? '') }}</textarea>
            </div>
        </div>

        <!-- Fan Cooler -->
        <div id="fan_cooler_campos" style="display:none;">
            <div class="text-primary-wrapper">🌀 Fan Cooler</div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Marca / Fabricante</label>
                    <input type="text" name="marca_fan" class="form-control" placeholder="Ej: LG, ASUS, Pioneer, Lenovo">
                </div>

                <div class="form-group">
                    <label>Tipo</label>
                    <input type="text" name="tipo_fan" class="form-control" placeholder="Ej: Aire, Líquido">
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Consumo eléctrico (W)</label>
                    <input type="text" name="consumo_fan" class="form-control" placeholder="Ej: 5W">
                </div>

                <div class="form-group">
                    <label>Ubicación</label>
                    <input type="text" name="ubicacion" class="form-control" placeholder="Ej. parte trasera del gabinete, sobre CPU, lateral izquierdo">
                </div>

                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado_fan" class="form-control">
                        <option>Operativo</option>
                        <option>Medio dañado</option>
                        <option>Dañado</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Detalles</label>
                <textarea name="detalles_fan" class="form-control" rows="5" placeholder="Información adicional del componente"></textarea>
            </div>
        </div>

        <div class="form-group mt-3 d-flex justify-content-start gap-2">
            @if(!empty($porEquipo) && $porEquipo && !empty($id_equipo))
            <a href="{{ route('componentes.porEquipo', $id_equipo) }}" class="btn btn-secondary mt-2">← Volver</a>
            @else
            <a href="{{ route('componentes.index') }}" class="btn btn-secondary mt-2">← Volver</a>
            @endif
            <button class="btn btn-primary mt-2">Guardar</button> <!-- o "Actualizar" según corresponda -->
        </div>
    </form>
</div>

@endsection

@section('scripts')
<script src="{{ asset('js/componente1.js') }}"></script>
<script src="{{ asset('js/unicos.js') }}"></script>
<script src="{{ asset('js/unidad.js') }}"></script>
<script src="{{ asset('js/tipoRam.js') }}"></script>
<script src="{{ asset('js/sidebar.js') }}"></script>
<script src="{{ asset('js/checkbox.js') }}"></script>
@endsection
