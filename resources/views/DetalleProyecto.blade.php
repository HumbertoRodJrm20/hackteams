@extends('Layout.app')

@section('title', 'Detalle del Proyecto: ' . $proyecto->titulo)

@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-lg-8">
                <div class="mb-4">
                    <a href="{{ route('equipos.index') }}" class="btn btn-sm btn-outline-secondary mb-3">
                        <i class="bi bi-arrow-left me-2"></i>Volver a Equipos
                    </a>

                    {{-- Título del Proyecto --}}
                    <h1 class="fw-bold text-success">{{ $proyecto->titulo }}</h1>
                    <p class="lead text-muted">Equipo: **{{ $proyecto->equipo->nombre }}**</p>
                </div>

                {{-- Sección de Información General --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h4 class="card-title fw-semibold mb-3"><i class="bi bi-info-circle me-2"></i>Información General</h4>

                        <p class="mb-3">{{ $proyecto->resumen }}</p>

                        <ul class="list-group list-group-flush small">
                            <li class="list-group-item">
                                **Evento:** {{ $proyecto->evento->nombre }}
                            </li>
                            <li class="list-group-item">
                                **Estado:** <span class="badge bg-warning text-dark">{{ ucfirst($proyecto->estado) }}</span>
                            </li>
                            @if ($proyecto->link_repositorio)
                                <li class="list-group-item">
                                    **Repositorio:** <a href="{{ $proyecto->link_repositorio }}" target="_blank">Ver en GitHub/GitLab <i class="bi bi-box-arrow-up-right"></i></a>
                                </li>
                            @endif
                            <li class="list-group-item">
                                **Fecha de Registro:** {{ $proyecto->created_at->isoFormat('D MMMM YYYY') }}
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Sección de Avances (Progreso del Proyecto) --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light fw-bold">
                        <i class="bi bi-clock-history me-2"></i>Avances y Entregas
                    </div>
                    <ul class="list-group list-group-flush">
                        @forelse ($proyecto->avances as $avance)
                            <li class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1 fw-semibold">{{ $avance->fecha->isoFormat('D MMMM YYYY') }}</h5>
                                    <small class="text-muted">ID Avance: #{{ $avance->id }}</small>
                                </div>
                                <p class="mb-1">{{ $avance->descripcion }}</p>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">
                                Aún no se han registrado avances para este proyecto.
                            </li>
                        @endforelse
                    </ul>

                    @if (auth()->check() && $proyecto->equipo->participantes->pluck('user_id')->contains(auth()->id()))
                        <div class="card-footer text-center">
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoAvance">
                                <i class="bi bi-plus-circle me-2"></i>Registrar Nuevo Avance
                            </button>
                        </div>
                    @endif
                </div>

            </div>

            {{-- Barra Lateral: Calificación (para Jueces/Admin) --}}
            <div class="col-lg-4">
                <div class="card shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-header bg-warning fw-bold text-dark">
                        <i class="bi bi-award-fill me-2"></i>Resultados
                    </div>
                    <div class="card-body text-center">
                        @if ($proyecto->calificaciones->isNotEmpty())
                            @php
                                // Calcula el promedio de la calificación
                                $promedio = $proyecto->calificaciones->avg('puntuacion_total');
                            @endphp
                            <p class="text-muted mb-1 small">Puntuación Promedio Final:</p>
                            <h2 class="display-4 fw-bold text-success">{{ number_format($promedio, 2) }}</h2>
                            <p class="small text-muted">Evaluado por {{ $proyecto->calificaciones->count() }} jueces.</p>
                            <hr>
                            <a href="#" class="btn btn-outline-primary btn-sm">Ver Detalle de Evaluación</a>
                        @else
                            <div class="alert alert-light border small">
                                Pendiente de evaluación.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL para Nuevo Avance (Añadir al final del archivo) --}}
    <div class="modal fade" id="modalNuevoAvance" tabindex="-1" aria-labelledby="modalNuevoAvanceLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('avances.store') }}">
                    @csrf
                    <input type="hidden" name="proyecto_id" value="{{ $proyecto->id }}">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="modalNuevoAvanceLabel">Registrar Avance</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="descripcion_avance" class="form-label">Descripción del Avance</label>
                            <textarea class="form-control" id="descripcion_avance" name="descripcion" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="fecha_avance" class="form-label">Fecha del Avance</label>
                            <input type="date" class="form-control" id="fecha_avance" name="fecha" value="{{ now()->toDateString() }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Avance</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
