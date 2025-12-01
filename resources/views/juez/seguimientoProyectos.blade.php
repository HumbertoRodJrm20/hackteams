@extends('Layout.app')

@section('nav_seguimiento', 'active') {{-- Asegúrate de añadir esta sección en tu Layout.app si no existe --}}
@section('title', 'Seguimiento de Proyectos')

@section('content')
<div class="container py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h1 class="fw-bold text-info"><i class="bi bi-graph-up-arrow me-2"></i>Seguimiento y Progreso de Proyectos</h1>
    </div>

    <p class="lead text-muted mb-4">
        Revisa el estado de avance de cada equipo en las diferentes fases del concurso.
    </p>
    
    <hr>
    
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        
        @php
            // Datos simulados para demostración
            $proyectos = [
                ['equipo' => 'Innovadores X', 'proyecto' => 'App de Reciclaje IA', 'fase' => 'Desarrollo Beta', 'progreso' => 90, 'estado' => 'success'],
                ['equipo' => 'Tech Titans', 'proyecto' => 'Plataforma Educativa VR', 'fase' => 'Diseño UX/UI', 'progreso' => 55, 'estado' => 'warning'],
                ['equipo' => 'Code Wizards', 'proyecto' => 'Sistema de Gestión Cloud', 'fase' => 'Definición de Requisitos', 'progreso' => 30, 'estado' => 'info'],
                ['equipo' => 'Dream Team', 'proyecto' => 'Motor de Juegos 3D', 'fase' => 'Pruebas Finales', 'progreso' => 100, 'estado' => 'success'],
                ['equipo' => 'Solution Seekers', 'proyecto' => 'E-commerce Ecológico', 'fase' => 'Implementación', 'progreso' => 75, 'estado' => 'primary'],
            ];
        @endphp

        @foreach ($proyectos as $p)
            <div class="col">
                <div class="card card-project h-100 shadow-sm border-0">
                    <div class="card-body d-flex flex-column">
                        
                        <h5 class="card-title fw-bold text-primary">{{ $p['equipo'] }}</h5>
                        <h6 class="card-subtitle mb-3 text-muted">{{ $p['proyecto'] }}</h6>
                        
                        <div class="mb-3">
                            <span class="badge bg-secondary mb-1">Fase Actual:</span>
                            <p class="fw-bold mb-1">{{ $p['fase'] }}</p>
                        </div>

                        {{-- Barra de Progreso --}}
                        <div class="mt-auto pt-3 border-top">
                            <label class="form-label small text-uppercase fw-bold text-success mb-1">Progreso General ({{ $p['progreso'] }}%)</label>
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-{{ $p['estado'] }}" 
                                     role="progressbar" 
                                     style="width: {{ $p['progreso'] }}%" 
                                     aria-valuenow="{{ $p['progreso'] }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                     @if ($p['progreso'] > 15)
                                        {{ $p['progreso'] }}%
                                     @endif
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 text-end">
                            <a href="#" class="btn btn-outline-info btn-sm">
                                Ver Tareas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection