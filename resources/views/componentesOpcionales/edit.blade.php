{{-- resources/views/componentesOpcionales/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Editar Componente Opcional</h3>
    <small class="form-text text-info mt-1">
        💡 Aquí puedes editar los componentes opcionales del equipo.
    </small>

    <form method="POST" action="{{ route('componentesOpcionales.update', $opcional->id_opcional) }}">
        @csrf
        @method('PUT')
        @if(isset($porEquipo) && $porEquipo)
        <input type="hidden" name="porEquipo" value="1">
        <input type="hidden" name="id_equipo" value="{{ $opcional->id_equipo }}">
        @endif

        <input type="hidden" name="id_opcional" value="{{ $opcional->id_opcional }}">

        {{-- Equipo --}}
        <select name="id_equipo" class="form-control" required>
            <option value="">Seleccione un equipo</option>
            @foreach ($equipos as $e)
            <option value="{{ $e->id_equipo }}"
                {{ (old('id_equipo') ?? $opcional->id_equipo) == $e->id_equipo ? 'selected' : '' }}>
                {{ $e->marca }} {{ $e->modelo }}
            </option>
            @endforeach
        </select>

        {{-- Tipo de Componente Opcional --}}
        <div class="form-group">
            <label>Tipo de Componente Opcional</label>
            <select id="tipo_opcional" name="tipo_opcional" class="form-control" required>
                <option value="">Seleccione un tipo</option>
                @foreach(['Memoria Ram','Disco Duro','Fan Cooler','Tarjeta Grafica','Tarjeta de Red','Tarjeta WiFi','Tarjeta de Sonido'] as $tipo)
                <option value="{{ $tipo }}" {{ old('tipo_opcional', $opcional->tipo_opcional)==$tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                @endforeach
            </select>
        </div>

        {{-- Memoria RAM --}}
        <div id="ram_campos" style="display:none;">
            <h5 class="text-primary mt-3">💾 Memoria RAM</h5>
            <div class="form-group">
                <label>Marca</label>
                <input type="text" name="marca_ram" class="form-control" placeholder="Ej: Corsair, Kingston, G. Skill, Crucial y ADATA" value="{{ old('marca_ram', $opcional->marca) }}">
            </div>
            <div class="form-group">
                <label>Tipo</label>
                <input type="text" name="tipo_ram" class="form-control"
                    placeholder="Ej: DDR4, DDR5"
                    value="{{ old('tipo_ram', $opcional->tipo) }}">
                @if($errors->has('tipo_ram'))
                <div class="alert alert-danger mt-2">
                    {{ $errors->first('tipo_ram') }}
                </div>
                @endif
            </div>
            <div class="form-group">
                <label>Slot RAM</label>
                <input type="text" name="slot_memoria" class="form-control" placeholder="Ej: DDR4, DDR5" value="{{ old('slot_memoria', $opcional->slot_memoria) }}">
                @if($errors->has('slot_memoria'))
                <div class="alert alert-danger mt-2">
                    {{ $errors->first('slot_memoria') }}
                </div>
                @endif
            </div>
            <div class="form-group">
                <label>Capacidad</label>
                <input type="text" name="capacidad_ram" class="form-control" placeholder="Ej: 8GB, 16GB" value="{{ old('capacidad_ram', $opcional->capacidad) }}">
                @if($errors->has('capacidad_ram'))
                <div class="alert alert-danger mt-2">
                    {{ $errors->first('capacidad_ram') }}
                </div>
                @endif
            </div>
            <div class="form-group">
                <label>Frecuencia</label>
                <input type="text" name="frecuencia_ram" class="form-control" placeholder="Ej: 3200MHz" value="{{ old('frecuencia_ram', $opcional->frecuencia) }}">
                @if($errors->has('frecuencia_ram'))
                <div class="alert alert-danger mt-2">
                    {{ $errors->first('frecuencia_ram') }}
                </div>
                @endif
            </div>
            <div class="form-group">
                <label>Estado</label>
                <select name="estado_ram" class="form-control">
                    @foreach(['Buen Funcionamiento','Operativo','Sin Funcionar'] as $estado)
                    <option value="{{ $estado }}" {{ old('estado_ram', $opcional->estado_ram)==$estado ? 'selected' : '' }}>{{ $estado }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Detalles</label>
                <textarea name="detalles_ram" class="form-control" rows="5" placeholder="Información adicional del componente">{{ old('detalles_ram', $opcional->detalles_ram ?? $opcional->detalles ?? '') }}</textarea>
            </div>
        </div>

        {{-- Disco Duro --}}
        <div id="disco_duro_campos" style="display:none;">
            <h5 class="text-primary mt-3">💽 Disco Duro</h5>
            <div class="form-group">
                <label>Marca</label>
                <input type="text" name="marca_disco" class="form-control" placeholder="Ej: Western Digital (WD), Seagate, Toshiba" value="{{ old('marca_disco', $opcional->marca) }}">
            </div>
            <div class="form-group">
                <label>Tipo</label>
                <select name="tipo_disco" class="form-control">
                    <option value="">Seleccione un tipo</option>
                    @foreach(['HDD','SSD','SSHD','NVMe'] as $tipo)
                    <option value="{{ $tipo }}" {{ old('tipo_disco', $opcional->tipo) == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Capacidad</label>
                <input type="text" name="capacidad_disco" class="form-control" placeholder="Ej: 1TB, 512GB" value="{{ old('capacidad_disco', $opcional->capacidad) }}">
            </div>
            <div class="form-group">
                <label>Estado</label>
                <select name="estado_disco" class="form-control">
                    @foreach(['Buen Funcionamiento','Operativo','Sin Funcionar'] as $estado)
                    <option value="{{ $estado }}" {{ old('estado_disco', $opcional->estado_disco)==$estado ? 'selected' : '' }}>{{ $estado }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Detalles</label>
                <textarea name="detalles_disco" class="form-control" rows="5" placeholder="Información adicional del componente">{{ old('detalles_disco', $opcional->detalles_disco ?? $opcional->detalles ?? '') }}</textarea>
            </div>
        </div>

        {{-- Fan Cooler --}}
        <div id="fan_cooler_campos" style="display:none;">
            <h5 class="text-primary mt-3">🌀 Fan Cooler</h5>
            <div class="form-group">
                <label>Marca / Fabricante</label>
                <input type="text" name="marca_fan" class="form-control" placeholder="Ej: LG, ASUS, Pioneer, Lenovo" value="{{ old('marca_fan', $opcional->marca) }}">
            </div>
            <div class="form-group">
                <label>Tipo</label>
                <input type="text" name="tipo_fan" class="form-control" placeholder="Ej: Aire, Líquido" value="{{ old('tipo_fan', $opcional->tipo) }}">
            </div>
            <div class="form-group">
                <label>Consumo eléctrico (W)</label>
                <input type="text" name="consumo_fan" class="form-control" placeholder="Ej: 5W" value="{{ old('consumo_fan', $opcional->consumo) }}">
            </div>
            <div class="form-group">
                <label>Ubicación</label>
                <input type="text" name="ubicacion_fan" class="form-control" placeholder="Ej. parte trasera del gabinete, sobre CPU, lateral izquierdo" value="{{ old('ubicacion_fan', $opcional->ubicacion) }}">
            </div>
            <div class="form-group">
                <label>Estado</label>
                <select name="estado_fan" class="form-control">
                    @foreach(['Buen Funcionamiento','Operativo','Sin Funcionar'] as $estado)
                    <option value="{{ $estado }}" {{ old('estado_fan', $opcional->estado_fan)==$estado ? 'selected' : '' }}>{{ $estado }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Tarjeta Gráfica --}}
        <div id="tarjeta_grafica_campos" style="display:none;">
            <h5 class="text-primary mt-3">🎮 Tarjeta Gráfica Integrada</h5>

            <div class="form-group">
                <label>Marca</label>
                <input type="text" name="marca_tarjeta_grafica" class="form-control" placeholder="Ej: Intel UHD, AMD Radeon Vega, Apple GPU"
                    value="{{ old('marca_tarjeta_grafica', $opcional->marca ?? '') }}">
            </div>

            <div class="form-group">
                <label>Modelo</label>
                <input type="text" name="modelo_tarjeta_grafica" class="form-control" placeholder="Ej: Intel Iris Xe Graphics G7, AMD Radeon Vega 8"
                    value="{{ old('modelo_tarjeta_grafica', $opcional->modelo ?? '') }}">
            </div>

            <div class="form-group">
                <label>VRAM</label>
                <input type="text" name="vrm" class="form-control" placeholder="Ej: GDDR5, GDDR6 / GDDR6X"
                    value="{{ old('vrm', $opcional->vrm ?? '') }}">
            </div>

            <div class="form-group">
                <label>Capacidad</label>
                <input type="text" name="capacidad_tarjeta_grafica" class="form-control" placeholder="Ej: 2GB, 4GB, 8GB"
                    value="{{ old('capacidad_tarjeta_grafica', $opcional->capacidad ?? '') }}">
            </div>

            <div class="form-group">
                <label>Compatibilidad</label>
                <input type="text" name="compatibilidad_tarjeta_grafica" class="form-control"
                    value="{{ old('compatibilidad_tarjeta_grafica', $opcional->compatibilidad ?? '') }}">
            </div>

            <div class="form-group">
                <label>Salidas de video</label><br>
                @php
                $salidasSeleccionadas = old('salidas_video')
                ? old('salidas_video')
                : (isset($opcional->salidas_video)
                ? array_map('trim', explode(',', $opcional->salidas_video))
                : []);
                @endphp
                <label><input type="checkbox" name="salidas_video[]" value="VGA" {{ in_array('VGA', $salidasSeleccionadas) ? 'checked' : '' }}> VGA</label><br>
                <label><input type="checkbox" name="salidas_video[]" value="HDMI" {{ in_array('HDMI', $salidasSeleccionadas) ? 'checked' : '' }}> HDMI</label><br>
                <label><input type="checkbox" name="salidas_video[]" value="DVI" {{ in_array('DVI', $salidasSeleccionadas) ? 'checked' : '' }}> DVI</label><br>
                <label><input type="checkbox" name="salidas_video[]" value="DisplayPort" {{ in_array('DisplayPort', $salidasSeleccionadas) ? 'checked' : '' }}> DisplayPort</label><br>
            </div>

            <div class="form-group">
                <label>Drivers disponibles / Sistemas operativos compatibles</label>
                <input type="text" name="drivers_sistema_tarjeta_grafica" class="form-control" placeholder="Ej: Windows 10, Linux Ubuntu, macOS Ventura, FreeBSD 13"
                    value="{{ old('drivers_sistema_tarjeta_grafica', $opcional->drivers ?? '') }}">
                <small class="form-text text-muted">
                    La compatibilidad depende del software instalado en el equipo, pero puede indicarse manualmente aquí.
                </small>
            </div>

            <div class="form-group">
                <label>Estado</label>
                <select name="estado_tarjeta_grafica" class="form-control">
                    @foreach(['Buen Funcionamiento','Operativo','Sin Funcionar'] as $estado)
                    <option value="{{ $estado }}" {{ (old('estado_tarjeta_grafica', $opcional->estado ?? '') == $estado) ? 'selected' : '' }}>
                        {{ $estado }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Detalles adicionales</label>
                <textarea name="detalles_tarjeta_grafica" class="form-control">{{ old('detalles_tarjeta_grafica', $opcional->detalles ?? '') }}</textarea>
            </div>
        </div>

        {{-- Tarjeta de Red --}}
        <div id="tarjeta_red_campos" style="display:none;">
            <h5 class="text-primary mt-3">🌐 Tarjeta de Red</h5>
            <div class="form-group">
                <label>Marca / Fabricante</label>
                <input type="text" name="marca_tarjeta_red" class="form-control" placeholder="Ej: TP-Link, ASUS, Intel, Netgear, Cudy, StarTech"
                    value="{{ old('marca_tarjeta_red', $opcional->marca) }}">
            </div>
            <div class="form-group">
                <label>Modelo</label>
                <input type="text" name="modelo_tarjeta_red" class="form-control" placeholder="Ej: Intel I219-V, Realtek RTL8111H, Marvell AQtion AQC113C"
                    value="{{ old('modelo_tarjeta_red', $opcional->modelo) }}">
            </div>
            <div class="form-group">
                <label>Velocidad de transferencia</label>
                <input type="text" name="velocidad_red" class="form-control" placeholder="Ej: 1Gbps, 100Mbps"
                    value="{{ old('velocidad_red', $opcional->velocidad) }}">
            </div>
            <div class="form-group">
                <label>Drivers disponibles</label>
                <input type="text" name="drivers_sistema_tarjeta_red" class="form-control" placeholder="Ej: Windows 10, Linux Ubuntu, macOS Ventura, FreeBSD 13"
                    value="{{ old('drivers_sistema_tarjeta_red', $opcional->drivers_sistema) }}">
                <small class="form-text text-muted">
                    La compatibilidad depende del software instalado en el equipo, pero puede indicarse manualmente aquí.
                </small>
            </div>
            <div class="form-group">
                <label>Compatibilidad del sistema</label>
                <select name="compatibilidad_tarjeta_red" class="form-control">
                    <option value="">Seleccione una opción</option>
                    @foreach(['Si','Parcialmente','No'] as $val)
                    <option value="{{ $val }}" {{ old('compatibilidad_tarjeta_red', $opcional->compatibilidad_sistema) == $val ? 'selected' : '' }}>{{ $val }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Estado</label>
                <select name="estado_tarjeta_red" class="form-control">
                    @foreach(['Buen Funcionamiento','Operativo','Sin Funcionar'] as $estado)
                    <option value="{{ $estado }}" {{ old('estado_tarjeta_red', $opcional->estado) == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Detalles adicionales</label>
                <textarea name="detalles_tarjeta_red" class="form-control">{{ old('detalles_tarjeta_red', $opcional->detalles) }}</textarea>
            </div>
        </div>

        {{-- Tarjeta WiFi --}}
        <div id="tarjeta_wifi_campos" style="display:none;">
            <h5 class="text-primary mt-3">🌐 Tarjeta Wifi</h5>

            <div class="form-group">
                <label>Marca / Fabricante</label>
                <input type="text" name="marca_tarjeta_wifi" class="form-control"
                    placeholder="Ej: TP-Link, ASUS, Intel, Netgear, Cudy, StarTech"
                    value="{{ old('marca_tarjeta_wifi', $opcional->marca) }}">
            </div>
            <div class="form-group">
                <label>Modelo</label>
                <input type="text" name="modelo_tarjeta_wifi" class="form-control"
                    placeholder="Ej: Intel I219-V, Realtek RTL8111H, Marvell AQtion AQC113C"
                    value="{{ old('modelo_tarjeta_wifi', $opcional->modelo) }}">
            </div>
            <div class="form-group">
                <label>Tipo de conexión</label>
                <select name="tipo_tarjeta_wifi" class="form-control">
                    <option value="">Seleccione una opción</option>
                    @foreach(['PCIe', 'USB', 'M.2', 'Mini PCIe'] as $tipo)
                    <option value="{{ $tipo }}" {{ old('tipo_tarjeta_wifi', $opcional->tipo) == $tipo ? 'selected' : '' }}>
                        {{ $tipo }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Velocidad de transferencia</label>
                <input type="text" name="velocidad_wifi" class="form-control"
                    placeholder="Ej: 1Gbps, 100Mbps"
                    value="{{ old('velocidad_wifi', $opcional->velocidad) }}">
            </div>
            <div class="form-group">
                <label>Frecuencia</label>
                <input type="text" name="frecuencia_wifi" class="form-control"
                    value="{{ old('frecuencia_wifi', $opcional->frecuencia ?? '') }}">
            </div>
            <div class="form-group">
                <label>Seguridad</label>
                @php
                $seguridades = ['WEP','WPA','WPA2-PSK','WPA2-Enterprise','WPA3-SAE','WPA3-Enterprise'];
                $seguridadSeleccionada = old('seguridad_wifi', $opcional->seguridad ?? '');
                // convertir string separado por comas en array
                $seguridadSeleccionada = $seguridadSeleccionada ? explode(',', str_replace(' ', '', $seguridadSeleccionada)) : [];
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
                    @foreach(['Sí','No'] as $val)
                    <option value="{{ $val }}" {{ old('bluetooth_wifi', $opcional->bluetooth ?? '') == $val ? 'selected' : '' }}>
                        {{ $val }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Drivers disponibles</label>
                <input type="text" name="drivers_sistema_tarjeta_wifi" class="form-control"
                    value="{{ old('drivers_sistema_tarjeta_wifi', $opcional->drivers) }}">
            </div>
            <div class="form-group">
                <label>Compatibilidad del sistema</label>
                <select name="compatibilidad_tarjeta_wifi" class="form-control">
                    <option value="">Seleccione una opción</option>
                    @foreach(['Si','Parcialmente','No'] as $val)
                    <option value="{{ $val }}" {{ old('compatibilidad_tarjeta_wifi', $opcional->compatibilidad) == $val ? 'selected' : '' }}>
                        {{ $val }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Estado</label>
                <select name="estado_tarjeta_wifi" class="form-control">
                    @foreach(['Buen Funcionamiento','Operativo','Sin Funcionar'] as $estado)
                    <option value="{{ $estado }}" {{ old('estado_tarjeta_wifi', $opcional->estado) == $estado ? 'selected' : '' }}>
                        {{ $estado }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Detalles adicionales</label>
                <textarea name="detalles_tarjeta_wifi" class="form-control">{{ old('detalles_tarjeta_wifi', $opcional->detalles) }}</textarea>
            </div>
        </div>

        {{-- Tarjeta de Sonido --}}
        <div id="tarjeta_sonido_campos" style="display:none;">
            <h5 class="text-primary mt-3">🎵 Tarjeta de Sonido</h5>

            <div class="form-group">
                <label>Marca / Fabricante</label>
                <input type="text" name="marca_tarjeta_sonido" class="form-control"
                    value="{{ old('marca_tarjeta_sonido', $opcional->marca) }}">
            </div>

            <div class="form-group">
                <label>Modelo</label>
                <input type="text" name="modelo_tarjeta_sonido" class="form-control"
                    value="{{ old('modelo_tarjeta_sonido', $opcional->modelo) }}">
            </div>

            {{-- Soporte de canales --}}
            <div class="form-group">
                <label>Soporte de Canales</label>
                @php
                $canalesSeleccionados = explode(', ', old('canales_tarjeta_sonido', $opcional->canales ?? ''));
                $canalesOpciones = ['Estéreo (2.0)', 'Surround 5.1 (6 canales)', 'Surround 7.1 (8 canales)', 'Multicanal profesional'];
                @endphp
                <div>
                    @foreach ($canalesOpciones as $canal)
                    <div class="form-check form-check-inline">
                        <input type="checkbox" class="form-check-input" name="canales_tarjeta_sonido[]" value="{{ $canal }}"
                            {{ in_array($canal, $canalesSeleccionados) ? 'checked' : '' }}>
                        <label class="form-check-label">{{ $canal }}</label>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Tipo de salidas --}}
            <div class="form-group">
                <label>Tipo de Salidas</label>
                @php
                $salidasSeleccionadas = explode(', ', old('salidas_audio', $opcional->salidas_audio ?? ''));
                $salidasOpciones = ['Jack 3.5mm (analógico)', 'RCA', 'Óptico (TOSLINK)', 'Coaxial', 'XLR', 'TRS balanceado', 'USB', 'ADAT'];
                @endphp
                <div>
                    @foreach ($salidasOpciones as $salida)
                    <div class="form-check form-check-inline">
                        <input type="checkbox" class="form-check-input" name="salidas_audio[]" value="{{ $salida }}"
                            {{ in_array($salida, $salidasSeleccionadas) ? 'checked' : '' }}>
                        <label class="form-check-label">{{ $salida }}</label>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Resolución de audio --}}
            <div class="form-group">
                <label>Resolución de Audio</label>
                @php
                $resolucionesSeleccionadas = explode(', ', old('resolucion_audio', $opcional->resolucion ?? ''));
                $resolucionesOpciones = ['Estándar: 16-bit / 44.1 kHz (CD)', 'Alta fidelidad: 24-bit / 96–192 kHz', 'Mastering: 32-bit / 384 kHz'];
                @endphp
                <div>
                    @foreach ($resolucionesOpciones as $res)
                    <div class="form-check form-check-inline">
                        <input type="checkbox" class="form-check-input" name="resolucion_audio[]" value="{{ $res }}"
                            {{ in_array($res, $resolucionesSeleccionadas) ? 'checked' : '' }}>
                        <label class="form-check-label">{{ $res }}</label>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="form-group">
                <label>Drivers disponibles</label>
                <input type="text" name="drivers_audio" class="form-control"
                    value="{{ old('drivers_audio', $opcional->drivers) }}">
            </div>

            <div class="form-group">
                <label>Compatibilidad del sistema</label>
                <select name="compatibilidad_tarjeta_audio" class="form-control">
                    @foreach(['Si','Parcialmente','No'] as $val)
                    <option value="{{ $val }}" {{ old('compatibilidad_tarjeta_audio', $opcional->compatibilidad_sistema) == $val ? 'selected' : '' }}>{{ $val }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Estado</label>
                <select name="estado_tarjeta_sonido" class="form-control">
                    @foreach(['Buen Funcionamiento','Operativo','Sin Funcionar'] as $estado)
                    <option value="{{ $estado }}" {{ old('estado_tarjeta_sonido', $opcional->estado) == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Detalles adicionales</label>
                <textarea name="detalles_tarjeta_sonido" class="form-control">{{ old('detalles_tarjeta_sonido', $opcional->detalles) }}</textarea>
            </div>
        </div>

        <div class="form-group mt-3 d-flex justify-content-start gap-2">
            @if(isset($porEquipo) && $porEquipo === true && isset($id_equipo))
            <a href="{{ route('componentesOpcionales.porEquipo', $id_equipo) }}" class="btn btn-secondary mt-2">← Volver</a>
            @else
            <a href="{{ route('componentesOpcionales.index') }}" class="btn btn-secondary mt-2">← Volver</a>
            @endif
            <button class="btn btn-primary mt-2">Actualizar</button>
        </div>
    </form>
</div>

<script>
    const BASE_URL = '{{ url(' / ') }}';
</script>

<script src="{{ asset('js/componenteOpcional.js') }}"></script>
<script src="{{ asset('js/componenteOpcional2.js') }}"></script>
@endsection