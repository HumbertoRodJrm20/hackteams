@extends('Layout.app')

@section('nav_equipos', 'active')
@section('title', 'Registrar Nuevo Equipo')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">

            <div class="d-flex align-items-center mb-4">
                <i class="bi bi-people fs-1 me-3 text-primary"></i>
                <h1 class="fw-bold mb-0">Crear Equipo</h1>
            </div>

            {{-- Mensajes de error de sesión --}}
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error:</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Mensajes de error de validación --}}
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error:</strong>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card shadow-sm p-4">
                <form method="POST" action="{{ route('equipos.store') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- Nombre del Equipo --}}
                    <div class="mb-4">
                        <label for="nombre" class="form-label fw-bold">Nombre del Equipo</label>
                        <input type="text" class="form-control form-control-lg @error('nombre') is-invalid @enderror"
                               id="nombre" name="nombre" value="{{ old('nombre') }}" required
                               placeholder="Ej: Los Innovadores, Team Alpha">
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Elige un nombre único y representativo</small>
                    </div>

                    {{-- Evento --}}
                    <div class="mb-4">
                        <label for="evento_id" class="form-label fw-bold">Evento</label>
                        @if($eventos->isEmpty())
                            <div class="alert alert-warning">
                                <i class="bi bi-info-circle me-2"></i>
                                No hay eventos disponibles para crear equipos. Los eventos disponibles ya han iniciado o no existen eventos próximos.
                            </div>
                            <a href="{{ route('equipos.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Volver a Mis Equipos
                            </a>
                        @else
                            <select class="form-select form-select-lg @error('evento_id') is-invalid @enderror"
                                    id="evento_id" name="evento_id" required>
                                <option value="">Selecciona un evento</option>
                                @foreach($eventos as $evento)
                                    <option value="{{ $evento->id }}" {{ old('evento_id') == $evento->id ? 'selected' : '' }}>
                                        {{ $evento->nombre }} - Inicia: {{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d/m/Y H:i') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('evento_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Solo puedes crear equipos para eventos que no hayan iniciado</small>
                        @endif
                    </div>

                    @if($eventos->isNotEmpty())
                    {{-- Tipo de Equipo --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold">Tipo de Equipo</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="es_publico" id="privado" value="0" {{ old('es_publico', '0') == '0' ? 'checked' : '' }}>
                            <label class="form-check-label" for="privado">
                                <strong>Privado</strong> - Solo tú puedes invitar miembros
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="es_publico" id="publico" value="1" {{ old('es_publico') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="publico">
                                <strong>Público</strong> - Otros participantes pueden unirse directamente
                            </label>
                        </div>
                        @error('es_publico')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Los equipos públicos serán visibles para otros participantes del evento
                        </small>
                    </div>

                    {{-- Logo del Equipo (opcional) --}}
                    <div class="mb-4">
                        <label for="logo" class="form-label fw-bold">Logo del Equipo (opcional)</label>
                        <input type="file" class="form-control @error('logo_path') is-invalid @enderror"
                               id="logo" name="logo_path" accept="image/*">
                        @error('logo_path')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Formatos: JPG, PNG, GIF (máximo 2MB)</small>
                    </div>

                    <hr class="my-4">

                    <p class="text-muted small">
                        <i class="bi bi-info-circle me-2"></i>
                        Después de crear el equipo, podrás invitar a otros participantes y asignarles roles.
                    </p>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg py-3 fw-bold">
                            <i class="bi bi-plus-circle me-2"></i>Crear Equipo
                        </button>
                        <a href="{{ route('equipos.index') }}" class="btn btn-outline-secondary btn-lg py-3">
                            <i class="bi bi-arrow-left me-2"></i>Volver
                        </a>
                    </div>
                    @endif
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
