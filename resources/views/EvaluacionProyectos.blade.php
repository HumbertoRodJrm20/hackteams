<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluación de Proyectos - Innovatec</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <style>
        body {
            background-color: #f8f9fa; /* Color de fondo muy claro */
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .logo {
            max-height: 50px;
            filter: drop-shadow(0 2px 2px rgba(0, 0, 0, 0.2));
        }
        .btn-custom-guardar {
            background-color: #5BC0DE; /* Azul cian similar al de la captura */
            color: white;
            border: 1px solid #4a9ec9;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }
        .btn-custom-guardar:hover {
            background-color: #4a9ec9;
            color: white;
        }
    </style>
</head>
<body>

    <div class="container py-5">
        <div class="text-center mb-5">

            <img src="URL_DEL_LOGO_HACKTEAMS_O_SIMULACION" alt="HackTeams Logo" class="logo mb-3"> 
            
            <h1 class="display-5 fw-bold mt-2">Evaluación de Proyectos</h1>
            <p class="lead text-muted">Proyecto: <strong>Nuevo sitio web</strong></p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-7">
               

                <div class="card">
                <div class="card-body p-4">

                    <form action="{{ route('proyectos.seguimiento') }}" method="GET">
                        
                        <div class="row mb-3">
                            <div class="col-6">
                                <h6 class="fw-bold text-muted">Nombre del equipo</h6>
                            </div>
                            <div class="col-6 text-end">
                                <h6 class="fw-bold text-muted">Calificación obtenida</h6>
                            </div>
                        </div>
                        
                        @php
                            $criterios = ['Innovación', 'Diseño', 'Funcionalidad', 'Trabajo en equipo'];
                        @endphp

                        @foreach ($criterios as $criterio)
                            <div class="row mb-3 align-items-center">
                                <div class="col-6">
                                    <label for="{{ Str::slug($criterio) }}" class="col-form-label">{{ $criterio }}</label>
                                </div>
                                <div class="col-6">

                                    <select class="form-select" id="{{ Str::slug($criterio) }}" name="puntaje[{{ Str::slug($criterio) }}]" required>
                                        <option value="" selected>Puntaje</option>
                                        @for ($i = 100; $i >= 0; $i--)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        @endforeach
                        
                        <hr class="my-4">

                        <div class="d-grid">
                            <button type="submit" class="btn btn-custom-guardar btn-lg">
                                Guardar Calificación (y ver seguimiento)
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>