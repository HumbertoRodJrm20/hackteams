@extends('Layout.app')

@section('nav_eventos', 'active')
@section('title', 'Eventos y Concursos Disponibles')

@section('content')
<div class="container-fluid py-5" style="min-height: 100vh;">
    <style>
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
    </style>

    <div class="container eventos-container" style="padding-top: 2rem; padding-bottom: 2rem;">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="fw-bold evento-header-text" style="font-size: 2.5rem;">
                    <i class="bi bi-event-fill me-3" style="color: #3498db;"></i>Catálogo de Eventos
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

        {{-- Grid de Eventos --}}
        <div class="row g-4">
            @forelse ($eventos as $evento)
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 border-0 shadow-sm" style="border-radius: 16px; overflow: hidden; transition: transform 0.3s, box-shadow 0.3s;">
                        {{-- Header con Gradient --}}
                        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 2rem; color: white;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                @php
                                    $badgeColor = match ($evento->estado) {
                                        'activo' => '#e74c3c',
                                        'proximo' => '#3498db',
                                        default => '#95a5a6',
                                    };
                                @endphp
                                <span class="badge" style="background-color: {{ $badgeColor }}; padding: 0.5rem 1rem; font-size: 0.85rem;">
                                    {{ ucfirst($evento->estado) }}
                                </span>
                                <span style="font-size: 2rem;">🎯</span>
                            </div>
                            <h5 class="fw-bold mt-3 mb-0" style="font-size: 1.3rem;">{{ $evento->nombre }}</h5>
                        </div>

                        {{-- Body --}}
                        <div class="card-body d-flex flex-column" style="padding: 2rem;">
                            <p class="card-text" style="color: #555; line-height: 1.6; flex-grow: 1;">
                                {{ Str::limit($evento->descripcion, 100) }}
                            </p>

                            {{-- Fechas --}}
                            <div class="mb-3" style="padding-top: 1rem; border-top: 1px solid #ecf0f1;">
                                <small style="color: #7f8c8d;">
                                    <i class="bi bi-calendar3 me-2" style="color: #3498db;"></i>
                                    Inicio: {{ \Carbon\Carbon::parse($evento->fecha_inicio)->isoFormat('D MMM YYYY') }}
                                </small>
                                <br>
                                <small style="color: #7f8c8d;">
                                    <i class="bi bi-flag me-2" style="color: #e74c3c;"></i>
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
                    <div class="card border-0 shadow-sm text-center" style="border-radius: 16px; padding: 3rem;">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #bdc3c7; margin-bottom: 1rem;"></i>
                        <h4 style="color: #2c3e50;">No hay eventos disponibles</h4>
                        <p style="color: #7f8c8d;">Vuelve pronto para descubrir nuevos eventos</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
    }
</style>
@endsection