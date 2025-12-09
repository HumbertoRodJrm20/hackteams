@extends('Layout.auth') {{-- ¡CORREGIDO! Usamos la plantilla sin navbar --}}

@section('title', 'Registro de Usuario')

{{-- ELIMINAMOS EL @section('styles') DE ANULACIÓN --}}
{{-- Ya no es necesario ocultar la navbar ni redefinir el ancho,
     pues 'Layout.auth' ya hace el centrado y el ancho compacto. --}}

@section('content')
<div class="card login-card"> {{-- Usamos login-card porque tiene los estilos en Layout.auth --}}
    <div class="text-center mb-4">
        <img src="{{ asset('images/HackTeams_Logo.png') }}" alt="HackTeams Logo" style="max-height: 60px;" class="mb-3">
        <h2 class="fw-bold">Crear una Cuenta</h2>
        <p class="text-muted">Ingresa tus datos para registrarte</p>
    </div>

    <form method="POST" action="{{ route('register.create') }}">
        @csrf 
        
        @if ($errors->any())
            <div class="alert alert-danger">
                Por favor, corrige los errores en el formulario.
            </div>
        @endif
        
        <h5 class="mb-3 text-primary">Datos Personales</h5>

        <div class="mb-3">
            <label for="name" class="form-label">Nombre Completo</label>
            <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" id="name" name="name" required value="{{ old('name') }}" placeholder="Ej: Juan Pérez García">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="matricula" class="form-label">Número de Control</label>
            <input type="text" class="form-control form-control-lg @error('matricula') is-invalid @enderror" id="matricula" name="matricula" required value="{{ old('matricula') }}" placeholder="Ej: 12345678">
            @error('matricula')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="carrera_id" class="form-label">Carrera</label>
            <select class="form-select form-select-lg @error('carrera_id') is-invalid @enderror" id="carrera_id" name="carrera_id" required>
                <option value="">Selecciona tu carrera...</option>
                @foreach($carreras as $carrera)
                    <option value="{{ $carrera->id }}" {{ old('carrera_id') == $carrera->id ? 'selected' : '' }}>
                        {{ $carrera->nombre }}
                    </option>
                @endforeach
            </select>
            @error('carrera_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <h5 class="mb-3 text-primary mt-4">Datos de Acceso</h5>

        <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" id="email" name="email" required value="{{ old('email') }}" placeholder="tu@email.com">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" id="password" name="password" required placeholder="Mínimo 8 caracteres">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
            <input type="password" class="form-control form-control-lg" id="password_confirmation" name="password_confirmation" required placeholder="Repite tu contraseña">
        </div>
        
        <div class="d-grid gap-2 pt-3">
            <button type="submit" class="btn btn-info btn-lg text-white">
                <i class="bi bi-person-plus-fill me-2"></i>Registrar Cuenta
            </button>
        </div>

        <div class="text-center mt-4">
            <p class="small">¿Ya tienes una cuenta? <a href="{{ route('login') }}">Iniciar Sesión</a></p>
        </div>

    </form>
</div>
@endsection