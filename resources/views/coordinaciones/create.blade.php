@extends('layouts.app')

@section('title', 'Agregar Coordinación')

@section('content')
<!-- -------------------------
         Estilo específico para el formulario
         -------------------------
         Se carga CSS compartido con la página de editar/agregar
    ------------------------- -->
<link rel="stylesheet" href="{{ asset('css/createagregarcomponente.css') }}">

<!-- -------------------------
         Fondo animado con figuras flotantes
         -------------------------
         Solo visual, no afecta funcionalidad del formulario
    ------------------------- -->
<div class="animated-background">
    <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
        <div class="shape shape-5"></div>
    </div>
</div>

<!-- -------------------------
         Contenedor principal del formulario
         ------------------------- -->
<div class="component-form-container">
    <!-- -------------------------
             Cabecera del formulario
             -------------------------
             Icono + título + descripción + botón de regreso
        ------------------------- -->
    <header class="form-header">
        <div class="header-content">
            <div class="header-icon-container">
                <i class="fas fa-network-wired header-icon"></i> <!-- Icono principal -->
            </div>
            <div class="header-text">
                <h1>Agregar Coordinación</h1> <!-- Título de la página -->
                <p>Registra una nueva coordinación en el sistema</p> <!-- Subtítulo -->
            </div>
        </div>
        <div class="header-actions">
            <!-- Botón de regresar al listado -->
            <a href="{{ route('coordinaciones.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </header>

    <!-- -------------------------
             Mensajes de error (validación)
             -------------------------
             Se muestran solo si hay errores de validación
        ------------------------- -->
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

    <!-- -------------------------
             Formulario principal
             -------------------------
             Método POST a la ruta store de Coordinaciones
        ------------------------- -->
    <form method="POST" action="{{ route('coordinaciones.store') }}" class="premium-form">
        @csrf <!-- Token CSRF para seguridad -->

        <div class="form-step active">
            <!-- -------------------------
                     Encabezado del paso del formulario
                     ------------------------- -->
            <div class="step-header">
                <div class="step-icon">
                    <i class="fas fa-info-circle"></i> <!-- Icono del paso -->
                </div>
                <div class="step-title">
                    <h3>Información General</h3> <!-- Título del paso -->
                    <p>Detalles de la coordinación</p> <!-- Descripción del paso -->
                </div>
            </div>

            <!-- -------------------------
                     Grid de campos (Dirección + División)
                     ------------------------- -->
            <div class="form-grid">
                <!-- Dirección (select) -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-building"></i> Dirección
                    </label>
                    <select id="direccion" name="id_direccion" class="form-select" required>
                        <option value="">Seleccione una Dirección</option>
                        @foreach ($direcciones as $d)
                        <option value="{{ $d->id_direccion }}">{{ $d->nombre_direccion }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- División (select dependiente de Dirección) -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-sitemap"></i> División
                    </label>
                    <select id="division" name="id_division" class="form-select" required>
                        <option value="">Seleccione primero una dirección</option>
                    </select>
                </div>
            </div>

            <!-- Nombre de la Coordinación -->
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-signature"></i> Nombre de la Coordinación
                </label>
                <input type="text" name="nombre_coordinacion" class="form-input" placeholder="Ej. Coordinación de Redes"
                    required value="{{ old('nombre_coordinacion') }}">
            </div>

            <!-- Botón de envío -->
            <div class="form-actions mt-4">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Guardar Coordinación
                </button>
            </div>
        </div>
    </form>
</div>

<style>
    /* -------------------------
       Variables de color y gradiente principal
       -------------------------
       --primary: color principal rojo
       --primary-light: tono más claro de rojo
       --gradient-primary: gradiente de rojo para botones y pasos
    ------------------------- */
    :root {
        --primary: #da0606;
        --primary-light: #ff4d4d;
        --gradient-primary: linear-gradient(135deg, #da0606 0%, #b70909 100%);
    }

    /* -------------------------
       Iconos y encabezados
       ------------------------- */
    .header-icon {
        color: var(--primary);
        /* Color rojo principal */
    }

    .step-icon {
        background: var(--gradient-primary);
        /* Fondo con gradiente rojo */
    }

    /* -------------------------
       Etiquetas y campos del formulario
       ------------------------- */
    .form-label {
        border-left-color: var(--primary);
        /* Línea lateral roja en label */
    }

    .form-label i {
        color: var(--primary);
        /* Iconos de label en rojo */
    }

    .form-input:focus,
    .form-textarea:focus,
    .form-select:focus {
        border-color: var(--primary);
        /* Borde rojo al enfocar */
        box-shadow: 0 0 0 4px rgba(218, 6, 6, 0.1);
        /* Sombra ligera roja */
    }

    /* -------------------------
       Botones
       ------------------------- */
    .btn-back {
        background: var(--gradient-primary);
        /* Botón de regresar */
    }

    .btn-submit {
        background: var(--gradient-primary);
        /* Botón de envío */
    }

    /* -------------------------
       Premium Form overrides
       ------------------------- */
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
        /* Bordes redondeados */
        font-size: 1.2rem;
        font-weight: 700;
        cursor: pointer;
        transition: var(--transition);
        /* Animaciones suaves */
        box-shadow: var(--shadow-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        /* Espacio entre icono y texto */
    }

    .btn-submit:hover {
        transform: translateY(-3px);
        /* Levanta el botón al pasar hover */
        box-shadow: var(--shadow-xl);
        /* Sombra más grande */
        filter: brightness(1.1);
        /* Aumenta brillo al pasar hover */
    }

    /* -------------------------
       Contenedor de alertas (errores)
       ------------------------- */
    .alert-container {
        animation: fadeIn 0.5s ease-out;
        /* Animación de aparición */
    }

    /* -------------------------
       Responsive (pantallas menores a 768px)
       ------------------------- */
    @media (max-width: 768px) {
        .form-header {
            height: auto;
            flex-direction: column;
            /* Encabezado vertical en móviles */
            padding: 1.5rem;
            text-align: center;
        }

        .header-content {
            flex-direction: column;
            /* Contenido del header vertical */
        }

        .header-icon-container {
            width: 60px;
            height: 60px;
            /* Ajuste de tamaño del icono */
        }

        .header-icon {
            font-size: 2.5rem;
            /* Icono más grande en móvil */
        }

        .header-text h1 {
            font-size: 1.8rem;
            /* Título ajustado en móvil */
        }
    }
</style>
@endsection

@section('scripts')
<!-- -------------------------
         JS específico de Coordinaciones
         -------------------------
         Aquí se incluyen las funciones para manejar el select dependiente
         de Dirección -> División y otros comportamientos dinámicos
    ------------------------- -->
<script src="{{ asset('js/coordinacion.js') }}"></script>
@endsection