@extends('Layout.auth')

@section('title', 'Recuperar Contraseña')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-key" style="font-size: 4rem; color: #667eea;"></i>
                        <h2 class="mt-3 fw-bold">Recuperar Contraseña</h2>
                        <p class="text-muted">Ingresa tu correo y te enviaremos un código de recuperación</p>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('password.email') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold">Correo Electrónico</label>
                            <input type="email"
                                   class="form-control form-control-lg @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   placeholder="tu@email.com"
                                   required
                                   autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
                                <i class="bi bi-envelope me-2"></i>Enviar Código
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <a href="{{ route('login') }}" class="text-decoration-none">
                            <i class="bi bi-arrow-left me-2"></i>Volver al inicio de sesión
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
