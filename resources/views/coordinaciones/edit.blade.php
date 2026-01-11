@extends('layouts.app')

@section('title', 'Editar Coordinación')

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
                    <h1>Editar Coordinación</h1>
                    <p>Modificar información de la coordinación</p>
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ route('coordinaciones.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </header>

        @if($errors->any())
            <div class="alert-container mb-4">
                @foreach($errors->all() as $error)
                    <div class="alert alert-danger fade show" role="alert"
                        style="border-radius: var(--radius-md); box-shadow: var(--shadow-sm);">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ $error }}
                    </div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('coordinaciones.update', $coordinacion->id_coordinacion) }}"
            class="premium-form">
            @csrf
            @method('PUT')

            <div class="form-step active">
                <div class="step-header">
                    <div class="step-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="step-title">
                        <h3>Información General</h3>
                        <p>Actualizar detalles</p>
                    </div>
                </div>

                <div class="form-grid">
                    <!-- Direccion (Filter) -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-building"></i> Dirección
                        </label>
                        <select id="direccion" name="id_direccion" class="form-select" required>
                            <option value="">Seleccione</option>
                            @foreach ($direcciones as $d)
                                <option value="{{ $d->id_direccion }}" {{ $d->id_direccion == ($coordinacion->division->id_direccion ?? '') ? 'selected' : '' }}>
                                    {{ $d->nombre_direccion }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Division (Target) -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-sitemap"></i> División
                        </label>
                        <select id="division" name="id_division" class="form-select" required>
                            <option value="">Seleccione</option>
                            @foreach ($divisiones as $div)
                                <option value="{{ $div->id_division }}" {{ $div->id_division == $coordinacion->id_division ? 'selected' : '' }}>
                                    {{ $div->nombre_division }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-signature"></i> Nombre de la Coordinación
                    </label>
                    <input type="text" name="nombre_coordinacion" class="form-input" placeholder="Ej. Coordinación de Redes"
                        required value="{{ old('nombre_coordinacion', $coordinacion->nombre_coordinacion) }}">
                </div>

                <div class="form-actions mt-4">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Actualizar Coordinación
                    </button>
                </div>
            </div>
        </form>
    </div>

    <style>
        /* Override gradient to RED to match common theme */
        :root {
            --primary: #da0606;
            --primary-light: #ff4d4d;
            --gradient-primary: linear-gradient(135deg, #da0606 0%, #b70909 100%);
        }

        .header-icon {
            color: var(--primary);
        }

        .step-icon {
            background: var(--gradient-primary);
        }

        .form-label {
            border-left-color: var(--primary);
        }

        .form-label i {
            color: var(--primary);
        }

        .form-input:focus,
        .form-textarea:focus,
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(218, 6, 6, 0.1);
        }

        .btn-back {
            background: var(--gradient-primary);
        }

        .btn-submit {
            background: var(--gradient-primary);
        }

        /* Premium Form overrides */
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
@endsection

@section('scripts')
    <script src="{{ asset('js/coordinacion.js') }}"></script>
@endsection