@extends('Layout.auth')

@section('title', 'Restablecer Contraseña')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-shield-lock" style="font-size: 4rem; color: #667eea;"></i>
                        <h2 class="mt-3 fw-bold">Nueva Contraseña</h2>
                        <p class="text-muted">Ingresa el código que recibiste por correo y tu nueva contraseña</p>
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

                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf

                        <input type="hidden" name="email" value="{{ session('email') ?? old('email') }}">

                        <div class="mb-3">
                            <label for="codigo" class="form-label fw-bold">Código de Verificación</label>
                            <input type="text"
                                   class="form-control form-control-lg text-center @error('codigo') is-invalid @enderror"
                                   id="codigo"
                                   name="codigo"
                                   maxlength="6"
                                   placeholder="000000"
                                   style="letter-spacing: 10px; font-size: 24px;"
                                   required
                                   autofocus>
                            @error('codigo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">Nueva Contraseña</label>
                            <input type="password"
                                   class="form-control form-control-lg @error('password') is-invalid @enderror"
                                   id="password"
                                   name="password"
                                   placeholder="Mínimo 8 caracteres"
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-bold">Confirmar Contraseña</label>
                            <input type="password"
                                   class="form-control form-control-lg"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   placeholder="Repite la contraseña"
                                   required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
                                <i class="bi bi-check-circle me-2"></i>Restablecer Contraseña
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <small class="text-muted">El código expira en 5 minutos</small>
                    </div>

                    <div class="text-center mt-3">
                        <a href="{{ route('password.request') }}" class="text-decoration-none">
                            <i class="bi bi-arrow-clockwise me-2"></i>Solicitar nuevo código
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
