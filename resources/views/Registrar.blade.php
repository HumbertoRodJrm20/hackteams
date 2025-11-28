@extends('Layout.auth') {{-- ¡CORREGIDO! Usamos la plantilla sin navbar --}}

@section('title', 'Registro de Usuario')

{{-- ELIMINAMOS EL @section('styles') DE ANULACIÓN --}}
{{-- Ya no es necesario ocultar la navbar ni redefinir el ancho,
     pues 'Layout.auth' ya hace el centrado y el ancho compacto. --}}

@section('content')
<div class="card login-card"> {{-- Usamos login-card porque tiene los estilos en Layout.auth --}}
    <div class="text-center mb-4">
        <img src="URL_LOGO_HACKTEAMS" alt="HackTeams Logo" style="max-height: 60px;" class="mb-3">
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
        
        <h5 class="mb-3 text-primary">Datos de Acceso</h5>

        <div class="mb-3">
            <label for="name" class="form-label">Nombre Completo</label>
            <input type="text" class="form-control form-control-lg" id="name" name="name" required value="{{ old('name') }}">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control form-control-lg" id="email" name="email" required value="{{ old('email') }}">
        </div>
    
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control form-control-lg" id="password" name="password" required>
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
            <input type="password" class="form-control form-control-lg" id="password_confirmation" name="password_confirmation" required>
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