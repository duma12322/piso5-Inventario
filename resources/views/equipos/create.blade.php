@extends('layouts.app')

@section('title', 'Agregar Nuevo Equipo')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/createagregarcomponente.css') }}">
    <style>
        /* Specific adjustments for Equipos form */
        :root {
            --primary: #da0606;
            --primary-light: #ff4d4d;
            --gradient-primary: linear-gradient(135deg, #da0606 0%, #b70909 100%);
        }

        .nivel {
            display: none;
            animation: fadeIn 0.4s ease;
        }

        .nivel.show {
            display: block;
        }

        .browser-checkboxes {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 1rem;
            background: rgba(102, 126, 234, 0.03);
            padding: 1rem;
            border-radius: var(--radius-md);
        }
    </style>

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
        <div id="app-data" data-direcciones='@json($direcciones)' data-divisiones='@json($divisiones)'
            data-coordinaciones='@json($coordinaciones)' data-tipos-software='@json($tiposSoftware)'
            data-software-actual='@json($softwareActual)'>
        </div>

        <!-- Form Header -->
        <header class="form-header">
            <div class="header-content">
                <div class="header-icon-container">
                    <i class="fas fa-desktop header-icon"></i>
                </div>
                <div class="header-text">
                    <h1>Agregar Nuevo Equipo</h1>
                    <p>Registra una nueva estación de trabajo en el sistema</p>
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ route('equipos.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
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

        <form method="POST" action="{{ route('equipos.store') }}" class="premium-form">
            @csrf

            <!-- Información Básica -->
            <div class="form-step active">
                <div class="step-header">
                    <div class="step-icon"><i class="fas fa-info-circle"></i></div>
                    <div class="step-title">
                        <h3>Información Básica</h3>
                        <p>Datos principales del equipo</p>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-tag"></i> Marca <span
                                class="text-danger">*</span></label>
                        <input type="text" name="marca" class="form-input" value="{{ old('marca') }}" required
                            placeholder="Ej: HP, Dell, Lenovo">
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-microchip"></i> Modelo <span
                                class="text-danger">*</span></label>
                        <input type="text" name="modelo" class="form-input" value="{{ old('modelo') }}" required
                            placeholder="Ej: Pavilion, Latitude">
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-barcode"></i> Serial</label>
                        <input type="text" name="serial" class="form-input" value="{{ old('serial') }}"
                            placeholder="S/N del equipo">
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-hashtag"></i> Número de Bien</label>
                        <input type="text" name="numero_bien" class="form-input" value="{{ old('numero_bien') }}"
                            placeholder="Identificador interno">
                    </div>
                </div>
            </div>

            <!-- Software Instalado -->
            <div class="form-step active mt-4">
                <div class="step-header">
                    <div class="step-icon"><i class="fas fa-laptop-code"></i></div>
                    <div class="step-title">
                        <h3>Software Instalado</h3>
                        <p>Configuración del sistema y aplicaciones</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label"><i class="fas fa-desktop"></i> Sistema Operativo <span
                            class="text-danger">*</span></label>
                    <div class="form-grid">
                        <select name="software_nombre[SO]" class="form-select" required>
                            <option value="">Seleccionar SO...</option>
                            @foreach ($tiposSoftware['Sistema Operativo'] as $so)
                                <option value="{{ $so }}" {{ (old('software_nombre.SO') ?? ($softwareActual['SO']['nombre'] ?? '')) === $so ? 'selected' : '' }}>{{ $so }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="software_version[SO]" class="form-input" placeholder="Versión (Ej: 22H2)"
                            value="{{ old('software_version.SO') }}">
                        <input type="text" name="software_bits[SO]" class="form-input" placeholder="Bits (Ej: 64)"
                            value="{{ old('software_bits.SO') }}">
                    </div>
                </div>

                <div class="form-group mt-3">
                    <label class="form-label"><i class="fas fa-file-alt"></i> Ofimática <span
                            class="text-danger">*</span></label>
                    <div class="form-grid">
                        <select name="software_nombre_ofimatica" class="form-select" required>
                            <option value="">Seleccionar Ofimática...</option>
                            @foreach ($tiposSoftware['Ofimática'] as $of)
                                <option value="{{ $of }}" {{ (old('software_nombre_ofimatica') ?? ($softwareActual['Ofimática']['nombre'] ?? '')) === $of ? 'selected' : '' }}>{{ $of }}
                                </option>
                            @endforeach
                        </select>
                        <input type="text" name="software_version_ofimatica" class="form-input" placeholder="Versión"
                            value="{{ old('software_version_ofimatica') }}">
                        <input type="text" name="software_bits_ofimatica" class="form-input" placeholder="Bits"
                            value="{{ old('software_bits_ofimatica') }}">
                    </div>
                </div>

                <div class="form-group mt-3">
                    <label class="form-label"><i class="fas fa-globe"></i> Navegadores</label>
                    <div class="browser-checkboxes">
                        @foreach ($tiposSoftware['Navegador'] as $nav)
                            <label class="checkbox-item">
                                <input type="checkbox" name="software_navegadores[]" value="{{ $nav }}" {{ in_array($nav, old('software_navegadores', [])) ? 'checked' : '' }}>
                                <span>{{ $nav }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Ubicación y Estado -->
            <div class="form-step active mt-4">
                <div class="step-header">
                    <div class="step-icon"><i class="fas fa-sitemap"></i></div>
                    <div class="step-title">
                        <h3>Ubicación y Estado</h3>
                        <p>Asignación organizativa y estado actual</p>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Nivel del Equipo</label>
                        <select id="nivel-equipo" name="nivel_equipo" class="form-select">
                            <option value="">Seleccione nivel</option>
                            <option value="direccion">Dirección</option>
                            <option value="division">División</option>
                            <option value="coordinacion">Coordinación</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Estado Funcional</label>
                        <select name="estado_funcional" class="form-select">
                            <option value="">Seleccionar...</option>
                            @foreach (['Buen Funcionamiento', 'Operativo', 'Sin Funcionar'] as $estado)
                                <option value="{{ $estado }}" {{ old('estado_funcional') == $estado ? 'selected' : '' }}>
                                    {{ $estado }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="nivel direccion">
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-building"></i> Dirección</label>
                        <select name="id_direccion" id="direccion" class="form-select">
                            <option value="">Seleccione</option>
                            @foreach ($direcciones as $d)
                                <option value="{{ $d->id_direccion }}">{{ $d->nombre_direccion }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="nivel division">
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-landmark"></i> División</label>
                        <select name="id_division" id="division" class="form-select">
                            <option value="">Seleccione</option>
                            @foreach ($divisiones as $div)
                                <option value="{{ $div->id_division }}">{{ $div->nombre_division }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="nivel coordinacion">
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-users"></i> Coordinación</label>
                        <select name="id_coordinacion" id="coordinacion" class="form-select">
                            <option value="">Seleccione</option>
                            @foreach ($coordinaciones as $c)
                                <option value="{{ $c['id_coordinacion'] }}">{{ $c['nombre_coordinacion'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-actions mt-4">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Guardar Equipo
                </button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <style>
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
    </style>
    <script src="{{ asset('js/equipos.js') }}"></script>
@endsection