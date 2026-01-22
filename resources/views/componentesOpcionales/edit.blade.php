@extends('layouts.app')

@section('title', 'Editar Componente Opcional')

@section('content')
<!-- ======================================================
     Importación de estilos específicos para creación/edición
     ======================================================
     Se carga un CSS dedicado para formularios de componentes opcionales.
     Se incluye versión dinámica con time() para evitar cache del navegador.
-->
<link rel="stylesheet" href="{{ asset('css/createagregarcomponente.css') }}">

<!-- ======================================================
     Fondo animado con formas flotantes
     ======================================================
     Proporciona un efecto visual animado en la parte superior del formulario.
     Cada <div class="shape"> representa un elemento flotante animado.
-->
<div class="animated-background">
    <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
        <div class="shape shape-5"></div>
    </div>
</div>

<!-- ======================================================
     Contenedor principal del formulario de edición
     ====================================================== -->
<div class="component-form-container">

    <!-- ======================================================
         Cabecera del formulario
         ======================================================
         Contiene:
         - Icono representativo
         - Título y subtítulo
         - Botón de regreso condicional según si se accede desde un equipo específico
    -->
    <header class="form-header">
        <div class="header-content">
            <div class="header-icon-container">
                <i class="fas fa-edit header-icon"></i>
            </div>
            <div class="header-text">
                <h1>Editar Componente Opcional</h1>
                <p>Modificar hardware adicional - ID: {{ $opcional->id_opcional }}</p>
            </div>
        </div>
        <div class="header-actions">
            <!-- Botón "Volver" condicional -->
            @if(isset($porEquipo) && $porEquipo === true && isset($id_equipo))
            <a href="{{ route('componentesOpcionales.porEquipo', $id_equipo) }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            @else
            <a href="{{ route('componentesOpcionales.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            @endif
        </div>
    </header>

    <!-- ======================================================
         Mostrar errores de validación
         ======================================================
         Se itera sobre $errors para mostrar mensajes individuales
         en alertas estilizadas con iconos.
    -->
    @if($errors->any())
    <div class="alert-container mb-4">
        @foreach($errors->all() as $error)
        <div class="alert alert-danger fade show" role="alert" style="border-radius: var(--radius-md);">
            <i class="fas fa-exclamation-circle me-2"></i> {{ $error }}
        </div>
        @endforeach
    </div>
    @endif

    <!-- ======================================================
         Formulario principal de edición
         ======================================================
         - Método POST con spoofing PUT para actualizar registro
         - Campos ocultos para manejar redirección desde equipo específico
    -->
    <form method="POST" action="{{ route('componentesOpcionales.update', $opcional->id_opcional) }}"
        class="premium-form">
        @csrf
        @method('PUT')

        @if(isset($porEquipo) && $porEquipo)
        <input type="hidden" name="porEquipo" value="1">
        <input type="hidden" name="id_equipo" value="{{ $opcional->id_equipo }}">
        @endif
        <input type="hidden" name="id_opcional" value="{{ $opcional->id_opcional }}">

        <!-- ======================================================
             Paso del formulario (puede extenderse a multi-step)
             ======================================================
             Actualmente solo hay un step activo.
        -->
        <div class="form-step active">
            <div class="step-header">
                <div class="step-icon">
                    <i class="fas fa-cog"></i>
                </div>
                <div class="step-title">
                    <h3>Configuración Base</h3>
                    <p>Equipo asociado y tipo de hardware opcional</p>
                </div>
            </div>

            <!-- ======================================================
                 Grid de campos básicos
                 ======================================================
                 Contiene:
                 - Selector de equipo (obligatorio)
                 - Selector de tipo de componente opcional (obligatorio)
            -->
            <div class="form-grid">
                <!-- Selector de equipo -->
                <div class="form-group">
                    <label class="form-label"><i class="fas fa-laptop"></i> Equipo</label>
                    <select name="id_equipo" class="form-select" required>
                        <option value="">Seleccione un equipo</option>
                        @foreach ($equipos as $e)
                        <option value="{{ $e->id_equipo }}" {{ (old('id_equipo') ?? $opcional->id_equipo) == $e->id_equipo ? 'selected' : '' }}>
                            {{ $e->marca }} {{ $e->modelo }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Selector de tipo de componente opcional -->
                <div class="form-group">
                    <label class="form-label"><i class="fas fa-tags"></i> Tipo de Componente Opcional</label>
                    <select id="tipo_opcional" name="tipo_opcional" class="form-select" required>
                        <option value="">Seleccione un tipo</option>
                        @foreach(['Memoria Ram', 'Disco Duro', 'Fan Cooler', 'Tarjeta Grafica', 'Tarjeta de Red', 'Tarjeta WiFi', 'Tarjeta de Sonido'] as $tipo)
                        <option value="{{ $tipo }}" {{ old('tipo_opcional', $opcional->tipo_opcional) == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="component-sections">

                {{-- ======================================================
         Sección de RAM
         Campos relacionados con memoria RAM adicional.
         Se oculta por defecto y se muestra dinámicamente
         según la selección del tipo de componente.
    ====================================================== --}}
                <div id="ram_campos" class="component-section" style="display:none;">
                    <div class="component-header">
                        <div class="component-icon"><i class="fas fa-memory"></i></div>
                        <div class="component-title">
                            <h4>Memoria RAM Extra</h4>
                        </div>
                    </div>

                    <!-- Grid de campos principales -->
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Marca</label>
                            <input type="text" name="marca_ram" class="form-input"
                                value="{{ old('marca_ram', $opcional->marca) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tipo</label>
                            <input type="text" name="tipo_ram" class="form-input"
                                value="{{ old('tipo_ram', $opcional->tipo) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Capacidad</label>
                            <input type="text" name="capacidad_ram" class="form-input"
                                value="{{ old('capacidad_ram', $opcional->capacidad) }}">
                        </div>
                    </div>

                    <!-- Grid de campos adicionales -->
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Slot</label>
                            <input type="text" name="slot_memoria" class="form-input"
                                value="{{ old('slot_memoria', $opcional->slot_memoria) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Frecuencia</label>
                            <input type="text" name="frecuencia_ram" class="form-input"
                                value="{{ old('frecuencia_ram', $opcional->frecuencia) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Estado</label>
                            <select name="estado_ram" class="form-select">
                                @foreach(['Buen Funcionamiento', 'Operativo', 'Sin Funcionar'] as $est)
                                <option value="{{ $est }}" {{ old('estado_ram', $opcional->estado_ram) == $est ? 'selected' : '' }}>{{ $est }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- ======================================================
         Sección Disco Duro
         Campos relacionados con discos adicionales (HDD, SSD, NVMe, SSHD).
    ====================================================== --}}
                <div id="disco_duro_campos" class="component-section" style="display:none;">
                    <div class="component-header">
                        <div class="component-icon"><i class="fas fa-hdd"></i></div>
                        <div class="component-title">
                            <h4>Disco Duro Adicional</h4>
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Marca</label>
                            <input type="text" name="marca_disco" class="form-input"
                                value="{{ old('marca_disco', $opcional->marca) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tipo</label>
                            <select name="tipo_disco" class="form-select">
                                @foreach(['HDD', 'SSD', 'SSHD', 'NVMe'] as $td)
                                <option value="{{ $td }}" {{ old('tipo_disco', $opcional->tipo) == $td ? 'selected' : '' }}>{{ $td }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Capacidad</label>
                            <input type="text" name="capacidad_disco" class="form-input"
                                value="{{ old('capacidad_disco', $opcional->capacidad) }}">
                        </div>
                    </div>
                </div>

                {{-- ======================================================
         Sección Tarjeta Gráfica
         Campos relacionados con GPU dedicada opcional.
    ====================================================== --}}
                <div id="tarjeta_grafica_campos" class="component-section" style="display:none;">
                    <div class="component-header">
                        <div class="component-icon"><i class="fas fa-video"></i></div>
                        <div class="component-title">
                            <h4>Tarjeta Gráfica</h4>
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Marca</label>
                            <input type="text" name="marca_tarjeta_grafica" class="form-input"
                                value="{{ old('marca_tarjeta_grafica', $opcional->marca) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Modelo</label>
                            <input type="text" name="modelo_tarjeta_grafica" class="form-input"
                                value="{{ old('modelo_tarjeta_grafica', $opcional->modelo) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Estado</label>
                            <select name="estado_tarjeta_grafica" class="form-select">
                                @foreach(['Buen Funcionamiento', 'Operativo', 'Sin Funcionar'] as $est)
                                <option value="{{ $est }}" {{ old('estado_tarjeta_grafica', $opcional->estado) == $est ? 'selected' : '' }}>{{ $est }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- ======================================================
         Sección Fan Cooler
         Campos relacionados con ventiladores adicionales o reemplazos.
    ====================================================== --}}
                <div id="fan_cooler_campos" class="component-section" style="display:none;">
                    <div class="component-header">
                        <div class="component-icon"><i class="fas fa-fan"></i></div>
                        <div class="component-title">
                            <h4>Fan Cooler</h4>
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Marca</label>
                            <input type="text" name="marca_fan" class="form-input"
                                value="{{ old('marca_fan', $opcional->marca) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tipo</label>
                            <input type="text" name="tipo_fan" class="form-input"
                                value="{{ old('tipo_fan', $opcional->tipo) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Ubicación</label>
                            <input type="text" name="ubicacion_fan" class="form-input"
                                value="{{ old('ubicacion_fan', $opcional->ubicacion) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Estado</label>
                            <select name="estado_fan" class="form-select">
                                @foreach(['Buen Funcionamiento', 'Operativo', 'Sin Funcionar'] as $est)
                                <option value="{{ $est }}" {{ old('estado_fan', $opcional->estado) == $est ? 'selected' : '' }}>{{ $est }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- ======================================================
         Sección Tarjeta de Red
         Campos para NIC adicional o reemplazo.
    ====================================================== --}}
                <div id="tarjeta_red_campos" class="component-section" style="display:none;">
                    <div class="component-header">
                        <div class="component-icon"><i class="fas fa-network-wired"></i></div>
                        <div class="component-title">
                            <h4>Tarjeta de Red</h4>
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Marca</label>
                            <input type="text" name="marca_tarjeta_red" class="form-input"
                                value="{{ old('marca_tarjeta_red', $opcional->marca) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Velocidad</label>
                            <input type="text" name="velocidad_red" class="form-input"
                                value="{{ old('velocidad_red', $opcional->velocidad) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Estado</label>
                            <select name="estado_tarjeta_red" class="form-select">
                                @foreach(['Buen Funcionamiento', 'Operativo', 'Sin Funcionar'] as $est)
                                <option value="{{ $est }}" {{ old('estado_tarjeta_red', $opcional->estado) == $est ? 'selected' : '' }}>{{ $est }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- ======================================================
         Sección Tarjeta WiFi
         Campos para adaptadores inalámbricos.
    ====================================================== --}}
                <div id="tarjeta_wifi_campos" class="component-section" style="display:none;">
                    <div class="component-header">
                        <div class="component-icon"><i class="fas fa-wifi"></i></div>
                        <div class="component-title">
                            <h4>Tarjeta WiFi</h4>
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Marca</label>
                            <input type="text" name="marca_tarjeta_wifi" class="form-input"
                                value="{{ old('marca_tarjeta_wifi', $opcional->marca) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Modelo</label>
                            <input type="text" name="modelo_tarjeta_wifi" class="form-input"
                                value="{{ old('modelo_tarjeta_wifi', $opcional->modelo) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Velocidad</label>
                            <input type="text" name="velocidad_wifi" class="form-input"
                                value="{{ old('velocidad_wifi', $opcional->velocidad) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Estado</label>
                            <select name="estado_tarjeta_wifi" class="form-select">
                                @foreach(['Buen Funcionamiento', 'Operativo', 'Sin Funcionar'] as $est)
                                <option value="{{ $est }}" {{ old('estado_tarjeta_wifi', $opcional->estado) == $est ? 'selected' : '' }}>{{ $est }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- ======================================================
         Sección Tarjeta de Sonido
         Campos para adaptadores de audio.
    ====================================================== --}}
                <div id="tarjeta_sonido_campos" class="component-section" style="display:none;">
                    <div class="component-header">
                        <div class="component-icon"><i class="fas fa-music"></i></div>
                        <div class="component-title">
                            <h4>Tarjeta de Sonido</h4>
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Marca</label>
                            <input type="text" name="marca_tarjeta_sonido" class="form-input"
                                value="{{ old('marca_tarjeta_sonido', $opcional->marca) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Modelo</label>
                            <input type="text" name="modelo_tarjeta_sonido" class="form-input"
                                value="{{ old('modelo_tarjeta_sonido', $opcional->modelo) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Estado</label>
                            <select name="estado_tarjeta_sonido" class="form-select">
                                @foreach(['Buen Funcionamiento', 'Operativo', 'Sin Funcionar'] as $est)
                                <option value="{{ $est }}" {{ old('estado_tarjeta_sonido', $opcional->estado) == $est ? 'selected' : '' }}>{{ $est }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ======================================================
              Botón de envío
             ====================================================== -->
            <div class="form-actions mt-4">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Actualizar Componente Opcional
                </button>
            </div>

        </div>
    </form>
</div>
@endsection

@section('scripts')
<!-- ======================================================
     ESTILOS INLINE PARA LA PÁGINA DE COMPONENTES OPCIONALES
     ======================================================
     Estos estilos están específicamente diseñados para:
     - Tematizar los formularios de edición y creación de componentes opcionales.
     - Dar un tema rojo consistente con el branding del sistema.
     - Ajustes responsivos para móviles.
-->
<style>
    /* ================================================
       Colores y gradientes principales
       ================================================ */
    :root {
        --primary: #da0606;
        /* Color principal rojo */
        --primary-light: #ff4d4d;
        /* Rojo más claro para hover/efectos */
        --gradient-primary: linear-gradient(135deg, #da0606 0%, #b70909 100%);
    }

    /* ================================================
       Formularios premium
       ================================================ */
    .premium-form {
        margin-top: 1rem;
        /* Separación superior del formulario */
    }

    /* Botón de envío del formulario */
    .btn-submit {
        width: 100%;
        /* Ocupa todo el ancho disponible */
        padding: 1.25rem;
        /* Espaciado interno */
        background: var(--gradient-primary);
        /* Gradiente rojo */
        color: white;
        border: none;
        border-radius: var(--radius-lg);
        font-size: 1.2rem;
        font-weight: 700;
        cursor: pointer;
        transition: var(--transition);
        box-shadow: var(--shadow-primary);
        display: flex;
        /* Para alinear iconos y texto */
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        /* Espacio entre icono y texto */
    }

    /* Efecto hover para el botón de envío */
    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-xl);
        filter: brightness(1.1);
    }

    /* ================================================
       Estilos para grupos de checkboxes
       ================================================ */
    .checkbox-group-wrapper {
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: rgba(102, 126, 234, 0.03);
        /* Fondo sutil */
        border-radius: var(--radius-md);
        border: 1px solid rgba(102, 126, 234, 0.1);
    }

    .group-label {
        display: block;
        margin-bottom: 1rem;
        color: var(--primary);
        /* Texto en rojo */
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .checkbox-options {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
        gap: 0.75rem;
    }

    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.85rem;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: var(--radius-sm);
        transition: var(--transition);
        background: white;
        border: 1px solid var(--gray-200);
    }

    .checkbox-item:hover {
        background: rgba(102, 126, 234, 0.05);
        border-color: var(--primary);
    }

    .checkbox-item input {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    /* ================================================
       Animaciones
       ================================================ */
    .alert-container {
        animation: fadeIn 0.5s ease-out;
    }

    /* ================================================
       Ajustes responsivos para pantallas pequeñas
       ================================================ */
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

<!-- ======================================================
     Variables JS globales
     ======================================================
     BASE_URL se puede usar en los scripts para generar rutas
     relativas desde JS.
-->
<script>
    const BASE_URL = '{{ url(' / ') }}';
</script>

<!-- ======================================================
     Scripts específicos de la página
     - componenteOpcional.js: Funcionalidad principal del formulario.
     - componenteOpcional2.js: Manejo de mostrar/ocultar campos
       según el tipo de componente seleccionado.
     ====================================================== -->
<script src="{{ asset('js/componenteOpcional.js') }}"></script>
<script src="{{ asset('js/componenteOpcional2.js') }}"></script>
@endsection