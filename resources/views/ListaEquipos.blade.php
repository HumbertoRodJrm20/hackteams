@extends('Layout.app')

@section('nav_equipos', 'active')
@section('title', 'Gestión de Equipos')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <i class="bi bi-people fs-2 me-3 text-success"></i>
            <h1 class="fw-bold d-inline">Mis Equipos</h1>
        </div>
        <a href="{{ route('equipos.registrar') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-plus-circle me-2"></i>Crear Equipo
        </a>
    </div>

    {{-- Mensajes --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <hr>

    @if($equipos->isEmpty())
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info text-center" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Sin equipos aún</strong><br>
                    No tienes equipos registrados. <a href="{{ route('equipos.registrar') }}">Crea uno ahora</a>
                </div>
            </div>
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($equipos as $equipo)
                <div class="col">
                    <div class="card h-100 shadow-sm border-0">
                        {{-- Logo --}}
                        @if($equipo['logo_path'])
                            <img src="{{ asset('storage/' . $equipo['logo_path']) }}"
                                 class="card-img-top" alt="Logo de {{ $equipo['nombre'] }}"
                                 style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                 style="height: 200px;">
                                <i class="bi bi-people text-secondary" style="font-size: 3rem;"></i>
                            </div>
                        @endif

                        <div class="card-body d-flex flex-column">
                            {{-- Nombre del equipo --}}
                            <h5 class="card-title fw-bold text-truncate">{{ $equipo['nombre'] }}</h5>

                            {{-- Información --}}
                            <ul class="list-group list-group-flush my-3 flex-grow-1">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span><i class="bi bi-people-fill me-2"></i>Miembros:</span>
                                    <strong>{{ $equipo['miembros'] }}</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span><i class="bi bi-gear-fill me-2"></i>Proyecto:</span>
                                    <strong class="text-truncate">{{ $equipo['proyecto'] }}</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span><i class="bi bi-calendar me-2"></i>Evento:</span>
                                    <strong class="text-truncate">{{ $equipo['evento'] }}</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span><i class="bi bi-flag-fill me-2"></i>Estado:</span>
                                    @php
                                        $badgeClass = match($equipo['estado']) {
                                            'pendiente' => 'bg-warning',
                                            'en_desarrollo' => 'bg-info',
                                            'terminado' => 'bg-success',
                                            'calificado' => 'bg-secondary',
                                            default => 'bg-light text-dark'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $equipo['estado'])) }}
                                    </span>
                                </li>
                            </ul>

                            {{-- Botones de acción --}}
                            <div class="d-grid gap-2">
                                <a href="{{ route('equipos.show', $equipo['id']) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye me-1"></i>Ver Detalles
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
