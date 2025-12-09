<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Innovatec - Gestión de Concursos')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --bg-primary: #f8f9fa;
            --bg-secondary: #ffffff;
            --text-primary: #2c3e50;
            --text-secondary: #7f8c8d;
            --border-color: #ecf0f1;
            --card-bg: #ffffff;
            --gradient-start: #667eea;
            --gradient-end: #764ba2;
            --input-bg: #f8f9fa;
            --input-border: #e0e0e0;
            --alert-bg: #ecf0f1;
        }

        [data-theme="dark"] {
            --bg-primary: #0f0f0f;
            --bg-secondary: #1a1a1a;
            --text-primary: #e0e0e0;
            --text-secondary: #a0a0a0;
            --border-color: #333333;
            --card-bg: #1e1e1e;
            --gradient-start: #5a67d8;
            --gradient-end: #6b4ba1;
            --input-bg: #2a2a2a;
            --input-border: #444444;
            --alert-bg: #2a2a2a;
        }

        * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        .eventos-container {
            transition: background 0.3s ease !important;
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            padding-top: 56px; /* Espacio para el navbar fixed */
        }

        .container, .container-fluid {
            color: var(--text-primary);
        }

        .navbar-top {
            background-color: #343a40;
        }

        .card {
            background-color: var(--card-bg);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .card-header {
            background-color: var(--card-bg) !important;
            border-color: var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        .card-footer {
            background-color: var(--card-bg) !important;
            border-color: var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        .form-control, .form-select, textarea {
            background-color: var(--input-bg);
            border-color: var(--input-border);
            color: var(--text-primary);
        }

        .form-control::placeholder {
            color: var(--text-secondary);
        }

        .form-control:focus, .form-select:focus, textarea:focus {
            background-color: var(--input-bg);
            border-color: #667eea;
            color: var(--text-primary);
        }

        .alert {
            background-color: var(--alert-bg);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .alert-success {
            background-color: rgba(39, 174, 96, 0.1);
            border-color: #27ae60;
            color: #27ae60;
        }

        .alert-info {
            background-color: rgba(52, 152, 219, 0.1);
            border-color: #3498db;
            color: #3498db;
        }

        .alert-warning {
            background-color: rgba(243, 156, 18, 0.1);
            border-color: #f39c12;
            color: #f39c12;
        }

        .alert-danger {
            background-color: rgba(231, 76, 60, 0.1);
            border-color: #e74c3c;
            color: #e74c3c;
        }

        .list-group-item {
            background-color: var(--card-bg);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .table {
            color: var(--text-primary);
            border-color: var(--border-color);
        }

        .table thead {
            background-color: var(--input-bg);
            color: var(--text-primary);
        }

        .table tbody tr {
            border-color: var(--border-color);
        }

        .table tbody tr:hover {
            background-color: var(--input-bg);
        }

        .btn-outline-secondary {
            color: var(--text-secondary);
            border-color: var(--border-color);
        }

        .btn-outline-secondary:hover {
            background-color: var(--input-bg);
            border-color: var(--text-secondary);
        }

        .text-muted {
            color: var(--text-secondary) !important;
        }

        .border {
            border-color: var(--border-color) !important;
        }

        hr {
            border-color: var(--border-color);
        }

        /* Estilos base compartidos */
        .card-evento, .card-equipo {
            border-radius: 8px;
            transition: transform 0.2s;
        }
        .card-evento:hover, .card-equipo:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        }

        .theme-toggle {
            cursor: pointer;
            font-size: 1.2rem;
            transition: transform 0.3s;
        }

        .theme-toggle:hover {
            transform: rotate(20deg);
        }

        [data-theme="dark"] .card,
        [data-theme="dark"] .list-group-item {
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        }

        [data-theme="dark"] .shadow-sm {
            box-shadow: 0 2px 8px rgba(0,0,0,0.3) !important;
        }

        [data-theme="dark"] .shadow {
            box-shadow: 0 4px 12px rgba(0,0,0,0.4) !important;
        }

        .modal-content {
            background-color: var(--card-bg);
            color: var(--text-primary);
            border-color: var(--border-color);
        }

        .modal-header {
            border-color: var(--border-color);
        }

        .modal-footer {
            border-color: var(--border-color);
        }

        .dropdown-menu {
            background-color: var(--card-bg);
            border-color: var(--border-color);
        }

        .dropdown-item {
            color: var(--text-primary);
        }

        .dropdown-item:hover,
        .dropdown-item:focus {
            background-color: var(--input-bg);
            color: var(--text-primary);
        }

        [data-theme="dark"] .dropdown-menu-dark {
            background-color: var(--card-bg);
            border-color: var(--border-color);
        }

        [data-theme="dark"] .dropdown-menu-dark .dropdown-item {
            color: var(--text-primary);
        }

        [data-theme="dark"] .dropdown-menu-dark .dropdown-item:hover {
            background-color: var(--input-bg);
            color: var(--text-primary);
        }

        /* Mejorado para modo oscuro */
        [data-theme="dark"] p,
        [data-theme="dark"] span,
        [data-theme="dark"] h1,
        [data-theme="dark"] h2,
        [data-theme="dark"] h3,
        [data-theme="dark"] h4,
        [data-theme="dark"] h5,
        [data-theme="dark"] h6,
        [data-theme="dark"] .lead,
        [data-theme="dark"] .small {
            color: var(--text-primary);
        }

        [data-theme="dark"] .text-muted,
        [data-theme="dark"] .small.text-muted {
            color: var(--text-secondary) !important;
        }

        /* Badge styles */
        [data-theme="dark"] .badge {
            color: white !important;
        }

        /* Pagination */
        .pagination .page-link {
            background-color: var(--card-bg);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .pagination .page-link:hover {
            background-color: var(--input-bg);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .pagination .page-item.active .page-link {
            background-color: #667eea;
            border-color: #667eea;
        }

        /* Scroll bar styling */
        [data-theme="dark"] ::-webkit-scrollbar {
            width: 10px;
        }

        [data-theme="dark"] ::-webkit-scrollbar-track {
            background: #0f0f0f;
        }

        [data-theme="dark"] ::-webkit-scrollbar-thumb {
            background: #444;
            border-radius: 5px;
        }

        [data-theme="dark"] ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Links */
        a {
            color: #667eea;
        }

        [data-theme="dark"] a {
            color: #7b9dda;
        }

        a:hover {
            color: #764ba2;
        }

        [data-theme="dark"] a:hover {
            color: #9db3e8;
        }
    </style>
</head>
<body>

    
    <nav class="navbar navbar-expand-lg navbar-dark navbar-top fixed-top">
        <div class="container-fluid container">

            <a class="navbar-brand d-flex align-items-center" href="{{ route('eventos.index') }}"> 
                <img src="{{ asset('images/HackTeams_Logo.png') }}" alt="HackTeams" style="max-height: 30px;" class="me-2">
                HackTeams
            </a>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">

                    {{-- Eventos: visible para todos --}}
                    <li class="nav-item">
                        <a class="nav-link @yield('nav_eventos')" href="{{ route('eventos.index') }}"><i class="bi bi-calendar-event me-1"></i>Eventos</a>
                    </li>

                    {{-- Equipos: visible solo para Participantes --}}
                    @if(Auth::user() && Auth::user()->hasRole('Participante'))
                        <li class="nav-item">
                            <a class="nav-link @yield('nav_equipos')" href="{{ route('equipos.index') }}">
                                <i class="bi bi-people-fill me-1"></i>Equipos
                                @if(($solicitudesEquipoPendientes ?? 0) > 0 || ($invitacionesPendientes ?? 0) > 0)
                                    <span class="badge bg-danger ms-1">{{ ($solicitudesEquipoPendientes ?? 0) + ($invitacionesPendientes ?? 0) }}</span>
                                @endif
                            </a>
                        </li>
                    @endif

                    {{-- Progreso: visible solo para Participantes --}}
                    @if(Auth::user() && Auth::user()->hasRole('Participante'))
                        <li class="nav-item">
                            <a class="nav-link @yield('nav_progreso')" href="{{ route('progreso.index') }}"><i class="bi bi-graph-up me-1"></i>Progreso</a>
                        </li>
                    @endif

                    {{-- Evaluación: visible solo para Jueces --}}
                    @if(Auth::user() && Auth::user()->hasRole('Juez'))
                        <li class="nav-item">
                            <a class="nav-link @yield('nav_evaluacion')" href="{{ route('proyectos.evaluacion') }}"><i class="bi bi-star-fill me-1"></i>Evaluación</a>
                        </li>
                    @endif

                    {{-- Constancias: visible para Participantes --}}
                    @if(Auth::user() && Auth::user()->hasRole('Participante'))
                        <li class="nav-item">
                            <a class="nav-link @yield('nav_constancia')" href="{{ route('constancia.index') }}"><i class="bi bi-patch-check-fill me-1"></i>Constancias</a>
                        </li>
                    @endif

                    {{-- Constancias: visible para Jueces --}}
                    @if(Auth::user() && Auth::user()->hasRole('Juez'))
                        <li class="nav-item">
                            <a class="nav-link @yield('nav_constancia')" href="{{ route('constancia.juez.index') }}"><i class="bi bi-patch-check-fill me-1"></i>Constancias</a>
                        </li>
                    @endif

                    {{-- Administración: visible solo para Admins --}}
                    @if(Auth::user() && Auth::user()->hasRole('Admin'))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-gear me-1"></i>Administración
                                @if(($solicitudesConstanciaPendientes ?? 0) > 0)
                                    <span class="badge bg-danger ms-1">{{ $solicitudesConstanciaPendientes }}</span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="adminDropdown">
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-bar-chart-line-fill me-2"></i>Dashboard</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('eventos.crear') }}"><i class="bi bi-calendar-plus me-2"></i>Crear Evento</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.usuarios.index') }}"><i class="bi bi-person-fill me-2"></i>Gestionar Usuarios</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.equipos.index') }}"><i class="bi bi-people-fill me-2"></i>Gestionar Equipos</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.proyectos.index') }}"><i class="bi bi-pencil-square me-2"></i>Proyectos y Jueces</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.rankings') }}"><i class="bi bi-trophy me-2"></i>Rankings</a></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.solicitudes') }}">
                                        <i class="bi bi-patch-check-fill me-2"></i>Solicitudes de Constancias
                                        @if(($solicitudesConstanciaPendientes ?? 0) > 0)
                                            <span class="badge bg-danger ms-1">{{ $solicitudesConstanciaPendientes }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('eventos.index') }}"><i class="bi bi-list-check me-2"></i>Ver Eventos</a></li>
                            </ul>
                        </li>
                    @endif

                    {{-- Perfil: visible para todos --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('perfil.index') }}"><i class="bi bi-person-circle me-1"></i>Perfil</a>
                    </li>

                    {{-- Modo Oscuro --}}
                    <li class="nav-item">
                        <button class="nav-link theme-toggle" id="themeToggle" style="background: none; border: none; cursor: pointer;">
                            <i class="bi bi-moon-stars-fill" style="color: #fff;"></i>
                        </button>
                    </li>

                    {{-- Logout --}}
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link" style="background: none; border: none; cursor: pointer;">
                                <i class="bi bi-box-arrow-right me-1"></i>Salir
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>



    <main>
        @yield('content') {{-- AQUÍ VA EL CONTENIDO ÚNICO DE CADA VISTA --}}
    </main>

    {{-- Footer opcional --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        // Sistema de Modo Oscuro
        const themeToggle = document.getElementById('themeToggle');
        const html = document.documentElement;
        const currentTheme = localStorage.getItem('theme') || 'light';

        // Aplicar tema guardado al cargar - SIEMPRE establecer data-theme
        html.setAttribute('data-theme', currentTheme);
        if (currentTheme === 'dark') {
            updateThemeIcon('dark');
        } else {
            updateThemeIcon('light');
        }

        // Cambiar tema al hacer clic
        themeToggle.addEventListener('click', function() {
            const newTheme = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
        });

        function updateThemeIcon(theme) {
            const icon = themeToggle.querySelector('i');
            if (theme === 'dark') {
                icon.className = 'bi bi-sun-fill';
            } else {
                icon.className = 'bi bi-moon-stars-fill';
            }
        }
    </script>

    @yield('scripts') {{-- Para JS específico de cada vista --}}
</body>
</html>