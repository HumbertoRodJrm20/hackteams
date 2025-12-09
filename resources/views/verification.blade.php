@extends('Layout.auth')

@section('title', 'Verificar Email')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-envelope-check" style="font-size: 4rem; color: #667eea;"></i>
                        <h2 class="mt-3 fw-bold">Verifica tu Email</h2>
                        <p class="text-muted">Ingresa el código de 6 dígitos que enviamos a tu correo</p>
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

                    @if(session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            {{ session('info') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('verification.verify') }}" method="POST">
                        @csrf

                        <input type="hidden" name="email" value="{{ session('email') ?? old('email') }}">

                        <div class="mb-4">
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

                        <div class="d-grid">
                            <button type="submit" class="btn btn-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
                                <i class="bi bi-check-circle me-2"></i>Verificar Email
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="text-muted mb-3">¿No recibiste el código?</p>
                        <form action="{{ route('verification.resend') }}" method="POST">
                            @csrf
                            <input type="hidden" name="email" value="{{ session('email') ?? old('email') }}">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-clockwise me-2"></i>Reenviar Código
                            </button>
                        </form>
                    </div>

                    <div class="text-center mt-3">
                        <small class="text-muted">El código expira en 5 minutos</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
