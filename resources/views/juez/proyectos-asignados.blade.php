@extends('Layout.app')

@section('nav_evaluacion', 'active')
@section('title', 'Proyectos para Calificar')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <i class="bi bi-pencil-square fs-1 me-3 text-primary"></i>
            <h1 class="fw-bold d-inline">Proyectos Asignados</h1>
        </div>
        <a href="{{ route('juez.mis-calificaciones') }}" class="btn btn-info btn-lg">
            <i class="bi bi-bar-chart me-2"></i>Ver Mis Calificaciones
        </a>
    </div>

    @if($proyectos->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox" style="font-size: 3rem; color: #bdc3c7; margin-bottom: 1rem;"></i>
                <h4>No hay proyectos asignados</h4>
                <p class="text-muted">Pronto serán asignados proyectos para que los califiques.</p>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($proyectos as $proyecto)
                <div class="col-lg-6">
                    <div class="card h-100 shadow-sm border-0">
                        {{-- Header --}}
                        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 1.5rem; color: white; border-radius: 0 0 0 0;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5 class="fw-bold mb-1">{{ $proyecto->titulo }}</h5>
                                    <small class="text-white-50">{{ $proyecto->equipo ? $proyecto->equipo->nombre : 'Sin equipo' }}</small>
                                </div>
                                {{-- Puesto --}}
                                @php
                                    $puesto = $proyecto->obtenerPuesto();
                                    $promedio = $proyecto->obtenerPromedio();
                                @endphp
                                @if($promedio > 0)
                                    @if($puesto == 1)
                                        <div class="badge bg-warning text-dark" style="font-size: 0.9rem;">
                                            <i class="bi bi-trophy-fill me-1"></i>1º Lugar
                                        </div>
                                    @elseif($puesto == 2)
                                        <div class="badge bg-secondary" style="font-size: 0.9rem;">
                                            <i class="bi bi-award me-1"></i>2º Lugar
                                        </div>
                                    @elseif($puesto == 3)
                                        <div class="badge" style="background-color: #CD7F32; color: white; font-size: 0.9rem;">
                                            <i class="bi bi-award-fill me-1"></i>3º Lugar
                                        </div>
                                    @else
                                        <div class="badge bg-light text-dark" style="font-size: 0.9rem;">
                                            {{ $puesto }}º Lugar
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>

                        {{-- Body --}}
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <small class="text-muted">Evento</small>
                                    <p class="fw-bold mb-0">{{ $proyecto->evento->nombre }}</p>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">Promedio</small>
                                    <p class="fw-bold mb-0">
                                        @if($promedio > 0)
                                            <span class="text-success">{{ number_format($promedio, 1) }}/100</span>
                                        @else
                                            <span class="text-muted">Aún sin calificar</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">Calificaciones Registradas</small>
                                    <p class="fw-bold mb-0">{{ $proyecto->calificaciones->count() }}</p>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">Tu Calificación</small>
                                    <p class="fw-bold mb-0">
                                        @php
                                            $miCalificacion = $proyecto->calificaciones()
                                                ->where('juez_user_id', Auth::id())
                                                ->first();
                                        @endphp
                                        @if($miCalificacion)
                                            <span class="text-info">{{ number_format($miCalificacion->puntuacion, 1) }}/100</span>
                                        @else
                                            <span class="text-warning">Pendiente</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="card-footer bg-white border-top">
                            <a href="{{ route('juez.proyectos.show', $proyecto->id) }}" class="btn btn-primary w-100">
                                <i class="bi bi-pencil me-1"></i>Calificar Proyecto
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Paginación --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $proyectos->links() }}
        </div>
    @endif
</div>
@endsection
