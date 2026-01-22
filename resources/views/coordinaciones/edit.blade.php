@extends('layouts.app')

{{-- Título de la página --}}
@section('title', 'Editar Coordinación')

@section('content')
{{-- Importar estilos específicos para el formulario de edición --}}
<link rel="stylesheet" href="{{ asset('css/createagregarcomponente.css') }}">

{{-- Fondo animado con formas flotantes --}}
<div class="animated-background">
    <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
        <div class="shape shape-5"></div>
    </div>
</div>

{{-- Contenedor del formulario --}}
<div class="component-form-container">

    {{-- Encabezado del formulario --}}
    <header class="form-header">
        <div class="header-content">

            {{-- Icono del header --}}
            <div class="header-icon-container">
                <i class="fas fa-edit header-icon"></i>
            </div>

            {{-- Texto del header --}}
            <div class="header-text">
                <h1>Editar Coordinación</h1>
                <p>Modificar información de la coordinación</p>
            </div>
        </div>

        {{-- Botón de regreso a la lista de coordinaciones --}}
        <div class="header-actions">
            <a href="{{ route('coordinaciones.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </header>

    {{-- Mostrar errores de validación --}}
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

    {{-- Formulario de edición --}}
    <form method="POST" action="{{ route('coordinaciones.update', $coordinacion->id_coordinacion) }}"
        class="premium-form">
        @csrf
        @method('PUT')

        <div class="form-step active">
            {{-- Encabezado del paso del formulario --}}
            <div class="step-header">
                <div class="step-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div class="step-title">
                    <h3>Información General</h3>
                    <p>Actualizar detalles</p>
                </div>
            </div>

            {{-- Grid del formulario para campos relacionados --}}
            <div class="form-grid">
                {{-- Dirección (Filtro) --}}
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

                {{-- División (Destino) --}}
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

            {{-- Nombre de la Coordinación --}}
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-signature"></i> Nombre de la Coordinación
                </label>
                <input type="text" name="nombre_coordinacion" class="form-input" placeholder="Ej. Coordinación de Redes"
                    required value="{{ old('nombre_coordinacion', $coordinacion->nombre_coordinacion) }}">
            </div>

            {{-- Botón de acción para actualizar --}}
            <div class="form-actions mt-4">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Actualizar Coordinación
                </button>
            </div>
        </div>
    </form>
</div>

<style>
    /* -------------------------
       Variables de tema
       -------------------------
       Se redefinen los colores primarios y gradientes para que coincidan
       con el tema rojo común de la aplicación.
    ------------------------- */
    :root {
        --primary: #da0606;
        /* Color principal */
        --primary-light: #ff4d4d;
        /* Color secundario / light */
        --gradient-primary: linear-gradient(135deg, #da0606 0%, #b70909 100%);
        /* Gradiente principal */
    }

    /* -------------------------
       Iconos y elementos de cabecera
    ------------------------- */
    .header-icon {
        color: var(--primary);
        /* Icono del header en color principal */
    }

    .step-icon {
        background: var(--gradient-primary);
        /* Iconos de paso con gradiente */
    }

    /* -------------------------
       Labels del formulario
    ------------------------- */
    .form-label {
        border-left-color: var(--primary);
        /* Borde izquierdo del label */
    }

    .form-label i {
        color: var(--primary);
        /* Icono dentro del label */
    }

    /* -------------------------
       Inputs, textarea y selects
       -------------------------
       Estilos al enfocar para resaltar el campo activo
    ------------------------- */
    .form-input:focus,
    .form-textarea:focus,
    .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(218, 6, 6, 0.1);
    }

    /* -------------------------
       Botones
    ------------------------- */
    .btn-back {
        background: var(--gradient-primary);
        /* Botón volver */
    }

    .btn-submit {
        background: var(--gradient-primary);
        /* Botón enviar */
    }

    /* -------------------------
       Premium Form Overrides
    ------------------------- */
    .premium-form {
        margin-top: 1rem;
        /* Separación superior del formulario */
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
        /* Espaciado entre icono y texto */
    }

    .btn-submit:hover {
        transform: translateY(-3px);
        /* Efecto de elevación */
        box-shadow: var(--shadow-xl);
        /* Sombra más intensa */
        filter: brightness(1.1);
        /* Ligero brillo al pasar el mouse */
    }

    /* -------------------------
       Alertas de validación
    ------------------------- */
    .alert-container {
        animation: fadeIn 0.5s ease-out;
        /* Animación al aparecer */
    }

    /* -------------------------
       Responsive (Pantallas pequeñas)
    ------------------------- */
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

{{-- -------------------------
     Scripts específicos
     -------------------------
     Se carga el JS para la lógica de coordinación
------------------------- --}}
@section('scripts')
<script src="{{ asset('js/coordinacion.js') }}"></script>
@endsection