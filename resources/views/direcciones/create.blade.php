@extends('layouts.app') {{-- Extiende tu layout principal --}}
<!-- <link rel="stylesheet" href="{{ asset('css/direcciones.create.css') }}"> -->
@section('title', 'Agregar Dirección')

@section('content')
<div class="container mt-4">
    <h3>Agregar Dirección</h3>

    {{-- Formulario POST a la ruta resource store --}}
    <form method="POST" action="{{ route('direcciones.store') }}">
        @csrf

        <div class="form-group">
            <label>Nombre de la Dirección</label>
            <input type="text" name="nombre_direccion" class="form-control" required value="{{ old('nombre_direccion') }}">
        </div>
        <div class="form-group mt-3 d-flex justify-content-start gap-2">
            <a href="{{ route('direcciones.index') }}" class="btn btn-secondary mt-2">← Volver</a>
            <button type="submit" class="btn btn-primary mt-2">Guardar</button>
        </div>
    </form>
</div>
@endsection