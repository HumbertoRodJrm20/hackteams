@extends('Layout.app')

@section('nav_eventos', 'active')
@section('title', 'Eventos y Concursos Disponibles')

@section('content')
<div class="eventos-container py-5 container-fluid" style="min-height: 100vh;">
    <style>
        .eventos-container {
            transition: background 0.3s ease;
        }

        [data-theme="light"] .eventos-container {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        [data-theme="dark"] .eventos-container {
            background: linear-gradient(135deg, #0f0f0f 0%, #1a1a1a 100%);
        }

        [data-theme="light"] .evento-header-text {
            color: #2c3e50;
        }

        [data-theme="dark"] .evento-header-text {
            color: #e0e0e0;
        }

        [data-theme="light"] .evento-subtitle {
            color: #7f8c8d;
        }

        [data-theme="dark"] .evento-subtitle {
            color: #a0a0a0;
        }

        /* Colores de texto dinámicos para cards */
        [data-theme="light"] .evento-card-text {
            color: #555;
        }

        [data-theme="dark"] .evento-card-text {
            color: #c0c0c0;
        }

        [data-theme="light"] .evento-date-text {
            color: #7f8c8d;
            border-top-color: #ecf0f1;
        }

        [data-theme="dark"] .evento-date-text {
            color: #a0a0a0;
            border-top-color: #333333;
        }

        [data-theme="light"] .evento-empty-text {
            color: #2c3e50;
        }

        [data-theme="dark"] .evento-empty-text {
            color: #e0e0e0;
        }

        [data-theme="light"] .evento-empty-subtitle {
            color: #7f8c8d;
        }

        [data-theme="dark"] .evento-empty-subtitle {
            color: #a0a0a0;
        }

        /* Icon colors */
        [data-theme="light"] .evento-icon-calendar {
            color: #3498db;
        }

        [data-theme="dark"] .evento-icon-calendar {
            color: #5dade2;
        }

        [data-theme="light"] .evento-icon-flag {
            color: #e74c3c;
        }

        [data-theme="dark"] .evento-icon-flag {
            color: #ec7063;
        }

        .evento-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .evento-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
        }

        [data-theme="dark"] .evento-card:hover {
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.5) !important;
        }
    </style>

    <div class="container" style="padding-top: 2rem; padding-bottom: 2rem;">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="fw-bold evento-header-text" style="font-size: 2.5rem;">
                    <i class="bi bi-event-fill me-3 evento-icon-calendar"></i>Catálogo de Eventos
                </h1>
                <p class="evento-subtitle" style="margin-top: 0.5rem;">Descubre y participa en los mejores eventos de innovación</p>
            </div>

            {{-- Botón de Creación visible solo para administradores --}}
            @auth
                @if(auth()->user()->hasRole('Admin'))
                    <a href="{{ route('eventos.crear') }}" class="btn btn-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
                        <i class="bi bi-plus-circle me-2"></i>Crear Evento
                    </a>
                @endif
            @endauth
        </div>

        {{-- Mensajes de Éxito/Error --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 10px; border: none;">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Formulario de Búsqueda y Filtros --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="card-body" style="padding: 1.5rem;">
                <form method="GET" action="{{ route('eventos.index') }}" class="row g-3">
                    <div class="col-md-6">
                        <label for="search" class="form-label">
                            <i class="bi bi-search me-1"></i>Buscar por nombre
                        </label>
                        <input type="text" class="form-control" id="search" name="search"
                               placeholder="Ej: Hackathon 2024..."
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="estado" class="form-label">
                            <i class="bi bi-filter me-1"></i>Estado del Evento
                        </label>
                        <select class="form-select" id="estado" name="estado">
                            <option value="">Todos los estados</option>
                            <option value="proximo" {{ request('estado') == 'proximo' ? 'selected' : '' }}>Próximo</option>
                            <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                            <option value="finalizado" {{ request('estado') == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-search"></i> Buscar
                        </button>
                        @if(request('search') || request('estado'))
                            <a href="{{ route('eventos.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- Grid de Eventos --}}
        <div class="row g-4">
            @forelse ($eventos as $evento)
                <div class="col-lg-4 col-md-6">
                    <div class="card evento-card h-100 border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
                        {{-- Imagen Promocional --}}
                        @if($evento->imagen)
                            <img src="{{ asset('storage/' . $evento->imagen) }}"
                                 alt="{{ $evento->nombre }}"
                                 style="width: 100%; height: 200px; object-fit: cover;">
                        @else
                            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 200px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-calendar-event" style="font-size: 5rem; color: rgba(255, 255, 255, 0.3);"></i>
                            </div>
                        @endif

                        {{-- Header con badge --}}
                        <div style="padding: 1.5rem 2rem; position: relative;">
                            @php
                                $badgeColor = match ($evento->estado) {
                                    'activo' => '#e74c3c',
                                    'proximo' => '#3498db',
                                    default => '#95a5a6',
                                };
                            @endphp
                            <span class="badge" style="background-color: {{ $badgeColor }}; padding: 0.5rem 1rem; font-size: 0.85rem; position: absolute; top: -10px; right: 2rem;">
                                {{ ucfirst($evento->estado) }}
                            </span>
                            <h5 class="fw-bold mt-2 mb-0 evento-card-text" style="font-size: 1.3rem;">{{ $evento->nombre }}</h5>
                        </div>

                        {{-- Body --}}
                        <div class="card-body d-flex flex-column" style="padding: 2rem;">
                            <p class="card-text evento-card-text" style="line-height: 1.6; flex-grow: 1;">
                                {{ Str::limit($evento->descripcion, 100) }}
                            </p>

                            {{-- Fechas --}}
                            <div class="mb-3 evento-date-text" style="padding-top: 1rem; border-top: 1px solid;">
                                <small>
                                    <i class="bi bi-calendar3 me-2 evento-icon-calendar"></i>
                                    Inicio: {{ \Carbon\Carbon::parse($evento->fecha_inicio)->isoFormat('D MMM YYYY') }}
                                </small>
                                <br>
                                <small>
                                    <i class="bi bi-flag me-2 evento-icon-flag"></i>
                                    Fin: {{ \Carbon\Carbon::parse($evento->fecha_fin)->isoFormat('D MMM YYYY') }}
                                </small>
                            </div>

                            {{-- Botones de Acción --}}
                            <div class="d-flex gap-2">
                                <a href="{{ route('eventos.show', $evento->id) }}" class="btn flex-grow-1" style="background-color: #3498db; color: white; border: none; border-radius: 8px;">
                                    <i class="bi bi-eye me-1"></i>Ver Detalles
                                </a>

                                @auth
                                    @if(auth()->user()->hasRole('Admin'))
                                        <form action="{{ route('eventos.destroy', $evento->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn" style="background-color: #e74c3c; color: white; border: none; border-radius: 8px;" onclick="return confirm('¿Eliminar este evento?');">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card evento-card border-0 shadow-sm text-center" style="border-radius: 16px; padding: 3rem;">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #bdc3c7; margin-bottom: 1rem;"></i>
                        <h4 class="evento-empty-text">No hay eventos disponibles</h4>
                        <p class="evento-empty-subtitle">Vuelve pronto para descubrir nuevos eventos</p>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Paginación --}}
        @if($eventos->hasPages())
            <div class="d-flex justify-content-center mt-5">
                {{ $eventos->links() }}
            </div>
        @endif
    </div>
</div>

@section('scripts')
<script>
    // Forzar scroll al inicio al cargar la página
    window.addEventListener('load', function() {
        window.scrollTo(0, 0);
    });

    // También forzar al inicio cuando se carga el DOM
    document.addEventListener('DOMContentLoaded', function() {
        window.scrollTo(0, 0);
    });
</script>
@endsection