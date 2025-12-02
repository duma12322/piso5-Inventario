@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/createagregarcomponente.css') }}">

@section('title', 'Agregar Componente');

@section('content')
<div class="component-form-container">
    <!-- Fondo animado -->
    <div class="animated-background">
        <div class="floating-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
            <div class="shape shape-4"></div>
            <div class="shape shape-5"></div>
        </div>
    </div>

    <!-- Header Section -->
    <div class="form-header">
        <div class="header-content">
            <div class="header-icon-container">
                <i class="fas fa-microchip header-icon"></i>
                <div class="icon-pulse"></div>
            </div>
            <div class="header-text">
                <h1>Agregar Nuevo Componente</h1>
                <p>Complete la información del componente informático</p>
            </div>
        </div>
        <div class="header-actions">
            @if(isset($porEquipo) && $porEquipo && isset($equipoSeleccionado))
            <a href="{{ route('componentes.porEquipo', $equipoSeleccionado->id_equipo) }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Volver al Equipo
            </a>
            @else
            <a href="{{ route('componentes.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Volver a la Lista
            </a>
            @endif
        </div>
    </div>

    <form method="POST" action="{{ route('componentes.store') }}" class="component-form" id="componentForm" enctype="multipart/form-data">
        @csrf
        
        @if(isset($porEquipo) && $porEquipo)
        <input type="hidden" name="porEquipo" value="1">
        <input type="hidden" name="id_equipo" value="{{ $equipoSeleccionado->id_equipo ?? '' }}">
        @endif

        <!-- Progress Steps -->
        <div class="progress-steps">
            <div class="step active" data-step="1">
                <div class="step-circle">
                    <span>1</span>
                    <div class="checkmark">✓</div>
                </div>
                <span class="step-label">Información Básica</span>
            </div>
            <div class="step-connector"></div>
            <div class="step" data-step="2">
                <div class="step-circle">
                    <span>2</span>
                    <div class="checkmark">✓</div>
                </div>
                <span class="step-label">Detalles del Componente</span>
            </div>
        </div>

        <!-- Step 1: Información Básica -->
        <div class="form-step active" id="step1">
            <div class="step-header">
                <div class="step-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div class="step-title">
                    <h3>Información Básica</h3>
                    <p>Seleccione el equipo y tipo de componente</p>
                </div>
            </div>
            
            <div class="form-grid">
                <!-- Selección del equipo -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-desktop"></i> Equipo
                    </label>
                    @if(isset($porEquipo) && $porEquipo && isset($equipoSeleccionado))
                    <input type="hidden" name="id_equipo" value="{{ old('id_equipo', $equipoSeleccionado->id_equipo) }}">
                    <div class="selected-equipo">
                        <div class="equipo-icon">
                            <i class="fas fa-laptop"></i>
                        </div>
                        <div class="equipo-info">
                            <span class="equipo-name">{{ $equipoSeleccionado->marca ?? '' }} {{ $equipoSeleccionado->modelo ?? '' }}</span>
                            <span class="equipo-status">Seleccionado</span>
                        </div>
                    </div>;
                    @else
                    <select id="id_equipo" name="id_equipo" class="form-select" required>
                        <option value="">Seleccione un equipo</option>
                        @foreach ($equipos as $e)
                        <option value="{{ $e->id_equipo }}"
                            {{ old('id_equipo') == $e->id_equipo ? 'selected' : '' }}>
                            {{ $e->marca }} {{ $e->modelo }}
                        </option>
                        @endforeach
                    </select>
                    @endif
                    @error('id_equipo')
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </div>
                    @enderror
                </div>

                <!-- Tipo de componente -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-shapes"></i> Tipo de Componente
                    </label>
                    <select id="tipo_componente" name="tipo_componente" class="form-select" required>
                        <option value="">Seleccione un tipo</option>
                        @foreach([
                        'Tarjeta Madre' => 'fa-microchip',
                        'Memoria RAM' => 'fa-memory',
                        'Procesador' => 'fa-brain',
                        'Fuente de Poder' => 'fa-bolt',
                        'Disco Duro' => 'fa-hdd',
                        'Tarjeta Grafica' => 'fa-video',
                        'Tarjeta Red' => 'fa-network-wired',
                        'Unidad Optica' => 'fa-compact-disc',
                        'Fan Cooler' => 'fa-fan'
                        ] as $tipo => $icon)
                        <option value="{{ $tipo }}" data-icon="{{ $icon }}" {{ old('tipo_componente') == $tipo ? 'selected' : '' }}>
                            {{ $tipo }}
                        </option>
                        @endforeach
                    </select>
                    @error('tipo_componente')
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>

            <div class="step-actions">
                <button type="button" class="btn-next" onclick="validateStep(1)">
                    <span>Siguiente</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- Step 2: Detalles del Componente -->
        <div class="form-step" id="step2">
            <div class="step-header">
                <div class="step-icon">
                    <i class="fas fa-cogs"></i>
                </div>
                <div class="step-title">
                    <h3>Detalles del Componente</h3>
                    <p id="component-description">Complete los detalles específicos del componente</p>
                </div>
            </div>

            <!-- Component Preview -->
            <div class="component-preview" id="componentPreview">
                <div class="preview-icon">
                    <i class="fas fa-microchip"></i>
                </div>
                <div class="preview-content">
                    <h4>Seleccione un tipo de componente</h4>
                    <p>Elija un tipo de componente en el paso anterior para ver los campos específicos</p>
                </div>
            </div>

            <div class="component-sections">
                {{-- Tarjeta Madre --}}
                <div id="tarjeta_madre_campos" class="component-section" style="display:none;">
                    <div class="component-header">
                        <div class="component-icon">
                            <i class="fas fa-microchip"></i>
                        </div>
                        <div class="component-title">
                            <h4>Tarjeta Madre</h4>
                            <p>Especificaciones de la placa base</p>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-tag"></i> Marca / Fabricante
                            </label>
                            <input type="text" name="marca" class="form-input" placeholder="Ej: Biostar, ASUS, Intel, Zotac, ASRock, MSI" value="{{ old('marca') }}">
                            @error('marca')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-cube"></i> Modelo
                            </label>
                            <input type="text" name="modelo" class="form-input" placeholder="Ej. HP dc5800 SFF, B450M-A" value="{{ old('modelo') }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-plug"></i> Socket
                            </label>
                            <input type="text" name="socket" class="form-input" placeholder="Ej. LGA1700, AM5" value="{{ old('socket') }}">
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-memory"></i> Cantidad de Slot RAM
                            </label>
                            <input type="number" name="cantidad_slot_memoria" id="cantidad_slot_memoria" class="form-input" placeholder="Cantidad de Slot" value="{{ old('cantidad_slot_memoria') }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-sd-card"></i> Memoria Máxima (GB)
                            </label>
                            <input type="number" name="memoria_maxima" class="form-input" min="1" placeholder="Ej: 64" value="{{ old('memoria_maxima') }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-bolt"></i> Tipo RAM
                            </label>
                            <input type="text" name="tipo_ram" class="form-input" placeholder="Ej. DDR3, DDR2" value="{{ old('tipo_ram') }}">
                        </div>
                    </div>

                    <!-- Frecuencias de Memoria -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-tachometer-alt"></i> Frecuencias de Memoria (MHz)
                        </label>
                        <div class="checkbox-grid" id="frecuencias-container">
                            @php
                            $opcionesFrecuencias = [
                                'DDR' => [200, 266, 333, 400],
                                'DDR2' => [400, 533, 667, 800, 1066],
                                'DDR3' => [800, 1066, 1333, 1600, 1866, 2133, 2400],
                                'DDR4' => [2133, 2400, 2666, 2800, 2933, 3000, 3200, 3466, 3600, 3733, 4000, 4266],
                                'DDR5' => [4800, 5200, 5600, 6000, 6400, 6800, 7200, 7600, 8000, 8400]
                            ];
                            
                            $seleccionadasFreq = old('frecuencias_memoria', []);
                            @endphp

                            @foreach($opcionesFrecuencias as $tipo => $frecs)
                            <div class="frecuencia-grupo" data-tipo="{{ $tipo }}">
                                <strong class="frecuencia-titulo">{{ $tipo }}</strong>
                                <div class="frecuencia-opciones">
                                    @foreach($frecs as $freq)
                                    <label class="checkbox-item">
                                        <input type="checkbox" class="checkbox-input" name="frecuencias_memoria[]" value="{{ $freq }}"
                                            {{ in_array($freq, (array)$seleccionadasFreq) ? 'checked' : '' }}>
                                        <span class="checkbox-custom"></span>
                                        <span class="checkbox-label">{{ $freq }} MHz</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @error('frecuencias_memoria')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Ranuras de expansión -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-expand-alt"></i> Ranuras de expansión
                        </label>
                        <div class="checkbox-grid compact">
                            @php
                            $opcionesRanuras = [
                                'ISA', 'AGP', 'PCI', 'PCI-X', 'AMR/CNR', 'PCIe x1', 'PCIe x2', 'PCIe x4', 
                                'PCIe x8', 'PCIe x12', 'PCIe x16', 'PCIe x32', 'Mini PCIe', 'M.2 (Key M)', 
                                'M.2 (Key E)', 'Thunderbolt header', 'OCP', 'CXL'
                            ];
                            $seleccionadas = old('ranuras_expansion', []);
                            @endphp

                            @foreach($opcionesRanuras as $ranura)
                            <label class="checkbox-item">
                                <input class="checkbox-input" type="checkbox" name="ranuras_expansion[]" value="{{ $ranura }}"
                                    {{ in_array($ranura, (array)$seleccionadas) ? 'checked' : '' }}>
                                <span class="checkbox-custom"></span>
                                <span class="checkbox-label">{{ $ranura }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>;

                    <!-- Estado -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-power-off"></i> Estado
                        </label>
                        <select name="estado" class="form-select" required>
                            <option value="">Seleccione estado</option>
                            @foreach(['Buen Funcionamiento' => 'success', 'Operativo' => 'info', 'Sin Funcionar' => 'danger'] as $estado => $color)
                            <option value="{{ $estado }}" {{ old('estado') == $estado ? 'selected' : '' }} data-color="{{ $color }}">
                                {{ $estado }}
                            </option>
                            @endforeach
                        </select>
                        @error('estado')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Detalles -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-file-alt"></i> Detalles
                        </label>
                        <textarea name="detalles" class="form-textarea" rows="4" placeholder="Información adicional del componente">{{ old('detalles') }}</textarea>
                    </div>
                </div>

                <!-- Memoria RAM -->
                <div id="memoria_ram_campos" class="component-section" style="display:none;">
                    <div class="component-header">
                        <div class="component-icon">
                            <i class="fas fa-memory"></i>
                        </div>
                        <div class="component-title">
                            <h4>Memoria RAM</h4>
                            <p>Especificaciones de la memoria</p>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-tag"></i> Marca
                            </label>
                            <input type="text" name="marca" class="form-input" placeholder="Ej: Corsair, Kingston, G.Skill, Crucial, ADATA" value="{{ old('marca') }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-bolt"></i> Tipo
                            </label>
                            <select name="tipo_ram" class="form-select">
                                <option value="">Seleccione tipo</option>
                                @foreach(['DDR', 'DDR2', 'DDR3', 'DDR4', 'DDR5'] as $tipo)
                                <option value="{{ $tipo }}" {{ old('tipo_ram') == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-sd-card"></i> Capacidad (GB)
                            </label>
                            <input type="number" name="capacidad" class="form-input" placeholder="Ej: 8, 16" value="{{ old('capacidad') }}" min="1">
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-tachometer-alt"></i> Frecuencia (MHz)
                            </label>
                            <input type="number" name="frecuencia" class="form-input" placeholder="Ej: 3200" value="{{ old('frecuencia') }}" min="1">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-memory"></i> Slot RAM
                            </label>
                            <input type="text" name="slot" class="form-input" placeholder="Ej: Slot 1, Slot A1" value="{{ old('slot') }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-power-off"></i> Estado
                            </label>
                            <select name="estado" class="form-select" required>
                                <option value="">Seleccione estado</option>
                                @foreach(['Buen Funcionamiento' => 'success', 'Operativo' => 'info', 'Sin Funcionar' => 'danger'] as $estado => $color)
                                <option value="{{ $estado }}" {{ old('estado') == $estado ? 'selected' : '' }} data-color="{{ $color }}">
                                    {{ $estado }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-file-alt"></i> Detalles
                        </label>
                        <textarea name="detalles" class="form-textarea" rows="4" placeholder="Información adicional del componente">{{ old('detalles') }}</textarea>
                    </div>
                </div>

                <!-- Procesador -->
                <div id="procesador_campos" class="component-section" style="display:none;">
                    <div class="component-header">
                        <div class="component-icon">
                            <i class="fas fa-brain"></i>
                        </div>
                        <div class="component-title">
                            <h4>Procesador</h4>
                            <p>Especificaciones del CPU</p>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-tag"></i> Marca
                            </label>
                            <input type="text" name="marca" class="form-input" placeholder="Ej. Intel, AMD, Apple" value="{{ old('marca') }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-cube"></i> Modelo
                            </label>
                            <input type="text" name="modelo" class="form-input" placeholder="Ej. Core i7-13700K, Ryzen 5 7600, Apple M3 Max" value="{{ old('modelo') }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-archway"></i> Arquitectura
                            </label>
                            <select name="arquitectura" class="form-select">
                                <option value="">Seleccione arquitectura</option>
                                @foreach(['x86 (32-bit)', 'x64 (64-bit)', 'ARM (32-bit)', 'ARM (64-bit)'] as $arch)
                                <option value="{{ $arch }}" {{ old('arquitectura') == $arch ? 'selected' : '' }}>{{ $arch }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-microchip"></i> Núcleos
                            </label>
                            <input type="number" name="nucleos" class="form-input" placeholder="Ej. 2, 4, 8" value="{{ old('nucleos') }}" min="1">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-tachometer-alt"></i> Frecuencia (GHz)
                            </label>
                            <input type="number" step="0.1" name="frecuencia" class="form-input" placeholder="Ej. 1.9, 3.6" value="{{ old('frecuencia') }}" min="0.1">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-plug"></i> Socket
                            </label>
                            <input type="text" name="socket" class="form-input" placeholder="Ej. LGA1700, AM5" value="{{ old('socket') }}">
                        </div>;
                    </div>;

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-bolt"></i> Consumo eléctrico (W)
                            </label>
                            <input type="number" name="consumo" class="form-input" placeholder="Ej. 65, 125, 350" value="{{ old('consumo') }}" min="1">
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-power-off"></i> Estado
                            </label>
                            <select name="estado" class="form-select" required>
                                <option value="">Seleccione estado</option>
                                @foreach(['Buen Funcionamiento' => 'success', 'Operativo' => 'info', 'Sin Funcionar' => 'danger'] as $estado => $color)
                                <option value="{{ $estado }}" {{ old('estado') == $estado ? 'selected' : '' }} data-color="{{ $color }}">
                                    {{ $estado }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>;

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-file-alt"></i> Detalles
                        </label>
                        <textarea name="detalles" class="form-textarea" rows="4" placeholder="Información adicional del componente">{{ old('detalles') }}</textarea>
                    </div>;
                </div>
            </div>

            <div class="step-actions">
                <button type="button" class="btn-prev" onclick="showStep(1)">
                    <i class="fas fa-arrow-left"></i>
                    <span>Anterior</span>
                </button>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i>
                    <span>Guardar Componente</span>
                </button>
            </div>
        </div>
    </form>
</div>;

<!-- Success Modal -->
<div id="successModal" class="modal" style="display: none;">
    <div class="modal-content success">
        <div class="modal-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h3>¡Componente Agregado!</h3>
        <p>El componente ha sido registrado exitosamente en el sistema.</p>
        <div class="modal-actions">
            <button class="btn-modal" onclick="closeModal()">Continuar</button>
        </div>
    </div>
</div>;
@endsection

@section('scripts')
<script>
// Variables globales
let currentStep = 1;

function showStep(step) {
    // Ocultar todos los pasos
    document.querySelectorAll('.form-step').forEach(stepEl => {
        stepEl.classList.remove('active');
    });
    
    // Mostrar paso seleccionado
    document.getElementById(`step${step}`).classList.add('active');
    currentStep = step;
    
    // Actualizar pasos de progreso
    document.querySelectorAll('.step').forEach(stepEl => {
        const stepNumber = parseInt(stepEl.dataset.step);
        if (stepNumber <= step) {
            stepEl.classList.add('active');
        } else {
            stepEl.classList.remove('active');
        }
    });
    
    // Actualizar conectores
    document.querySelectorAll('.step-connector').forEach(connector => {
        if (step >= 2) {
            connector.classList.add('active');
        } else {
            connector.classList.remove('active');
        }
    });
    
    // Mostrar campos según tipo de componente
    updateComponentFields();
}

function validateStep(step) {
    let isValid = true;
    const stepElement = document.getElementById(`step${step}`);
    const requiredFields = stepElement.querySelectorAll('[required]');
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('error');
            showFieldError(field, 'Este campo es requerido');
            isValid = false;
        } else {
            field.classList.remove('error');
            hideFieldError(field);
        }
    });
    
    if (isValid && step === 1) {
        showStep(2);
    }
    
    return isValid;
}

function showFieldError(field, message) {
    let errorDiv = field.parentElement.querySelector('.field-error');
    if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.className = 'field-error';
        field.parentElement.appendChild(errorDiv);
    }
    errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
}

function hideFieldError(field) {
    const errorDiv = field.parentElement.querySelector('.field-error');
    if (errorDiv) {
        errorDiv.remove();
    }
}

function updateComponentPreview() {
    const select = document.getElementById('tipo_componente');
    const selectedOption = select.options[select.selectedIndex];
    const preview = document.getElementById('componentPreview');
    
    if (selectedOption.value) {
        const icon = selectedOption.getAttribute('data-icon');
        preview.innerHTML = `
            <div class="preview-icon">
                <i class="fas ${icon}"></i>
            </div>
            <div class="preview-content">
                <h4>${selectedOption.text}</h4>
                <p>Complete los detalles específicos del ${selectedOption.text.toLowerCase()}</p>
            </div>
        `;
        preview.classList.add('has-selection');
    } else {
        preview.innerHTML = `
            <div class="preview-icon">
                <i class="fas fa-microchip"></i>
            </div>
            <div class="preview-content">
                <h4>Seleccione un tipo de componente</h4>
                <p>Elija un tipo de componente en el paso anterior para ver los campos específicos</p>
            </div>
        `;
        preview.classList.remove('has-selection');
    }
    
    // Actualizar campos del componente
    updateComponentFields();
}

function updateComponentFields() {
    // Ocultar todas las secciones
    document.querySelectorAll('.component-section').forEach(section => {
        section.style.display = 'none';
    });
    
    // Mostrar sección correspondiente
    const tipo = document.getElementById('tipo_componente').value;
    if (tipo) {
        const sectionId = tipo.toLowerCase().replace(/ /g, '_') + '_campos';
        const section = document.getElementById(sectionId);
        if (section) {
            section.style.display = 'block';
            section.style.animation = 'fadeIn 0.5s ease-out';
        }
    }
}

function closeModal() {
    document.getElementById('successModal').style.display = 'none';
    // Redirigir después de cerrar modal
    setTimeout(() => {
        @if(isset($porEquipo) && $porEquipo && isset($equipoSeleccionado))
        window.location.href = "{{ route('componentes.porEquipo', $equipoSeleccionado->id_equipo) }}";
        @else
        window.location.href = "{{ route('componentes.index') }}";
        @endif
    }, 500);
}

// Inicializar
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar select de tipo de componente
    const tipoComponente = document.getElementById('tipo_componente');
    tipoComponente.addEventListener('change', updateComponentPreview);
    updateComponentPreview();
    
    // Manejar envío del formulario
    document.getElementById('componentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!validateStep(1) || !validateStep(2)) {
            showStep(1); // Regresar al primer paso si hay errores
            return;
        }
        
        // Enviar formulario
        const formData = new FormData(this);
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('successModal').style.display = 'flex';
            } else {
                // Mostrar errores
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const input = document.querySelector(`[name="${field}"]`);
                        if (input) {
                            showFieldError(input, data.errors[field][0]);
                        }
                    });
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al guardar el componente');
        });
    });
    
    // Limpiar errores al escribir
    document.querySelectorAll('.form-input, .form-select, .form-textarea').forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('error');
            hideFieldError(this);
        });
    });
});
</script>
@endsection
