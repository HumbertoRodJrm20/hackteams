@extends('Layout.app')

@section('title', 'Editar Equipo: ' . $equipo->nombre)

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="d-flex align-items-center mb-4">
                <i class="bi bi-pencil-square fs-1 me-3 text-warning"></i>
                <h1 class="fw-bold mb-0">Editar Equipo</h1>
            </div>

            <div class="card shadow-sm p-4">
                <form method="POST" action="{{ route('admin.equipos.update', $equipo->id) }}">
                    @csrf
                    @method('PUT')

                    {{-- Nombre --}}
                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-bold">Nombre del Equipo</label>
                        <input type="text"
                               class="form-control @error('nombre') is-invalid @enderror"
                               id="nombre"
                               name="nombre"
                               value="{{ old('nombre', $equipo->nombre) }}"
                               required>
                        @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Evento --}}
                    <div class="mb-4">
                        <label for="evento_id" class="form-label fw-bold">Evento</label>
                        <select class="form-select @error('evento_id') is-invalid @enderror"
                                id="evento_id"
                                name="evento_id"
                                required>
                            @foreach($eventos as $evento)
                                <option value="{{ $evento->id }}" {{ old('evento_id', $equipo->evento_id) == $evento->id ? 'selected' : '' }}>
                                    {{ $evento->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('evento_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-warning btn-lg text-white">
                            <i class="bi bi-pencil-square me-2"></i>Actualizar Equipo
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
