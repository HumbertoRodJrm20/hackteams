<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Evento - Hackatec</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar-top {
            background-color: #343a40; 
        }
        .header-section {
            padding-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
        }
        .content-card {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .action-icon {
            cursor: pointer;
            font-size: 1.5rem;
            color: #6c757d;
            transition: color 0.2s;
        }
        .action-icon:hover {
            color: #007bff;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-top sticky-top">
        <div class="container-fluid container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="URL_LOGO_HACKTEAMS" alt="HackTeams" style="max-height: 30px;" class="me-2">
                Innovatec
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="bi bi-calendar-event me-1"></i>Eventos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="bi bi-people-fill me-1"></i>Equipos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="bi bi-graph-up me-1"></i>Progreso</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="bi bi-patch-check-fill me-1"></i>Constancias</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="bi bi-person-circle me-1"></i>Perfil</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                
                <div class="header-section text-center mb-4">
                    <img src="URL_LOGO_HACKTEAMS" alt="HackTeams Logo" style="max-height: 40px;" class="mb-3">
                    <h1 class="display-4 fw-bold">Información del Evento</h1>
                </div>

                <div class="content-card">
                    
                    <h2 class="fw-bold mb-3 text-primary">Hackatec</h2>
                    
                    <p class="lead text-muted">
                        Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
                    </p>
                    
                    <p>
                        Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.
                    </p>
                    
                    <p>
                        Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur.
                    </p>

                    <hr class="my-4">

                    <div class="mb-4">
                        <div style="height: 350px; background-color: #e9ecef; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #6c757d;">
                            [Imagen/Video o Recurso del Evento]
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-4 mb-4">
                        <i class="bi bi-heart action-icon" title="Me Gusta / Favorito"></i>
                        <i class="bi bi-list-task action-icon" title="Añadir a lista / Tareas"></i>
                        <i class="bi bi-share action-icon" title="Compartir"></i>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="{{ url('/eventos/hackatec/editar') }}" class="btn btn-info btn-lg w-100 py-3">
                                <i class="bi bi-pencil-square me-2"></i>Editar
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ url('/eventos/hackatec/equipos') }}" class="btn btn-primary btn-lg w-100 py-3" style="background-color: #28a745; border-color: #28a745;">
                                <i class="bi bi-people me-2"></i>Gestionar equipos
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>