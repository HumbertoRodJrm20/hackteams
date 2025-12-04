@extends('Layout.app')

@section('title', 'Detalle del Equipo: ' . $equipo->nombre)

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-lg-8">
            <div class="d-flex align-items-center mb-4">
                <i class="bi bi-people-fill fs-1 me-3 text-info"></i>
                <h1 class="fw-bold mb-0">{{ $equipo->nombre }}</h1>
            </div>

            {{-- Información General --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">Información del Equipo</h5>
                    <dl class="row">
                        <dt class="col-sm-4 fw-bold">Evento:</dt>
                        <dd class="col-sm-8">{{ $equipo->evento?->nombre ?? 'Sin evento asignado' }}</dd>

                        <dt class="col-sm-4 fw-bold">Miembros:</dt>
                        <dd class="col-sm-8">{{ $equipo->participantes->count() }}</dd>

                        <dt class="col-sm-4 fw-bold">Proyectos:</dt>
                        <dd class="col-sm-8">{{ $equipo->proyectos->count() }}</dd>
                    </dl>
                </div>
            </div>

            {{-- Miembros del Equipo --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light fw-bold">
                    <i class="bi bi-people me-2"></i>Miembros del Equipo
                </div>
                <div class="list-group list-group-flush">
                    @forelse($equipo->participantes as $participante)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $participante->user->nombre }}</strong>
                                <br>
                                <small class="text-muted">{{ $participante->user->email }}</small>
                                @if($participante->pivot->es_lider)
                                    <br>
                                    <span class="badge bg-warning text-dark">Líder</span>
                                @endif
                            </div>
                            <form action="{{ route('admin.equipos.removeParticipant', [$equipo->id, $participante->user_id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Deseas remover a este participante?');">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="list-group-item text-center text-muted py-4">
                            No hay miembros en este equipo
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Proyectos del Equipo --}}
            <div class="card shadow-sm">
                <div class="card-header bg-light fw-bold">
                    <i class="bi bi-folder me-2"></i>Proyectos del Equipo
                </div>
                <div class="list-group list-group-flush">
                    @forelse($equipo->proyectos as $proyecto)
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $proyecto->titulo }}</h6>
                                <a href="{{ route('proyectos.show', $proyecto->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i>Ver
                                </a>
                            </div>
                            <p class="mb-1 small">{{ $proyecto->resumen }}</p>
                            <small class="text-muted">
                                Estado: <span class="badge bg-secondary">{{ ucfirst($proyecto->estado) }}</span>
                            </small>
                        </div>
                    @empty
                        <div class="list-group-item text-center text-muted py-4">
                            Este equipo aún no tiene proyectos registrados
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Sidebar con Acciones --}}
        <div class="col-lg-4">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-light fw-bold">
                    Acciones
                </div>
                <div class="card-body d-grid gap-2">
                    <a href="{{ route('admin.equipos.edit', $equipo->id) }}" class="btn btn-warning text-white">
                        <i class="bi bi-pencil-square me-2"></i>Editar Equipo
                    </a>
                    <form action="{{ route('admin.equipos.destroy', $equipo->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('¿Deseas eliminar este equipo? Esta acción es irreversible.');">
                            <i class="bi bi-trash me-2"></i>Eliminar Equipo
                        </button>
                    </form>
                    <a href="{{ route('admin.equipos.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Volver a la Lista
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
