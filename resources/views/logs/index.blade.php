@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/logins.css') }}">
@section('content')
<div class="container mt-4">
    <h3 class="mb-0">
        <span class="material-symbols-outlined">
            Registro
        </span>
        de Logs
    </h3>

    <!-- BUSCADOR -->
    <div class="d-flex justify-content-end align-items-center mb-3">
        <form method="GET" action="{{ route('logs.index') }}" class="d-flex buscador-mini" style="gap: 5px;">
            <input type="text" name="search_usuario" class="form-control"
                placeholder="Usuario..."
                value="{{ request('search_usuario') }}">
            <input type="text" name="search_accion" class="form-control"
                placeholder="Acción..."
                value="{{ request('search_accion') }}">
            <input type="text" name="search_fecha" class="form-control"
                placeholder="dd/mm/yyyy"
                value="{{ request('search_fecha') }}">
            <button class="btn btn-primary">🔍</button>
        </form>
    </div>

    <!-- TABLA -->
    <table class="table table-bordered table-striped mt-3 text-center">
        <thead class="custom-thead">
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
                <td>{{ $logs->firstItem() + $index }}</td>
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

    <!-- PAGINACIÓN -->
    <div class="mt-3">
        <ul class="pagination">
            {{-- Botón anterior --}}
            @if($logs->onFirstPage())
            @else
            <li><a href="{{ $logs->previousPageUrl() }}">«</a></li>
            @endif

            {{-- Rango de páginas --}}
            @php
            $start = max($logs->currentPage() - 2, 1);
            $end = min($logs->currentPage() + 2, $logs->lastPage());
            @endphp

            @for ($page = $start; $page <= $end; $page++)
                <li class="{{ $page == $logs->currentPage() ? 'active' : '' }}">
                <a href="{{ $logs->url($page) }}">{{ $page }}</a>
                </li>
                @endfor

                {{-- Botón siguiente --}}
                @if($logs->hasMorePages())
                <li><a href="{{ $logs->nextPageUrl() }}">»</a></li>
                @endif
        </ul>
    </div>

</div>
@endsection