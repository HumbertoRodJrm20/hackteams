@extends('Layout.app')

@section('title', 'Editar Usuario: ' . $usuario->nombre)

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="d-flex align-items-center mb-4">
                <i class="bi bi-pencil-square fs-1 me-3 text-warning"></i>
                <h1 class="fw-bold mb-0">Editar Usuario</h1>
            </div>

            <div class="card shadow-sm p-4">
                <form method="POST" action="{{ route('admin.usuarios.update', $usuario->id) }}">
                    @csrf
                    @method('PUT')

                    {{-- Nombre --}}
                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-bold">Nombre Completo</label>
                        <input type="text"
                               class="form-control @error('nombre') is-invalid @enderror"
                               id="nombre"
                               name="nombre"
                               value="{{ old('nombre', $usuario->nombre) }}"
                               required>
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
                               value="{{ old('email', $usuario->email) }}"
                               required>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Contraseña (opcional) --}}
                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold">Nueva Contraseña (Opcional)</label>
                        <input type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               id="password"
                               name="password"
                               minlength="8"
                               placeholder="Deja en blanco para mantener la actual">
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Confirmar Contraseña --}}
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label fw-bold">Confirmar Nueva Contraseña</label>
                        <input type="password"
                               class="form-control"
                               id="password_confirmation"
                               name="password_confirmation"
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
                            @foreach($roles as $rol)
                                <option value="{{ $rol->id }}" {{ old('rol_id', $usuarioRol?->id) == $rol->id ? 'selected' : '' }}>
                                    {{ $rol->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('rol_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-warning btn-lg text-white">
                            <i class="bi bi-pencil-square me-2"></i>Actualizar Usuario
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
