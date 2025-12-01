@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <!-- Contenedor invisible con datos en atributos -->
    <div id="app-data"
        data-direcciones='@json($direcciones)'
        data-divisiones='@json($divisiones)'
        data-coordinaciones='@json($coordinaciones)'
        data-tipos-software='@json($tiposSoftware)'
        data-software-actual='@json($softwareActual)'>
    </div>

    <h3>Editar Equipo</h3>

    <form method="POST" action="{{ route('equipos.update', $equipo) }}">
        @csrf
        @method('PUT')

        <input type="hidden" name="id_equipo" value="{{ $equipo->id }}">

        <div class="form-group">
            <label>Marca</label>
            <input type="text" name="marca" class="form-control" value="{{ old('marca', $equipo->marca) }}" required>
        </div>

        <div class="form-group">
            <label>Modelo</label>
            <input type="text" name="modelo" class="form-control" value="{{ old('modelo', $equipo->modelo) }}" required>
        </div>

        <div class="form-group">
            <label>Serial</label>
            <input type="text" name="serial" class="form-control" value="{{ old('serial', $equipo->serial) }}">
        </div>

        <div class="form-group">
            <label>Número de Bien</label>
            <input type="text" name="numero_bien" class="form-control" value="{{ old('numero_bien', $equipo->numero_bien) }}">
        </div>

        <h5>Software instalado</h5>

        <!-- Sistema Operativo -->
        <div class="form-group">
            <label>Sistema Operativo</label>
            <select name="software_nombre[SO]" class="form-control" required>
                @foreach ($tiposSoftware['Sistema Operativo'] as $so)
                <option value="{{ $so }}" {{ (old('software_nombre.SO') ?? ($softwareActual['SO']['nombre'] ?? '')) === $so ? 'selected' : '' }}>
                    {{ $so }}
                </option>
                @endforeach
            </select>
            <input type="text" name="software_version[SO]" class="form-control mt-1" placeholder="Versión" value="{{ old('software_version.SO', $softwareActual['SO']['version'] ?? '') }}">
            <input type="text" name="software_bits[SO]" class="form-control mt-1" placeholder="Bits" value="{{ old('software_bits.SO', $softwareActual['SO']['bits'] ?? '') }}">
        </div>

        <!-- Ofimática -->
        <div class="form-group">
            <label>Ofimática</label>
            <select name="software_nombre_ofimatica" class="form-control" required>
                @foreach ($tiposSoftware['Ofimática'] as $of)
                <option value="{{ $of }}"
                    {{ (old('software_nombre_ofimatica') ?? ($softwareActual['Ofimática']['nombre'] ?? '')) === $of ? 'selected' : '' }}>
                    {{ $of }}
                </option>
                @endforeach
            </select>
            <input type="text" name="software_version_ofimatica" class="form-control mt-1" placeholder="Versión"
                value="{{ old('software_version_ofimatica', $softwareActual['Ofimática']['version'] ?? '') }}">
            <input type="text" name="software_bits_ofimatica" class="form-control mt-1" placeholder="Bits"
                value="{{ old('software_bits_ofimatica', $softwareActual['Ofimática']['bits'] ?? '') }}">
        </div>

        <!-- Navegadores -->
        <div class="form-group">
            <label>Navegadores</label>
            <div>
                @foreach ($tiposSoftware['Navegador'] as $nav)
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="software_navegadores[]" value="{{ $nav }}"
                        {{ in_array($nav, old('software_navegadores', $softwareActual['Navegador'] ?? [])) ? 'checked' : '' }}>
                    <label class="form-check-label">{{ $nav }}</label>
                </div>
                @endforeach
            </div>
        </div>

        <div class="form-group">
            <label>Tipo Gabinete</label>
            <input type="text" name="tipo_gabinete" class="form-control" value="{{ old('tipo_gabinete', $equipo->tipo_gabinete) }}">
        </div>

        <div class="form-group">
            <label>Estado Gabinete</label>
            <select name="estado_gabinete" class="form-control">
                @foreach (['Nuevo','Deteriorado','Dañado'] as $estado)
                <option value="{{ $estado }}" {{ old('estado_gabinete', $equipo->estado_gabinete) == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Nivel del equipo</label>
            <select id="nivel-equipo" name="nivel_equipo" class="form-control">
                <option value="">Seleccione nivel</option>
                <option value="direccion" {{ $equipo->id_direccion && !$equipo->id_division && !$equipo->id_coordinacion ? 'selected' : '' }}>Dirección</option>
                <option value="division" {{ $equipo->id_division && !$equipo->id_coordinacion ? 'selected' : '' }}>División</option>
                <option value="coordinacion" {{ $equipo->id_coordinacion ? 'selected' : '' }}>Coordinación</option>
            </select>
        </div>

        <!-- Direcciones, divisiones y coordinaciones -->
        <div class="form-group nivel direccion">
            <label>Dirección</label>
            <select name="id_direccion" id="direccion" class="form-control">
                <option value="">Seleccione</option>
                @foreach ($direcciones as $d)
                <option value="{{ $d->id_direccion }}" {{ ($equipo->id_direccion == $d->id_direccion) ? 'selected' : '' }}>
                    {{ $d->nombre_direccion }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="form-group nivel division">
            <label>División</label>
            <select name="id_division" id="division" class="form-control">
                <option value="">Seleccione</option>
                @foreach ($divisiones as $div)
                <option value="{{ $div->id_division }}" {{ ($equipo->id_division == $div->id_division) ? 'selected' : '' }}>
                    {{ $div->nombre_division }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="form-group nivel coordinacion">
            <label>Coordinación</label>
            <select name="id_coordinacion" id="coordinacion" class="form-control">
                <option value="">Seleccione</option>
                @foreach ($coordinaciones as $c)
                <option value="{{ $c['id_coordinacion'] }}" {{ (isset($equipo) && $equipo->id_coordinacion == $c['id_coordinacion']) ? 'selected' : '' }}>
                    {{ $c['nombre_coordinacion'] }}
                </option>
                @endforeach
            </select>
        </div>

        <!-- Estados -->
        <div class="form-group">
            <label>Estado Funcional</label>
            <select name="estado_funcional" class="form-control">
                @foreach (['Buen Funcionamiento','Operativo','Sin Funcionar'] as $estado)
                <option value="{{ $estado }}" {{ old('estado_funcional', $equipo->estado_funcional) == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Estado Tecnológico</label>
            <select name="estado_tecnologico" class="form-control">
                @foreach (['Nuevo','Actualizable','Obsoleto'] as $estado)
                <option value="{{ $estado }}" {{ old('estado_tecnologico', $equipo->estado_tecnologico) == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group mt-3 d-flex justify-content-start gap-2">
            <a href="{{ route('equipos.index') }}" class="btn btn-secondary mt-2">← Volver</a>
            <button class="btn btn-primary mt-2">Actualizar</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/equipos.js') }}"></script>
@endsection
