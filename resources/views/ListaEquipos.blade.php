@extends('Layout.app') 

@section('nav_equipos', 'active') 
@section('title', 'Gestión de Equipos')


@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4">
        <i class="bi bi-people fs-2 me-3 text-success"></i>
        <h1 class="fw-bold mb-0">Gestión de Equipos</h1>
    </div>

    <div class="row mb-4 align-items-center">
        
        {{-- Buscador y Botón Crear Equipo --}}
        <div class="col-md-7 mb-3 mb-md-0">
            <input type="text" class="form-control" placeholder="Buscar equipo por nombre o evento...">
        </div>

        <div class="col-md-5">
            <div class="d-grid d-md-flex justify-content-md-end">

                <a href="{{ route('equipos.registrar') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-circle me-2"></i>Crear Nuevo Equipo
                </a>
            </div>
        </div>
    </div>

    <hr>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        
        @php
            $equipos = [
                ['nombre' => 'Equipo Alpha', 'proyecto' => 'Nuevo sitio web', 'miembros' => 4, 'evento' => 'Hackatec 2025'],
                ['nombre' => 'The Coders', 'proyecto' => 'App de inventario', 'miembros' => 5, 'evento' => 'Innovatec Challenge'],
                ['nombre' => 'Design Masters', 'proyecto' => 'Branding y UX', 'miembros' => 3, 'evento' => 'Hackatec 2025'],
                ['nombre' => 'Data Wizards', 'proyecto' => 'Análisis de clima', 'miembros' => 4, 'evento' => 'Innovatec Challenge'],
            ];
        @endphp

        @foreach ($equipos as $equipo)
        <div class="col">
            <div class="card card-equipo h-100 shadow-sm">
                <div class="card-body">
                    <h4 class="card-title fw-bold text-primary">{{ $equipo['nombre'] }}</h4>
                    <h6 class="card-subtitle mb-2 text-muted">{{ $equipo['evento'] }}</h6>
                    
                    <ul class="list-group list-group-flush mt-3 mb-3">
                        <li class="list-group-item d-flex justify-content-between">
                            <span><i class="bi bi-gear-fill me-2"></i>Proyecto:</span>
                            <strong>{{ $equipo['proyecto'] }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><i class="bi bi-people-fill me-2"></i>Miembros:</span>
                            <strong>{{ $equipo['miembros'] }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><i class="bi bi-clipboard-check me-2"></i>Estado:</span>
                            <strong class="text-success">Activo</strong>
                        </li>
                    </ul>
                    
                    <div class="d-flex justify-content-between pt-2">
                        <a href="{{ route('equipos.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-eye me-1"></i>Ver Detalles
                        </a>
                        
                        <a href="{{ route('proyectos.evaluacion') }}" class="btn btn-outline-info btn-sm">
                            <i class="bi bi-star me-1"></i>Evaluar
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        
    </div>
</div>
@endsection