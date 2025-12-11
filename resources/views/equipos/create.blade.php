@extends('layouts.app')
 <link rel="stylesheet" href="{{ asset('css/crearnuevoequipo.css') }}">
    
@section('content')
<div class="container equipo-form-container mt-4">
    <!-- Contenedor invisible con datos en atributos -->
    <div id="app-data"
        data-direcciones='@json($direcciones)'
        data-divisiones='@json($divisiones)'
        data-coordinaciones='@json($coordinaciones)'
        data-tipos-software='@json($tiposSoftware)'
        data-software-actual='@json($softwareActual)'>
    </div>

    <div class="form-header">
        <h2 class="form-title"><i class="fas fa-plus-circle me-2"></i>Agregar Nuevo Equipo</h2>
        <a href="{{ route('equipos.index') }}" class="btn btn-outline-secondary btn-back">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <form method="POST" action="{{ route('equipos.store') }}" class="equipo-form">
        @csrf

        <!-- Información Básica -->
        <section class="form-section">
            <h3 class="section-title"><i class="fas fa-info-circle me-2"></i>Información Básica</h3>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label"><i class="fas fa-tag"></i> Marca <span class="text-danger">*</span></label>
                    <input type="text" name="marca" class="form-control" value="{{ old('marca') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label"><i class="fas fa-microchip"></i> Modelo <span class="text-danger">*</span></label>
                    <input type="text" name="modelo" class="form-control" value="{{ old('modelo') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label"><i class="fas fa-barcode"></i> Serial</label>
                    <input type="text" name="serial" class="form-control" value="{{ old('serial') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label"><i class="fas fa-hashtag"></i> Número de Bien</label>
                    <input type="text" name="numero_bien" class="form-control" value="{{ old('numero_bien') }}">
                </div>
            </div>
        </section>

        <!-- Software Instalado -->
        <section class="form-section">
            <h3 class="section-title"><i class="fas fa-laptop-code me-2"></i>Software Instalado</h3>

            <!-- Sistema Operativo -->
            <div class="software-group mb-4">
                <label class="form-label d-block"><i class="fas fa-desktop"></i> Sistema Operativo <span class="text-danger">*</span></label>
                <div class="row">
                    <div class="col-md-4">
                        <select name="software_nombre[SO]" class="form-select" required>
                            <option value="">Seleccionar...</option>
                            @foreach ($tiposSoftware['Sistema Operativo'] as $so)
                                <option value="{{ $so }}" {{ (old('software_nombre.SO') ?? ($softwareActual['SO']['nombre'] ?? '')) === $so ? 'selected' : '' }}>
                                    {{ $so }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="software_version[SO]" class="form-control" placeholder="Versión" value="{{ old('software_version.SO', $softwareActual['SO']['version'] ?? '') }}">
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="software_bits[SO]" class="form-control" placeholder="Bits" value="{{ old('software_bits.SO', $softwareActual['SO']['bits'] ?? '') }}">
                    </div>
                </div>
            </div>

            <!-- Ofimática -->
            <div class="software-group mb-4">
                <label class="form-label d-block"><i class="fas fa-file-alt"></i> Ofimática <span class="text-danger">*</span></label>
                <div class="row">
                    <div class="col-md-4">
                        <select name="software_nombre_ofimatica" class="form-select" required>
                            <option value="">Seleccionar...</option>
                            @foreach ($tiposSoftware['Ofimática'] as $of)
                                <option value="{{ $of }}" {{ (old('software_nombre_ofimatica') ?? ($softwareActual['Ofimática']['nombre'] ?? '')) === $of ? 'selected' : '' }}>
                                    {{ $of }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="software_version_ofimatica" class="form-control" placeholder="Versión" value="{{ old('software_version_ofimatica', $softwareActual['Ofimática']['version'] ?? '') }}">
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="software_bits_ofimatica" class="form-control" placeholder="Bits" value="{{ old('software_bits_ofimatica', $softwareActual['Ofimática']['bits'] ?? '') }}">
                    </div>
                </div>
            </div>

            <!-- Navegadores -->
            <div class="software-group">
                <label class="form-label d-block"><i class="fas fa-globe"></i> Navegadores</label>
                <div class="browser-checkboxes">
                    @foreach ($tiposSoftware['Navegador'] as $nav)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="software_navegadores[]" value="{{ $nav }}"
                                {{ in_array($nav, old('software_navegadores', $softwareActual['Navegador'] ?? [])) ? 'checked' : '' }}>
                            <label class="form-check-label">{{ $nav }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Características Físicas -->
        <section class="form-section">
            <h3 class="section-title"><i class="fas fa-server me-2"></i>Características Físicas</h3>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label"><i class="fas fa-cube"></i> Tipo de Gabinete</label>
                    <input type="text" name="tipo_gabinete" class="form-control" value="{{ old('tipo_gabinete') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label"><i class="fas fa-exclamation-triangle"></i> Estado del Gabinete</label>
                    <select name="estado_gabinete" class="form-select">
                        <option value="">Seleccionar...</option>
                        @foreach (['Nuevo','Deteriorado','Dañado'] as $estado)
                            <option value="{{ $estado }}" {{ old('estado_gabinete') == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </section>

        <!-- Nivel Organizativo -->
        <section class="form-section">
            <h3 class="section-title"><i class="fas fa-sitemap me-2"></i>Nivel Organizativo</h3>
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-layer-group"></i> Nivel del Equipo</label>
                <select id="nivel-equipo" name="nivel_equipo" class="form-select">
                    <option value="">Seleccione nivel</option>
                    <option value="direccion">Dirección</option>
                    <option value="division">División</option>
                    <option value="coordinacion">Coordinación</option>
                </select>
            </div>

            <div class="nivel nivel-direccion">
                <label class="form-label"><i class="fas fa-building"></i> Dirección</label>
                <select name="id_direccion" id="direccion" class="form-select">
                    <option value="">Seleccione</option>
                    @foreach ($direcciones as $d)
                        <option value="{{ $d->id_direccion }}">{{ $d->nombre_direccion }}</option>
                    @endforeach
                </select>
            </div>

            <div class="nivel nivel-division">
                <label class="form-label"><i class="fas fa-landmark"></i> División</label>
                <select name="id_division" id="division" class="form-select">
                    <option value="">Seleccione</option>
                    @foreach ($divisiones as $div)
                        <option value="{{ $div->id_division }}">{{ $div->nombre_division }}</option>
                    @endforeach
                </select>
            </div>

            <div class="nivel nivel-coordinacion">
                <label class="form-label"><i class="fas fa-users"></i> Coordinación</label>
                <select name="id_coordinacion" id="coordinacion" class="form-select">
                    <option value="">Seleccione</option>
                    @foreach ($coordinaciones as $c)
                        <option value="{{ $c['id_coordinacion'] }}">{{ $c['nombre_coordinacion'] }}</option>
                    @endforeach
                </select>
            </div>
        </section>

        <!-- Estado Funcional -->
        <section class="form-section">
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-heartbeat"></i> Estado Funcional</label>
                <select name="estado_funcional" class="form-select">
                    <option value="">Seleccionar...</option>
                    @foreach (['Buen Funcionamiento','Operativo','Sin Funcionar'] as $estado)
                        <option value="{{ $estado }}" {{ old('estado_funcional') == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                    @endforeach
                </select>
            </div>
        </section>

        <!-- Botones de Acción -->
        <div class="form-actions d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
            <a href="{{ route('equipos.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver a la lista
            </a>
            <button type="submit" class="btn btn-success btn-lg px-4">
                <i class="fas fa-save me-2"></i>Guardar Equipo
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/equipos.js') }}"></script>
@endpush
