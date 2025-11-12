<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'Piso 5 Inventario') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .sidebar {
            width: 230px;
            background: #ffffff;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .list-group-item {
            border: none;
            font-weight: 500;
        }

        .list-group-item:hover {
            background-color: #e9ecef;
        }

        .logout-btn {
            color: #dc3545 !important;
            font-weight: bold;
        }

        .logout-btn:hover {
            background-color: #f8d7da;
            color: #b02a37 !important;
        }

        .footer-btn {
            position: absolute;
            bottom: 20px;
            width: 85%;
            left: 7.5%;
        }
    </style>
</head>
@if(session('debug_user'))
<script>
    console.log("Usuario en sesión al eliminar: {{ session('debug_user') }}");
</script>
@endif

<body>
    @if (!request()->is('login'))
    {{-- Menú lateral solo se muestra si NO estás en /login --}}
    <div class="d-flex">
        <div class="sidebar vh-100 p-3 position-relative">
            <h5 class="text-center mb-4 text-primary fw-bold">{{ config('app.name', 'Inventario') }}</h5>
            <div class="list-group">
                <a href="{{ route('dashboard.index') }}" class="list-group-item list-group-item-action">🏠 Dashboard</a>
                <a href="{{ route('equipos.index') }}" class="list-group-item list-group-item-action">💻 Equipos</a>
                <a href="{{ route('componentes.index') }}" class="list-group-item list-group-item-action">🔌 Componentes</a>
                <a href="{{ route('componentesOpcionales.index') }}" class="list-group-item list-group-item-action text-info fw-bold">⚙️ Componentes Opcionales</a>
                <a href="{{ route('direcciones.index') }}" class="list-group-item list-group-item-action">🏢 Direcciones</a>
                <a href="{{ route('divisiones.index') }}" class="list-group-item list-group-item-action">📂 Divisiones</a>
                <a href="{{ route('coordinaciones.index') }}" class="list-group-item list-group-item-action">🗂 Coordinaciones</a>
                <a href="{{ route('usuarios.index') }}" class="list-group-item list-group-item-action">👤 Usuarios</a>
                <a href="{{ route('logs.index') }}" class="list-group-item list-group-item-action text-warning fw-bold">📜 Logs</a>

                {{-- Botón de cerrar sesión --}}
                <form action="{{ route('logout') }}" method="POST" class="footer-btn">
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
    {{-- Si estamos en /login, solo muestra el contenido (sin menú) --}}
    <div class="p-4 w-100">
        @yield('content')
    </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Aquí Laravel insertará los scripts personalizados -->
    @yield('scripts')
</body>

</html>