@extends('Layout.app')

@section('title', 'Calificaciones - ' . $proyecto->titulo)

@section('content')
<div class="container py-4">
    <a href="{{ route('admin.proyectos.index') }}" class="btn btn-outline-secondary mb-3">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>

    <div class="row">
        {{-- Panel Principal --}}
        <div class="col-lg-8">
            {{-- Información del Proyecto --}}
            <div class="card shadow-sm mb-4">
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 2rem; color: white; border-radius: 12px 12px 0 0;">
                    <h2 class="fw-bold mb-2">{{ $proyecto->titulo }}</h2>
                    <p class="mb-0">
                        <i class="bi bi-people-fill me-1"></i><strong>Equipo:</strong> {{ $proyecto->equipo ? $proyecto->equipo->nombre : 'Sin equipo' }}
                    </p>
                    <p class="mb-0">
                        <i class="bi bi-calendar me-1"></i><strong>Evento:</strong> {{ $proyecto->evento->nombre }}
                    </p>
                </div>
                <div class="card-body">
                    <p>{{ $proyecto->resumen }}</p>
                    @if($proyecto->link_repositorio)
                        <a href="{{ $proyecto->link_repositorio }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-github me-1"></i>Ver Repositorio
                        </a>
                    @endif
                </div>
            </div>

            {{-- Tabla de Calificaciones --}}
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-table me-2"></i>Calificaciones Detalladas
                    </h5>
                </div>

                @if($calificaciones->isEmpty())
                    <div class="card-body">
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle me-2"></i>No hay calificaciones aún para este proyecto.
                        </div>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Juez</th>
                                    <th>Puntuación</th>
                                    <th>Visualización</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($calificaciones as $cal)
                                    <tr>
                                        <td>
                                            <strong>{{ $cal->juez->nombre }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $cal->juez->email }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-success" style="font-size: 1.1rem; padding: 0.5rem 1rem;">
                                                {{ number_format($cal->puntuacion, 1) }}/100
                                            </span>
                                        </td>
                                        <td>
                                            {{-- Estrellas --}}
                                            @php
                                                $fullStars = (int)($cal->puntuacion / 20);
                                                $hasHalf = ($cal->puntuacion % 20) >= 10;
                                            @endphp
                                            @for($i = 0; $i < $fullStars; $i++)
                                                <i class="bi bi-star-fill text-warning"></i>
                                            @endfor
                                            @if($hasHalf && $fullStars < 5)
                                                <i class="bi bi-star-half text-warning"></i>
                                                @php $fullStars++ @endphp
                                            @endif
                                            @for($i = $fullStars; $i < 5; $i++)
                                                <i class="bi bi-star text-muted"></i>
                                            @endfor
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $cal->updated_at->format('d/m/Y H:i') }}
                                            </small>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- Sidebar: Estadísticas --}}
        <div class="col-lg-4">
            {{-- Resumen --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-graph-up me-2"></i>Resumen de Calificaciones
                    </h5>
                </div>
                <div class="card-body">
                    {{-- Promedio Grande --}}
                    <div class="text-center mb-4">
                        <div style="font-size: 3rem; color: #667eea; font-weight: bold;">
                            {{ number_format($promedio, 1) }}
                        </div>
                        <small class="text-muted">/100</small>
                    </div>

                    {{-- Puesto --}}
                    @if($cantidadJueces > 0)
                        <div class="mb-3 text-center">
                            @if($puesto == 1)
                                <div class="badge bg-warning text-dark" style="font-size: 1rem; padding: 0.75rem 1.5rem;">
                                    <i class="bi bi-trophy-fill me-1"></i>🥇 1º Lugar
                                </div>
                            @elseif($puesto == 2)
                                <div class="badge bg-secondary" style="font-size: 1rem; padding: 0.75rem 1.5rem;">
                                    <i class="bi bi-award me-1"></i>🥈 2º Lugar
                                </div>
                            @elseif($puesto == 3)
                                <div class="badge" style="background-color: #CD7F32; color: white; font-size: 1rem; padding: 0.75rem 1.5rem;">
                                    <i class="bi bi-award-fill me-1"></i>🥉 3º Lugar
                                </div>
                            @else
                                <div class="badge bg-light text-dark" style="font-size: 1rem; padding: 0.75rem 1.5rem;">
                                    {{ $puesto }}º Lugar
                                </div>
                            @endif
                        </div>
                    @endif

                    <hr>

                    {{-- Estadísticas --}}
                    <div class="mb-3">
                        <small class="text-muted d-block">Cantidad de Jueces</small>
                        <h5 class="mb-0">{{ $cantidadJueces }}</h5>
                    </div>

                    @if($cantidadJueces > 0)
                        <div class="mb-3">
                            <small class="text-muted d-block">Puntuación Máxima</small>
                            <h5 class="mb-0 text-success">
                                {{ number_format($calificaciones->max('puntuacion'), 1) }}/100
                            </h5>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">Puntuación Mínima</small>
                            <h5 class="mb-0 text-danger">
                                {{ number_format($calificaciones->min('puntuacion'), 1) }}/100
                            </h5>
                        </div>

                        <div>
                            <small class="text-muted d-block">Diferencia (Rango)</small>
                            <h5 class="mb-0">
                                {{ number_format($calificaciones->max('puntuacion') - $calificaciones->min('puntuacion'), 1) }}
                            </h5>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Jueces Asignados pero sin Calificar --}}
            @php
                $juecesAsignados = $proyecto->jueces()->count();
                $juecesQueCalificaron = $calificaciones->count();
                $juecesQueNoCalificaron = $juecesAsignados - $juecesQueCalificaron;
            @endphp

            @if($juecesQueNoCalificaron > 0)
                <div class="card shadow-sm mb-4 border-warning">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0 fw-bold">
                            <i class="bi bi-exclamation-triangle me-2"></i>Pendientes de Calificar
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-3">
                            <strong>{{ $juecesQueNoCalificaron }}</strong> de <strong>{{ $juecesAsignados }}</strong> jueces aún no han calificado.
                        </p>

                        <div style="max-height: 250px; overflow-y: auto;">
                            @foreach($proyecto->jueces as $juez)
                                @php
                                    $calificado = $calificaciones->where('juez_user_id', $juez->id)->first();
                                @endphp
                                @if(!$calificado)
                                    <div class="mb-2 p-2 border rounded">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <strong>{{ $juez->nombre }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $juez->email }}</small>
                                            </div>
                                            <span class="badge bg-warning text-dark">Sin calificar</span>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="card shadow-sm border-success">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0 fw-bold">
                            <i class="bi bi-check-circle me-2"></i>Calificación Completa
                        </h6>
                    </div>
                    <div class="card-body text-center">
                        <p class="mb-0">✅ Todos los jueces han calificado este proyecto</p>
                    </div>
                </div>
            @endif

            {{-- Acciones --}}
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-tools me-2"></i>Acciones
                    </h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.proyectos.asignar-jueces', $proyecto->id) }}" class="btn btn-primary w-100 mb-2">
                        <i class="bi bi-person-plus me-1"></i>Gestionar Jueces
                    </a>
                    <a href="{{ route('proyectos.show', $proyecto->id) }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-eye me-1"></i>Ver Proyecto
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    [data-theme="dark"] .table-hover tbody tr:hover {
        background-color: #2a2a2a !important;
    }
</style>
@endsection
