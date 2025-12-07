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

    <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i>
        Las constancias se generan automáticamente una vez finalizado cada evento.
    </div>

    @if($constancias->isEmpty())
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle me-2"></i>
            No tienes constancias disponibles aún.
        </div>
    @else

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
