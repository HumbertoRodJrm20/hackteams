@extends('Layout.app')

@section('title', 'Constancias')
@section('nav_constancia', 'active')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-5">
        <i class="bi bi-patch-check-fill fs-1 me-3 text-warning"></i> 
        <h1 class="fw-bold mb-0">Mis Constancias y Certificados</h1>
    </div>

    <p class="lead text-muted mb-4">
        Aquí encontrarás todos los documentos oficiales que certifican tu participación, evaluación o reconocimiento en los eventos de HackTeams.
    </p>

    @if($constancias->isEmpty())
        <p>No tienes constancias disponibles aún.</p>
    @else

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
            <a href="{{ route('solicitudes.create') }}" class="btn btn-outline-success w-100">
    <i class="bi bi-send-fill me-2"></i>Solicitar Nueva Constancia
</a>

        </div>
    </div>

    <hr class="my-4">

        <div class="list-group">
            @foreach ($constancias as $c)
                    <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                <div class="d-flex align-items-center">
                <i class="bi bi-file-earmark-pdf fs-4 text-danger me-3"></i>
                <div>
                        <h5 class="mb-0 fw-bold">
                        Certificado de {{ $c->tipo_constancia == 'ganador' ? 'Ganador' : 'Participación' }} - {{ $c->evento->nombre }}
                    </h5>
                    <small class="text-muted">
                        Tipo: Juez
                        | Evento: {{ $c->evento->nombre }}
                    </small>
                    </div>
</div>
            <div class="d-flex gap-3 align-items-center">

                    <a href="{{ route('constancia.juez.generar', $c->id) }}"
                    class="btn btn-sm btn-primary">
                    <i class="bi bi-download me-1"></i>  
                        Descargar
                    </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
