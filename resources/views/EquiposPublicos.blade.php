@extends('Layout.app')

@section('nav_equipos', 'active')
@section('title', 'Equipos Públicos')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <i class="bi bi-search fs-2 me-3 text-success"></i>
            <h1 class="fw-bold d-inline">Equipos Públicos</h1>
        </div>
        <div>
            <a href="{{ route('equipos.index') }}" class="btn btn-outline-secondary btn-lg">
                <i class="bi bi-arrow-left me-2"></i>Mis Equipos
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

    <hr>

    @if($equiposPorEvento->isEmpty())
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info text-center" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>No hay equipos públicos disponibles</strong><br>
                    No hay equipos públicos en eventos activos en este momento.
                </div>
            </div>
        </div>
    @else
        @foreach($equiposPorEvento as $eventoData)
            <div class="mb-5">
                <h3 class="fw-bold mb-3">
                    <i class="bi bi-calendar-event me-2"></i>{{ $eventoData['evento']->nombre }}
                </h3>

                @if($eventoData['yaEnEquipo'])
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Ya estás en un equipo para este evento.
                    </div>
                @endif

                @if($eventoData['eventoIniciado'])
                    <div class="alert alert-info" role="alert">
                        <i class="bi bi-info-circle me-2"></i>
                        Este evento ya ha iniciado. No puedes unirte a equipos.
                    </div>
                @endif

                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    @foreach($eventoData['equipos'] as $equipo)
                        <div class="col">
                            <div class="card h-100 shadow-sm border-0">
                                @if($equipo['logo_path'])
                                    <img src="{{ asset('storage/' . $equipo['logo_path']) }}"
                                         class="card-img-top" alt="Logo de {{ $equipo['nombre'] }}"
                                         style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                         style="height: 200px;">
                                        <i class="bi bi-people text-secondary" style="font-size: 3rem;"></i>
                                    </div>
                                @endif

                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title fw-bold text-truncate">
                                        {{ $equipo['nombre'] }}
                                        <span class="badge bg-success ms-2">Público</span>
                                    </h5>

                                    <ul class="list-group list-group-flush my-3 flex-grow-1">
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span><i class="bi bi-people-fill me-2"></i>Miembros:</span>
                                            <strong>{{ $equipo['miembros'] }}</strong>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span><i class="bi bi-puzzle-fill me-2"></i>Perfiles disponibles:</span>
                                            <strong>{{ $equipo['perfiles_disponibles'] }}</strong>
                                        </li>
                                    </ul>

                                    <div class="d-grid gap-2">
                                        @if($eventoData['yaEnEquipo'] || $eventoData['eventoIniciado'])
                                            <button class="btn btn-secondary btn-sm" disabled>
                                                <i class="bi bi-lock-fill me-1"></i>No disponible
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-success btn-sm w-100"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalSolicitud{{ $equipo['id'] }}">
                                                <i class="bi bi-envelope-plus me-1"></i>Solicitar Unirse
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Modal para solicitar unirse --}}
                        <div class="modal fade" id="modalSolicitud{{ $equipo['id'] }}" tabindex="-1" aria-labelledby="modalSolicitudLabel{{ $equipo['id'] }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('equipos.solicitar', $equipo['id']) }}">
                                        @csrf
                                        <div class="modal-header bg-success text-white">
                                            <h5 class="modal-title" id="modalSolicitudLabel{{ $equipo['id'] }}">
                                                <i class="bi bi-envelope-plus me-2"></i>Solicitar Unirse a {{ $equipo['nombre'] }}
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="mensaje{{ $equipo['id'] }}" class="form-label fw-bold">
                                                    Mensaje para el líder (opcional)
                                                </label>
                                                <textarea class="form-control" id="mensaje{{ $equipo['id'] }}" name="mensaje" rows="4"
                                                          placeholder="Cuéntale al líder por qué quieres unirte, tus habilidades, experiencia, etc."></textarea>
                                                <small class="form-text text-muted">Este mensaje ayudará al líder a conocerte mejor.</small>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-success">
                                                <i class="bi bi-send me-1"></i>Enviar Solicitud
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
