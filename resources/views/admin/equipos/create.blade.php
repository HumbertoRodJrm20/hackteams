@extends('Layout.app')

@section('title', 'Crear Nuevo Equipo')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="d-flex align-items-center mb-4">
                <i class="bi bi-people-fill fs-1 me-3 text-success"></i>
                <h1 class="fw-bold mb-0">Crear Nuevo Equipo</h1>
            </div>

            <div class="card shadow-sm p-4">
                <form method="POST" action="{{ route('admin.equipos.store') }}">
                    @csrf

                    {{-- Nombre --}}
                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-bold">Nombre del Equipo</label>
                        <input type="text"
                               class="form-control @error('nombre') is-invalid @enderror"
                               id="nombre"
                               name="nombre"
                               value="{{ old('nombre') }}"
                               required
                               placeholder="Ej: Equipo A, Innovadores, etc.">
                        @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Evento --}}
                    <div class="mb-3">
                        <label for="evento_id" class="form-label fw-bold">Evento</label>
                        <select class="form-select @error('evento_id') is-invalid @enderror"
                                id="evento_id"
                                name="evento_id"
                                required>
                            <option value="" disabled selected>Selecciona un evento...</option>
                            @foreach($eventos as $evento)
                                <option value="{{ $evento->id }}" {{ old('evento_id') == $evento->id ? 'selected' : '' }}>
                                    {{ $evento->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('evento_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Participante Líder --}}
                    <div class="mb-4">
                        <label for="participante_lider_id" class="form-label fw-bold">Líder del Equipo</label>
                        <select class="form-select @error('participante_lider_id') is-invalid @enderror"
                                id="participante_lider_id"
                                name="participante_lider_id"
                                required>
                            <option value="" disabled selected>Selecciona el líder...</option>
                            @foreach($participantes as $participante)
                                <option value="{{ $participante->user_id }}" {{ old('participante_lider_id') == $participante->user_id ? 'selected' : '' }}>
                                    {{ $participante->user->nombre }} ({{ $participante->user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('participante_lider_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-people-fill me-2"></i>Crear Equipo
                        </button>
                        <a href="{{ route('admin.equipos.index') }}" class="btn btn-outline-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
