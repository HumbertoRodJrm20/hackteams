@extends('Layout.app')

@section('title', 'Gestión de Proyectos y Asignación a Jueces')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4">
        <i class="bi bi-pencil-square fs-1 me-3 text-warning"></i>
        <h1 class="fw-bold mb-0">Gestión de Proyectos</h1>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <a href="{{ route('admin.rankings') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-trophy me-2"></i>Ver Rankings
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @forelse ($eventos as $evento)
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-event me-2"></i>{{ $evento->nombre }}
                    <span class="badge bg-secondary ms-2">{{ $evento->proyectos->count() }} Proyectos</span>
                </h5>
            </div>

            @if($evento->proyectos->isEmpty())
                <div class="card-body">
                    <p class="text-muted">No hay proyectos en este evento.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Proyecto</th>
                                <th>Equipo</th>
                                <th>Estado</th>
                                <th>Promedio</th>
                                <th>Jueces Asignados</th>
                                <th>Calificaciones</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($evento->proyectos as $proyecto)
                                <tr>
                                    <td>
                                        <strong>{{ $proyecto->titulo }}</strong>
                                    </td>
                                    <td>{{ $proyecto->equipo->nombre }}</td>
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
                                        <a href="{{ route('admin.proyectos.asignar-jueces', $proyecto->id) }}" class="btn btn-sm btn-outline-primary" title="Asignar Jueces">
                                            <i class="bi bi-person-plus"></i>
                                        </a>
                                        <a href="{{ route('proyectos.show', $proyecto->id) }}" class="btn btn-sm btn-outline-secondary" title="Ver Detalles">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @empty
        <div class="alert alert-info">
            No hay eventos registrados.
        </div>
    @endforelse
</div>
@endsection
