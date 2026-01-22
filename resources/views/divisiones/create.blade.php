@extends('layouts.app')

@section('title', 'Agregar División')

@section('content')
<!-- Enlace al CSS específico para formularios de componentes/divisiones -->
<link rel="stylesheet" href="{{ asset('css/createagregarcomponente.css') }}">

<!-- ============================================================
         Fondo animado con formas flotantes para efecto visual
         ============================================================ -->
<div class="animated-background">
    <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
        <div class="shape shape-5"></div>
    </div>
</div>

<!-- ============================================================
         Contenedor principal del formulario
         ============================================================ -->
<div class="component-form-container">
    <!-- Form Header -->
    <header class="form-header">
        <div class="header-content">
            <div class="header-icon-container">
                <i class="fas fa-sitemap header-icon"></i> <!-- Icono decorativo -->
            </div>
            <div class="header-text">
                <h1>Agregar División</h1> <!-- Título principal -->
                <p>Registra una nueva división en el sistema</p> <!-- Subtítulo descriptivo -->
            </div>
        </div>
        <div class="header-actions">
            <!-- Botón de regreso a la lista de divisiones -->
            <a href="{{ route('divisiones.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </header>

    <!-- ============================================================
             Mostrar errores de validación si existen
             ============================================================ -->
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

    <!-- ============================================================
             Formulario principal para agregar división
             ============================================================ -->
    <form method="POST" action="{{ route('divisiones.store') }}" class="premium-form">
        @csrf <!-- Token CSRF para seguridad -->

        <!-- Paso del formulario: Información General -->
        <div class="form-step active">
            <div class="step-header">
                <div class="step-icon">
                    <i class="fas fa-info-circle"></i> <!-- Icono del paso -->
                </div>
                <div class="step-title">
                    <h3>Información General</h3> <!-- Subtítulo del paso -->
                    <p>Detalles de la división</p> <!-- Descripción del paso -->
                </div>
            </div>

            <!-- Grid del formulario -->
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-building"></i> Dirección <!-- Icono y texto de label -->
                    </label>
                    <!-- Select para elegir la Dirección asociada -->
                    <select name="id_direccion" class="form-select" required>
                        <option value="">Seleccione una Dirección</option>
                        @foreach ($direcciones as $direccion)
                        <option value="{{ $direccion->id_direccion }}">{{ $direccion->nombre_direccion }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Campo para el nombre de la división -->
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-signature"></i> Nombre de la División
                </label>
                <input type="text" name="nombre_division" class="form-input" placeholder="Ej. División de Soporte"
                    required value="{{ old('nombre_division') }}">
            </div>

            <!-- Botón de envío -->
            <div class="form-actions mt-4">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Guardar División
                </button>
            </div>
        </div>
    </form>
</div>

<style>
    /* ============================================================
       Variables de tema principal (gradientes y colores)
       ============================================================ */
    :root {
        --primary: #da0606;
        /* Color principal rojo */
        --primary-light: #ff4d4d;
        /* Versión más clara del rojo */
        --gradient-primary: linear-gradient(135deg, #da0606 0%, #b70909 100%);
        /* Gradiente rojo */
    }

    /* Iconos de cabecera del formulario */
    .header-icon {
        color: var(--primary);
        /* Color rojo principal */
    }

    /* Icono de cada paso del formulario */
    .step-icon {
        background: var(--gradient-primary);
        /* Fondo con gradiente rojo */
    }

    /* Etiquetas de los campos del formulario */
    .form-label {
        border-left-color: var(--primary);
        /* Línea lateral roja */
    }

    /* Iconos dentro de las etiquetas de formulario */
    .form-label i {
        color: var(--primary);
        /* Color rojo */
    }

    /* Focus de inputs, selects y textareas */
    .form-input:focus,
    .form-textarea:focus,
    .form-select:focus {
        border-color: var(--primary);
        /* Borde rojo al enfocar */
        box-shadow: 0 0 0 4px rgba(218, 6, 6, 0.1);
        /* Sombra suave roja */
    }

    /* Botón de regresar */
    .btn-back {
        background: var(--gradient-primary);
        /* Gradiente rojo */
    }

    /* Botón de enviar */
    .btn-submit {
        background: var(--gradient-primary);
        /* Gradiente rojo */
    }

    /* ============================================================
       Premium Form overrides
       ============================================================ */
    .premium-form {
        margin-top: 1rem;
        /* Separación superior del formulario */
    }

    /* Estilo completo del botón submit */
    .btn-submit {
        width: 100%;
        /* Botón ocupa todo el ancho */
        padding: 1.25rem;
        /* Espaciado interno */
        background: var(--gradient-primary);
        /* Gradiente rojo */
        color: white;
        /* Texto blanco */
        border: none;
        /* Sin borde */
        border-radius: var(--radius-lg);
        /* Esquinas redondeadas grandes */
        font-size: 1.2rem;
        /* Tamaño de fuente */
        font-weight: 700;
        /* Negrita */
        cursor: pointer;
        /* Cursor pointer al pasar encima */
        transition: var(--transition);
        /* Transición suave */
        box-shadow: var(--shadow-primary);
        /* Sombra primaria */
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        /* Espacio entre icono y texto */
    }

    /* Hover del botón submit */
    .btn-submit:hover {
        transform: translateY(-3px);
        /* Efecto levitar */
        box-shadow: var(--shadow-xl);
        /* Sombra más grande */
        filter: brightness(1.1);
        /* Ligero brillo */
    }

    /* Contenedor de alertas */
    .alert-container {
        animation: fadeIn 0.5s ease-out;
        /* Animación de aparición suave */
    }

    /* ============================================================
       Responsivo: para pantallas menores a 768px
       ============================================================ */
    @media (max-width: 768px) {
        .form-header {
            height: auto;
            /* Ajusta altura automática */
            flex-direction: column;
            /* Coloca elementos en columna */
            padding: 1.5rem;
            /* Padding interno */
            text-align: center;
            /* Centra texto */
        }

        .header-content {
            flex-direction: column;
            /* Contenido en columna */
        }

        .header-icon-container {
            width: 60px;
            /* Ancho reducido */
            height: 60px;
            /* Alto reducido */
        }

        .header-icon {
            font-size: 2.5rem;
            /* Icono más grande para móviles */
        }

        .header-text h1 {
            font-size: 1.8rem;
            /* Ajuste del tamaño del título */
        }
    }
</style>
@endsection