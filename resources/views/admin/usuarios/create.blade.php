@extends('Layout.app')

@section('title', 'Crear Nuevo Usuario')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="d-flex align-items-center mb-4">
                <i class="bi bi-person-plus fs-1 me-3 text-success"></i>
                <h1 class="fw-bold mb-0">Crear Nuevo Usuario</h1>
            </div>

            <div class="card shadow-sm p-4">
                <form method="POST" action="{{ route('admin.usuarios.store') }}">
                    @csrf

                    {{-- Nombre --}}
                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-bold">Nombre Completo</label>
                        <input type="text"
                               class="form-control @error('nombre') is-invalid @enderror"
                               id="nombre"
                               name="nombre"
                               value="{{ old('nombre') }}"
                               required
                               placeholder="Ej: Juan Pérez">
                        @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">Correo Electrónico</label>
                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               placeholder="usuario@example.com">
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Contraseña --}}
                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold">Contraseña</label>
                        <input type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               id="password"
                               name="password"
                               required
                               minlength="8"
                               placeholder="Mínimo 8 caracteres">
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Confirmar Contraseña --}}
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label fw-bold">Confirmar Contraseña</label>
                        <input type="password"
                               class="form-control"
                               id="password_confirmation"
                               name="password_confirmation"
                               required
                               minlength="8"
                               placeholder="Repite la contraseña">
                    </div>

                    {{-- Rol --}}
                    <div class="mb-4">
                        <label for="rol_id" class="form-label fw-bold">Rol</label>
                        <select class="form-select @error('rol_id') is-invalid @enderror"
                                id="rol_id"
                                name="rol_id"
                                required>
                            <option value="" disabled selected>Selecciona un rol...</option>
                            @foreach($roles as $rol)
                                <option value="{{ $rol->id }}" {{ old('rol_id') == $rol->id ? 'selected' : '' }}>
                                    {{ $rol->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('rol_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-person-plus me-2"></i>Crear Usuario
                        </button>
                        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
