@extends('layouts.app')

@section('title', 'Agregar Coordinación')

@section('content')
<div class="container mt-4">
    <h3>Agregar Coordinación</h3>
    <form method="POST" action="{{ route('coordinaciones.store') }}">
        @csrf

        <div class="form-group">
            <label>Dirección</label>
            <select id="direccion" class="form-control" required>
                <option value="">Seleccione</option>
                @foreach ($direcciones as $d)
                <option value="{{ $d->id_direccion }}">{{ $d->nombre_direccion }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>División</label>
            <select name="id_division" id="division" class="form-control" required>
                <option value="">Seleccione</option>
            </select>
        </div>

        <div class="form-group">
            <label>Nombre de la Coordinación</label>
            <input type="text" name="nombre_coordinacion" class="form-control" required>
        </div>
        <div class="form-group mt-3 d-flex justify-content-start gap-2">
            <a href="{{ route('coordinaciones.index') }}" class="btn btn-secondary mt-2">← Volver</a>
            <button class="btn btn-primary mt-2">Guardar</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/coordinacion.js') }}"></script>
@endsection