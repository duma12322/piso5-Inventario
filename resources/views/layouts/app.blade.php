<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'Piso 5 Inventario') }}</title>
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        {{-- Sidebar Moderno --}}
        <div class="sidebar vh-100 position-fixed">
            {{-- Header del Sidebar --}}
            <div class="sidebar-header">
                <div class="logo-container">
                    <div class="logo-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <h5 class="logo-text">{{ config('app.name', 'Inventario') }}</h5>
                </div>
                <div class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-chevron-left"></i>
                </div>
            </div>

            {{-- Menú de Navegación --}}
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="section-label">Navegación Principal</div>
                    <a href="{{ route('dashboard.index') }}" class="nav-item" data-tooltip="Dashboard">
                        <div class="nav-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <span class="nav-text">Dashboard</span>
                        <div class="nav-indicator"></div>
                    </a>

                    <a href="{{ route('equipos.index') }}" class="nav-item" data-tooltip="Equipos">
                        <div class="nav-icon">
                            <i class="fas fa-laptop"></i>
                        </div>
                        <span class="nav-text">Equipos</span>
                        <div class="nav-indicator"></div>
                    </a>

                    <a href="{{ route('componentes.index') }}" class="nav-item" data-tooltip="Componentes">
                        <div class="nav-icon">
                            <i class="fas fa-microchip"></i>
                        </div>
                        <span class="nav-text">Componentes</span>
                        <div class="nav-indicator"></div>
                    </a>

                    <a href="{{ route('componentesOpcionales.index') }}" class="nav-item featured"
                        data-tooltip="Componentes Opcionales">
                        <div class="nav-icon">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <span class="nav-text">Componentes Opcionales</span>
                        <!-- <div class="nav-badge">Nuevo</div> -->
                        <div class="nav-indicator"></div>
                    </a>
                </div>

                {{-- Solo el ADMIN puede ver estas opciones --}}
                @if(Auth::user() && Auth::user()->rol === 'Administrador')
                <div class="nav-section">
                    <div class="section-label">Administración</div>
                    <a href="{{ route('direcciones.index') }}" class="nav-item" data-tooltip="Direcciones">
                        <div class="nav-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <span class="nav-text">Direcciones</span>
                        <div class="nav-indicator"></div>
                    </a>

                    <a href="{{ route('divisiones.index') }}" class="nav-item" data-tooltip="Divisiones">
                        <div class="nav-icon">
                            <i class="fas fa-sitemap"></i>
                        </div>
                        <span class="nav-text">Divisiones</span>
                        <div class="nav-indicator"></div>
                    </a>

                    <a href="{{ route('coordinaciones.index') }}" class="nav-item" data-tooltip="Coordinaciones">
                        <div class="nav-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <span class="nav-text">Coordinaciones</span>
                        <div class="nav-indicator"></div>
                    </a>

                    <a href="{{ route('equipos.inactivos') }}" class="nav-item warning"
                        data-tooltip="Equipos Inactivos">
                        <div class="nav-icon">
                            <i class="fas fa-skull-crossbones"></i>
                        </div>
                        <span class="nav-text">Equipos Inactivos</span>
                        <div class="nav-indicator"></div>
                    </a>

                    <a href="{{ route('usuarios.index') }}" class="nav-item" data-tooltip="Usuarios">
                        <div class="nav-icon">
                            <i class="fas fa-user-cog"></i>
                        </div>
                        <span class="nav-text">Usuarios</span>
                        <div class="nav-indicator"></div>
                    </a>

                    <a href="{{ route('logs.index') }}" class="nav-item warning" data-tooltip="Logs del Sistema">
                        <div class="nav-icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <span class="nav-text">Bitacora</span>
                        <div class="nav-indicator"></div>
                    </a>
                </div>
                @endif

                @if(Auth::user() && Auth::user()->rol === 'Administrador')
                <a href="{{ asset('ayuda/ayuda-admin.pdf') }}"
                    class="nav-item"
                    target="_blank"
                    data-tooltip="Manual Administrador">
                    <div class="nav-icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <span class="nav-text">Ayuda</span>
                    <div class="nav-indicator"></div>
                </a>
                @endif

                @if(Auth::user() && Auth::user()->rol === 'Usuario')
                <a href="{{ asset('ayuda/ayuda-usuario.pdf') }}"
                    class="nav-item"
                    target="_blank"
                    data-tooltip="Manual Administrador">
                    <div class="nav-icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <span class="nav-text">Ayuda</span>
                    <div class="nav-indicator"></div>
                </a>
                @endif

            </nav>

            {{-- Footer del Sidebar --}}
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

            {{-- Efecto de Partículas --}}
            <div class="sidebar-particles" id="particles"></div>
        </div>

        {{-- Contenido principal --}}
        <div class="main-content flex-grow-1">
            <div class="content-wrapper">
                @yield('content')
            </div>
        </div>
    </div>
    @else
    {{-- Página de login sin menú --}}
    <div class="login-page">
        @yield('content')
    </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            const sidebarToggle = document.getElementById('sidebarToggle');

            // Crear elementos para móvil
            const mobileBtn = document.createElement('button');
            mobileBtn.className = 'mobile-menu-btn';
            mobileBtn.innerHTML = '<i class="fas fa-bars"></i>';
            document.body.appendChild(mobileBtn);

            const overlay = document.createElement('div');
            overlay.className = 'sidebar-overlay';
            document.body.appendChild(overlay);

            // Toggle Desktop (Colapsar/Expandir)
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    if (window.innerWidth > 768) {
                        sidebar.classList.toggle('collapsed');
                        mainContent.classList.toggle('expanded');

                        // Cambiar ícono
                        const icon = this.querySelector('i');
                        if (sidebar.classList.contains('collapsed')) {
                            icon.className = 'fas fa-chevron-right';
                        } else {
                            icon.className = 'fas fa-chevron-left';
                        }
                    } else {
                        // En móvil, el botón interno cierra el sidebar
                        sidebar.classList.remove('mobile-open');
                        overlay.classList.remove('active');
                        mobileBtn.style.opacity = '1';
                    }
                });
            }

            // Toggle Móvil (Abrir/Cerrar)
            mobileBtn.addEventListener('click', function() {
                sidebar.classList.add('mobile-open');
                overlay.classList.add('active');
                this.style.opacity = '0';
            });

            // Cerrar al clickear overlay
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('active');
                mobileBtn.style.opacity = '1';
            });

            // Efectos hover para items del menú
            const navItems = document.querySelectorAll('.nav-item');
            navItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    // Solo aplicar efecto hover si el sidebar NO está colapsado o si estamos en móvil
                    if (!sidebar.classList.contains('collapsed') || window.innerWidth <= 768) {
                        this.style.transform = 'translateX(8px)';
                    }
                });

                item.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateX(0)';
                });
            });

            // Indicador de página activa
            const currentPath = window.location.pathname;
            navItems.forEach((item, index) => {
                if (item.href && currentPath.includes(new URL(item.href).pathname)) {
                    item.classList.add('active');
                }
                item.style.setProperty('--item-index', index);
            });

            // Efecto de partículas
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

            // Tooltips (Solo desktop colapsado)
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

            // Manejar resize
            window.addEventListener('resize', function() {
                if (!sidebar) return; // Prevent error on login page

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

            // Inicializar estado del botón móvil
            if (window.innerWidth > 768) {
                mobileBtn.style.opacity = '0';
                mobileBtn.style.pointerEvents = 'none';
            }
        });
    </script>

    @yield('scripts')

</body>

</html>