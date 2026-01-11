@extends('layouts.app')

@section('title', 'Editar Componente Opcional')

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
                    <h1>Editar Componente Opcional</h1>
                    <p>Modificar hardware adicional - ID: {{ $opcional->id_opcional }}</p>
                </div>
            </div>
            <div class="header-actions">
                @if(isset($porEquipo) && $porEquipo === true && isset($id_equipo))
                    <a href="{{ route('componentesOpcionales.porEquipo', $id_equipo) }}" class="btn-back">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                @else
                    <a href="{{ route('componentesOpcionales.index') }}" class="btn-back">
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

        <form method="POST" action="{{ route('componentesOpcionales.update', $opcional->id_opcional) }}"
            class="premium-form">
            @csrf
            @method('PUT')

            @if(isset($porEquipo) && $porEquipo)
                <input type="hidden" name="porEquipo" value="1">
                <input type="hidden" name="id_equipo" value="{{ $opcional->id_equipo }}">
            @endif
            <input type="hidden" name="id_opcional" value="{{ $opcional->id_opcional }}">

            <div class="form-step active">
                <div class="step-header">
                    <div class="step-icon">
                        <i class="fas fa-cog"></i>
                    </div>
                    <div class="step-title">
                        <h3>Configuración Base</h3>
                        <p>Equipo asociado y tipo de hardware opcional</p>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-laptop"></i> Equipo</label>
                        <select name="id_equipo" class="form-select" required>
                            <option value="">Seleccione un equipo</option>
                            @foreach ($equipos as $e)
                                <option value="{{ $e->id_equipo }}" {{ (old('id_equipo') ?? $opcional->id_equipo) == $e->id_equipo ? 'selected' : '' }}>
                                    {{ $e->marca }} {{ $e->modelo }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-tags"></i> Tipo de Componente Opcional</label>
                        <select id="tipo_opcional" name="tipo_opcional" class="form-select" required>
                            <option value="">Seleccione un tipo</option>
                            @foreach(['Memoria Ram', 'Disco Duro', 'Fan Cooler', 'Tarjeta Grafica', 'Tarjeta de Red', 'Tarjeta WiFi', 'Tarjeta de Sonido'] as $tipo)
                                <option value="{{ $tipo }}" {{ old('tipo_opcional', $opcional->tipo_opcional) == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="component-sections">
                    {{-- RAM --}}
                    <div id="ram_campos" class="component-section" style="display:none;">
                        <div class="component-header">
                            <div class="component-icon"><i class="fas fa-memory"></i></div>
                            <div class="component-title">
                                <h4>Memoria RAM Extra</h4>
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Marca</label>
                                <input type="text" name="marca_ram" class="form-input"
                                    value="{{ old('marca_ram', $opcional->marca) }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Tipo</label>
                                <input type="text" name="tipo_ram" class="form-input"
                                    value="{{ old('tipo_ram', $opcional->tipo) }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Capacidad</label>
                                <input type="text" name="capacidad_ram" class="form-input"
                                    value="{{ old('capacidad_ram', $opcional->capacidad) }}">
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Slot</label>
                                <input type="text" name="slot_memoria" class="form-input"
                                    value="{{ old('slot_memoria', $opcional->slot_memoria) }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Frecuencia</label>
                                <input type="text" name="frecuencia_ram" class="form-input"
                                    value="{{ old('frecuencia_ram', $opcional->frecuencia) }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Estado</label>
                                <select name="estado_ram" class="form-select">
                                    @foreach(['Buen Funcionamiento', 'Operativo', 'Sin Funcionar'] as $est)
                                        <option value="{{ $est }}" {{ old('estado_ram', $opcional->estado_ram) == $est ? 'selected' : '' }}>{{ $est }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Disco --}}
                    <div id="disco_duro_campos" class="component-section" style="display:none;">
                        <div class="component-header">
                            <div class="component-icon"><i class="fas fa-hdd"></i></div>
                            <div class="component-title">
                                <h4>Disco Duro Adicional</h4>
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Marca</label>
                                <input type="text" name="marca_disco" class="form-input"
                                    value="{{ old('marca_disco', $opcional->marca) }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Tipo</label>
                                <select name="tipo_disco" class="form-select">
                                    @foreach(['HDD', 'SSD', 'SSHD', 'NVMe'] as $td)
                                        <option value="{{ $td }}" {{ old('tipo_disco', $opcional->tipo) == $td ? 'selected' : '' }}>{{ $td }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Capacidad</label>
                                <input type="text" name="capacidad_disco" class="form-input"
                                    value="{{ old('capacidad_disco', $opcional->capacidad) }}">
                            </div>
                        </div>
                    </div>

                    {{-- Tarjeta Grafica --}}
                    <div id="tarjeta_grafica_campos" class="component-section" style="display:none;">
                        <div class="component-header">
                            <div class="component-icon"><i class="fas fa-video"></i></div>
                            <div class="component-title">
                                <h4>Tarjeta Gráfica</h4>
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Marca</label>
                                <input type="text" name="marca_tarjeta_grafica" class="form-input"
                                    value="{{ old('marca_tarjeta_grafica', $opcional->marca) }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Modelo</label>
                                <input type="text" name="modelo_tarjeta_grafica" class="form-input"
                                    value="{{ old('modelo_tarjeta_grafica', $opcional->modelo) }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Estado</label>
                                <select name="estado_tarjeta_grafica" class="form-select">
                                    @foreach(['Buen Funcionamiento', 'Operativo', 'Sin Funcionar'] as $est)
                                        <option value="{{ $est }}" {{ old('estado_tarjeta_grafica', $opcional->estado) == $est ? 'selected' : '' }}>{{ $est }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Fan Cooler --}}
                    <div id="fan_cooler_campos" class="component-section" style="display:none;">
                        <div class="component-header">
                            <div class="component-icon"><i class="fas fa-fan"></i></div>
                            <div class="component-title">
                                <h4>Fan Cooler</h4>
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Marca</label>
                                <input type="text" name="marca_fan" class="form-input"
                                    value="{{ old('marca_fan', $opcional->marca) }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Tipo</label>
                                <input type="text" name="tipo_fan" class="form-input"
                                    value="{{ old('tipo_fan', $opcional->tipo) }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Ubicación</label>
                                <input type="text" name="ubicacion_fan" class="form-input"
                                    value="{{ old('ubicacion_fan', $opcional->ubicacion) }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Estado</label>
                                <select name="estado_fan" class="form-select">
                                    @foreach(['Buen Funcionamiento', 'Operativo', 'Sin Funcionar'] as $est)
                                        <option value="{{ $est }}" {{ old('estado_fan', $opcional->estado) == $est ? 'selected' : '' }}>{{ $est }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Tarjeta de Red --}}
                    <div id="tarjeta_de_red_campos" class="component-section" style="display:none;">
                        <div class="component-header">
                            <div class="component-icon"><i class="fas fa-network-wired"></i></div>
                            <div class="component-title">
                                <h4>Tarjeta de Red</h4>
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Marca</label>
                                <input type="text" name="marca_tarjeta_red" class="form-input"
                                    value="{{ old('marca_tarjeta_red', $opcional->marca) }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Velocidad</label>
                                <input type="text" name="velocidad_red" class="form-input"
                                    value="{{ old('velocidad_red', $opcional->velocidad) }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Estado</label>
                                <select name="estado_tarjeta_red" class="form-select">
                                    @foreach(['Buen Funcionamiento', 'Operativo', 'Sin Funcionar'] as $est)
                                        <option value="{{ $est }}" {{ old('estado_tarjeta_red', $opcional->estado) == $est ? 'selected' : '' }}>{{ $est }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Tarjeta WiFi --}}
                    <div id="tarjeta_wifi_campos" class="component-section" style="display:none;">
                        <div class="component-header">
                            <div class="component-icon"><i class="fas fa-wifi"></i></div>
                            <div class="component-title">
                                <h4>Tarjeta WiFi</h4>
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Marca</label>
                                <input type="text" name="marca_tarjeta_wifi" class="form-input"
                                    value="{{ old('marca_tarjeta_wifi', $opcional->marca) }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Modelo</label>
                                <input type="text" name="modelo_tarjeta_wifi" class="form-input"
                                    value="{{ old('modelo_tarjeta_wifi', $opcional->modelo) }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Velocidad</label>
                                <input type="text" name="velocidad_wifi" class="form-input"
                                    value="{{ old('velocidad_wifi', $opcional->velocidad) }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Estado</label>
                                <select name="estado_tarjeta_wifi" class="form-select">
                                    @foreach(['Buen Funcionamiento', 'Operativo', 'Sin Funcionar'] as $est)
                                        <option value="{{ $est }}" {{ old('estado_tarjeta_wifi', $opcional->estado) == $est ? 'selected' : '' }}>{{ $est }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Tarjeta de Sonido --}}
                    <div id="tarjeta_de_sonido_campos" class="component-section" style="display:none;">
                        <div class="component-header">
                            <div class="component-icon"><i class="fas fa-music"></i></div>
                            <div class="component-title">
                                <h4>Tarjeta de Sonido</h4>
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Marca</label>
                                <input type="text" name="marca_tarjeta_sonido" class="form-input"
                                    value="{{ old('marca_tarjeta_sonido', $opcional->marca) }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Modelo</label>
                                <input type="text" name="modelo_tarjeta_sonido" class="form-input"
                                    value="{{ old('modelo_tarjeta_sonido', $opcional->modelo) }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Estado</label>
                                <select name="estado_tarjeta_sonido" class="form-select">
                                    @foreach(['Buen Funcionamiento', 'Operativo', 'Sin Funcionar'] as $est)
                                        <option value="{{ $est }}" {{ old('estado_tarjeta_sonido', $opcional->estado) == $est ? 'selected' : '' }}>{{ $est }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions mt-4">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Actualizar Componente Opcional
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <style>
        /* Red Theme Override */
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
    <script>const BASE_URL = '{{ url('/') }}';</script>
    <script src="{{ asset('js/componenteOpcional.js') }}"></script>
    <script src="{{ asset('js/componenteOpcional2.js') }}"></script>
@endsection