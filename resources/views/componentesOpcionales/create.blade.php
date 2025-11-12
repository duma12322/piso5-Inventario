{{-- resources/views/componentesOpcionales/create.blade.php --}}
@extends('layouts.app')

@if (session('success'))
<div class="alert alert-success mt-2">
    {{ session('success') }}
</div>
@endif

@section('content')
<div class="container mt-4">
    <h3>Agregar Componente Opcional</h3>
    <small class="form-text text-info mt-1">
        💡 Aquí van los componentes opcionales que puedes agregar si es necesario.
    </small>

    <form method="POST" action="{{ route('componentesOpcionales.store') }}">
        @csrf
        @if(isset($porEquipo) && $porEquipo)
        <input type="hidden" name="porEquipo" value="1">
        @else
        <input type="hidden" name="porEquipo" value="0">
        @endif
        {{-- Equipo --}}
        <div class="form-group">
            <label>Equipo</label>
            @if(isset($porEquipo) && $porEquipo)
            <input type="hidden" name="id_equipo" value="{{ $equipoSeleccionado->id_equipo }}">
            <input type="text" class="form-control" value="{{ $equipoSeleccionado->marca }} {{ $equipoSeleccionado->modelo }}" readonly>
            @else
            <select name="id_equipo" class="form-control" required>
                <option value="">Seleccione</option>
                @foreach ($equipos as $e)
                <option value="{{ $e->id_equipo }}" {{ old('id_equipo') == $e->id_equipo ? 'selected' : '' }}>
                    {{ $e->marca }} {{ $e->modelo }}
                </option>
                @endforeach
            </select>
            @endif
        </div>

        {{-- Tipo de Componente Opcional --}}
        <div class="form-group">
            <label>Tipo de Componente Opcional</label>
            <select id="tipo_opcional" name="tipo_opcional" class="form-control" required>
                <option value="">Seleccione un tipo</option>
                @foreach(['Memoria Ram','Disco Duro','Fan Cooler','Tarjeta Grafica','Tarjeta de Red','Tarjeta WiFi','Tarjeta de Sonido'] as $tipo)
                <option value="{{ $tipo }}" {{ old('tipo_opcional')==$tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                @endforeach
            </select>
        </div>

        {{-- Memoria RAM --}}
        <div id="memoria_ram_campos" style="display:none;">
            <h5 class="text-primary mt-3">💾 Memoria RAM</h5>
            <div class="form-group">
                <label>Marca</label>
                <input type="text" name="marca_ram" class="form-control" placeholder="Ej: Corsair, Kingston, G. Skill, Crucial y ADATA" value="{{ old('marca_ram') }}">
            </div>
            <div class="form-group">
                <label>Tipo</label>
                <input type="text" name="tipo_ram" class="form-control" placeholder="Ej: DDR4, DDR5" value="{{ old('tipo_ram') }}">
                @if($errors->has('tipo_ram'))
                <div class="alert alert-danger mt-2">
                    {{ $errors->first('tipo_ram') }}
                </div>
                @endif
            </div>
            <div class="form-group">
                <label>Slot RAM</label>
                <input type="text" name="slot_memoria" class="form-control" placeholder="En cual Slot se ubica la RAM" value="{{ old('slot_memoria') }}">
                @if($errors->has('slot_memoria'))
                <div class="alert alert-danger mt-2">
                    {{ $errors->first('slot_memoria') }}
                </div>
                @endif
            </div>

            <div class="form-group">
                <label>Capacidad</label>
                <input type="text" name="capacidad_ram" class="form-control" placeholder="Ej: 8GB, 16GB" value="{{ old('capacidad_ram') }}">
                @if($errors->has('capacidad_ram'))
                <div class="alert alert-danger mt-2">
                    {{ $errors->first('capacidad_ram') }}
                </div>
                @endif
            </div>
            <div class="form-group">
                <label>Frecuencia</label>
                <input type="text" name="frecuencia_ram" class="form-control" placeholder="Ej: 3200MHz" value="{{ old('frecuencia_ram') }}">
                @if($errors->has('frecuencia_ram'))
                <div class="alert alert-danger mt-2">
                    {{ $errors->first('frecuencia_ram') }}
                </div>
                @endif
            </div>
            <div class="form-group">
                <label>Estado</label>
                <select name="estado_ram" class="form-control">
                    <option value="">Seleccione un estado</option>
                    <option value="Buen Funcionamiento">Buen Funcionamiento</option>
                    <option value="Operativo">Operativo</option>
                    <option value="Sin Funcionar">Sin Funcionar</option>
                </select>
            </div>
            <div class="form-group">
                <label>Detalles adicionales</label>
                <textarea name="detalles_ram" class="form-control"></textarea>
            </div>
        </div>

        {{-- Disco Duro --}}
        <div id="disco_duro_campos" style="display:none;">
            <h5 class="text-primary mt-3">💽 Disco Duro</h5>
            <div class="form-group">
                <label>Marca</label>
                <input type="text" name="marca_disco" class="form-control" placeholder="Ej: Western Digital (WD), Seagate, Toshiba" value="{{ old('marca_disco') }}">
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
                <input type="text" name="capacidad_disco" class="form-control" placeholder="Ej: 1TB, 512GB" value="{{ old('capacidad_disco') }}">
            </div>
            <div class="form-group">
                <label>Estado</label>
                <select name="estado_disco" class="form-control">
                    @foreach(['Buen Funcionamiento','Operativo','Sin Funcionar'] as $estado)
                    <option {{ old('estado_disco')==$estado ? 'selected' : '' }}>{{ $estado }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Detalles adicionales</label>
                <textarea name="detalles_disco" class="form-control"></textarea>
            </div>
        </div>

        {{-- Tarjeta Grafica --}}
        <div id="tarjeta_grafica_campos" style="display:none;">
            <h5 class="text-primary mt-3">🎮 Tarjeta Gráfica Integrada</h5>
            <div class="form-group">
                <label>Marca</label>
                <input type="text" name="marca_tarjeta_grafica" class="form-control" placeholder="Ej: Intel UHD, AMD Radeon Vega, Apple GPU" value="{{ old('marca_tarjeta_grafica') }}">
            </div>
            <div class="form-group">
                <label>Modelo</label>
                <input type="text" name="modelo_tarjeta_grafica" class="form-control" placeholder="Ej: Intel Iris Xe Graphics G7, AMD Radeon Vega 8" value="{{ old('modelo_tarjeta_grafica') }}">
            </div>
            <div class="form-group">
                <label>VRAM</label>
                <input type="text" name="vrm" class="form-control" placeholder="Ej: GDDR5, GDDR6 / GDDR6X" value="{{ old('vrm') }}">
            </div>
            <div class="form-group">
                <label>Capacidad</label>
                <input type="text" name="capacidad_tarjeta_grafica" class="form-control" placeholder="Ej: 2GB, 4GB, 8GB" value="{{ old('capacidad_tarjeta_grafica') }}">
            </div>
            <div class="form-group">
                <label>Compatibilidad del sistema</label>
                <select name="compatibilidad_tarjeta_grafica" class="form-control">
                    <option value="">Seleccione una opción</option>
                    @foreach(['Si','Parcialmente','No'] as $val)
                    <option value="{{ $val }}" {{ old('compatibilidad_tarjeta_grafica')==$val ? 'selected' : '' }}>{{ $val }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Salidas de video</label><br>
                @php
                // Si hay old(), lo usamos; si no, un array vacío
                $salidasSeleccionadas = old('salidas_video') ?? [];
                @endphp
                <label><input type="checkbox" name="salidas_video[]" value="VGA" {{ in_array('VGA', $salidasSeleccionadas) ? 'checked' : '' }}> VGA</label><br>
                <label><input type="checkbox" name="salidas_video[]" value="HDMI" {{ in_array('HDMI', $salidasSeleccionadas) ? 'checked' : '' }}> HDMI</label><br>
                <label><input type="checkbox" name="salidas_video[]" value="DVI" {{ in_array('DVI', $salidasSeleccionadas) ? 'checked' : '' }}> DVI</label><br>
                <label><input type="checkbox" name="salidas_video[]" value="DisplayPort" {{ in_array('DisplayPort', $salidasSeleccionadas) ? 'checked' : '' }}> DisplayPort</label><br>
            </div>

            <div class="form-group">
                <label>Drivers disponibles / Sistemas operativos compatibles</label>
                <input type="text" name="drivers_sistema_tarjeta_grafica" class="form-control" placeholder="Ej: Windows 10, Linux Ubuntu, macOS Ventura, FreeBSD 13" value="{{ old('drivers_sistema_tarjeta_grafica') }}">
                <small class="form-text text-muted">
                    La compatibilidad depende del software instalado en el equipo, pero puede indicarse manualmente aquí.
                </small>
            </div>
            <div class="form-group">
                <label>Estado</label>
                <select name="estado_tarjeta_grafica" class="form-control">
                    @foreach(['Operativo','Medio dañado','Dañado'] as $estado)
                    <option {{ old('estado_tarjeta_grafica')==$estado ? 'selected' : '' }}>{{ $estado }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Detalles adicionales</label>
                <textarea name="detalles_tarjeta_grafica" class="form-control"></textarea>
            </div>
        </div>

        {{-- Tarjeta de Red --}}
        <div id="tarjeta_red_campos" style="display:none;">
            <h5 class="text-primary mt-3">🌐 Tarjeta de Red</h5>
            <div class="form-group">
                <label>Marca / Fabricante</label>
                <input type="text" name="marca_tarjeta_red" class="form-control" placeholder="Ej: TP-Link, ASUS, Intel, Netgear, Cudy, StarTech" value="{{ old('marca_tarjeta_red') }}">
            </div>
            <div class="form-group">
                <label>Modelo</label>
                <input type="text" name="modelo_tarjeta_red" class="form-control" placeholder="Ej: Intel I219-V, Realtek RTL8111H, Marvell AQtion AQC113C" value="{{ old('modelo_tarjeta_red') }}">
            </div>
            <div class="form-group">
                <label>Velocidad de transferencia</label>
                <input type="text" name="velocidad_transferencia" class="form-control" placeholder="Ej: 1Gbps, 100Mbps" value="{{ old('velocidad_transferencia') }}">
            </div>
            <div class="form-group">
                <label>Drivers disponibles</label>
                <input type="text" name="drivers_sistema_tarjeta_red" class="form-control" placeholder="Ej: Windows 10, Linux Ubuntu, macOS Ventura, FreeBSD 13" value="{{ old('drivers_sistema_tarjeta_red') }}">
                <small class="form-text text-muted">
                    La compatibilidad depende del software instalado en el equipo, pero puede indicarse manualmente aquí.
                </small>
            </div>
            <div class="form-group">
                <label>Compatibilidad del sistema</label>
                <select name="compatibilidad_tarjeta_red" class="form-control">
                    <option value="">Seleccione una opción</option>
                    @foreach(['Si','Parcialmente','No'] as $val)
                    <option value="{{ $val }}" {{ old('compatibilidad_tarjeta_red')==$val ? 'selected' : '' }}>{{ $val }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Estado</label>
                <select name="estado_tarjeta_red" class="form-control">
                    @foreach(['Operativo','Medio dañado','Dañado'] as $estado)
                    <option {{ old('estado_tarjeta_red')==$estado ? 'selected' : '' }}>{{ $estado }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Detalles adicionales</label>
                <textarea name="detalles_tarjeta_red" class="form-control"></textarea>
            </div>
        </div>

        {{-- Tarjeta WiFi --}}
        <div id="tarjeta_wifi_campos" style="display:none;">
            <h5 class="text-primary mt-3">🌐 Tarjeta Wifi</h5>
            <div class="form-group">
                <label>Marca / Fabricante</label>
                <input type="text" name="marca_tarjeta_wifi" class="form-control" placeholder="Ej: TP-Link, ASUS, Intel, Netgear, Cudy, StarTech" value="{{ old('marca_tarjeta_wifi') }}">
            </div>
            <div class="form-group">
                <label>Modelo</label>
                <input type="text" name="modelo_tarjeta_wifi" class="form-control" placeholder="Ej: Intel I219-V, Realtek RTL8111H, Marvell AQtion AQC113C" value="{{ old('modelo_tarjeta_wifi') }}">
            </div>
            <div class="form-group">
                <label>Tipo de conexión</label>
                <select name="tipo_tarjeta_wifi" class="form-control">
                    <option value="">Seleccione un tipo</option>
                    @php
                    $tiposWifi = [
                    'PCIe' => 'Pc de escritorio',
                    'USB' => '',
                    'M.2' => 'Laptops y mini PC, Integración compacta, Wi-Fi + Bluetooth',
                    'Mini PCIe' => 'Laptops antiguos, sistemas embebidos'
                    ];
                    @endphp

                    @foreach($tiposWifi as $tipo => $descripcion)
                    <option value="{{ $tipo }}"
                        {{ old('tipo_tarjeta_wifi', $opcional->tipo ?? '') == $tipo ? 'selected' : '' }}>
                        {{ $tipo }} {{ $descripcion ? "($descripcion)" : "" }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Velocidad de transferencia</label>
                <input type="text" name="velocidad_wifi" class="form-control" placeholder="Ej: 1Gbps, 100Mbps" value="{{ old('velocidad_wifi') }}">
            </div>
            <div class="form-group">
                <label>Frecuencia</label>
                <input type="text" name="frecuencia_wifi" class="form-control" placeholder="Ej: 5150–5850 MHz, 2400–2483.5 MHz" value="{{ old('frecuencia_wifi') }}">
            </div>
            <div class="form-group">
                <label>Seguridad</label>
                @php
                $seguridades = ['WEP','WPA','WPA2-PSK','WPA2-Enterprise','WPA3-SAE','WPA3-Enterprise'];
                $valorSeguridad = old('seguridad_wifi', $opcional->seguridad ?? '');
                $seguridadSeleccionada = is_array($valorSeguridad) ? $valorSeguridad : explode(',', $valorSeguridad);
                @endphp
                <div>
                    @foreach($seguridades as $seg)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="seguridad_wifi[]" value="{{ $seg }}"
                            {{ in_array($seg, $seguridadSeleccionada) ? 'checked' : '' }}>
                        <label class="form-check-label">{{ $seg }}</label>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="form-group">
                <label>¿Tiene Bluetooth?</label>
                <select name="bluetooth_wifi" class="form-control">
                    <option value="">Seleccione una opción</option>
                    @foreach(['Sí', 'No'] as $val)
                    <option value="{{ $val }}" {{ old('bluetooth_wifi', $opcional->bluetooth ?? '') == $val ? 'selected' : '' }}>
                        {{ $val }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Drivers disponibles</label>
                <input type="text" name="drivers_sistema_tarjeta_wifi" class="form-control" placeholder="Ej: Windows 10, Linux Ubuntu, macOS Ventura, FreeBSD 13" value="{{ old('drivers_sistema_tarjeta_wifi') }}">
                <small class="form-text text-muted">
                    La compatibilidad depende del software instalado en el equipo, pero puede indicarse manualmente aquí.
                </small>
            </div>
            <div class="form-group">
                <label>Compatibilidad del sistema</label>
                <select name="compatibilidad_tarjeta_wifi" class="form-control">
                    <option value="">Seleccione una opción</option>
                    @foreach(['Si','Parcialmente','No'] as $val)
                    <option value="{{ $val }}" {{ old('compatibilidad_tarjeta_wifi')==$val ? 'selected' : '' }}>{{ $val }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Estado</label>
                <select name="estado_tarjeta_wifi" class="form-control">
                    @foreach(['Operativo','Medio dañado','Dañado'] as $estado)
                    <option {{ old('estado_tarjeta_wifi')==$estado ? 'selected' : '' }}>{{ $estado }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Detalles adicionales</label>
                <textarea name="detalles_tarjeta_wifi" class="form-control"></textarea>
            </div>
        </div>

        {{-- Fan Cooler --}}
        <div id="fan_cooler_campos" style="display:none;">
            <h5 class="text-primary mt-3">🌀 Fan Cooler</h5>
            <div class="form-group">
                <label>Marca / Fabricante</label>
                <input type="text" name="marca_fan" class="form-control" placeholder="Ej: LG, ASUS, Pioneer, Lenovo" value="{{ old('marca_fan') }}">
            </div>
            <div class="form-group">
                <label>Tipo</label>
                <input type="text" name="tipo_fan" class="form-control" placeholder="Ej: Aire, Líquido" value="{{ old('tipo_fan') }}">
            </div>
            <div class="form-group">
                <label>Consumo eléctrico (W)</label>
                <input type="text" name="consumo_fan" class="form-control" placeholder="Ej: 5W" value="{{ old('consumo_fan') }}">
            </div>
            <div class="form-group">
                <label>Ubicación</label>
                <input type="text" name="ubicacion_fan" class="form-control" placeholder="Ej. parte trasera del gabinete, sobre CPU, lateral izquierdo" value="{{ old('ubicacion_fan') }}">
            </div>
            <div class="form-group">
                <label>Estado</label>
                <select name="estado_fan" class="form-control">
                    @foreach(['Operativo','Medio dañado','Dañado'] as $estado)
                    <option {{ old('estado_fan')==$estado ? 'selected' : '' }}>{{ $estado }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Detalles adicionales</label>
                <textarea name="detalles_fan" class="form-control"></textarea>
            </div>
        </div>

        {{-- Tarjeta de Sonido --}}
        <div id="tarjeta_sonido_campos" style="display:none;">
            <h5 class="text-primary mt-3">🎧 Tarjeta de Sonido</h5>

            <!-- Marca / Modelo -->
            <div class="form-group">
                <label>Marca / Fabricante</label>
                <input type="text" name="marca_tarjeta_sonido" class="form-control" placeholder="Ej: Creative, ASUS, Realtek"
                    value="{{ old('marca_tarjeta_sonido') }}">
            </div>

            <div class="form-group">
                <label>Modelo</label>
                <input type="text" name="modelo_tarjeta_sonido" class="form-control" placeholder="Ej: Sound Blaster Z, Xonar SE"
                    value="{{ old('modelo_tarjeta_sonido') }}">
            </div>

            <!-- Soporte de canales -->
            <div class="form-group">
                <label>Soporte de Canales</label>
                @php
                $canales = [
                'Estéreo (2.0)' => 'básico, ideal para música y uso cotidiano.',
                'Surround 5.1 (6 canales)' => 'cine en casa, gaming envolvente.',
                'Surround 7.1 (8 canales)' => 'experiencia inmersiva, edición avanzada.',
                'Multicanal profesional' => 'más de 8 canales, usado en estudios con interfaces XLR o TRS.'
                ];
                @endphp
                <div>
                    @foreach ($canales as $canal => $desc)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="canales_tarjeta_sonido[]"
                            value="{{ $canal }}"
                            {{ in_array($canal, explode(', ', old('canales_tarjeta_sonido', ''))) ? 'checked' : '' }}>
                        <label class="form-check-label">{{ $canal }} <small class="text-muted">({{ $desc }})</small></label>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Tipo de salidas -->
            <div class="form-group">
                <label>Tipo de Salidas</label>
                @php
                $salidas = ['Jack 3.5mm (analógico)', 'RCA', 'Óptico (TOSLINK)', 'Coaxial', 'XLR', 'TRS balanceado', 'USB', 'ADAT'];
                @endphp
                <div>
                    @foreach ($salidas as $salida)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="salidas_audio[]" value="{{ $salida }}"
                            {{ in_array($salida, explode(', ', old('salidas_audio', ''))) ? 'checked' : '' }}>
                        <label class="form-check-label">{{ $salida }}</label>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Resolución -->
            <div class="form-group">
                <label>Resolución de Audio</label>
                @php
                $resoluciones = [
                '16-bit / 44.1 kHz (CD)',
                '24-bit / 96–192 kHz (Alta fidelidad)',
                '32-bit / 384 kHz (Mastering)'
                ];
                @endphp
                <div>
                    @foreach ($resoluciones as $res)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="resolucion_audio[]" value="{{ $res }}"
                            {{ in_array($res, explode(', ', old('resolucion_audio', ''))) ? 'checked' : '' }}>
                        <label class="form-check-label">{{ $res }}</label>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Drivers -->
            <div class="form-group">
                <label>Drivers disponibles</label>
                <input type="text" name="drivers_audio" class="form-control"
                    placeholder="Ej: Windows 10, Linux Ubuntu, macOS Ventura"
                    value="{{ old('drivers_audio') }}">
                <small class="form-text text-muted">
                    Indique manualmente los sistemas compatibles, si aplica.
                </small>
            </div>

            <!-- Compatibilidad -->
            <div class="form-group">
                <label>Compatibilidad del sistema</label>
                <select name="compatibilidad_tarjeta_audio" class="form-control">
                    @foreach (['Si','Parcialmente','No'] as $val)
                    <option value="{{ $val }}" {{ old('compatibilidad_tarjeta_audio') == $val ? 'selected' : '' }}>
                        {{ $val }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Estado -->
            <div class="form-group">
                <label>Estado</label>
                <select name="estado_tarjeta_sonido" class="form-control">
                    @foreach (['Operativo','Medio dañado','Dañado'] as $estado)
                    <option {{ old('estado_tarjeta_sonido') == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Detalles -->
            <div class="form-group">
                <label>Detalles adicionales</label>
                <textarea name="detalles_tarjeta_sonido" class="form-control">{{ old('detalles_tarjeta_sonido') }}</textarea>
            </div>
        </div>

        <div class="form-group mt-3 d-flex justify-content-start gap-2">
            @if(!empty($id_equipo))
            <a href="{{ route('componentes.porEquipo', $id_equipo) }}" class="btn btn-secondary mt-2">← Volver</a>
            @else
            <a href="{{ route('componentesOpcionales.index') }}" class="btn btn-secondary mt-2">← Volver</a>
            @endif
            <button class="btn btn-primary mt-2">Guardar</button>
        </div>

    </form>
</div>

{{-- JS para mostrar/ocultar campos según tipo --}}
<script>
    const BASE_URL = '{{ url(' / ') }}';
</script>
<script src="{{ asset('js/componenteOpcional.js') }}"></script>
@endsection