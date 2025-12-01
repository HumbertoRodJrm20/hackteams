@extends('Layout.app')

@section('nav_eventos', 'active')
@section('title', 'Detalles: ' . $evento->nombre)

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- Mensajes --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Encabezado --}}
            <div class="d-flex align-items-center mb-4">
                <i class="bi bi-calendar-event fs-1 me-3 text-primary"></i>
                <h1 class="fw-bold mb-0">{{ $evento->nombre }}</h1>
            </div>

            {{-- Tarjeta del evento --}}
            <div class="card shadow-sm p-4 mb-4">
                <h4 class="mb-3 text-primary">Información del Evento</h4>

                {{-- Descripción --}}
                <div class="mb-4">
                    <label class="form-label text-muted small">Descripción</label>
                    <p class="fs-5">{{ $evento->descripcion }}</p>
                </div>

                {{-- Fechas --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Fecha de Inicio</label>
                        <p class="fs-5 fw-bold">
                            <i class="bi bi-calendar me-2"></i>
                            {{ \Carbon\Carbon::parse($evento->fecha_inicio)->isoFormat('D [de] MMMM [de] YYYY') }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Fecha de Fin</label>
                        <p class="fs-5 fw-bold">
                            <i class="bi bi-calendar me-2"></i>
                            {{ \Carbon\Carbon::parse($evento->fecha_fin)->isoFormat('D [de] MMMM [de] YYYY') }}
                        </p>
                    </div>
                </div>

                {{-- Estado --}}
                <div class="mb-4">
                    <label class="form-label text-muted small">Estado</label>
                    @php
                        $badgeClass = match($evento->estado) {
                            'activo' => 'bg-danger',
                            'proximo' => 'bg-info',
                            'finalizado' => 'bg-secondary',
                            default => 'bg-warning',
                        };
                    @endphp
                    <p>
                        <span class="badge {{ $badgeClass }} fs-6">
                            {{ ucfirst($evento->estado) }}
                        </span>
                    </p>
                </div>

                {{-- Participantes registrados --}}
                <div class="mb-4">
                    <label class="form-label text-muted small">Participantes</label>
                    <p class="fs-5">
                        <i class="bi bi-people-fill me-2"></i>
                        {{ $evento->participantes ? $evento->participantes()->count() : 0 }} participante(s) registrado(s)
                    </p>
                </div>

                <hr class="my-4">

                {{-- Botones de acción --}}
                @auth
                    @if(Auth::user()->hasRole('Participante'))
                        @php
                            $esUnido = $evento->hasParticipante(Auth::id());
                            $eventoFinalizado = $evento->estado === 'finalizado';
                        @endphp

                        @if($esUnido)
                            {{-- Usuario ya está unido al evento --}}
                            <div class="alert alert-success mb-4">
                                <i class="bi bi-check-circle me-2"></i>
                                Te encuentras registrado en este evento
                            </div>

                            <div class="d-grid gap-2">
                                @if(!$eventoFinalizado)
                                    <a href="{{ route('equipos.registrar') }}" class="btn btn-primary btn-lg">
                                        <i class="bi bi-plus-circle me-2"></i>Crear o Unirse a un Equipo
                                    </a>
                                @endif
                                <form action="{{ route('eventos.leave', $evento->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('¿Deseas abandonar este evento?');">
                                        <i class="bi bi-box-arrow-left me-2"></i>Abandonar Evento
                                    </button>
                                </form>
                            </div>
                        @else
                            {{-- Usuario NO está unido al evento --}}
                            @if($eventoFinalizado)
                                <div class="alert alert-warning mb-4">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    Este evento ha finalizado. No puedes unirte en este momento.
                                </div>
                            @else
                                <div class="d-grid">
                                    <form action="{{ route('eventos.join', $evento->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-lg">
                                            <i class="bi bi-plus-circle me-2"></i>Unirse al Evento
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endif
                    @elseif(Auth::user()->hasRole('Admin'))
                        {{-- Admin puede editar --}}
                        <div class="d-grid gap-2">
                            <a href="{{ route('eventos.crear') }}" class="btn btn-warning btn-lg text-white">
                                <i class="bi bi-pencil-square me-2"></i>Editar Evento
                            </a>
                            <form action="{{ route('eventos.destroy', $evento->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('¿Deseas eliminar este evento? Esta acción es irreversible.');">
                                    <i class="bi bi-trash me-2"></i>Eliminar Evento
                                </button>
                            </form>
                        </div>
                    @endif
                @else
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <a href="{{ route('login') }}">Inicia sesión</a> para unirte a este evento
                    </div>
                @endauth
            </div>

        </div>
    </div>
</div>
@endsection
