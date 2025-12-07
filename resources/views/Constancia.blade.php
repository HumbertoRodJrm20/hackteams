@extends('Layout.app') 

@section('nav_constancias', 'active')
@section('title', 'Constancias')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-5">
        <i class="bi bi-patch-check-fill fs-1 me-3 text-warning"></i> 
        <h1 class="fw-bold mb-0">Mis Constancias y Certificados</h1>
    </div>

    <p class="lead text-muted mb-4">
        Aquí encontrarás todos los documentos oficiales que certifican tu participación, evaluación o reconocimiento en los eventos de HackTeams.
    </p>

    {{-- Información --}}
    <div class="alert alert-info mb-4">
        <i class="bi bi-info-circle me-2"></i>
        Las constancias se generan automáticamente al finalizar cada evento. Aquí podrás ver y descargar las constancias de los eventos en los que has participado.
    </div>

    <div class="list-group">
        
        @forelse ($constancias as $constancia)
        <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
            
            <div class="d-flex align-items-center">
                <i class="bi bi-file-earmark-pdf fs-4 text-danger me-3"></i>
                <div>
                    <h5 class="mb-0 fw-bold">
                        Certificado de {{ $constancia->tipo_constancia == 'ganador' ? 'Ganador' : 'Participación' }} - {{ $constancia->evento->nombre }}
                    </h5>
                    <small class="text-muted">
                        Tipo: {{ $constancia->tipo_constancia == 'ganador' ? 'Ganador' : 'Participación' }} 
                        | Evento: {{ $constancia->evento->nombre }}
                    </small>
                </div>
            </div>

            <div class="d-flex gap-3 align-items-center">
                
                {{-- Usa el accessor $constancia->ruta_archivo --}}
                @if ($constancia->ruta_archivo)
                    <span class="badge bg-success me-2">Disponible</span>
                    
                    <a href="{{ route('constancias.generar', $constancia->id) }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-download me-1"></i>    
                    Descargar</a>

                @else
                    <span class="badge bg-warning text-dark me-2">Pendiente de firma</span>
                    <button class="btn btn-sm btn-secondary" disabled>
                        <i class="bi bi-clock me-1"></i> Esperar
                    </button>
                @endif
            </div>
            
        </li>
        @empty
            <div class="alert alert-warning text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                <h5>Aún no tienes constancias disponibles</h5>
                <p class="mb-0">Las constancias se generarán automáticamente al finalizar los eventos en los que participes.</p>
            </div>
        @endforelse

    </div>

</div>
@endsection