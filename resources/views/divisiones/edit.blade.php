@extends('layouts.app')

@section('title', 'Editar División')

@section('content')
<div class="container mt-4">
    <h3>Editar División</h3>
    <form method="POST" action="{{ route('divisiones.update', $division->id_division) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Dirección</label>
            <select name="id_direccion" class="form-control" required>
                @foreach ($direcciones as $direccion)
                <option value="{{ $direccion->id_direccion }}"
                    {{ $direccion->id_direccion == $division->id_direccion ? 'selected' : '' }}>
                    {{ $direccion->nombre_direccion }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Nombre de la División</label>
            <input type="text" name="nombre_division" class="form-control" value="{{ $division->nombre_division }}" required>
        </div>
        <div class="form-group mt-3 d-flex justify-content-start gap-2">
            <a href="{{ route('divisiones.index') }}" class="btn btn-secondary mt-2">← Volver</a>
            <button class="btn btn-primary mt-2">Actualizar</button>
        </div>
    </form>
</div>
@endsection