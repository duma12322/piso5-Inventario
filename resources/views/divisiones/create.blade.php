@extends('layouts.app')

@section('title', 'Agregar División')

@section('content')
<div class="container mt-4">
    <h3>Agregar División</h3>
    <form method="POST" action="{{ route('divisiones.store') }}">
        @csrf

        <div class="form-group">
            <label>Dirección</label>
            <select name="id_direccion" class="form-control" required>
                <option value="">Seleccione</option>
                @foreach ($direcciones as $direccion)
                <option value="{{ $direccion->id_direccion }}">{{ $direccion->nombre_direccion }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Nombre de la División</label>
            <input type="text" name="nombre_division" class="form-control" required>
        </div>
        <div class="form-group mt-3 d-flex justify-content-start gap-2">
            <a href="{{ route('divisiones.index') }}" class="btn btn-secondary mt-2">← Volver</a>
            <button class="btn btn-primary mt-2">Guardar</button>
        </div>
    </form>
</div>
@endsection