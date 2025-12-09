@extends('Layout.app')

@section('title', 'Solicitudes de Equipo')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4">
        <i class="bi bi-inbox fs-1 me-3 text-primary"></i>
        <h1 class="fw-bold mb-0">Solicitudes para Unirse a tus Equipos</h1>
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

    @if($solicitudes->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox" style="font-size: 3rem; color: #bdc3c7;"></i>
                <h4 class="mt-3">No tienes solicitudes pendientes</h4>
                <p class="text-muted">Cuando alguien solicite unirse a tus equipos, aparecerán aquí.</p>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($solicitudes as $solicitud)
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                            <h5 class="mb-0">
                                <i class="bi bi-people-fill me-2"></i>
                                {{ $solicitud->equipo->nombre }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                     style="width: 50px; height: 50px; font-size: 1.5rem;">
                                    <i class="bi bi-person"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $solicitud->participante->user->name }}</h6>
                                    <small class="text-muted">{{ $solicitud->participante->user->email }}</small>
                                </div>
                            </div>

                            @if($solicitud->mensaje)
                                <div class="alert alert-info">
                                    <strong><i class="bi bi-chat-left-text me-1"></i>Mensaje:</strong>
                                    <p class="mb-0 mt-2">{{ $solicitud->mensaje }}</p>
                                </div>
                            @endif

                            <div class="text-muted small mb-3">
                                <i class="bi bi-clock me-1"></i>
                                Solicitado {{ $solicitud->created_at->diffForHumans() }}
                            </div>

                            <div class="d-flex gap-2">
                                <form action="{{ route('equipos.solicitudes.aceptar', $solicitud->id) }}" method="POST" class="flex-grow-1">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100" onclick="return confirm('¿Aceptar esta solicitud?')">
                                        <i class="bi bi-check-circle me-1"></i>Aceptar
                                    </button>
                                </form>
                                <form action="{{ route('equipos.solicitudes.rechazar', $solicitud->id) }}" method="POST" class="flex-grow-1">
                                    @csrf
                                    <button type="submit" class="btn btn-danger w-100" onclick="return confirm('¿Rechazar esta solicitud?')">
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
