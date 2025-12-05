@extends('Layout.app')

@section('nav_evaluacion', 'active')
@section('title', 'Mis Calificaciones')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <i class="bi bi-bar-chart fs-1 me-3 text-success"></i>
            <h1 class="fw-bold d-inline">Mis Calificaciones</h1>
        </div>
        <a href="{{ route('juez.proyectos.index') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-pencil-square me-2"></i>Calificar Proyectos
        </a>
    </div>

    @if($eventosPorCalificacion->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox" style="font-size: 3rem; color: #bdc3c7;"></i>
                <h4 class="mt-3">Aún no has calificado proyectos</h4>
                <p class="text-muted">Los proyectos que califiques aparecerán aquí.</p>
            </div>
        </div>
    @else
        @foreach($eventosPorCalificacion as $eventoCalificacion)
            <div class="card shadow-sm mb-4">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-event me-2"></i>{{ $eventoCalificacion['evento']->nombre }}
                        <span class="badge bg-white text-dark ms-2">{{ $eventoCalificacion['proyectos']->count() }} Proyectos</span>
                    </h5>
                </div>

                <div class="card-body p-0">
                    @foreach($eventoCalificacion['proyectos'] as $proyecto)
                        <div class="ranking-item p-3 border-bottom">
                            <div class="row align-items-center">
                                {{-- Puesto --}}
                                <div class="col-auto">
                                    @php
                                        $puesto = $proyecto['puesto'] ?? 0;
                                        $promedio = $proyecto['promedio'] ?? 0;
                                    @endphp
                                    @if($puesto == 1)
                                        <div class="badge bg-warning text-dark" style="font-size: 1.2rem; padding: 0.75rem;">
                                            <i class="bi bi-trophy-fill"></i> 1º
                                        </div>
                                    @elseif($puesto == 2)
                                        <div class="badge bg-secondary" style="font-size: 1.2rem; padding: 0.75rem;">
                                            <i class="bi bi-award"></i> 2º
                                        </div>
                                    @elseif($puesto == 3)
                                        <div class="badge" style="background-color: #CD7F32; color: white; font-size: 1.2rem; padding: 0.75rem;">
                                            <i class="bi bi-award-fill"></i> 3º
                                        </div>
                                    @else
                                        <div class="badge bg-light text-dark" style="font-size: 1.2rem; padding: 0.75rem;">
                                            {{ $puesto }}º
                                        </div>
                                    @endif
                                </div>

                                {{-- Información --}}
                                <div class="col">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="mb-1 fw-bold">{{ $proyecto['titulo'] }}</h6>
                                            <small class="text-muted">{{ $proyecto['equipo'] }}</small>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <small class="d-block text-muted">Promedio</small>
                                                <strong style="font-size: 1.2rem; color: #667eea;">
                                                    {{ number_format($promedio, 1) }}
                                                </strong>
                                                <small class="text-muted">/100</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <small class="d-block text-muted">Mi Calificación</small>
                                                @if($proyecto['mi_calificacion'])
                                                    <strong style="font-size: 1.2rem; color: #27ae60;">
                                                        {{ number_format($proyecto['mi_calificacion'], 1) }}
                                                    </strong>
                                                @else
                                                    <span class="text-warning">Pendiente</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Acciones --}}
                                <div class="col-auto">
                                    <a href="{{ route('juez.proyectos.show', $proyecto['id']) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    @endif
</div>

<style>
    .ranking-item {
        transition: background-color 0.3s ease;
    }

    .ranking-item:hover {
        background-color: #f8f9fa;
    }

    [data-theme="dark"] .ranking-item:hover {
        background-color: #2a2a2a;
    }
</style>
@endsection
