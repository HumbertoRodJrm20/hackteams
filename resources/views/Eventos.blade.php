@extends('Layout.app')

@section('nav_eventos', 'active')
@section('title', 'Eventos y Concursos Disponibles')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-primary"><i class="bi bi-calendar-event me-2"></i>Catálogo de Eventos</h1>
        
        {{-- Botón de Creación visible solo para administradores --}}
        @auth
            {{-- Asumiendo el helper hasRole('Admin') --}}
            @if(auth()->user()->hasRole('Admin'))
                <a href="{{ route('eventos.crear') }}" class="btn btn-success btn-lg">
                    <i class="bi bi-plus-circle me-2"></i>Crear Nuevo Evento
                </a>
            @endif
        @endauth
    </div>
    
    {{-- Mensajes de Éxito/Error --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <hr>
    
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        
        {{-- El controlador ya pasa la variable $eventos --}}
        @forelse ($eventos as $evento)
            <div class="col">
                <div class="card card-evento h-100 shadow-sm border-0">
                    <div class="card-body d-flex flex-column">
                        
                        {{-- Estado del Evento --}}
                        <div class="mb-2">
                            @php
                                $badgeClass = match ($evento->estado) {
                                    'activo' => 'bg-danger',
                                    'proximo' => 'bg-info',
                                    default => 'bg-secondary',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }} fw-bold text-uppercase mb-2">
                                {{ ucfirst($evento->estado) }}
                            </span>
                        </div>
                        
                        <h5 class="card-title fw-bold text-truncate">{{ $evento->nombre }}</h5>
                        
                        {{-- Fechas --}}
                        <p class="card-text small text-muted">
                            <i class="bi bi-clock me-1"></i> 
                            **Inicio:** {{ \Carbon\Carbon::parse($evento->fecha_inicio)->isoFormat('D MMM YYYY') }}
                        </p>
                        
                        {{-- Descripción Corta --}}
                        <p class="card-text flex-grow-1 mb-4">
                            {{ Str::limit($evento->descripcion, 80) }}
                        </p>

                        {{-- Botones de Acción (Admin y Usuarios) --}}
                        <div class="mt-auto d-flex justify-content-between align-items-center pt-3 border-top">
                            
                            {{-- Botón Leer Más (Enlace a Detalle) --}}
                            <a href="{{ route('eventos.show', $evento->id) }}" class="btn btn-link text-info fw-bold p-0">
                                LEER MÁS <i class="bi bi-arrow-right"></i>
                            </a>

                            @auth
                                @if(auth()->user()->hasRole('Admin'))
                                    {{-- Botón Eliminar (Visible solo para Admin) --}}
                                    <form action="{{ route('eventos.destroy', $evento->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar el evento: {{ $evento->nombre }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger fw-bold p-0">
                                            ELIMINAR
                                        </button>
                                    </form>
                                @endif
                            @endauth

                        </div>
                    </div>
                </div>
            </div>
        @empty
            {{-- Mensaje si no hay eventos --}}
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <h4 class="alert-heading"><i class="bi bi-exclamation-triangle-fill"></i> ¡Ups!</h4>
                    <p>Actualmente no hay eventos programados.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection