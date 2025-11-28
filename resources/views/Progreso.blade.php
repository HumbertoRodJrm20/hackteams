@extends('Layout.app') 

@section('nav_progreso', 'active') 
@section('title', 'Progreso de Proyectos y Evaluación')


@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-5">
        <i class="bi bi-graph-up-arrow fs-1 me-3 text-info"></i>
        <h1 class="fw-bold mb-0">Progreso y Evaluación de Proyectos</h1>
    </div>

    <div class="row g-4 mb-5">
        
        <div class="col-md-3">
            <div class="card text-white bg-primary h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-light">Total de Proyectos</h5>
                    <p class="card-text fs-2 fw-bold">12</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-light">Evaluaciones Finalizadas</h5>
                    <p class="card-text fs-2 fw-bold">9 / 12</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-light">Promedio General</h5>
                    <p class="card-text fs-2 fw-bold">8.7</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-secondary h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-light">Jueces Activos</h5>
                    <p class="card-text fs-2 fw-bold">6</p>
                </div>
            </div>
        </div>
    </div>

    <hr class="my-5">

    <h2 class="mb-4">Estatus de Evaluación por Equipo</h2>
    
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Equipo</th>
                    <th scope="col">Evento</th>
                    <th scope="col">Jueces Faltantes</th>
                    <th scope="col">Calificación P.</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">1</th>
                    <td>Equipo Alpha</td>
                    <td>Hackatec 2025</td>
                    <td><span class="badge bg-success">0</span></td>
                    <td>9.5</td>
                    <td><a href="{{ route('proyectos.evaluacion') }}" class="btn btn-sm btn-outline-info">Ver Evaluación</a></td>
                </tr>
                <tr>
                    <th scope="row">2</th>
                    <td>The Coders</td>
                    <td>Innovatec Challenge</td>
                    <td><span class="badge bg-warning text-dark">1</span></td>
                    <td>8.2</td>
                    <td><a href="{{ route('proyectos.evaluacion') }}" class="btn btn-sm btn-outline-info">Evaluar</a></td>
                </tr>
                <tr>
                    <th scope="row">3</th>
                    <td>Data Wizards</td>
                    <td>Innovatec Challenge</td>
                    <td><span class="badge bg-danger">2</span></td>
                    <td>N/A</td>
                    <td><a href="{{ route('proyectos.evaluacion') }}" class="btn btn-sm btn-outline-info">Evaluar</a></td>
                </tr>
                <tr>
                    <th scope="row">4</th>
                    <td>Design Masters</td>
                    <td>Hackatec 2025</td>
                    <td><span class="badge bg-success">0</span></td>
                    <td>9.1</td>
                    <td><a href="{{ route('proyectos.evaluacion') }}" class="btn btn-sm btn-outline-info">Ver Evaluación</a></td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
@endsection