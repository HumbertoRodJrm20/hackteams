@extends('Layout.app')

@section('nav_progreso', 'active')
@section('title', 'Progreso de Proyectos')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4">
        <i class="bi bi-graph-up fs-1 me-3 text-info"></i>
        <h1 class="fw-bold mb-0">Progreso de Mis Proyectos</h1>
    </div>

    {{-- Estadísticas --}}
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card text-white bg-primary h-100 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title fw-light">Total de Proyectos</h6>
                    <p class="card-text fs-3 fw-bold">{{ $proyectos->count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info h-100 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title fw-light">En Desarrollo</h6>
                    <p class="card-text fs-3 fw-bold">{{ $proyectos->where('estado', 'en_desarrollo')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success h-100 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title fw-light">Terminados</h6>
                    <p class="card-text fs-3 fw-bold">{{ $proyectos->where('estado', 'terminado')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-secondary h-100 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title fw-light">Calificados</h6>
                    <p class="card-text fs-3 fw-bold">{{ $proyectos->where('estado', 'calificado')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <hr>

    {{-- Tabla de Proyectos --}}
    @if($proyectos->isEmpty())
        <div class="alert alert-info text-center" role="alert">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Sin proyectos aún</strong><br>
            No tienes proyectos registrados. <a href="{{ route('proyectos.registrar') }}">Registra uno ahora</a>
        </div>
    @else
        <h3 class="mb-4">Mis Proyectos</h3>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Título del Proyecto</th>
                        <th>Equipo</th>
                        <th>Evento</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($proyectos as $proyecto)
                        <tr>
                            <td>
                                <strong>{{ $proyecto['titulo'] }}</strong>
                            </td>
                            <td>
                                <a href="{{ route('equipos.show', $proyecto['equipo_id']) }}">
                                    {{ $proyecto['equipo_nombre'] }}
                                </a>
                            </td>
                            <td>{{ $proyecto['evento_nombre'] }}</td>
                            <td>
                                @php
                                    $badgeClass = match($proyecto['estado']) {
                                        'pendiente' => 'bg-warning',
                                        'en_desarrollo' => 'bg-info',
                                        'terminado' => 'bg-success',
                                        'calificado' => 'bg-secondary',
                                        default => 'bg-light text-dark'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $proyecto['estado'])) }}
                                </span>
                            </td>
                            <td>{{ $proyecto['fecha_creacion'] }}</td>
                            <td>
                                <a href="{{ route('proyectos.show', $proyecto['id']) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i>Ver
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <hr class="my-5">

        {{-- Detalles de Proyectos --}}
        <h3 class="mb-4">Detalles de Proyectos</h3>
        <div class="row g-4">
            @foreach($proyectos as $proyecto)
                <div class="col-lg-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title fw-bold text-primary">{{ $proyecto['titulo'] }}</h5>
                            <p class="card-text text-muted small mb-3">
                                <i class="bi bi-people-fill me-1"></i>{{ $proyecto['equipo_nombre'] }}
                                <br>
                                <i class="bi bi-calendar me-1"></i>{{ $proyecto['evento_nombre'] }}
                            </p>

                            <p class="card-text">{{ $proyecto['resumen'] }}</p>

                            <div class="mb-3">
                                <label class="form-label text-muted small">Estado</label>
                                @php
                                    $badgeClass = match($proyecto['estado']) {
                                        'pendiente' => 'bg-warning',
                                        'en_desarrollo' => 'bg-info',
                                        'terminado' => 'bg-success',
                                        'calificado' => 'bg-secondary',
                                        default => 'bg-light text-dark'
                                    };
                                @endphp
                                <p>
                                    <span class="badge {{ $badgeClass }} fs-6">
                                        {{ ucfirst(str_replace('_', ' ', $proyecto['estado'])) }}
                                    </span>
                                </p>
                            </div>

                            @if($proyecto['link_repositorio'])
                                <p class="mb-3">
                                    <a href="{{ $proyecto['link_repositorio'] }}" target="_blank" class="btn btn-sm btn-outline-dark">
                                        <i class="bi bi-github me-1"></i>Ver Repositorio
                                    </a>
                                </p>
                            @endif

                            <a href="{{ route('proyectos.show', $proyecto['id']) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-eye me-1"></i>Ver Detalles Completos
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
