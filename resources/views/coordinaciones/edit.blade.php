@extends('layouts.app')

@section('title', 'Editar Coordinación')

@section('content')
<div class="container mt-4">
    <h3>Editar Coordinación</h3>

    <form method="POST" action="{{ route('coordinaciones.update', $coordinacion->id_coordinacion) }}">
        @csrf
        @method('PUT')

        {{-- DIRECCIÓN --}}
        <div class="form-group">
            <label>Dirección</label>
            <select name="id_direccion" id="direccion" class="form-control" required>
                <option value="">Seleccione</option>
                @foreach ($direcciones as $d)
                <option value="{{ $d->id_direccion }}"
                    {{ $d->id_direccion == $coordinacion->division->id_direccion ? 'selected' : '' }}>
                    {{ $d->nombre_direccion }}
                </option>
                @endforeach
            </select>
        </div>

        {{-- DIVISIÓN --}}
        <div class="form-group">
            <label>División</label>
            <select name="id_division" id="division" class="form-control" required>
                <option value="">Seleccione</option>
                @foreach ($divisiones as $div)
                <option value="{{ $div->id_division }}"
                    {{ $div->id_division == $coordinacion->id_division ? 'selected' : '' }}>
                    {{ $div->nombre_division }}
                </option>
                @endforeach
            </select>
        </div>

        {{-- COORDINACIÓN --}}
        <div class="form-group">
            <label>Nombre de la Coordinación</label>
            <input type="text" name="nombre_coordinacion" class="form-control"
                value="{{ $coordinacion->nombre_coordinacion }}" required>
        </div>
        <div class="form-group mt-3 d-flex justify-content-start gap-2">
            <a href="{{ route('coordinaciones.index') }}" class="btn btn-secondary mt-2">← Volver</a>
            <button class="btn btn-primary mt-2">Actualizar</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/coordinacion.js') }}"></script>
@endsection