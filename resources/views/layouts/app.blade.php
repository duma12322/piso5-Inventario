<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'Piso 5 Inventario') }}</title>
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

@if(session('debug_user'))
<script>
    console.log("Usuario en sesión al eliminar: {{ session('debug_user') }}");
</script>
@endif

<body>
    @if (!request()->is('login'))
    {{-- Menú lateral solo si NO estás en /login --}}
    <div class="d-flex">
        <div class="sidebar vh-100 p-3 position-relative">
            <h5 class="text-center mb-4 text-primary fw-bold">{{ config('app.name', 'Inventario') }}</h5>

            <div class="list-group">
                {{-- Enlaces generales --}}
                <a href="{{ route('dashboard.index') }}" class="list-group-item list-group-item-action">🏠 Dashboard</a>
                <a href="{{ route('equipos.index') }}" class="list-group-item list-group-item-action">💻 Equipos</a>
                <a href="{{ route('componentes.index') }}" class="list-group-item list-group-item-action">🔌 Componentes</a>
                <a href="{{ route('componentesOpcionales.index') }}" class="list-group-item list-group-item-action text-info fw-bold">⚙️ Componentes Opcionales</a>

                {{-- Solo el ADMIN puede ver estas opciones --}}
                @if(Auth::user() && Auth::user()->rol === 'Administrador')
                <a href="{{ route('direcciones.index') }}" class="list-group-item list-group-item-action">🏢 Direcciones</a>
                <a href="{{ route('divisiones.index') }}" class="list-group-item list-group-item-action">📂 Divisiones</a>
                <a href="{{ route('coordinaciones.index') }}" class="list-group-item list-group-item-action">🗂 Coordinaciones</a>
                <a href="{{ route('equipos.inactivos') }}" class="list-group-item list-group-item-action text-danger fw-bold">💀 Equipos Inactivos</a>
                <a href="{{ route('usuarios.index') }}" class="list-group-item list-group-item-action">👤 Usuarios</a>
                <a href="{{ route('logs.index') }}" class="list-group-item list-group-item-action text-warning fw-bold">📜 Logs</a>
                @endif

                {{-- Botón de cerrar sesión --}}
                <form action="{{ route('logout') }}" method="POST" class="footer-btn mt-3">
                    @csrf
                    <button type="submit" class="list-group-item list-group-item-action logout-btn">
                        🚪 Cerrar sesión
                    </button>
                </form>
            </div>
        </div>

        {{-- Contenido principal --}}
        <div class="flex-grow-1 p-4">
            @yield('content')
        </div>
    </div>
    @else
    {{-- Página de login sin menú --}}
    <div class="p-4 w-100">
        @yield('content')
    </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>

</html>