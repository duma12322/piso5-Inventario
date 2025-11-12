@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/direcciones.css') }}">

@section('title', 'Direcciones')

@section('content')
<div class="container mt-4">
    <h3>Direcciones</h3>
    <a href="{{ route('direcciones.create') }}" class="btn btn-success mb-2">Agregar Dirección</a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre de la Dirección</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($direcciones as $direccion)
            <tr>
                <td>{{ $direccion->id_direccion }}</td>
                <td>{{ $direccion->nombre_direccion }}</td>
                <td>
                    <a href="{{ route('direcciones.edit', $direccion->id_direccion) }}" class="btn btn-primary btn-sm">Editar</a>

                    <form action="{{ route('direcciones.destroy', $direccion->id_direccion) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar dirección?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection