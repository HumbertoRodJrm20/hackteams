@extends('Layout.app')

@section('title', 'Calificar - ' . $proyecto->titulo)

@section('content')
<div class="container py-4">
    <a href="{{ route('juez.proyectos.index') }}" class="btn btn-outline-secondary mb-3">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>

    <div class="row">
        <div class="col-lg-8">
            {{-- Información del Proyecto --}}
            <div class="card shadow-sm mb-4">
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 2rem; color: white;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h2 class="fw-bold mb-2">{{ $proyecto->titulo }}</h2>
                            <p class="mb-0">
                                <i class="bi bi-people-fill me-1"></i>
                                <strong>Equipo:</strong> {{ $proyecto->equipo->nombre }}
                            </p>
                            <p class="mb-0">
                                <i class="bi bi-calendar me-1"></i>
                                <strong>Evento:</strong> {{ $proyecto->evento->nombre }}
                            </p>
                        </div>
                        {{-- Puesto --}}
                        @php
                            $puesto = $proyecto->obtenerPuesto();
                            $promedio = $proyecto->obtenerPromedio();
                        @endphp
                        @if($promedio > 0)
                            @if($puesto == 1)
                                <div class="text-center">
                                    <div class="badge bg-warning text-dark" style="font-size: 1rem; padding: 0.5rem 1rem;">
                                        <i class="bi bi-trophy-fill me-1"></i>1º Lugar
                                    </div>
                                </div>
                            @elseif($puesto == 2)
                                <div class="text-center">
                                    <div class="badge bg-secondary" style="font-size: 1rem; padding: 0.5rem 1rem;">
                                        <i class="bi bi-award me-1"></i>2º Lugar
                                    </div>
                                </div>
                            @elseif($puesto == 3)
                                <div class="text-center">
                                    <div class="badge" style="background-color: #CD7F32; color: white; font-size: 1rem; padding: 0.5rem 1rem;">
                                        <i class="bi bi-award-fill me-1"></i>3º Lugar
                                    </div>
                                </div>
                            @else
                                <div class="text-center">
                                    <div class="badge bg-light text-dark" style="font-size: 1rem; padding: 0.5rem 1rem;">
                                        {{ $puesto }}º Lugar
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <h5 class="mb-3 fw-bold">Descripción</h5>
                    <p>{{ $proyecto->resumen }}</p>

                    @if($proyecto->link_repositorio)
                        <hr>
                        <a href="{{ $proyecto->link_repositorio }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-github me-1"></i>Ver Repositorio
                        </a>
                    @endif
                </div>
            </div>

            {{-- Avances --}}
            @if($proyecto->avances->isNotEmpty())
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="bi bi-clock-history me-2"></i>Avances del Proyecto
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach($proyecto->avances as $avance)
                            <div class="mb-3">
                                <h6 class="fw-bold text-primary">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    {{ $avance->fecha->isoFormat('D MMMM YYYY') }}
                                </h6>
                                <p class="mb-0">{{ $avance->descripcion }}</p>
                            </div>
                            @if(!$loop->last)
                                <hr>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar: Calificación --}}
        <div class="col-lg-4">
            {{-- Estadísticas --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-bar-chart me-2"></i>Estadísticas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Promedio General</small>
                        <h4 class="mb-0">
                            @if($promedio > 0)
                                <span class="text-success">{{ number_format($promedio, 1) }}/100</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </h4>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <small class="text-muted">Puesto en Evento</small>
                        <h5 class="mb-0">
                            @if($promedio > 0)
                                @if($puesto == 1)
                                    <span class="badge bg-warning text-dark">🥇 {{ $puesto }}º Lugar</span>
                                @elseif($puesto == 2)
                                    <span class="badge bg-secondary">🥈 {{ $puesto }}º Lugar</span>
                                @elseif($puesto == 3)
                                    <span class="badge" style="background-color: #CD7F32; color: white;">🥉 {{ $puesto }}º Lugar</span>
                                @else
                                    <span class="badge bg-light text-dark">{{ $puesto }}º Lugar</span>
                                @endif
                            @else
                                <span class="text-muted">Sin clasificar</span>
                            @endif
                        </h5>
                    </div>

                    <hr>

                    <small class="text-muted">Calificaciones Registradas: {{ $proyecto->calificaciones->count() }}</small>
                </div>
            </div>

            {{-- Formulario de Calificación por Criterios --}}
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-star-fill me-2"></i>Criterios de Evaluación
                    </h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($criterios->isEmpty())
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>No hay criterios de evaluación definidos para este evento.
                        </div>
                    @else
                        @foreach($criterios as $criterio)
                            <form method="POST" action="{{ route('juez.proyectos.calificar', $proyecto->id) }}" class="mb-4 pb-4 border-bottom">
                                @csrf
                                <input type="hidden" name="criterio_id" value="{{ $criterio->id }}">

                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="fw-bold mb-1">{{ $criterio->nombre }}</h6>
                                        <small class="text-muted">Ponderación: {{ $criterio->ponderacion }}%</small>
                                    </div>
                                    @if($misCalificaciones->has($criterio->id))
                                        <span class="badge bg-success">
                                            {{ number_format($misCalificaciones[$criterio->id]->puntuacion, 0) }}/100
                                        </span>
                                    @endif
                                </div>

                                <div class="d-flex gap-2 align-items-center mb-2">
                                    <input
                                        type="range"
                                        class="form-range criterio-range"
                                        data-criterio-id="{{ $criterio->id }}"
                                        min="0"
                                        max="100"
                                        step="1"
                                        value="{{ $misCalificaciones[$criterio->id]->puntuacion ?? 0 }}"
                                        style="flex: 1;"
                                    >
                                    <input
                                        type="number"
                                        name="puntuacion"
                                        class="form-control criterio-number"
                                        data-criterio-id="{{ $criterio->id }}"
                                        min="0"
                                        max="100"
                                        step="0.1"
                                        value="{{ $misCalificaciones[$criterio->id]->puntuacion ?? 0 }}"
                                        style="width: 80px;"
                                        readonly
                                    >
                                </div>

                                <div class="mb-2">
                                    <div class="criterio-stars" data-criterio-id="{{ $criterio->id }}"></div>
                                </div>

                                <button type="submit" class="btn btn-sm btn-primary w-100">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Guardar
                                </button>
                            </form>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- Todas las Calificaciones --}}
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-list me-2"></i>Todas las Calificaciones
                    </h5>
                </div>
                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                    @if($proyecto->calificaciones->isEmpty())
                        <p class="text-muted text-center">Sin calificaciones aún.</p>
                    @else
                        @foreach($proyecto->calificaciones as $cal)
                            <div class="mb-2 pb-2 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $cal->juez->nombre }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $cal->criterio->nombre }}</small>
                                    </div>
                                    <span class="badge bg-success">{{ number_format($cal->puntuacion, 1) }}/100</span>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Manejar cambios en los rangos de criterios
    document.querySelectorAll('.criterio-range').forEach(range => {
        range.addEventListener('input', function() {
            const criterioId = this.dataset.criterioId;
            const numberInput = document.querySelector(`.criterio-number[data-criterio-id="${criterioId}"]`);
            const starsDiv = document.querySelector(`.criterio-stars[data-criterio-id="${criterioId}"]`);

            numberInput.value = this.value;
            updateStars(this.value, starsDiv);
        });
    });

    function updateStars(value, starsDiv) {
        const fullStars = Math.floor(value / 20);
        const hasHalf = (value % 20) >= 10;
        let html = '';

        for (let i = 0; i < fullStars; i++) {
            html += '<i class="bi bi-star-fill text-warning"></i>';
        }

        if (hasHalf && fullStars < 5) {
            html += '<i class="bi bi-star-half text-warning"></i>';
        }

        for (let i = fullStars + (hasHalf ? 1 : 0); i < 5; i++) {
            html += '<i class="bi bi-star text-muted"></i>';
        }

        starsDiv.innerHTML = html;
    }

    // Inicializar estrellas para todos los criterios
    document.querySelectorAll('.criterio-stars').forEach(starsDiv => {
        const criterioId = starsDiv.dataset.criterioId;
        const rangeInput = document.querySelector(`.criterio-range[data-criterio-id="${criterioId}"]`);
        if (rangeInput) {
            updateStars(rangeInput.value, starsDiv);
        }
    });
</script>
@endsection
