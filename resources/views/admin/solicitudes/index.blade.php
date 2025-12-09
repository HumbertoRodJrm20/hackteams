@extends('Layout.app')

@section('content')
@section('nav_solicitudes_admin', 'active')

<div class="container py-4">
    <div class="d-flex align-items-center mb-4">
        <i class="bi bi-file-earmark-check fs-1 me-3 text-info"></i>
        <h1 class="fw-bold mb-0">Solicitudes de Constancias</h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Formulario de Búsqueda y Filtros --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.solicitudes') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">
                        <i class="bi bi-search me-1"></i>Buscar participante
                    </label>
                    <input type="text" class="form-control" id="search" name="search"
                           placeholder="Nombre del participante..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label for="evento_id" class="form-label">
                        <i class="bi bi-calendar-event me-1"></i>Evento
                    </label>
                    <select class="form-select" id="evento_id" name="evento_id">
                        <option value="">Todos los eventos</option>
                        @foreach($eventos as $evento)
                            <option value="{{ $evento->id }}" {{ request('evento_id') == $evento->id ? 'selected' : '' }}>
                                {{ $evento->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="estatus" class="form-label">
                        <i class="bi bi-flag me-1"></i>Estado
                    </label>
                    <select class="form-select" id="estatus" name="estatus">
                        <option value="">Todos los estados</option>
                        <option value="Pendiente" {{ request('estatus') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="Aprobado" {{ request('estatus') == 'Aprobado' ? 'selected' : '' }}>Aprobado</option>
                        <option value="Rechazado" {{ request('estatus') == 'Rechazado' ? 'selected' : '' }}>Rechazado</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                    @if(request('search') || request('evento_id') || request('estatus'))
                        <a href="{{ route('admin.solicitudes') }}" class="btn btn-outline-secondary">
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
                <i class="bi bi-list-ul me-2"></i>Solicitudes
            </h5>
            <span class="badge bg-secondary">{{ $solicitudes->total() }} solicitudes</span>
        </div>

        @if($solicitudes->isEmpty())
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox" style="font-size: 3rem; color: #bdc3c7;"></i>
                <h5 class="mt-3">No se encontraron solicitudes</h5>
                <p class="text-muted">
                    @if(request('search') || request('evento_id') || request('estatus'))
                        Intenta ajustar los filtros de búsqueda.
                    @else
                        No hay solicitudes registradas aún.
                    @endif
                </p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Participante</th>
                            <th>Evento</th>
                            <th>Rol</th>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Evidencia</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($solicitudes as $s)
                        <tr>
                            <td><strong>#{{ $s->id }}</strong></td>
                            <td>{{ $s->participante->name }}</td>
                            <td><small class="text-muted">{{ $s->evento->nombre }}</small></td>
                            <td>{{ $s->rol }}</td>
                            <td>{{ $s->fecha_evento ? \Carbon\Carbon::parse($s->fecha_evento)->format('d/m/Y') : '-' }}</td>
                            <td>{{ ucfirst($s->tipo) }}</td>
                            <td>
                                @if ($s->evidencia_path)
                                    <a href="{{ asset('storage/' . $s->evidencia_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-file-pdf"></i> Ver
                                    </a>
                                @else
                                    <span class="text-muted">Sin archivo</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge
                                    @if($s->estatus == 'Pendiente') bg-warning text-dark
                                    @elseif($s->estatus == 'Aprobado') bg-success
                                    @else bg-danger @endif">
                                    {{ $s->estatus }}
                                </span>
                            </td>
                            <td>
                                @if ($s->estatus == 'Pendiente')
                                    <div class="btn-group btn-group-sm" role="group">
                                        <form action="{{ route('admin.solicitudes.aprobar', $s->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-success" onclick="return confirm('¿Aprobar esta solicitud?')">
                                                <i class="bi bi-check-circle"></i> Aprobar
                                            </button>
                                        </form>
                                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rechazoModal{{ $s->id }}">
                                            <i class="bi bi-x-circle"></i> Rechazar
                                        </button>
                                    </div>
                                @else
                                    <span class="text-muted">Procesada</span>
                                @endif
                            </td>
                        </tr>

                        <!-- MODAL DE RECHAZO -->
                        <div class="modal fade" id="rechazoModal{{ $s->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form method="POST" action="{{ route('admin.solicitudes.rechazar', $s->id) }}">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title">
                                                <i class="bi bi-x-circle me-2"></i>Rechazar Solicitud #{{ $s->id }}
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <label class="form-label fw-bold">Motivo del rechazo:</label>
                                            <textarea name="comentario_rechazo" class="form-control" rows="4"
                                                      placeholder="Explica por qué se rechaza esta solicitud..." required></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-danger">
                                                <i class="bi bi-x-circle me-1"></i>Rechazar Solicitud
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            @if($solicitudes->hasPages())
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-center">
                        {{ $solicitudes->links() }}
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
