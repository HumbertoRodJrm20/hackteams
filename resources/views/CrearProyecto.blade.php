@extends('Layout.app')

@section('title', 'Registrar Nuevo Proyecto')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="card shadow-lg border-0">
                    <div class="card-header bg-success text-white text-center py-3">
                        <h2 class="h4 mb-0 fw-bold"><i class="bi bi-journal-plus me-2"></i>Registrar Proyecto</h2>
                    </div>
                    <div class="card-body p-4">

                        {{-- Información del Equipo --}}
                        <div class="alert alert-primary text-center">
                            <h5 class="mb-0">Registrando proyecto para el equipo: **{{ $equipo->nombre }}**</h5>
                            <small class="text-muted">ID de Equipo: #{{ $equipo->id }}</small>
                        </div>

                        {{-- Mostrar errores --}}
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form method="POST" action="{{ route('proyectos.store') }}" enctype="multipart/form-data">
                            @csrf

                            {{-- Campo: Evento --}}
                            <div class="mb-3">
                                <label for="evento_id" class="form-label fw-bold">Evento al que Aplicará</label>
                                <select class="form-select @error('evento_id') is-invalid @enderror"
                                        id="evento_id"
                                        name="evento_id"
                                        required>
                                    <option value="" disabled selected>Selecciona el evento...</option>

                                    @foreach ($eventos as $evento)
                                        <option value="{{ $evento->id }}"
                                            {{ old('evento_id') == $evento->id ? 'selected' : '' }}>
                                            {{ $evento->nombre }} ({{ ucfirst($evento->estado) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('evento_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Campo: Categoría --}}
                            <div class="mb-3">
                                <label for="categoria_id" class="form-label fw-bold">Categoría del Proyecto</label>
                                <select class="form-select @error('categoria_id') is-invalid @enderror"
                                        id="categoria_id"
                                        name="categoria_id"
                                        required>
                                    <option value="" disabled selected>Selecciona una categoría...</option>

                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->id }}"
                                            {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                            <i class="bi {{ $categoria->icono }}"></i> {{ $categoria->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('categoria_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">La categoría ayuda a clasificar tu proyecto</small>
                            </div>

                            {{-- Campo: Título del Proyecto --}}
                            <div class="mb-3">
                                <label for="titulo" class="form-label fw-bold">Título del Proyecto</label>
                                <input type="text"
                                       class="form-control @error('titulo') is-invalid @enderror"
                                       id="titulo"
                                       name="titulo"
                                       value="{{ old('titulo') }}"
                                       required
                                       placeholder="Ej: Plataforma de Gestión de Hackathones">
                                @error('titulo')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Campo: Resumen / Descripción --}}
                            <div class="mb-3">
                                <label for="resumen" class="form-label fw-bold">Resumen del Proyecto</label>
                                <textarea class="form-control @error('resumen') is-invalid @enderror"
                                          id="resumen"
                                          name="resumen"
                                          rows="5"
                                          required
                                          placeholder="Describe brevemente el problema que resuelve y la solución propuesta.">{{ old('resumen') }}</textarea>
                                @error('resumen')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Campo: Link Repositorio --}}
                            <div class="mb-4">
                                <label for="link_repositorio" class="form-label fw-bold">Link al Repositorio (GitHub, GitLab, etc.)</label>
                                <input type="url"
                                       class="form-control @error('link_repositorio') is-invalid @enderror"
                                       id="link_repositorio"
                                       name="link_repositorio"
                                       value="{{ old('link_repositorio') }}"
                                       placeholder="https://github.com/usuario/mi-proyecto">
                                @error('link_repositorio')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <h5 class="mb-3 text-primary"><i class="bi bi-paperclip me-2"></i>Archivos del Proyecto</h5>

                            {{-- Campo: Imagen del Proyecto --}}
                            <div class="mb-3">
                                <label for="imagen" class="form-label fw-bold">Imagen del Proyecto (Screenshot, Mockup, etc.)</label>
                                <input type="file"
                                       class="form-control @error('imagen') is-invalid @enderror"
                                       id="imagen"
                                       name="imagen"
                                       accept="image/jpeg,image/jpg,image/png,image/webp">
                                @error('imagen')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Formatos aceptados: JPG, PNG, WEBP (Máximo 5MB)</small>
                            </div>

                            {{-- Campo: Documento/Evidencia del Proyecto --}}
                            <div class="mb-4">
                                <label for="documento" class="form-label fw-bold">Documento de Evidencias (PDF)</label>
                                <input type="file"
                                       class="form-control @error('documento') is-invalid @enderror"
                                       id="documento"
                                       name="documento"
                                       accept="application/pdf">
                                @error('documento')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Puedes incluir presentación, manual técnico, etc. (Máximo 10MB)</small>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="bi bi-box-arrow-in-up-right me-2"></i>Guardar Proyecto
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
