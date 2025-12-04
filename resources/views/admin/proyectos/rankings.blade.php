@extends('Layout.app')

@section('title', 'Rankings de Proyectos')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center mb-4">
        <i class="bi bi-trophy fs-1 me-3 text-warning"></i>
        <h1 class="fw-bold mb-0">Rankings de Proyectos</h1>
    </div>

    <a href="{{ route('admin.proyectos.index') }}" class="btn btn-outline-secondary mb-3">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>

    @forelse($eventosConRankings as $eventoData)
        <div class="card shadow-sm mb-4">
            <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-event me-2"></i>{{ $eventoData['nombre'] }}
                    <span class="badge bg-white text-dark ms-2">{{ $eventoData['proyectos']->count() }} Proyectos</span>
                </h5>
            </div>

            @if($eventoData['proyectos']->isEmpty())
                <div class="card-body">
                    <p class="text-muted">No hay proyectos calificados en este evento.</p>
                </div>
            @else
                <div class="card-body p-0">
                    @foreach($eventoData['proyectos'] as $proyecto)
                        <div class="ranking-item p-3 border-bottom">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    {{-- Medalla o Puesto --}}
                                    @if($proyecto['puesto'] == 1)
                                        <div class="badge bg-warning text-dark" style="font-size: 1.2rem; padding: 0.75rem;">
                                            <i class="bi bi-trophy-fill"></i> 1º
                                        </div>
                                    @elseif($proyecto['puesto'] == 2)
                                        <div class="badge bg-secondary" style="font-size: 1.2rem; padding: 0.75rem;">
                                            <i class="bi bi-award"></i> 2º
                                        </div>
                                    @elseif($proyecto['puesto'] == 3)
                                        <div class="badge bg-warning" style="font-size: 1.2rem; padding: 0.75rem; color: #8B4513;">
                                            <i class="bi bi-award-fill"></i> 3º
                                        </div>
                                    @else
                                        <div class="badge bg-light text-dark" style="font-size: 1.2rem; padding: 0.75rem;">
                                            {{ $proyecto['puesto'] }}º
                                        </div>
                                    @endif
                                </div>
                                <div class="col">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h6 class="mb-0 fw-bold">{{ $proyecto['titulo'] }}</h6>
                                            <small class="text-muted">{{ $proyecto['equipo'] }}</small>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center gap-2">
                                                {{-- Promedio --}}
                                                <div class="text-center">
                                                    <strong class="d-block" style="font-size: 1.3rem; color: #667eea;">
                                                        {{ number_format($proyecto['promedio'], 1) }}
                                                    </strong>
                                                    <small class="text-muted">/100</small>
                                                </div>
                                                {{-- Estrellas --}}
                                                <div>
                                                    @php
                                                        $estrellasLlenas = (int)($proyecto['promedio'] / 20);
                                                        $tieneMedia = ($proyecto['promedio'] % 20) >= 10;
                                                    @endphp
                                                    @for($i = 0; $i < $estrellasLlenas; $i++)
                                                        <i class="bi bi-star-fill text-warning"></i>
                                                    @endfor
                                                    @if($tieneMedia && $estrellasLlenas < 5)
                                                        <i class="bi bi-star-half text-warning"></i>
                                                        @php $estrellasLlenas++ @endphp
                                                    @endif
                                                    @for($i = $estrellasLlenas; $i < 5; $i++)
                                                        <i class="bi bi-star text-muted"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        <i class="bi bi-people me-1"></i>{{ $proyecto['calificaciones_count'] }} calificaciones
                                    </small>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('proyectos.show', $proyecto['id']) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @empty
        <div class="alert alert-info">
            No hay eventos registrados.
        </div>
    @endforelse
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
