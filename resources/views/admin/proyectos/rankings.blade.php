@extends('Layout.app')

@section('title', 'Rankings de Proyectos')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <i class="bi bi-trophy fs-1 me-3 text-warning"></i>
            <h1 class="fw-bold d-inline">Rankings por Evento</h1>
        </div>
        <a href="{{ route('admin.proyectos.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>

    @forelse($eventosConRankings as $eventoData)
        <div class="mb-5">
            {{-- Header del Evento --}}
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2rem; border-radius: 12px; margin-bottom: 2rem;">
                <h2 class="fw-bold mb-2">{{ $eventoData['nombre'] }}</h2>
                <p class="mb-0">
                    <i class="bi bi-journals me-1"></i>{{ $eventoData['proyectos']->count() }} Proyectos Evaluados
                </p>
            </div>

            @if($eventoData['proyectos']->isEmpty())
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>No hay proyectos calificados en este evento.
                </div>
            @else
                {{-- Podio (Top 3) --}}
                @if($eventoData['proyectos']->count() >= 1 || $eventoData['proyectos']->count() >= 2 || $eventoData['proyectos']->count() >= 3)
                    <div class="row g-4 mb-5">
                        {{-- 2º Lugar (Izquierda) --}}
                        @if(isset($eventoData['proyectos'][1]))
                            <div class="col-md-4 d-flex flex-column justify-content-end">
                                <div class="card border-0 shadow-lg h-100" style="border-top: 4px solid #c0c0c0;">
                                    <div class="card-body text-center">
                                        <div class="mb-3" style="font-size: 3rem;">🥈</div>
                                        <h5 class="fw-bold mb-2">{{ $eventoData['proyectos'][1]['titulo'] }}</h5>
                                        <p class="text-muted mb-2 small">{{ $eventoData['proyectos'][1]['equipo'] }}</p>
                                        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                                            <div class="fw-bold" style="font-size: 2rem;">{{ number_format($eventoData['proyectos'][1]['promedio'], 1) }}</div>
                                            <small>/100</small>
                                        </div>
                                        <a href="{{ route('admin.proyectos.asignar-jueces', $eventoData['proyectos'][1]['id']) }}" class="btn btn-sm btn-outline-primary w-100">
                                            <i class="bi bi-eye me-1"></i>Ver Detalles
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- 1º Lugar (Centro - Más alto) --}}
                        @if(isset($eventoData['proyectos'][0]))
                            <div class="col-md-4">
                                <div class="card border-0 shadow-xl h-100" style="border-top: 4px solid #ffd700; transform: scale(1.05);">
                                    <div class="card-body text-center">
                                        <div class="mb-3" style="font-size: 4rem;">🥇</div>
                                        <h5 class="fw-bold mb-2" style="font-size: 1.3rem;">{{ $eventoData['proyectos'][0]['titulo'] }}</h5>
                                        <p class="text-muted mb-2 small">{{ $eventoData['proyectos'][0]['equipo'] }}</p>
                                        <div style="background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%); color: #333; padding: 1.2rem; border-radius: 8px; margin-bottom: 1rem;">
                                            <div class="fw-bold" style="font-size: 2.5rem;">{{ number_format($eventoData['proyectos'][0]['promedio'], 1) }}</div>
                                            <small>/100</small>
                                        </div>
                                        <a href="{{ route('admin.proyectos.asignar-jueces', $eventoData['proyectos'][0]['id']) }}" class="btn btn-sm btn-warning w-100">
                                            <i class="bi bi-eye me-1"></i>Ver Detalles
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- 3º Lugar (Derecha) --}}
                        @if(isset($eventoData['proyectos'][2]))
                            <div class="col-md-4 d-flex flex-column justify-content-end">
                                <div class="card border-0 shadow-lg h-100" style="border-top: 4px solid #cd7f32;">
                                    <div class="card-body text-center">
                                        <div class="mb-3" style="font-size: 3rem;">🥉</div>
                                        <h5 class="fw-bold mb-2">{{ $eventoData['proyectos'][2]['titulo'] }}</h5>
                                        <p class="text-muted mb-2 small">{{ $eventoData['proyectos'][2]['equipo'] }}</p>
                                        <div style="background: linear-gradient(135deg, #cd7f32 0%, #d4a574 100%); color: white; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                                            <div class="fw-bold" style="font-size: 2rem;">{{ number_format($eventoData['proyectos'][2]['promedio'], 1) }}</div>
                                            <small>/100</small>
                                        </div>
                                        <a href="{{ route('admin.proyectos.asignar-jueces', $eventoData['proyectos'][2]['id']) }}" class="btn btn-sm btn-outline-primary w-100">
                                            <i class="bi bi-eye me-1"></i>Ver Detalles
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Tabla de Resto de Proyectos (4º en adelante) --}}
                @if($eventoData['proyectos']->count() > 3)
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-bold">Más Proyectos</h6>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Proyecto</th>
                                        <th>Equipo</th>
                                        <th>Promedio</th>
                                        <th>Calificaciones</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($eventoData['proyectos']->slice(3) as $index => $proyecto)
                                        <tr>
                                            <td>
                                                <strong class="text-muted">{{ $proyecto['puesto'] }}º</strong>
                                            </td>
                                            <td>
                                                <strong>{{ $proyecto['titulo'] }}</strong>
                                            </td>
                                            <td>{{ $proyecto['equipo'] }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <strong class="ranking-promedio">{{ number_format($proyecto['promedio'], 1) }}</strong>
                                                    <small>/100</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $proyecto['calificaciones_count'] }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.proyectos.asignar-jueces', $proyecto['id']) }}" class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    @empty
        <div class="alert alert-info">
            No hay eventos registrados.
        </div>
    @endforelse
</div>

<style>
    .shadow-xl {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2) !important;
    }

    [data-theme="dark"] .shadow-xl {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5) !important;
    }

    [data-theme="light"] .ranking-promedio {
        color: #667eea;
    }

    [data-theme="dark"] .ranking-promedio {
        color: #a9a8ff;
    }

    @media (max-width: 768px) {
        .row.g-4 .col-md-4:nth-child(2) .card {
            transform: scale(1) !important;
        }
    }
</style>
@endsection
