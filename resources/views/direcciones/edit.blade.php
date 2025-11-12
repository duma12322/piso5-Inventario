@extends('layouts.app') {{-- Extiende tu layout principal --}}

@section('title', 'Editar Dirección')

@section('content')
<div class="container mt-4">
    <h3>Editar Dirección</h3>

    {{-- Formulario PATCH a la ruta resource update --}}
    <form method="POST" action="{{ route('direcciones.update', $direccion->id_direccion) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Nombre de la Dirección</label>
            <input type="text" name="nombre_direccion" class="form-control" required value="{{ old('nombre_direccion', $direccion->nombre_direccion) }}">
        </div>
        <div class="form-group mt-3 d-flex justify-content-start gap-2">
            <a href="{{ route('direcciones.index') }}" class="btn btn-secondary mt-2">← Volver</a>
            <button type="submit" class="btn btn-primary mt-2">Actualizar</button>
        </div>
    </form>
</div>
@endsection