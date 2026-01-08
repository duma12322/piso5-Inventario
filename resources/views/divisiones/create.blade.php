@extends('layouts.app')

@section('title', 'Agregar División')

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
                    <i class="fas fa-sitemap header-icon"></i>
                </div>
                <div class="header-text">
                    <h1>Agregar División</h1>
                    <p>Registra una nueva división en el sistema</p>
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ route('divisiones.index') }}" class="btn-back">
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

        <form method="POST" action="{{ route('divisiones.store') }}" class="premium-form">
            @csrf

            <div class="form-step active">
                <div class="step-header">
                    <div class="step-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="step-title">
                        <h3>Información General</h3>
                        <p>Detalles de la división</p>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-building"></i> Dirección
                        </label>
                        <select name="id_direccion" class="form-select" required>
                            <option value="">Seleccione una Dirección</option>
                            @foreach ($direcciones as $direccion)
                                <option value="{{ $direccion->id_direccion }}">{{ $direccion->nombre_direccion }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-signature"></i> Nombre de la División
                    </label>
                    <input type="text" name="nombre_division" class="form-input" placeholder="Ej. División de Soporte"
                        required value="{{ old('nombre_division') }}">
                </div>

                <div class="form-actions mt-4">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Guardar División
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