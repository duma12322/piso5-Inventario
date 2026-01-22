@extends('layouts.app')

@section('title', 'Editar División') {{-- Título de la página --}}

@section('content')
{{-- ============================================================
         CSS específico para formularios de creación/edición
         ============================================================ --}}
<link rel="stylesheet" href="{{ asset('css/createagregarcomponente.css') }}">

{{-- ============================================================
         Fondo animado con formas flotantes decorativas
         ============================================================ --}}
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
    {{-- ============================================================
             Header del formulario con icono y título
             ============================================================ --}}
    <header class="form-header">
        <div class="header-content">
            <div class="header-icon-container">
                <i class="fas fa-edit header-icon"></i> {{-- Icono del formulario --}}
            </div>
            <div class="header-text">
                <h1>Editar División</h1> {{-- Título principal --}}
                <p>Modificar información de la división</p> {{-- Descripción --}}
            </div>
        </div>
        <div class="header-actions">
            {{-- Botón para regresar al listado de divisiones --}}
            <a href="{{ route('divisiones.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </header>

    {{-- ============================================================
             Alertas de validación si existen errores
             ============================================================ --}}
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

    {{-- ============================================================
             Formulario de edición de la división
             ============================================================ --}}
    <form method="POST" action="{{ route('divisiones.update', $division->id_division) }}" class="premium-form">
        @csrf
        @method('PUT')

        <div class="form-step active">
            {{-- Encabezado del paso del formulario --}}
            <div class="step-header">
                <div class="step-icon">
                    <i class="fas fa-info-circle"></i> {{-- Icono de información --}}
                </div>
                <div class="step-title">
                    <h3>Información General</h3> {{-- Subtítulo del paso --}}
                    <p>Actualizar detalles</p> {{-- Descripción breve --}}
                </div>
            </div>

            {{-- Grid del formulario --}}
            <div class="form-grid">
                {{-- Selección de la dirección asociada --}}
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-building"></i> Dirección
                    </label>
                    <select name="id_direccion" class="form-select" required>
                        <option value="">Seleccione</option>
                        @foreach ($direcciones as $direccion)
                        <option value="{{ $direccion->id_direccion }}" {{ $direccion->id_direccion == $division->id_direccion ? 'selected' : '' }}>
                            {{ $direccion->nombre_direccion }} {{-- Nombre de la dirección --}}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Campo de texto para el nombre de la división --}}
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-signature"></i> Nombre de la División
                </label>
                <input type="text" name="nombre_division" class="form-input" placeholder="Ej. División de Soporte"
                    required value="{{ old('nombre_division', $division->nombre_division) }}">
            </div>

            {{-- Botón de acción del formulario --}}
            <div class="form-actions mt-4">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Actualizar División
                </button>
            </div>
        </div>
    </form>
</div>

<style>
    /* ============================================================
       Variables CSS globales para colores y gradientes
       Se usa rojo para mantener coherencia con el tema principal
       ============================================================ */
    :root {
        --primary: #da0606;
        /* Color principal */
        --primary-light: #ff4d4d;
        /* Color secundario más claro */
        --gradient-primary: linear-gradient(135deg, #da0606 0%, #b70909 100%);
        /* Gradiente principal */
    }

    /* ============================================================
       Estilos del icono del header
       ============================================================ */
    .header-icon {
        color: var(--primary);
        /* Color rojo definido en variables */
    }

    /* ============================================================
       Estilos del icono de cada paso del formulario
       ============================================================ */
    .step-icon {
        background: var(--gradient-primary);
        /* Fondo en gradiente */
    }

    /* ============================================================
       Etiquetas de los campos del formulario
       ============================================================ */
    .form-label {
        border-left-color: var(--primary);
        /* Línea decorativa a la izquierda */
    }

    .form-label i {
        color: var(--primary);
        /* Icono de la etiqueta en rojo */
    }

    /* ============================================================
       Efectos de foco para inputs, textareas y selects
       ============================================================ */
    .form-input:focus,
    .form-textarea:focus,
    .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(218, 6, 6, 0.1);
        /* Resalta el foco con sombra roja */
    }

    /* ============================================================
       Botón de volver y botón de submit
       ============================================================ */
    .btn-back {
        background: var(--gradient-primary);
    }

    .btn-submit {
        background: var(--gradient-primary);
    }

    /* ============================================================
       Formulario Premium: margen superior y estilos de submit
       ============================================================ */
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
        /* Aumenta brillo al pasar el mouse */
    }

    /* ============================================================
       Contenedor de alertas: animación de aparición
       ============================================================ */
    .alert-container {
        animation: fadeIn 0.5s ease-out;
    }

    /* ============================================================
       Adaptaciones responsive para pantallas pequeñas (≤768px)
       ============================================================ */
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