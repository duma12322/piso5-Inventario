@extends('layouts.app')

@section('title', 'Editar Componente')

@section('content')
<div class="container mt-4">
    <h3>Editar Componente</h3>

    <form method="POST" action="{{ route('componentes.update', $componente->id_componente) }}">
        @csrf
        @method('PUT')
        @if(isset($porEquipo) && $porEquipo)
        <input type="hidden" name="porEquipo" value="1">
        <input type="hidden" name="id_equipo" value="{{ $componente->id_equipo }}">
        @endif

        <input type="hidden" name="id_componente" value="{{ $componente->id_componente }}">
        <!-- EQUIPO -->
        <div class="form-grid">
            <div class="form-group">
                <label>Equipo</label>
                <select name="id_equipo" class="form-control" required>
                    <option value="">Seleccione un equipo</option>
                    @foreach ($equipos as $e)
                    <option value="{{ $e->id_equipo }}" {{ $e->id_equipo == old('id_equipo', $componente->id_equipo) ? 'selected' : '' }}>
                        {{ $e->marca }} {{ $e->modelo }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- TIPO DE COMPONENTE -->
            <div class="form-group">
                <label>Tipo de Componente</label>
                <select id="tipo_componente" name="tipo_componente" class="form-control" required>
                    <option value="">Seleccione un tipo</option>
                    @foreach ($tiposComponentes as $tipo)
                    <option value="{{ $tipo }}"
                        @if(in_array($tipo, $componentesExistentes) && in_array($tipo, $componentesUnicos)) disabled @endif
                        {{ old('tipo_componente', $componente->tipo_componente) == $tipo ? 'selected' : '' }}>
                        {{ $tipo }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- =================== Tarjeta Madre =================== --}}
        <div id="tarjeta_madre_campos" style="display:none;">
            <div class="text-primary-wrapper">🔧 Tarjeta Madre</div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Marca / Fabricante</label>
                    <input type="text" name="marca_tarjeta_madre" class="form-control"
                        value="{{ old('marca_tarjeta_madre', $componente->marca_tarjeta_madre ?? $componente->marca) }}">
                </div>
                <div class="form-group">
                    <label>Modelo</label>
                    <input type="text" name="modelo_tarjeta_madre" class="form-control"
                        value="{{ old('modelo_tarjeta_madre', $componente->modelo) }}">
                </div>
                <div class="form-group">
                    <label>Socket</label>
                    <input type="text" name="socket_tarjeta_madre" class="form-control"
                        value="{{ old('socket_tarjeta_madre', $componente->socket) }}">
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Cantidad de Slot RAM</label>
                    <input type="text" name="cantidad_slot_memoria" class="form-control"
                        value="{{ old('cantidad_slot_memoria', $componente->cantidad_slot_memoria) }}">
                </div>

                <div class="form-group">
                    <label for="memoria_maxima">Memoria Máxima (GB)</label>
                    <input type="text" name="memoria_maxima" class="form-control" min="1"
                        value="{{ old('memoria_maxima', $componente->memoria_maxima ?? '') }}">
                </div>

                <div class="form-group">
                    <label>Tipo RAM</label>
                    <input type="text" name="tipo_tarjeta_madre" class="form-control"
                        value="{{ old('tipo_tarjeta_madre', $componente->tipo) }}">
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
                        ? array_map('trim', explode(',', $componente->frecuencias_memoria))
                        : [];
                        $tipoTarjeta = old('tipo_tarjeta_madre', $componente->tipo_tarjeta_madre ?? '');
                        @endphp
                        @foreach($opcionesFrecuencias as $tipo => $frecs)
                        <div class="frecuencia-grupo" data-tipo="{{ $tipo }}">
                            <strong>{{ $tipo }}</strong><br>
                            <div class="form-check-container">
                                @foreach($frecs as $freq)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="frecuencias_memoria[]" value="{{ $freq }}"
                                        {{ in_array($freq, $seleccionadasFreq) ? 'checked' : '' }}>
                                    <label class="form-check-label">{{ $freq }} MHz</label>
                                </div>
                                @endforeach
                            </div>

                            @if($errors->has('frecuencias_memoria'))
                            <div class="alert alert-danger mt-2">
                                {{ $errors->first('frecuencias_memoria') }}
                            </div>
                            @endif
                        </div>
                        @endforeach
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

                        $valorOld = old('ranuras_expansion', $componente->ranuras_expansion ?? '');
                        if (is_array($valorOld)) {
                        $seleccionadas = $valorOld;
                        } elseif (!empty($valorOld)) {
                        $seleccionadas = array_map('trim', explode(',', $valorOld)); // <- trim aquí
                            } else {
                            $seleccionadas=[];
                            }
                            @endphp

                            @foreach($opcionesRanuras as $index=> $ranura)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="ranuras_expansion[]" id="ranura_{{ $index }}" value="{{ $ranura }}"
                                    {{ in_array($ranura, $seleccionadas) ? 'checked' : '' }}>
                                <label class="form-check-label" for="ranura_{{ $index }}">{{ $ranura }}</label>
                            </div>
                            @endforeach
                    </div>
                </div>
            </div>

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
                        'PCIe x1/x4/x16',
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
                        'Thunderbolt header'
                        ];
                        $valorAnterior = old('puertos_internos', $componente->puertos_internos ?? '');
                        if (is_array($valorAnterior)) {
                        $seleccionadosInternos = $valorAnterior;
                        } else {
                        $seleccionadosInternos = array_map('trim', explode(',', $valorAnterior));
                        }
                        @endphp
                        @foreach ($puertosInternos as $puerto)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="puertos_internos[]" value="{{ $puerto }}"
                                {{ in_array($puerto, $seleccionadosInternos) ? 'checked' : '' }}>
                            <label class="form-check-label">{{ $puerto }}</label>
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
                        $puertosExternos = ['HDMI', 'DisplayPort', 'Mini DisplayPort', 'DVI', 'VGA', 'USB 2.0', 'USB 3.0/3.1 Gen1', 'USB 3.2 Gen2', 'USB-C', 'RJ-45 Ethernet', 'RJ-11', 'Jack 3.5 mm', 'S/PDIF', 'PS/2', 'Thunderbolt 3/4', 'eSATA', 'FireWire'];

                        $valorExt = old('puertos_externos', $componente->puertos_externos ?? '');
                        $seleccionadosExt = is_array($valorExt) ? $valorExt : explode(',', $valorExt);
                        // quitar espacios extra
                        $seleccionadosExt = array_map('trim', $seleccionadosExt);
                        @endphp

                        @foreach ($puertosExternos as $p)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="puertos_externos[]" value="{{ $p }}"
                                {{ in_array($p, $seleccionadosExt) ? 'checked' : '' }}>
                            <label class="form-check-label">{{ $p }}</label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

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

                        $valorCon = old('conectores_alimentacion', $componente->conectores_alimentacion ?? '');
                        $seleccionadosCon = is_array($valorCon) ? $valorCon : explode(',', $valorCon);
                        // quitar espacios extra
                        $seleccionadosCon = array_map('trim', $seleccionadosCon);

                        $otrosConectores = [];
                        foreach ($seleccionadosCon as $k => $v) {
                        if (!in_array($v, $conectores)) {
                        $otrosConectores[] = $v;
                        unset($seleccionadosCon[$k]);
                        }
                        }
                        $otrosConectoresTexto = implode(', ', $otrosConectores);
                        @endphp

                        @foreach ($conectores as $con)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="conectores_alimentacion[]" value="{{ $con }}"
                                {{ in_array($con, $seleccionadosCon) ? 'checked' : '' }}>
                            <label class="form-check-label">{{ $con }}</label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>BIOS/UEFI</label>
                    <input type="text" name="bios_uefi" class="form-control" value="{{ old('bios_uefi', $componente->bios_uefi) }}">
                </div>

                <div class="form-group">
                    <label>Año de instalación</label>
                    <input type="number" name="fecha_instalacion" class="form-control" min="2000" max="{{ date('Y') }}"
                        value="{{ old('fecha_instalacion', $componente->fecha_instalacion) }}">
                </div>

                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado_tarjeta_madre" class="form-control">
                        @php $estados = ['Buen Funcionamiento','Operativo','Sin Funcionar']; @endphp
                        @foreach ($estados as $estado)
                        <option value="{{ $estado }}" {{ old('estado_tarjeta_madre', $componente->estado_tarjeta_madre ?? $componente->estado) == $estado ? 'selected' : '' }}>
                            {{ $estado }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Detalles</label>
                <textarea name="detalles_tarjeta_madre" class="form-control" rows="5" placeholder="Información adicional del componente">{{ old('detalles_tarjeta_madre', $componente->detalles_tarjeta_madre ?? $componente->detalles ?? '') }}</textarea>
            </div>
        </div>

        {{-- =================== Memoria RAM =================== --}}
        <div id="memoria_ram_campos" style="display:none;">
            <div class="text-primary-wrapper">💾 Memoria RAM</div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Marca</label>
                    <input type="text" name="marca_memoria" class="form-control"
                        value="{{ old('marca_memoria', $componente->marca_memoria ?? $componente->marca) }}">
                </div>

                <div class="form-group">
                    <label>Tipo</label>
                    <input type="text" name="tipo_ram" class="form-control"
                        value="{{ old('tipo_ram', $componente->tipo_ram ?? $componente->tipo) }}">
                    @if($errors->has('tipo'))
                    <div class="alert alert-danger mt-2">
                        {{ $errors->first('tipo') }}
                    </div>
                    @endif
                </div>

                <div class="form-group">
                    <label>Capacidad</label>
                    <input type="text" name="capacidad_ram" class="form-control"
                        value="{{ old('capacidad_ram', $componente->capacidad_ram ?? $componente->capacidad) }}">
                    @if($errors->has('capacidad'))
                    <div class="alert alert-danger mt-2">
                        {{ $errors->first('capacidad') }}
                    </div>
                    @endif
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Slot RAM</label>
                    <input type="text" name="slot_memoria" class="form-control"
                        value="{{ old('slot_memoria', $componente->slot_memoria) }}">
                    @if($errors->has('slot_memoria'))
                    <div class="alert alert-danger mt-2">
                        {{ $errors->first('slot_memoria') }}
                    </div>
                    @endif
                </div>

                <div class="form-group">
                    <label>Frecuencia</label>
                    <input type="text" name="frecuencia_ram" class="form-control"
                        value="{{ old('frecuencia_ram', $componente->frecuencia_ram ?? $componente->frecuencia) }}">
                    @if($errors->has('frecuencia'))
                    <div class="alert alert-danger mt-2">
                        {{ $errors->first('frecuencia') }}
                    </div>
                    @endif
                </div>

                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado_memoria" class="form-control">
                        @foreach ($estados as $estado)
                        <option value="{{ $estado }}" {{ old('estado_memoria', $componente->estado_memoria ?? $componente->estado) == $estado ? 'selected' : '' }}>
                            {{ $estado }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Detalles</label>
                <textarea name="detalles_ram" class="form-control" rows="5" placeholder="Información adicional del componente">{{ old('detalles_ram', $componente->detalles_ram ?? $componente->detalles ?? '') }}</textarea>
            </div>
        </div>

        {{-- =================== Procesador =================== --}}
        <div id="procesador_campos" style="display:none;">
            <div class="text-primary-wrapper">🧠 Procesador</div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Marca</label>
                    <input type="text" name="marca_procesador" class="form-control"
                        value="{{ old('marca_procesador', $componente->marca_procesador ?? $componente->marca) }}">
                </div>

                <div class="form-group">
                    <label>Modelo</label>
                    <input type="text" name="modelo_procesador" class="form-control"
                        value="{{ old('modelo_procesador', $componente->modelo_procesador ?? $componente->modelo) }}">
                </div>

                <div class="form-group">
                    <label>Arquitectura</label>
                    <input type="text" name="arquitectura_procesador" class="form-control"
                        placeholder="x64 o x86, ARM (32 o 64 bits)"
                        value="{{ old('arquitectura_procesador', $componente->arquitectura ?? '') }}">
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Núcleos</label>
                    <input type="number" name="nucleos" class="form-control"
                        value="{{ old('nucleos', $componente->nucleos) }}">
                </div>

                <div class="form-group">
                    <label>Frecuencia (GHz)</label>
                    <input type="text" name="frecuencia_procesador" class="form-control"
                        value="{{ old('frecuencia_procesador', $componente->frecuencia_procesador ?? $componente->frecuencia) }}">
                </div>

                <div class="form-group">
                    <label>Socket</label>
                    <input type="text" name="socket_procesador" class="form-control"
                        value="{{ old('socket_procesador', $componente->socket_procesador ?? $componente->socket) }}">
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
                    <input type="text" name="consumo_procesador" class="form-control"
                        value="{{ old('consumo_procesador', $componente->consumo_procesador ?? $componente->consumo) }}">
                </div>
                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado_procesador" class="form-control">
                        @foreach ($estados as $estado)
                        <option value="{{ $estado }}" {{ old('estado_procesador', $componente->estado_procesador ?? $componente->estado) == $estado ? 'selected' : '' }}>
                            {{ $estado }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Detalles</label>
                <textarea name="detalles_procesador" class="form-control" rows="5" placeholder="Información adicional del componente">{{ old('detalles_procesador', $componente->detalles_procesador ?? $componente->detalles ?? '') }}</textarea>
            </div>
        </div>

        {{-- =================== Fuente de Poder =================== --}}
        <div id="fuente_poder_campos" style="display:none;">
            <div class="text-primary-wrapper">⚡ Fuente de Poder</div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Marca</label>
                    <input type="text" name="marca_fuente" class="form-control"
                        value="{{ old('marca_fuente', $componente->marca_fuente ?? $componente->marca) }}">
                </div>

                <div class="form-group">
                    <label>Modelo</label>
                    <input type="text" name="modelo_fuente" class="form-control"
                        value="{{ old('modelo_fuente', $componente->modelo_fuente ?? $componente->modelo) }}">
                </div>

                <div class="form-group">
                    <label>Potencia</label>
                    <input type="text" name="potencia" class="form-control"
                        value="{{ old('potencia', $componente->potencia) }}">
                </div>

                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado_fuente" class="form-control">
                        @foreach ($estados as $estado)
                        <option value="{{ $estado }}" {{ old('estado_fuente', $componente->estado_fuente ?? $componente->estado) == $estado ? 'selected' : '' }}>
                            {{ $estado }}
                        </option>
                        @endforeach
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
                        '19V DC' => 'voltaje_19v',
                        '12V DC' => 'voltaje_12vmini',
                        '5V' => 'voltaje_5vmini',
                        '+1.8V' => 'voltaje_1_8v',
                        '+3.0V' => 'voltaje_3v',
                        '+1.2V' => 'voltaje_1_2v',
                        '+2.5V' => 'voltaje_2_5v',
                        '+24V' => 'voltaje_24v',
                        ];

                        // Obtener voltajes guardados o old input
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
                            <label class="form-check-label" for="{{ $id }}">{{ $v }}@if($v == '19V DC') (Laptops)@endif</label>
                        </div>
                        @endforeach

                        <div class="form-group mt-2">
                            <input type="text" name="voltaje_otro" class="form-control" placeholder="Otro voltaje" value="{{ old('voltaje_otro', $voltajeOtro) }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Detalles</label>
                <textarea name="detalles_fuente" class="form-control" rows="5" placeholder="Información adicional del componente">{{ old('detalles_fuente', $componente->detalles_fuente ?? $componente->detalles ?? '') }}</textarea>
            </div>
        </div>

        {{-- =================== Disco Duro =================== --}}
        <div id="disco_duro_campos" style="display:none;">
            <div class="text-primary-wrapper">💽 Disco Duro</div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Marca</label>
                    <input type="text" name="marca_disco" class="form-control"
                        value="{{ old('marca_disco', $componente->marca_disco ?? $componente->marca) }}">
                </div>

                <div class="form-group">
                    <label>Tipo</label>
                    <select name="tipo_disco" class="form-control">
                        <option value="">Seleccione el tipo de disco</option>
                        <option value="HDD" {{ old('tipo_disco', $componente->tipo_disco ?? $componente->tipo) == 'HDD' ? 'selected' : '' }}>HDD (Hard Disk Drive)</option>
                        <option value="SSD" {{ old('tipo_disco', $componente->tipo_disco ?? $componente->tipo) == 'SSD' ? 'selected' : '' }}>SSD (Solid State Drive)</option>
                        <option value="SSHD" {{ old('tipo_disco', $componente->tipo_disco ?? $componente->tipo) == 'SSHD' ? 'selected' : '' }}>SSHD (Solid State Hybrid Drive)</option>
                        <option value="NVMe" {{ old('tipo_disco', $componente->tipo_disco ?? $componente->tipo) == 'NVMe' ? 'selected' : '' }}>NVMe (Non-Volatile Memory Express)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Capacidad</label>
                    <input type="text" name="capacidad_disco" class="form-control"
                        value="{{ old('capacidad_disco', $componente->capacidad_disco ?? $componente->capacidad) }}">
                </div>

                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado_disco" class="form-control">
                        @foreach ($estados as $estado)
                        <option value="{{ $estado }}" {{ old('estado_disco', $componente->estado_disco ?? $componente->estado) == $estado ? 'selected' : '' }}>
                            {{ $estado }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Detalles</label>
                <textarea name="detalles_disco" class="form-control" rows="5" placeholder="Información adicional del componente">{{ old('detalles_disco', $componente->detalles_disco ?? $componente->detalles ?? '') }}</textarea>
            </div>
        </div>

        {{-- =================== Tarjeta Gráfica =================== --}}
        <div id="tarjeta_grafica_campos" style="display:none;">
            <div class="text-primary-wrapper">🎮 Tarjeta Gráfica Integrada</div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Marca</label>
                    <input type="text" name="marca_tarjeta_grafica" class="form-control"
                        value="{{ old('marca_tarjeta_grafica', $componente->marca_tarjeta_grafica ?? $componente->marca) }}">
                </div>

                <div class="form-group">
                    <label>Modelo</label>
                    <input type="text" name="modelo_tarjeta_grafica" class="form-control"
                        value="{{ old('modelo_tarjeta_grafica', $componente->modelo_tarjeta_grafica ?? $componente->modelo) }}">
                </div>

                <div class="form-group">
                    <label>Capacidad</label>
                    <input type="text" name="capacidad_tarjeta_grafica" class="form-control"
                        value="{{ old('capacidad_tarjeta_grafica', $componente->capacidad_tarjeta_grafica?? $componente->capacidad) }}">
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

                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado_tarjeta_grafica" class="form-control">
                        @foreach ($estados as $estado)
                        <option value="{{ $estado }}" {{ old('estado_tarjeta_grafica', $componente->estado_tarjeta_grafica ?? $componente->estado) == $estado ? 'selected' : '' }}>
                            {{ $estado }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Detalles</label>
                <textarea name="detalles_tarjeta_grafica" class="form-control" rows="5" placeholder="Información adicional del componente">{{ old('detalles_tarjeta_grafica', $componente->detalles_tarjeta_grafica ?? $componente->detalles ?? '') }}</textarea>
            </div>
        </div>

        {{-- =================== Tarjeta de Red =================== --}}
        <div id="tarjeta_red_campos" style="display:none;">
            <div class="text-primary-wrapper">🌐 Tarjeta de Red</div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Marca</label>
                    <input type="text" name="marca_tarjeta_red" class="form-control"
                        value="{{ old('marca_tarjeta_red', $componente->marca_tarjeta_red ?? $componente->marca) }}">
                </div>

                <div class="form-group">
                    <label>Modelo</label>
                    <input type="text" name="modelo_tarjeta_red" class="form-control"
                        value="{{ old('modelo_tarjeta_red', $componente->modelo_tarjeta_red ?? $componente->modelo) }}">
                </div>

                <div class="form-group">
                    <label>Velocidad (Mbps)</label>
                    <input type="text" name="velocidad_transferencia" class="form-control"
                        value="{{ old('velocidad_transferencia', $componente->velocidad_transferencia) }}">
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Tipo</label>
                    <div class="checkbox-group">
                        <div class="form-check-container">
                            @php
                            // Convertimos la cadena guardada en array para checkboxes
                            $tiposSeleccionados = explode(', ', old('tipo_tarjeta_red', $componente->tipo ?? ''));
                            @endphp

                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" name="tipo_tarjeta_red[]" value="Ethernet (LAN)"
                                        {{ in_array('Ethernet (LAN)', $tiposSeleccionados) ? 'checked' : '' }}>
                                    Ethernet (LAN)
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" name="tipo_tarjeta_red[]" value="Wi-Fi"
                                        {{ in_array('Wi-Fi', $tiposSeleccionados) ? 'checked' : '' }}>
                                    Wi-Fi
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" name="tipo_tarjeta_red[]" value="Bluetooth"
                                        {{ in_array('Bluetooth', $tiposSeleccionados) ? 'checked' : '' }}>
                                    Bluetooth
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado_tarjeta_red" class="form-control">
                        @foreach ($estados as $estado)
                        <option value="{{ $estado }}" {{ old('estado_tarjeta_red', $componente->estado_tarjeta_red ?? $componente->estado) == $estado ? 'selected' : '' }}>
                            {{ $estado }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Detalles</label>
                <textarea name="detalles_tarjeta_red" class="form-control" rows="5" placeholder="Información adicional del componente">{{ old('detalles_tarjeta_red', $componente->detalles_tarjeta_red ?? $componente->detalles ?? '') }}</textarea>
            </div>
        </div>

        {{-- =================== Unidad Óptica =================== --}}
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


        {{-- =================== Fan Cooler =================== --}}
        <div id="fan_cooler_campos" style="display:none;">
            <h5 class="text-primary mt-3">❄️ Fan Cooler</h5>

            <div class="form-grid">
                <div class="form-group">
                    <label>Marca</label>
                    <input type="text" name="marca_fan" class="form-control"
                        value="{{ old('marca_fan', $componente->marca_fan ?? $componente->marca) }}">
                </div>

                <div class="form-group">
                    <label>Tipo</label>
                    <input type="text" name="tipo_fan" class="form-control"
                        value="{{ old('tipo_fan', $componente->tipo_fan ?? $componente->tipo) }}">
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Consumo eléctrico (W)</label>
                    <input type="text" name="consumo_fan" class="form-control"
                        value="{{ old('consumo_fan', $componente->consumo_fan ?? $componente->consumo) }}">
                </div>

                <div class="form-group">
                    <label>Ubicacion</label>
                    <input type="text" name="ubicacion" class="form-control"
                        value="{{ old('ubicacion', $componente->ubicacion) }}">
                </div>

                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado_fan" class="form-control">
                        @foreach ($estados as $estado)
                        <option value="{{ $estado }}" {{ old('estado_fan', $componente->estado_fan ?? $componente->estado) == $estado ? 'selected' : '' }}>
                            {{ $estado }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Detalles</label>
                <textarea name="detalles_fan" class="form-control" rows="5" placeholder="Información adicional del componente">{{ old('detalles_fan', $componente->detalles_fan ?? $componente->detalles ?? '') }}</textarea>
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
@endsection