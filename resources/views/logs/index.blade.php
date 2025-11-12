@extends('layouts.app')
@if(session('debug_user'))
<script>
    console.log("Usuario en sesión al eliminar: {{ session('debug_user') }}");
</script>
@endif
@section('content')
<div class="container mt-4">
    <h3>📜 Registro de Logs</h3>

    <table class="table table-bordered table-striped mt-3">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Usuario</th>
                <th>Acción</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $index => $log)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $log->usuario ?? 'Sistema' }}</td>
                <td>{{ $log->accion }}</td>
                <td>{{ \Carbon\Carbon::parse($log->fecha)->format('d/m/Y H:i:s') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">No hay registros</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection