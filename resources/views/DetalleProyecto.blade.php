@extends('Layout.app')

@section('title', 'Detalle del Proyecto: ' . $proyecto->titulo)

@section('content')
    <style>
        [data-theme="light"] .proyecto-bg {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        [data-theme="dark"] .proyecto-bg {
            background: linear-gradient(135deg, #0f0f0f 0%, #1a1a1a 100%);
        }

        [data-theme="light"] .proyecto-text {
            color: #2c3e50;
        }

        [data-theme="dark"] .proyecto-text {
            color: #e0e0e0;
        }

        [data-theme="light"] .proyecto-text-secondary {
            color: #7f8c8d;
        }

        [data-theme="dark"] .proyecto-text-secondary {
            color: #a0a0a0;
        }
    </style>

    <div class="container-fluid py-5 proyecto-bg" style="min-height: 100vh;">
        <div class="container">
            {{-- Header --}}
            <div class="mb-5">
                <a href="{{ route('equipos.index') }}" class="btn btn-sm" style="background-color: #6c757d; color: white; border: none;">
                    <i class="bi bi-arrow-left me-2"></i>Volver
                </a>

                <div class="mt-4">
                    <h1 class="fw-bold proyecto-text">{{ $proyecto->titulo }}</h1>
                    <p class="lead proyecto-text-secondary">
                        <i class="bi bi-people-fill me-2"></i>Equipo: <strong>{{ $proyecto->equipo->nombre }}</strong>
                    </p>
                </div>
            </div>

            <div class="row g-4">
                {{-- Contenido Principal --}}
                <div class="col-lg-8">
                    {{-- Tarjeta de Información General --}}
                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
                        <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
                            <h5 class="mb-0"><i class="bi bi-file-text me-2"></i>Descripción del Proyecto</h5>
                        </div>
                        <div class="card-body" style="padding: 2rem;">
                            <p class="card-text" style="color: #2c3e50; line-height: 1.8; font-size: 1.05rem;">
                                {{ $proyecto->resumen }}
                            </p>

                            <hr style="border-color: #ecf0f1;">

                            <div class="row mt-4">
                                <div class="col-md-6 mb-3">
                                    <small style="color: #7f8c8d;">Evento</small>
                                    <p class="fw-bold" style="color: #2c3e50;">{{ $proyecto->evento->nombre }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <small style="color: #7f8c8d;">Estado</small>
                                    <p>
                                        @php
                                            $badgeColor = match($proyecto->estado) {
                                                'pendiente' => '#f39c12',
                                                'en_desarrollo' => '#3498db',
                                                'terminado' => '#27ae60',
                                                'calificado' => '#9b59b6',
                                                default => '#95a5a6',
                                            };
                                        @endphp
                                        <span class="badge" style="background-color: {{ $badgeColor }}; padding: 0.5rem 1rem; font-size: 0.9rem;">
                                            {{ ucfirst(str_replace('_', ' ', $proyecto->estado)) }}
                                        </span>
                                    </p>
                                </div>
                            </div>

                            @if ($proyecto->link_repositorio)
                                <div class="mt-3">
                                    <a href="{{ $proyecto->link_repositorio }}" target="_blank" class="btn btn-sm" style="background-color: #34495e; color: white; border: none;">
                                        <i class="bi bi-github me-1"></i>Ver Repositorio
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Sección de Avances --}}
                    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                        <div class="card-header" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border: none;">
                            <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Avances del Proyecto</h5>
                        </div>
                        <div class="card-body" style="padding: 0;">
                            @forelse ($proyecto->avances as $avance)
                                <div style="padding: 1.5rem; border-bottom: 1px solid #ecf0f1;">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="fw-bold mb-2" style="color: #2c3e50;">
                                                <i class="bi bi-calendar-event me-2" style="color: #3498db;"></i>
                                                {{ $avance->fecha->isoFormat('D MMMM YYYY') }}
                                            </h6>
                                            <p class="mb-0" style="color: #555;">{{ $avance->descripcion }}</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div style="padding: 2rem; text-align: center;">
                                    <p style="color: #7f8c8d;">
                                        <i class="bi bi-inbox me-2"></i>Aún no hay avances registrados
                                    </p>
                                </div>
                            @endforelse
                        </div>

                        @if (auth()->check() && $proyecto->equipo->participantes->pluck('user_id')->contains(auth()->id()))
                            <div class="card-footer text-center" style="background-color: #f8f9fa; border: none;">
                                <button class="btn btn-sm" style="background-color: #3498db; color: white; border: none;" data-bs-toggle="modal" data-bs-target="#modalNuevoAvance">
                                    <i class="bi bi-plus-circle me-1"></i>Agregar Avance
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Barra Lateral --}}
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm" style="border-radius: 12px; position: sticky; top: 20px;">
                        <div class="card-header" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white; border: none;">
                            <h5 class="mb-0"><i class="bi bi-award me-2"></i>Evaluación</h5>
                        </div>
                        <div class="card-body text-center" style="padding: 2rem;">
                            @if ($proyecto->calificaciones->isNotEmpty())
                                @php
                                    $promedio = $proyecto->calificaciones->avg('puntuacion');
                                @endphp
                                <p style="color: #7f8c8d; margin-bottom: 0.5rem;">Puntuación Promedio</p>
                                <h2 class="fw-bold" style="color: #27ae60; font-size: 3rem; margin: 0.5rem 0;">
                                    {{ number_format($promedio ?? 0, 1) }}
                                </h2>
                                <small style="color: #7f8c8d;">Evaluado por {{ $proyecto->calificaciones->count() }} juez(ces)</small>
                            @else
                                <div style="padding: 1rem; background-color: #ecf0f1; border-radius: 8px;">
                                    <p style="color: #7f8c8d; margin: 0;">
                                        <i class="bi bi-hourglass-split me-2"></i>Pendiente de evaluación
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL para Nuevo Avance (Añadir al final del archivo) --}}
    <div class="modal fade" id="modalNuevoAvance" tabindex="-1" aria-labelledby="modalNuevoAvanceLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('avances.store') }}">
                    @csrf
                    <input type="hidden" name="proyecto_id" value="{{ $proyecto->id }}">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="modalNuevoAvanceLabel">Registrar Avance</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="descripcion_avance" class="form-label">Descripción del Avance</label>
                            <textarea class="form-control" id="descripcion_avance" name="descripcion" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="fecha_avance" class="form-label">Fecha del Avance</label>
                            <input type="date" class="form-control" id="fecha_avance" name="fecha" value="{{ now()->toDateString() }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Avance</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
