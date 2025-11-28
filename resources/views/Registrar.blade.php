@extends('Layout.app')

@section('title', 'Registro de Usuario')

@section('styles')
<style>
    /* Oculta el menú de navegación para la pantalla de login/registro */
    .navbar-top {
        display: none !important;
    }
    .register-container {
        /* Se ajusta para que sea un poco más angosta ahora que no hay columna de perfil */
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh; 
        background-color: #f8f9fa;
        padding: 40px 0; 
    }
    .register-card {
        max-width: 450px; /* Reducimos el ancho máximo */
        width: 100%;
        padding: 40px;
        border-radius: 10px;
        background-color: white;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
</style>
@endsection

@section('content')
<div class="register-container">
    <div class="card register-card">
        <div class="text-center mb-4">
            <img src="URL_LOGO_HACKTEAMS" alt="HackTeams Logo" style="max-height: 60px;" class="mb-3">
            <h2 class="fw-bold">Crear una Cuenta</h2>
            <p class="text-muted">Ingresa tus datos para registrarte</p>
        </div>

        <form method="POST" action="{{ route('register.create') }}">
            @csrf 
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <h5 class="mb-3 text-primary">Datos de Acceso</h5>

            <div class="mb-3">
                <label for="name" class="form-label">Nombre Completo</label>
                <input type="text" class="form-control" id="name" name="name" required value="{{ old('name') }}">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" required value="{{ old('email') }}">
            </div>
        
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>
            
            <div class="d-grid gap-2 pt-3">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="bi bi-person-plus-fill me-2"></i>Registrar Cuenta
                </button>
            </div>

            <div class="text-center mt-4">
                <p class="small">¿Ya tienes una cuenta? <a href="{{ route('login') }}">Iniciar Sesión</a></p>
            </div>

        </form>
    </div>
</div>
@endsection