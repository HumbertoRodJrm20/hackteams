@extends('Layout.app') {{-- Usando 'Layout' con L mayúscula --}}

@section('nav_constancias', 'active') {{-- Marca 'Constancias' como activo en el menú --}}
@section('title', 'Repositorio de Constancias')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-5">
        {{-- Asegúrate de tener los iconos de Bootstrap cargados, si no, usa una imagen o emoji --}}
        <i class="bi bi-patch-check-fill fs-1 me-3 text-warning"></i> 
        <h1 class="fw-bold mb-0">Mis Constancias y Certificados</h1>
    </div>

    <p class="lead text-muted mb-4">
        Aquí encontrarás todos los documentos oficiales que certifican tu participación, evaluación o reconocimiento en los eventos de HackTeams.
    </p>

    {{-- Controles de filtrado y solicitud (Se mantienen los placeholders) --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <label for="filtroEvento" class="form-label text-muted">Filtrar por Evento:</label>
            <select id="filtroEvento" class="form-select">
                <option selected>Todos los Eventos</option>
                {{-- Aquí iría un loop real de los eventos a los que ha asistido el usuario --}}
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
        
        {{-- Aquí se eliminó el array de simulación y se usa la variable $constancias real --}}
        {{-- La variable $constancias debe ser pasada desde el controlador (ej. ConstanciaController@index) --}}

        @forelse ($constancias as $constancia)
        <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
            
            <div class="d-flex align-items-center">
                <i class="bi bi-file-earmark-pdf fs-4 text-danger me-3"></i>
                <div>
                    {{-- Usamos los datos reales del modelo Constancia --}}
                    <h5 class="mb-0 fw-bold">
                        {{-- Ejemplo de título basado en el tipo: "Certificado de Participación - Nombre del Evento" --}}
                        Certificado de {{ $constancia->tipo_constancia == 'ganador' ? 'Ganador' : 'Participación' }} - {{ $constancia->evento->nombre }}
                    </h5>
                    {{-- Nota: El modelo Constancia tiene una relación 'evento' para acceder al nombre --}}
                    <small class="text-muted">
                        Tipo: {{ $constancia->tipo_constancia == 'ganador' ? 'Ganador' : 'Participación' }} 
                        | Evento: {{ $constancia->evento->nombre }}
                    </small>
                </div>
            </div>

            <div class="d-flex gap-3 align-items-center">
                
                {{-- Si la ruta_archivo existe, la constancia está disponible --}}
                @if ($constancia->ruta_archivo)
                    <span class="badge bg-success me-2">Disponible</span>
                    
                    {{-- *** ESTE ES EL CAMBIO CLAVE: Enlaza a la ruta de descarga *** --}}
                    <a href="{{ route('constancias.descargar', $constancia->id) }}" class="btn btn-sm btn-primary" target="_blank">
                        <i class="bi bi-download me-1"></i> Descargar
                    </a>
                @else
                    {{-- Si no hay ruta_archivo, la constancia está pendiente --}}
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