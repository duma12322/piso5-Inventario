@extends('layouts.app')

@section('title', 'Editar Componente')

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
                    <i class="fas fa-edit header-icon"></i>
                </div>
                <div class="header-text">
                    <h1>Editar Componente</h1>
                    <p>Actualizar especificaciones del hardware - ID: {{ $componente->id_componente }}</p>
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
                    <div class="alert alert-danger fade show" role="alert" style="border-radius: var(--radius-md);">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ $error }}
                    </div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('componentes.update', $componente->id_componente) }}" class="premium-form">
            @csrf
            @method('PUT')

            @if(isset($porEquipo) && $porEquipo)
                <input type="hidden" name="porEquipo" value="1">
                <input type="hidden" name="id_equipo" value="{{ $componente->id_equipo }}">
            @endif

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

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-laptop"></i> Equipo</label>
                        <select name="id_equipo" class="form-select" required>
                            <option value="">Seleccione un equipo</option>
                            @foreach ($equipos as $e)
                                <option value="{{ $e->id_equipo }}" {{ $e->id_equipo == old('id_equipo', $componente->id_equipo) ? 'selected' : '' }}>
                                    {{ $e->marca }} {{ $e->modelo }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-tags"></i> Tipo de Componente</label>
                        <select id="tipo_componente" name="tipo_componente" class="form-select" required>
                            <option value="">Seleccione un tipo</option>
                            @foreach ($tiposComponentes as $tipo)
                                <option value="{{ $tipo }}" {{ old('tipo_componente', $componente->tipo_componente) == $tipo ? 'selected' : '' }}>
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

                    {{-- Add the rest of sections as needed following this pattern --}}
                </div>

                <div class="form-actions mt-4">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Actualizar Componente
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
        }

        .checkbox-group-wrapper {
            margin-bottom: 1rem;
            padding: 1rem;
            background: rgba(102, 126, 234, 0.05);
            border-radius: var(--radius-md);
        }

        .group-label {
            color: var(--primary);
            font-size: 0.85rem;
            text-transform: uppercase;
            display: block;
            margin-bottom: 0.5rem;
        }

        .checkbox-options {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 0.5rem;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
        }
    </style>
    <script src="{{ asset('js/componente1.js') }}"></script>
    <script src="{{ asset('js/unicos.js') }}"></script>
    <script src="{{ asset('js/unidad.js') }}"></script>
    <script src="{{ asset('js/tipoRam.js') }}"></script>
@endsection