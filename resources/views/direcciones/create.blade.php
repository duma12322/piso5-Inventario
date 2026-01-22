@extends('layouts.app')

@section('title', 'Editar Dirección')

@section('content')
<!-- Importar estilos específicos del formulario -->
<link rel="stylesheet" href="{{ asset('css/createagregarcomponente.css') }}">

<!-- Fondo animado con formas flotantes -->
<div class="animated-background">
    <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
        <div class="shape shape-5"></div>
    </div>
</div>

<!-- Contenedor principal del formulario -->
<div class="component-form-container">

    <!-- Header del formulario -->
    <header class="form-header">
        <div class="header-content">
            <!-- Icono representativo del formulario -->
            <div class="header-icon-container">
                <i class="fas fa-edit header-icon"></i>
            </div>
            <!-- Título y descripción del formulario -->
            <div class="header-text">
                <h1>Editar Dirección</h1>
                <p>Modificar información de la dirección: {{ $direccion->nombre_direccion }}</p>
            </div>
        </div>
        <!-- Botón para volver a la lista de direcciones -->
        <div class="header-actions">
            <a href="{{ route('direcciones.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </header>

    <!-- Mostrar errores de validación -->
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

    <!-- Formulario para actualizar la dirección -->
    <form method="POST" action="{{ route('direcciones.update', $direccion->id_direccion) }}" class="premium-form">
        @csrf
        @method('PUT')

        <div class="form-step active">
            <!-- Encabezado del paso del formulario -->
            <div class="step-header">
                <div class="step-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div class="step-title">
                    <h3>Información General</h3>
                    <p>Actualizar detalles</p>
                </div>
            </div>

            <!-- Campo para el nombre de la dirección -->
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-signature"></i> Nombre de la Dirección
                    </label>
                    <input type="text" name="nombre_direccion" class="form-input"
                        placeholder="Ej. Dirección de Tecnología" required
                        value="{{ old('nombre_direccion', $direccion->nombre_direccion) }}">
                </div>
            </div>

            <!-- Campo opcional para descripción -->
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-align-left"></i> Descripción (Opcional)
                </label>
                <textarea name="descripcion" class="form-textarea"
                    placeholder="Breve descripción de la dirección">{{ old('descripcion', $direccion->descripcion ?? '') }}</textarea>
            </div>

            <!-- Botón de acción para actualizar -->
            <div class="form-actions mt-4">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Actualizar Dirección
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Estilos específicos para este formulario -->
<style>
    /* ===============================
           Variables de color y gradiente
        ================================*/
    :root {
        --primary: #da0606;
        --primary-light: #ff4d4d;
        --gradient-primary: linear-gradient(135deg, #da0606 0%, #b70909 100%);
    }

    /* Iconos del header */
    .header-icon {
        color: var(--primary);
    }

    /* Iconos de paso del formulario */
    .step-icon {
        background: var(--gradient-primary);
    }

    /* Etiquetas de los campos */
    .form-label {
        border-left-color: var(--primary);
    }

    .form-label i {
        color: var(--primary);
    }

    /* Campos al enfocarse */
    .form-input:focus,
    .form-textarea:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(218, 6, 6, 0.1);
    }

    /* Botones */
    .btn-back {
        background: var(--gradient-primary);
    }

    .btn-submit {
        background: var(--gradient-primary);
    }

    /* Margen superior del formulario */
    .premium-form {
        margin-top: 1rem;
    }

    /* Estilo detallado del botón submit */
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

    /* Hover efecto */
    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-xl);
        filter: brightness(1.1);
    }

    /* Animación de aparición de alertas */
    .alert-container {
        animation: fadeIn 0.5s ease-out;
    }

    /* Ajustes responsive para dispositivos móviles */
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