@extends('Layout.app')

@section('nav_eventos', 'active')
@section('title', 'Editar Evento')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="d-flex align-items-center mb-4">
                <i class="bi bi-pencil-square fs-1 me-3 text-warning"></i>
                <h1 class="fw-bold mb-0">Editar Evento</h1>
            </div>

            <div class="card shadow-sm p-4">

                <form method="POST" action="{{ route('eventos.update', $evento->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <h4 class="mb-3 text-primary">Información Básica</h4>

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Evento</label>
                        <input type="text" class="form-control form-control-lg @error('nombre') is-invalid @enderror" id="nombre" name="nombre" required value="{{ old('nombre', $evento->nombre) }}" placeholder="Ej: Hackatec 2026 o Innovatec Challenge">
                        @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="descripcion" class="form-label">Descripción Detallada</label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" rows="4" required placeholder="Describe el propósito, las reglas y los participantes objetivo.">{{ old('descripcion', $evento->descripcion) }}</textarea>
                        @error('descripcion')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <h4 class="mb-3 text-primary">Fechas y Estado</h4>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                            <input type="date" class="form-control @error('fecha_inicio') is-invalid @enderror" id="fecha_inicio" name="fecha_inicio" required value="{{ old('fecha_inicio', $evento->fecha_inicio) }}">
                            @error('fecha_inicio')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="fecha_fin" class="form-label">Fecha de Finalización</label>
                            <input type="date" class="form-control @error('fecha_fin') is-invalid @enderror" id="fecha_fin" name="fecha_fin" required value="{{ old('fecha_fin', $evento->fecha_fin) }}">
                            @error('fecha_fin')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="max_equipos" class="form-label">Máximo de Equipos</label>
                            <input type="number" class="form-control @error('max_equipos') is-invalid @enderror" id="max_equipos" name="max_equipos" required min="1" value="{{ old('max_equipos', $evento->max_equipos) }}" placeholder="Ej: 50">
                            @error('max_equipos')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Define cuántos equipos máximo pueden participar en este evento.</small>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="estado" class="form-label">Estado del Evento</label>
                        <select class="form-select @error('estado') is-invalid @enderror" id="estado" name="estado" required>
                            <option value="proximo" {{ old('estado', $evento->estado) == 'proximo' ? 'selected' : '' }}>Próximo</option>
                            <option value="activo" {{ old('estado', $evento->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                            <option value="finalizado" {{ old('estado', $evento->estado) == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                        </select>
                        @error('estado')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Define si el evento está en planeación, abierto a registro, o si ya terminó.</small>
                    </div>

                    <h4 class="mb-3 text-primary">Imagen Promocional</h4>

                    <div class="mb-4">
                        <label for="imagen" class="form-label">Subir Imagen Promocional (Banner)</label>
                        <input class="form-control @error('imagen') is-invalid @enderror" type="file" id="imagen" name="imagen" accept="image/*">
                        @error('imagen')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Sube una imagen atractiva para el evento (JPG, PNG). Dejar vacío para mantener la imagen actual.</small>
                    </div>

                    <hr class="my-4">

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-warning btn-lg py-3 text-white fw-bold">
                            <i class="bi bi-check-circle me-2"></i>Actualizar Evento
                        </button>
                        <a href="{{ route('eventos.show', $evento->id) }}" class="btn btn-outline-secondary btn-lg py-3">
                            <i class="bi bi-x-circle me-2"></i>Cancelar
                        </a>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
@endsection
