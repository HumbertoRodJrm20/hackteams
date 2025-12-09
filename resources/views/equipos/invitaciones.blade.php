@extends('Layout.app')

@section('title', 'Mis Invitaciones')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4">
        <i class="bi bi-envelope-open fs-1 me-3 text-success"></i>
        <h1 class="fw-bold mb-0">Mis Invitaciones de Equipo</h1>
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

    @if($invitaciones->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-envelope-open" style="font-size: 3rem; color: #bdc3c7;"></i>
                <h4 class="mt-3">No tienes invitaciones pendientes</h4>
                <p class="text-muted">Cuando alguien te invite a un equipo, aparecerán aquí.</p>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($invitaciones as $invitacion)
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                            <h5 class="mb-0">
                                <i class="bi bi-people-fill me-2"></i>
                                {{ $invitacion->equipo->nombre }}
                            </h5>
                        </div>
                        <div class="card-body">
                            @php
                                $lider = $invitacion->equipo->participantes()->wherePivot('es_lider', true)->first();
                            @endphp

                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center me-3"
                                     style="width: 50px; height: 50px; font-size: 1.5rem;">
                                    <i class="bi bi-star-fill"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">Invitado por {{ $lider ? $lider->user->name : 'el líder' }}</h6>
                                    <small class="text-muted">Líder del equipo</small>
                                </div>
                            </div>

                            <div class="mb-3">
                                <strong>Evento:</strong>
                                <span class="badge bg-info">{{ $invitacion->equipo->evento ? $invitacion->equipo->evento->nombre : 'Sin evento' }}</span>
                            </div>

                            <div class="mb-3">
                                <strong>Miembros actuales:</strong>
                                <span class="badge bg-secondary">{{ $invitacion->equipo->participantes->count() }}</span>
                            </div>

                            @if($invitacion->mensaje)
                                <div class="alert alert-warning">
                                    <strong><i class="bi bi-chat-left-text me-1"></i>Mensaje del líder:</strong>
                                    <p class="mb-0 mt-2">{{ $invitacion->mensaje }}</p>
                                </div>
                            @endif

                            <div class="text-muted small mb-3">
                                <i class="bi bi-clock me-1"></i>
                                Invitado {{ $invitacion->created_at->diffForHumans() }}
                            </div>

                            <div class="d-flex gap-2">
                                <form action="{{ route('equipos.invitaciones.aceptar', $invitacion->id) }}" method="POST" class="flex-grow-1">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100" onclick="return confirm('¿Unirte a este equipo?')">
                                        <i class="bi bi-check-circle me-1"></i>Aceptar y Unirme
                                    </button>
                                </form>
                                <form action="{{ route('equipos.invitaciones.rechazar', $invitacion->id) }}" method="POST" class="flex-grow-1">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('¿Rechazar esta invitación?')">
                                        <i class="bi bi-x-circle me-1"></i>Rechazar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
