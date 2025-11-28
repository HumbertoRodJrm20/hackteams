@extends('Layout.app') 

@section('title', 'Iniciar Sesión')

@section('styles')
<style>
    .navbar-top {
        display: none !important;
    }
    .login-container {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh; 
        background-color: #f8f9fa;
    }
    .login-card {
        max-width: 400px;
        width: 100%;
        padding: 40px;
        border-radius: 10px;
        background-color: white;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
</style>

@endsection

@section('content')
<div class="login-container">
    <div class="card login-card">
        <div class="text-center mb-4">
            <img src="URL_LOGO_HACKTEAMS" alt="HackTeams Logo" style="max-height: 60px;" class="mb-3">
            <h2 class="fw-bold">Bienvenido a Innovatec</h2>
            <p class="text-muted">Inicia sesión para continuar</p>
        </div>

        <form method="POST" action="{{ route('login.auth') }}">
            @csrf 
            
            @error('loginError')
                <div class="alert alert-danger text-center">{{ $message }}</div>
            @enderror

            <div class="mb-3">
                <label for="username" class="form-label">Usuario / Email</label>
                <input type="text" class="form-control form-control-lg" id="username" name="username" required autofocus value="{{ old('username') }}">
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control form-control-lg" id="password" name="password" required>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                </button>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('password.request') }}" class="text-muted small">¿Olvidaste tu contraseña?</a>
                <p class="mt-2 small">¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate aquí</a></p>
            </div>

        </form>
    </div>
</div>
@endsection