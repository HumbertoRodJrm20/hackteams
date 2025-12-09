@extends('Layout.app')

@section('title', 'Gestión de Proyectos y Asignación a Jueces')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center">
            <i class="bi bi-pencil-square fs-1 me-3 text-warning"></i>
            <h1 class="fw-bold mb-0">Gestión de Proyectos</h1>
        </div>
        <a href="{{ route('admin.rankings') }}" class="btn btn-primary">
            <i class="bi bi-trophy me-2"></i>Ver Rankings
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Formulario de Búsqueda y Filtros --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.proyectos.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">
                        <i class="bi bi-search me-1"></i>Buscar proyecto
                    </label>
                    <input type="text" class="form-control" id="search" name="search"
                           placeholder="Título del proyecto..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label for="equipo" class="form-label">
                        <i class="bi bi-people me-1"></i>Buscar equipo
                    </label>
                    <input type="text" class="form-control" id="equipo" name="equipo"
                           placeholder="Nombre del equipo..."
                           value="{{ request('equipo') }}">
                </div>
                <div class="col-md-2">
                    <label for="evento_id" class="form-label">
                        <i class="bi bi-calendar-event me-1"></i>Evento
                    </label>
                    <select class="form-select" id="evento_id" name="evento_id">
                        <option value="">Todos</option>
                        @foreach($eventos as $evento)
                            <option value="{{ $evento->id }}" {{ request('evento_id') == $evento->id ? 'selected' : '' }}>
                                {{ $evento->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="estado" class="form-label">
                        <i class="bi bi-flag me-1"></i>Estado
                    </label>
                    <select class="form-select" id="estado" name="estado">
                        <option value="">Todos</option>
                        <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="en_desarrollo" {{ request('estado') == 'en_desarrollo' ? 'selected' : '' }}>En Desarrollo</option>
                        <option value="terminado" {{ request('estado') == 'terminado' ? 'selected' : '' }}>Terminado</option>
                        <option value="calificado" {{ request('estado') == 'calificado' ? 'selected' : '' }}>Calificado</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                    @if(request('search') || request('equipo') || request('evento_id') || request('estado'))
                        <a href="{{ route('admin.proyectos.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Resultados --}}
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-list-ul me-2"></i>Proyectos Registrados
            </h5>
            <span class="badge bg-secondary">{{ $proyectos->total() }} proyectos</span>
        </div>

        @if($proyectos->isEmpty())
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox" style="font-size: 3rem; color: #bdc3c7;"></i>
                <h5 class="mt-3">No se encontraron proyectos</h5>
                <p class="text-muted">
                    @if(request('search') || request('equipo') || request('evento_id') || request('estado'))
                        Intenta ajustar los filtros de búsqueda.
                    @else
                        No hay proyectos registrados aún.
                    @endif
                </p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Proyecto</th>
                            <th>Equipo</th>
                            <th>Evento</th>
                            <th>Estado</th>
                            <th>Promedio</th>
                            <th>Jueces</th>
                            <th>Calif.</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($proyectos as $proyecto)
                            <tr>
                                <td>
                                    <strong>{{ $proyecto->titulo }}</strong>
                                </td>
                                <td>{{ $proyecto->equipo ? $proyecto->equipo->nombre : 'Sin equipo' }}</td>
                                <td>
                                    <small class="text-muted">{{ $proyecto->evento->nombre }}</small>
                                </td>
                                <td>
                                    @php
                                        $estadoBadge = match($proyecto->estado) {
                                            'pendiente' => 'warning',
                                            'en_desarrollo' => 'info',
                                            'terminado' => 'success',
                                            'calificado' => 'secondary',
                                            default => 'secondary',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $estadoBadge }}">
                                        {{ ucfirst(str_replace('_', ' ', $proyecto->estado)) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $promedio = $proyecto->obtenerPromedio();
                                    @endphp
                                    @if($promedio > 0)
                                        <strong class="text-success">{{ number_format($promedio, 1) }}/100</strong>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $proyecto->jueces->count() }}
                                    </span>
                                </td>
                                <td>
                                    {{ $proyecto->calificaciones->count() }}
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.proyectos.asignar-jueces', $proyecto->id) }}"
                                           class="btn btn-outline-primary"
                                           title="Asignar Jueces">
                                            <i class="bi bi-person-plus"></i>
                                        </a>
                                        @if($proyecto->calificaciones->isNotEmpty())
                                            <a href="{{ route('admin.proyectos.ver-calificaciones', $proyecto->id) }}"
                                               class="btn btn-outline-info"
                                               title="Ver Calificaciones">
                                                <i class="bi bi-bar-chart"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('proyectos.show', $proyecto->id) }}"
                                           class="btn btn-outline-secondary"
                                           title="Ver Detalles">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            @if($proyectos->hasPages())
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-center">
                        {{ $proyectos->links() }}
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
