@extends('Layout.app') {{-- Usando 'Layout' con L mayúscula --}}

@section('nav_constancias', 'active') {{-- Usamos plural para consistencia --}}
@section('title', 'Repositorio de Constancias')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-5">
        <i class="bi bi-patch-check-fill fs-1 me-3 text-warning"></i> 
        <h1 class="fw-bold mb-0">Mis Constancias y Certificados</h1>
    </div>

    <p class="lead text-muted mb-4">
        Aquí encontrarás todos los documentos oficiales que certifican tu participación, evaluación o reconocimiento en los eventos de HackTeams.
    </p>

    {{-- Controles de filtrado y solicitud --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <label for="filtroEvento" class="form-label text-muted">Filtrar por Evento:</label>
            <select id="filtroEvento" class="form-select">
                <option selected>Todos los Eventos</option>
                <option>Hackatec 2025</option>
                <option>Innovatec Challenge</option>
            </select>
        </div>
        <div class="col-md-6 d-flex align-items-end">
            <a href="#" class="btn btn-outline-success w-100">
                <i class="bi bi-send-fill me-2"></i>Solicitar Nueva Constancia
            </a>
        </div>
    </div>
    
    <hr class="my-4">

    <div class="list-group">
        
        {{-- 🚨 CAMBIO CRÍTICO: Iteramos sobre $constancias (plural) --}}
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
                    
                    {{-- 🚨 CORRECCIÓN: La ruta DEBE SER PLURAL 'constancias.descargar' --}}
                    <a href="{{ route('constancias.generar', $constancia->id) }}" 
   class="bg-blue-600 text-white px-3 py-2 rounded">
   Descargar PDF
</a>

                @else
                    <span class="badge bg-warning text-dark me-2">Pendiente de firma</span>
                    <button class="btn btn-sm btn-secondary" disabled>
                        <i class="bi bi-clock me-1"></i> Esperar
                    </button>
                @endif
            </div>
            
        </li>
        @empty
            <div class="alert alert-info text-center mt-3">
                Aún no tienes constancias disponibles. ¡Participa en un evento para obtener una!
            </div>
        @endforelse

    </div>

</div>
@endsection