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

            {{-- Mensajes de error --}}
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
                <form method="POST" action="{{ route('equipos.store') }}">
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
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
