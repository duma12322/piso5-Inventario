<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    {{-- Título dinámico de la aplicación --}}
    <title>{{ config('app.name', 'Piso 5 Inventario') }}</title>

    {{-- Estilos personalizados del sidebar --}}
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    {{-- Font Awesome para iconos --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- Bootstrap para estilos generales --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

{{-- Debug: muestra en consola el usuario de sesión al eliminar --}}
@if(session('debug_user'))
<script>
    console.log("Usuario en sesión al eliminar: {{ session('debug_user') }}");
</script>
@endif

<body>
    {{-- Menú lateral visible solo si NO estamos en la ruta /login --}}
    @if (!request()->is('login'))
    <div class="d-flex">
        {{-- Sidebar moderno --}}
        <div class="sidebar vh-100 position-fixed">

            {{-- Header del sidebar --}}
            <div class="sidebar-header">
                <div class="logo-container">
                    <div class="logo-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <h5 class="logo-text">{{ config('app.name', 'Inventario') }}</h5>
                </div>
                {{-- Botón para colapsar sidebar --}}
                <div class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-chevron-left"></i>
                </div>
            </div>

            {{-- Navegación principal --}}
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="section-label">Navegación Principal</div>

                    {{-- Dashboard --}}
                    <a href="{{ route('dashboard.index') }}" class="nav-item" data-tooltip="Dashboard">
                        <div class="nav-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <span class="nav-text">Dashboard</span>
                        <div class="nav-indicator"></div>
                    </a>

                    {{-- Equipos --}}
                    <a href="{{ route('equipos.index') }}" class="nav-item" data-tooltip="Equipos">
                        <div class="nav-icon">
                            <i class="fas fa-laptop"></i>
                        </div>
                        <span class="nav-text">Equipos</span>
                        <div class="nav-indicator"></div>
                    </a>

                    {{-- Componentes --}}
                    <a href="{{ route('componentes.index') }}" class="nav-item" data-tooltip="Componentes">
                        <div class="nav-icon">
                            <i class="fas fa-microchip"></i>
                        </div>
                        <span class="nav-text">Componentes</span>
                        <div class="nav-indicator"></div>
                    </a>

                    {{-- Componentes Opcionales destacado --}}
                    <a href="{{ route('componentesOpcionales.index') }}" class="nav-item featured" data-tooltip="Componentes Opcionales">
                        <div class="nav-icon">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <span class="nav-text">Componentes Opcionales</span>
                        <div class="nav-indicator"></div>
                    </a>
                </div>

                {{-- Sección de Administración solo visible para Administrador --}}
                @if(Auth::user() && Auth::user()->rol === 'Administrador')
                <div class="nav-section">
                    <div class="section-label">Administración</div>

                    {{-- Direcciones --}}
                    <a href="{{ route('direcciones.index') }}" class="nav-item" data-tooltip="Direcciones">
                        <div class="nav-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <span class="nav-text">Direcciones</span>
                        <div class="nav-indicator"></div>
                    </a>

                    {{-- Divisiones --}}
                    <a href="{{ route('divisiones.index') }}" class="nav-item" data-tooltip="Divisiones">
                        <div class="nav-icon">
                            <i class="fas fa-sitemap"></i>
                        </div>
                        <span class="nav-text">Divisiones</span>
                        <div class="nav-indicator"></div>
                    </a>

                    {{-- Coordinaciones --}}
                    <a href="{{ route('coordinaciones.index') }}" class="nav-item" data-tooltip="Coordinaciones">
                        <div class="nav-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <span class="nav-text">Coordinaciones</span>
                        <div class="nav-indicator"></div>
                    </a>

                    {{-- Equipos Inactivos --}}
                    <a href="{{ route('equipos.inactivos') }}" class="nav-item warning" data-tooltip="Equipos Inactivos">
                        <div class="nav-icon">
                            <i class="fas fa-skull-crossbones"></i>
                        </div>
                        <span class="nav-text">Equipos Inactivos</span>
                        <div class="nav-indicator"></div>
                    </a>

                    {{-- Usuarios --}}
                    <a href="{{ route('usuarios.index') }}" class="nav-item" data-tooltip="Usuarios">
                        <div class="nav-icon">
                            <i class="fas fa-user-cog"></i>
                        </div>
                        <span class="nav-text">Usuarios</span>
                        <div class="nav-indicator"></div>
                    </a>

                    {{-- Bitácora --}}
                    <a href="{{ route('logs.index') }}" class="nav-item warning" data-tooltip="Logs del Sistema">
                        <div class="nav-icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <span class="nav-text">Bitacora</span>
                        <div class="nav-indicator"></div>
                    </a>
                </div>
                @endif

                {{-- Manual de ayuda según rol --}}
                @if(Auth::user() && Auth::user()->rol === 'Administrador')
                <a href="{{ asset('ayuda/ayuda-admin.pdf') }}" class="nav-item" target="_blank" data-tooltip="Manual Administrador">
                    <div class="nav-icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <span class="nav-text">Ayuda</span>
                    <div class="nav-indicator"></div>
                </a>
                @endif

                @if(Auth::user() && Auth::user()->rol === 'Usuario')
                <a href="{{ asset('ayuda/ayuda-usuario.pdf') }}" class="nav-item" target="_blank" data-tooltip="Manual Administrador">
                    <div class="nav-icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <span class="nav-text">Ayuda</span>
                    <div class="nav-indicator"></div>
                </a>
                @endif

            </nav>

            {{-- Footer del sidebar con info de usuario y logout --}}
            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="user-details">
                        <div class="user-name">{{ Auth::user()->name ?? 'Usuario' }}</div>
                        <div class="user-role">{{ Auth::user()->rol ?? 'Rol' }}</div>
                    </div>
                </div>

                {{-- Formulario de cierre de sesión --}}
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <div class="logout-icon">
                            <i class="fas fa-sign-out-alt"></i>
                        </div>
                        <span class="logout-text">Cerrar Sesión</span>
                    </button>
                </form>
            </div>

            {{-- Contenedor para efectos visuales tipo partículas --}}
            <div class="sidebar-particles" id="particles"></div>
        </div>

        {{-- Contenido principal de la página --}}
        <div class="main-content flex-grow-1">
            <div class="content-wrapper">
                @yield('content')
            </div>
        </div>
    </div>

    @else
    {{-- Página de login: no se muestra menú lateral --}}
    <div class="login-page">
        @yield('content')
    </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ----------------------------------------
            // Referencias a elementos principales
            // ----------------------------------------
            const sidebar = document.querySelector('.sidebar'); // Sidebar lateral
            const mainContent = document.querySelector('.main-content'); // Contenido principal
            const sidebarToggle = document.getElementById('sidebarToggle'); // Botón de colapsar/expandir

            // ----------------------------------------
            // Crear elementos para móvil
            // ----------------------------------------
            const mobileBtn = document.createElement('button'); // Botón para abrir menú en móvil
            mobileBtn.className = 'mobile-menu-btn';
            mobileBtn.innerHTML = '<i class="fas fa-bars"></i>'; // Icono de hamburguesa
            document.body.appendChild(mobileBtn);

            const overlay = document.createElement('div'); // Fondo semi-transparente cuando el sidebar está abierto en móvil
            overlay.className = 'sidebar-overlay';
            document.body.appendChild(overlay);

            // ----------------------------------------
            // Toggle Desktop: colapsar/expandir sidebar
            // ----------------------------------------
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    if (window.innerWidth > 768) { // Solo desktop
                        sidebar.classList.toggle('collapsed'); // Alterna clase collapsed
                        mainContent.classList.toggle('expanded'); // Ajusta contenido principal

                        // Cambiar ícono según estado
                        const icon = this.querySelector('i');
                        if (sidebar.classList.contains('collapsed')) {
                            icon.className = 'fas fa-chevron-right';
                        } else {
                            icon.className = 'fas fa-chevron-left';
                        }
                    } else {
                        // En móvil, cerrar sidebar y overlay si se hace click en el toggle interno
                        sidebar.classList.remove('mobile-open');
                        overlay.classList.remove('active');
                        mobileBtn.style.opacity = '1';
                    }
                });
            }

            // ----------------------------------------
            // Toggle Móvil: abrir sidebar
            // ----------------------------------------
            mobileBtn.addEventListener('click', function() {
                sidebar.classList.add('mobile-open'); // Mostrar sidebar
                overlay.classList.add('active'); // Mostrar overlay
                this.style.opacity = '0'; // Ocultar botón móvil
            });

            // ----------------------------------------
            // Cerrar sidebar al clickear overlay
            // ----------------------------------------
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('active');
                mobileBtn.style.opacity = '1';
            });

            // ----------------------------------------
            // Efectos hover para items del menú
            // ----------------------------------------
            const navItems = document.querySelectorAll('.nav-item');
            navItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    // Solo aplicar hover si sidebar NO está colapsado o si estamos en móvil
                    if (!sidebar.classList.contains('collapsed') || window.innerWidth <= 768) {
                        this.style.transform = 'translateX(8px)';
                    }
                });

                item.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateX(0)';
                });
            });

            // ----------------------------------------
            // Indicador de página activa
            // ----------------------------------------
            const currentPath = window.location.pathname;
            navItems.forEach((item, index) => {
                if (item.href && currentPath.includes(new URL(item.href).pathname)) {
                    item.classList.add('active'); // Marca el item actual
                }
                item.style.setProperty('--item-index', index); // Variable CSS para animaciones si se usa
            });

            // ----------------------------------------
            // Efecto de partículas en sidebar
            // ----------------------------------------
            const particlesContainer = document.getElementById('particles');
            if (particlesContainer) {
                createParticles(particlesContainer);
            }

            function createParticles(container) {
                for (let i = 0; i < 15; i++) {
                    const particle = document.createElement('div');
                    particle.className = 'particle';
                    particle.style.left = Math.random() * 100 + '%';
                    particle.style.animationDelay = Math.random() * 5 + 's';
                    particle.style.animationDuration = (Math.random() * 10 + 10) + 's';
                    container.appendChild(particle);
                }
            }

            // ----------------------------------------
            // Tooltips para items cuando sidebar está colapsado
            // ----------------------------------------
            const tooltipItems = document.querySelectorAll('[data-tooltip]');
            tooltipItems.forEach(item => {
                item.addEventListener('mouseenter', function(e) {
                    if (window.innerWidth > 768 && sidebar.classList.contains('collapsed')) {
                        const tooltip = document.createElement('div');
                        tooltip.className = 'sidebar-tooltip';
                        tooltip.textContent = this.getAttribute('data-tooltip');
                        document.body.appendChild(tooltip);

                        const rect = this.getBoundingClientRect();
                        tooltip.style.left = rect.right + 10 + 'px';
                        tooltip.style.top = rect.top + (rect.height - tooltip.offsetHeight) / 2 + 'px';

                        this.tooltipElement = tooltip;
                    }
                });

                item.addEventListener('mouseleave', function() {
                    if (this.tooltipElement) {
                        this.tooltipElement.remove();
                        this.tooltipElement = null;
                    }
                });
            });

            // ----------------------------------------
            // Manejar cambios de tamaño de ventana
            // ----------------------------------------
            window.addEventListener('resize', function() {
                if (!sidebar) return; // Evitar error si estamos en login

                if (window.innerWidth > 768) {
                    sidebar.classList.remove('mobile-open');
                    overlay.classList.remove('active');
                    mobileBtn.style.opacity = '0';
                    mobileBtn.style.pointerEvents = 'none';
                } else {
                    mobileBtn.style.opacity = '1';
                    mobileBtn.style.pointerEvents = 'auto';
                }
            });

            // ----------------------------------------
            // Inicializar estado del botón móvil según pantalla
            // ----------------------------------------
            if (window.innerWidth > 768) {
                mobileBtn.style.opacity = '0';
                mobileBtn.style.pointerEvents = 'none';
            }
        });
    </script>

    {{-- Scripts adicionales que se pueden inyectar desde cada página --}}
    @yield('scripts')

</body>

</html>