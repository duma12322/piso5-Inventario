{{-- Extiende el layout principal --}}
@extends('layouts.app')

{{-- Título de la página --}}
@section('title', 'Editar Usuario')

{{-- Contenido principal --}}
@section('content')

{{-- Hoja de estilos específica para el formulario --}}
<link rel="stylesheet" href="{{ asset('css/createagregarcomponente.css') }}">

{{-- ================= FONDO ANIMADO ================= --}}
<div class="animated-background">
    <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
        <div class="shape shape-5"></div>
    </div>
</div>

{{-- ================= CONTENEDOR DEL FORMULARIO ================= --}}
<div class="component-form-container">

    {{-- ---------- Encabezado del formulario ---------- --}}
    <header class="form-header">
        <div class="header-content">

            {{-- Ícono del encabezado --}}
            <div class="header-icon-container">
                <i class="fas fa-user-edit header-icon"></i>
            </div>

            {{-- Título y descripción --}}
            <div class="header-text">
                <h1>Editar Usuario</h1>
                <p>Modificar información del usuario</p>
            </div>
        </div>

        {{-- Botón para volver al listado --}}
        <div class="header-actions">
            <a href="{{ route('usuarios.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </header>

    {{-- ================= MENSAJE DE ÉXITO ================= --}}
    @if(session('success'))
    <div class="alert alert-success fade show mb-4" role="alert"
        style="border-radius: var(--radius-md);">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
    </div>
    @endif

    {{-- ================= MENSAJES DE ERROR ================= --}}
    @if($errors->any())
    <div class="alert-container mb-4">
        @foreach($errors->all() as $error)
        <div class="alert alert-danger fade show" role="alert"
            style="border-radius: var(--radius-md);">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ $error }}
        </div>
        @endforeach
    </div>
    @endif

    {{-- ================= FORMULARIO DE EDICIÓN ================= --}}
    <form method="POST"
        action="{{ route('usuarios.update', ['usuario' => $usuario->id_usuario]) }}"
        class="premium-form">

        @csrf
        @method('PUT')

        {{-- Paso activo del formulario --}}
        <div class="form-step active">

            {{-- ---------- Encabezado del paso ---------- --}}
            <div class="step-header">
                <div class="step-icon">
                    <i class="fas fa-id-card"></i>
                </div>
                <div class="step-title">
                    <h3>Datos de Acceso</h3>
                    <p>Actualizar credenciales y permisos</p>
                </div>
            </div>

            {{-- ---------- Datos básicos ---------- --}}
            <div class="form-grid">

                {{-- Usuario --}}
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-user"></i> Usuario
                    </label>
                    <input type="text"
                        name="usuario"
                        class="form-input"
                        value="{{ old('usuario', $usuario->usuario) }}"
                        required>
                </div>

                {{-- Rol --}}
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-user-tag"></i> Rol
                    </label>
                    <select name="rol" class="form-select" required>
                        <option value="Administrador"
                            {{ old('rol', $usuario->rol) == 'Administrador' ? 'selected' : '' }}>
                            Administrador
                        </option>
                        <option value="Usuario"
                            {{ old('rol', $usuario->rol) == 'Usuario' ? 'selected' : '' }}>
                            Usuario
                        </option>
                    </select>
                </div>
            </div>

            {{-- ---------- Contraseñas ---------- --}}
            <div class="form-grid mt-3">

                {{-- Nueva contraseña --}}
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-key"></i> Nueva Contraseña
                    </label>
                    <input type="password"
                        name="password"
                        class="form-input"
                        placeholder="Dejar vacío para mantener actual">
                </div>

                {{-- Contraseña actual (obligatoria) --}}
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-lock"></i> Contraseña Actual
                        <span class="text-danger">*</span>
                    </label>
                    <input type="password"
                        name="password_actual"
                        class="form-input"
                        required
                        placeholder="Requerido para guardar cambios">
                </div>
            </div>

            {{-- ---------- Acciones del formulario ---------- --}}
            <div class="form-actions mt-4">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Actualizar Usuario
                </button>
            </div>

        </div>
    </form>
</div>

<style>
    /* =====================================================
       Variables globales
       Override del esquema de color principal a ROJO
       ===================================================== */
    :root {
        --primary: #da0606;
        --primary-light: #ff4d4d;
        --gradient-primary: linear-gradient(135deg, #da0606 0%, #b70909 100%);
    }

    /* =====================================================
       Iconografía y encabezados
       ===================================================== */

    /* Ícono principal del encabezado */
    .header-icon {
        color: var(--primary);
    }

    /* Ícono del paso del formulario */
    .step-icon {
        background: var(--gradient-primary);
    }

    /* =====================================================
       Etiquetas de formulario
       ===================================================== */

    /* Borde izquierdo de las etiquetas */
    .form-label {
        border-left-color: var(--primary);
    }

    /* Íconos dentro de las etiquetas */
    .form-label i {
        color: var(--primary);
    }

    /* =====================================================
       Estados focus de campos de formulario
       ===================================================== */

    .form-input:focus,
    .form-textarea:focus,
    .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(218, 6, 6, 0.1);
    }

    /* =====================================================
       Botones de navegación y envío
       ===================================================== */

    /* Botón volver */
    .btn-back {
        background: var(--gradient-primary);
    }

    /* Botón enviar (base) */
    .btn-submit {
        background: var(--gradient-primary);
    }

    /* =====================================================
       Overrides del formulario premium
       ===================================================== */

    /* Separación superior del formulario */
    .premium-form {
        margin-top: 1rem;
    }

    /* Botón enviar con estilo premium */
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

    /* Efecto hover del botón enviar */
    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-xl);
        filter: brightness(1.1);
    }

    /* =====================================================
       Alertas
       ===================================================== */

    /* Animación de entrada para alertas */
    .alert-container {
        animation: fadeIn 0.5s ease-out;
    }

    /* =====================================================
       Responsive (pantallas pequeñas)
       ===================================================== */
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