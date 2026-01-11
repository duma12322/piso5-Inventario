@extends('layouts.app')

@section('title', 'Agregar Componente Opcional')

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
                    <i class="fas fa-plus-circle header-icon"></i>
                </div>
                <div class="header-text">
                    <h1>Agregar Componente Opcional</h1>
                    <p>Hardware adicional para expandir capacidades</p>
                </div>
            </div>
            <div class="header-actions">
                @if(!empty($id_equipo))
                    <a href="{{ route('componentes.porEquipo', $id_equipo) }}" class="btn-back">
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

        <form method="POST" action="{{ route('componentesOpcionales.store') }}" class="premium-form">
            @csrf
            @if(isset($porEquipo) && $porEquipo)
                <input type="hidden" name="porEquipo" value="1">
                <input type="hidden" name="id_equipo" value="{{ $equipoSeleccionado->id_equipo }}">
            @else
                <input type="hidden" name="porEquipo" value="0">
            @endif

            <div class="form-step active">
                <div class="step-header">
                    <div class="step-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="step-title">
                        <h3>Información General</h3>
                        <p>Selecciona el equipo y el tipo de componente opcional</p>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-laptop"></i> Equipo</label>
                        @if(isset($porEquipo) && $porEquipo)
                            <div class="selected-equipo">
                                <div class="equipo-icon"><i class="fas fa-desktop"></i></div>
                                <div class="equipo-info">
                                    <span class="equipo-name">{{ $equipoSeleccionado->marca }}
                                        {{ $equipoSeleccionado->modelo }}</span>
                                    <span class="equipo-status">Equipo Seleccionado</span>
                                </div>
                            </div>
                        @else
                            <select name="id_equipo" class="form-select" required>
                                <option value="">Seleccione un equipo</option>
                                @foreach ($equipos as $e)
                                    <option value="{{ $e->id_equipo }}" {{ old('id_equipo') == $e->id_equipo ? 'selected' : '' }}>
                                        {{ $e->marca }} {{ $e->modelo }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-tags"></i> Tipo de Componente Opcional</label>
                        <select id="tipo_opcional" name="tipo_opcional" class="form-select" required>
                            <option value="">Seleccione un tipo</option>
                            @foreach(['Memoria Ram', 'Disco Duro', 'Fan Cooler', 'Tarjeta Grafica', 'Tarjeta de Red', 'Tarjeta WiFi', 'Tarjeta de Sonido'] as $tipo)
                                <option value="{{ $tipo }}" {{ old('tipo_opcional') == $tipo ? 'selected' : '' }}>{{ $tipo }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="component-sections">
                    {{-- Memoria RAM --}}
                    <div id="memoria_ram_campos" class="component-section" style="display:none;">
                        <div class="component-header">
                            <div class="component-icon"><i class="fas fa-memory"></i></div>
                            <div class="component-title">
                                <h4>Memoria RAM Extra</h4>
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Marca</label>
                                <input type="text" name="marca_ram" class="form-input" placeholder="Ej: Corsair, Kingston"
                                    value="{{ old('marca_ram') }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Tipo</label>
                                <input type="text" name="tipo_ram" class="form-input" placeholder="Ej: DDR4, DDR5"
                                    value="{{ old('tipo_ram') }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Capacidad</label>
                                <input type="text" name="capacidad_ram" class="form-input" placeholder="Ej: 8GB, 16GB"
                                    value="{{ old('capacidad_ram') }}">
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Frecuencia</label>
                                <input type="text" name="frecuencia_ram" class="form-input" placeholder="Ej: 3200MHz"
                                    value="{{ old('frecuencia_ram') }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Slot RAM</label>
                                <input type="text" name="slot_memoria" class="form-input" placeholder="Ej: Slot 2"
                                    value="{{ old('slot_memoria') }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Estado</label>
                                <select name="estado_ram" class="form-select">
                                    @foreach(['Buen Funcionamiento', 'Operativo', 'Sin Funcionar'] as $est)
                                        <option value="{{ $est }}">{{ $est }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Detalles adicionales</label>
                            <textarea name="detalles_ram" class="form-textarea"></textarea>
                        </div>
                    </div>

                    {{-- Disco Duro --}}
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
                                <input type="text" name="marca_disco" class="form-input" value="{{ old('marca_disco') }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Tipo</label>
                                <select name="tipo_disco" class="form-select">
                                    <option value="HDD">HDD</option>
                                    <option value="SSD">SSD</option>
                                    <option value="NVMe">NVMe</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Capacidad</label>
                                <input type="text" name="capacidad_disco" class="form-input"
                                    value="{{ old('capacidad_disco') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Estado</label>
                            <select name="estado_disco" class="form-select">
                                @foreach(['Buen Funcionamiento', 'Operativo', 'Sin Funcionar'] as $est)
                                    <option value="{{ $est }}">{{ $est }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Tarjeta Grafica --}}
                    <div id="tarjeta_grafica_campos" class="component-section" style="display:none;">
                        <div class="component-header">
                            <div class="component-icon"><i class="fas fa-video"></i></div>
                            <div class="component-title">
                                <h4>Tarjeta Gráfica Dedicada</h4>
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Marca</label>
                                <input type="text" name="marca_tarjeta_grafica" class="form-input"
                                    value="{{ old('marca_tarjeta_grafica') }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Modelo</label>
                                <input type="text" name="modelo_tarjeta_grafica" class="form-input"
                                    value="{{ old('modelo_tarjeta_grafica') }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">VRAM</label>
                                <input type="text" name="vrm" class="form-input" value="{{ old('vrm') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Add the rest of sections as needed following this pattern --}}
                </div>

                <div class="form-actions mt-4">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Guardar Componente Opcional
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
    <script src="{{ asset('js/componenteOpcional.js') }}"></script>
@endsection