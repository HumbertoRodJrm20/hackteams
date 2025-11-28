<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Innovatec - Gestión de Concursos')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar-top {
            background-color: #343a40; 
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
    </style>
</head>
<body>

    
    <nav class="navbar navbar-expand-lg navbar-dark navbar-top sticky-top">
        <div class="container-fluid container">

            <a class="navbar-brand d-flex align-items-center" href="{{ route('eventos.index') }}"> 
                <img src="{{ asset('images/hackteams-logo.png') }}" alt="HackTeams" style="max-height: 30px;" class="me-2">
                
            </a>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    
                    <li class="nav-item">
                        <a class="nav-link @yield('nav_eventos')" href="{{ route('eventos.index') }}"><i class="bi bi-calendar-event me-1"></i>Eventos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @yield('nav_equipos')" href="{{ route('equipos.index') }}"><i class="bi bi-people-fill me-1"></i>Equipos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @yield('nav_progreso')" href="{{ route('progreso.index') }}"><i class="bi bi-graph-up me-1"></i>Progreso</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @yield('nav_constancias')" href="{{ route('constancia.index') }}"><i class="bi bi-patch-check-fill me-1"></i>Constancias</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('perfil.index') }}"><i class="bi bi-person-circle me-1"></i>Perfil</a>
                    </li>
                </ul>
            </div>


        </div>
    </nav>



    <main class="py-4">
        @yield('content') {{-- AQUÍ VA EL CONTENIDO ÚNICO DE CADA VISTA --}}
    </main>

    {{-- Footer opcional --}}

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    @yield('scripts') {{-- Para JS específico de cada vista --}}
</body>
</html>