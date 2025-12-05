@extends('Layout.app')

@section('title', 'Asignar Jueces - ' . $proyecto->titulo)

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-lg-8">
            {{-- Información del Proyecto --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-journal me-2"></i>Información del Proyecto
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Título</label>
                            <p class="fw-bold">{{ $proyecto->titulo }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Equipo</label>
                            <p class="fw-bold">{{ $proyecto->equipo->nombre }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Evento</label>
                            <p class="fw-bold">{{ $proyecto->evento->nombre }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Promedio Actual</label>
                            <p class="fw-bold">
                                @php
                                    $promedio = $proyecto->obtenerPromedio();
                                @endphp
                                @if($promedio > 0)
                                    <span class="text-success">{{ number_format($promedio, 1) }}/100</span>
                                @else
                                    <span class="text-muted">Aún no evaluado</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Formulario de Asignación --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-person-plus me-2"></i>Asignar Jueces
                    </h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.proyectos.guardar-asignacion', $proyecto->id) }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-bold">Selecciona los Jueces</label>
                            <div style="max-height: 300px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 0.25rem; padding: 1rem;">
                                @forelse($juecesDisponibles as $juez)
                                    <div class="form-check mb-2">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            id="juez_{{ $juez->id }}"
                                            name="jueces[]"
                                            value="{{ $juez->id }}"
                                            @checked(in_array($juez->id, $juecesAsignados))
                                        >
                                        <label class="form-check-label" for="juez_{{ $juez->id }}">
                                            {{ $juez->nombre }} ({{ $juez->email }})
                                        </label>
                                    </div>
                                @empty
                                    <p class="text-muted">No hay jueces disponibles en el sistema.</p>
                                @endforelse
                            </div>
                            @error('jueces')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i>Guardar Asignación
                            </button>
                            <a href="{{ route('admin.proyectos.ver-calificaciones', $proyecto->id) }}" class="btn btn-outline-info">
                                <i class="bi bi-eye me-1"></i>Ver Calificaciones
                            </a>
                            <a href="{{ route('admin.proyectos.index') }}" class="btn btn-outline-secondary">
                                Volver
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Sidebar: Jueces Asignados Actualmente --}}
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-people-fill me-2"></i>Jueces Asignados ({{ $proyecto->jueces->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    @if($proyecto->jueces->isEmpty())
                        <p class="text-muted text-center py-3">
                            <i class="bi bi-info-circle me-1"></i>
                            Aún no hay jueces asignados.
                        </p>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($proyecto->jueces as $juez)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $juez->nombre }}</h6>
                                        <small class="text-muted">{{ $juez->email }}</small>
                                    </div>
                                    <form action="{{ route('admin.proyectos.eliminar-asignacion', [$proyecto->id, $juez->id]) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Remover este juez?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Remover">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Información de Calificaciones --}}
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-star-fill me-2"></i>Calificaciones Registradas
                    </h5>
                </div>
                <div class="card-body">
                    @if($proyecto->calificaciones->isEmpty())
                        <p class="text-muted text-center py-3">
                            Sin calificaciones aún.
                        </p>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($proyecto->calificaciones as $cal)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>{{ $cal->juez->nombre }}</span>
                                        <span class="badge bg-success">{{ number_format($cal->puntuacion, 1) }}/100</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
