@extends('layouts.app')

@section('title', 'Editar Equipo')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/createagregarcomponente.css') }}">
    <style>
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
            grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
            gap: 1rem;
            background: rgba(102, 126, 234, 0.03);
            padding: 1.25rem;
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
                    <i class="fas fa-edit header-icon"></i>
                </div>
                <div class="header-text">
                    <h1>Editar Equipo</h1>
                    <p>Actualizar datos de la terminal - ID: {{ $equipo->id }}</p>
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

        <form method="POST" action="{{ route('equipos.update', $equipo) }}" class="premium-form">
            @csrf
            @method('PUT')

            <input type="hidden" name="id_equipo" value="{{ $equipo->id }}">

            <!-- Información Básica -->
            <div class="form-step active">
                <div class="step-header">
                    <div class="step-icon"><i class="fas fa-info-circle"></i></div>
                    <div class="step-title">
                        <h3>Información Básica</h3>
                        <p>Revisa la marca, modelo e identificadores</p>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-tag"></i> Marca</label>
                        <input type="text" name="marca" class="form-input" value="{{ old('marca', $equipo->marca) }}"
                            required>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-microchip"></i> Modelo</label>
                        <input type="text" name="modelo" class="form-input" value="{{ old('modelo', $equipo->modelo) }}"
                            required>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-barcode"></i> Serial</label>
                        <input type="text" name="serial" class="form-input" value="{{ old('serial', $equipo->serial) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-hashtag"></i> Número de Bien</label>
                        <input type="text" name="numero_bien" class="form-input"
                            value="{{ old('numero_bien', $equipo->numero_bien) }}">
                    </div>
                </div>
            </div>

            <!-- Software Instalado -->
            <div class="form-step active mt-4">
                <div class="step-header">
                    <div class="step-icon"><i class="fas fa-laptop-code"></i></div>
                    <div class="step-title">
                        <h3>Software Instalado</h3>
                        <p>Actualizar sistema operativo y aplicaciones</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label"><i class="fas fa-desktop"></i> Sistema Operativo</label>
                    <div class="form-grid">
                        <select name="software_nombre[SO]" class="form-select" required>
                            @foreach ($tiposSoftware['Sistema Operativo'] as $so)
                                <option value="{{ $so }}" {{ (old('software_nombre.SO') ?? ($softwareActual['SO']['nombre'] ?? '')) === $so ? 'selected' : '' }}>{{ $so }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="software_version[SO]" class="form-input" placeholder="Versión"
                            value="{{ old('software_version.SO', $softwareActual['SO']['version'] ?? '') }}">
                        <input type="text" name="software_bits[SO]" class="form-input" placeholder="Bits"
                            value="{{ old('software_bits.SO', $softwareActual['SO']['bits'] ?? '') }}">
                    </div>
                </div>

                <div class="form-group mt-3">
                    <label class="form-label"><i class="fas fa-file-alt"></i> Ofimática</label>
                    <div class="form-grid">
                        <select name="software_nombre_ofimatica" class="form-select" required>
                            @foreach ($tiposSoftware['Ofimática'] as $of)
                                <option value="{{ $of }}" {{ (old('software_nombre_ofimatica') ?? ($softwareActual['Ofimática']['nombre'] ?? '')) === $of ? 'selected' : '' }}>{{ $of }}
                                </option>
                            @endforeach
                        </select>
                        <input type="text" name="software_version_ofimatica" class="form-input" placeholder="Versión"
                            value="{{ old('software_version_ofimatica', $softwareActual['Ofimática']['version'] ?? '') }}">
                        <input type="text" name="software_bits_ofimatica" class="form-input" placeholder="Bits"
                            value="{{ old('software_bits_ofimatica', $softwareActual['Ofimática']['bits'] ?? '') }}">
                    </div>
                </div>

                <div class="form-group mt-3">
                    <label class="form-label"><i class="fas fa-globe"></i> Navegadores</label>
                    <div class="browser-checkboxes">
                        @foreach ($tiposSoftware['Navegador'] as $nav)
                            <label class="checkbox-item">
                                <input type="checkbox" name="software_navegadores[]" value="{{ $nav }}" {{ in_array($nav, old('software_navegadores', $softwareActual['Navegador'] ?? [])) ? 'checked' : '' }}>
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
                        <p>Gestionar asignación y estado de salud</p>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Gabinete</label>
                        <div class="form-grid">
                            <input type="text" name="tipo_gabinete" class="form-input" placeholder="Gabinete"
                                value="{{ old('tipo_gabinete', $equipo->tipo_gabinete) }}">
                            <select name="estado_gabinete" class="form-select">
                                @foreach (['Nuevo', 'Deteriorado', 'Dañado'] as $estado)
                                    <option value="{{ $estado }}" {{ old('estado_gabinete', $equipo->estado_gabinete) == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nivel del Equipo</label>
                        <select id="nivel-equipo" name="nivel_equipo" class="form-select">
                            <option value="">Seleccione nivel</option>
                            <option value="direccion" {{ $equipo->id_direccion && !$equipo->id_division && !$equipo->id_coordinacion ? 'selected' : '' }}>Dirección</option>
                            <option value="division" {{ $equipo->id_division && !$equipo->id_coordinacion ? 'selected' : '' }}>División</option>
                            <option value="coordinacion" {{ $equipo->id_coordinacion ? 'selected' : '' }}>Coordinación
                            </option>
                        </select>
                    </div>
                </div>

                <div class="form-grid mt-3">
                    <div class="form-group">
                        <label class="form-label">Estado Funcional</label>
                        <select name="estado_funcional" class="form-select">
                            @foreach (['Buen Funcionamiento', 'Operativo', 'Sin Funcionar'] as $estado)
                                <option value="{{ $estado }}" {{ old('estado_funcional', $equipo->estado_funcional) == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Estado Tecnológico</label>
                        <select name="estado_tecnologico" class="form-select">
                            @foreach (['Nuevo', 'Actualizable', 'Obsoleto'] as $estado)
                                <option value="{{ $estado }}" {{ old('estado_tecnologico', $equipo->estado_tecnologico) == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group nivel direccion">
                    <label class="form-label"><i class="fas fa-building"></i> Dirección</label>
                    <select name="id_direccion" id="direccion" class="form-select">
                        <option value="">Seleccione</option>
                        @foreach ($direcciones as $d)
                            <option value="{{ $d->id_direccion }}" {{ ($equipo->id_direccion == $d->id_direccion) ? 'selected' : '' }}>{{ $d->nombre_direccion }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group nivel division">
                    <label class="form-label"><i class="fas fa-landmark"></i> División</label>
                    <select name="id_division" id="division" class="form-select">
                        <option value="">Seleccione</option>
                        @foreach ($divisiones as $div)
                            <option value="{{ $div->id_division }}" {{ ($equipo->id_division == $div->id_division) ? 'selected' : '' }}>{{ $div->nombre_division }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group nivel coordinacion">
                    <label class="form-label"><i class="fas fa-users"></i> Coordinación</label>
                    <select name="id_coordinacion" id="coordinacion" class="form-select">
                        <option value="">Seleccione</option>
                        @foreach ($coordinaciones as $c)
                            <option value="{{ $c['id_coordinacion'] }}" {{ (isset($equipo) && $equipo->id_coordinacion == $c['id_coordinacion']) ? 'selected' : '' }}>
                                {{ $c['nombre_coordinacion'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-actions mt-4">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Actualizar Equipo
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