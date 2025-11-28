@extends('Layout.app')

@section('nav_perfil', 'active')
@section('title', 'Mi Perfil de Usuario')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            
            <div class="d-flex align-items-center mb-4">
                <i class="bi bi-person-circle fs-1 me-3 text-secondary"></i>
                <h1 class="fw-bold mb-0">Configuración de Perfil</h1>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(isset($user))
            
                <div class="card shadow-sm p-4">
                    <h4 class="mb-4 text-primary">Datos de Identificación</h4>

                    
                    <div class="mb-3">
                        <label class="form-label">Nombre Completo</label>
                        <p class="form-control-static fw-bold fs-5">{{ $user->name ?? 'N/A' }}</p>
                        {{-- Campo oculto para asegurar que el controlador reciba el valor del nombre --}}
                        <input type="hidden" name="name" value="{{ $user->name ?? '' }}">
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Correo Electrónico</label>
                        <p class="form-control-static fw-bold fs-5">{{ $user->email ?? 'N/A' }}</p>
                        {{-- Campo oculto para asegurar que el controlador reciba el valor del email --}}
                        <input type="hidden" name="email" value="{{ $user->email ?? '' }}">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Rol del Sistema</label>
                        <p class="form-control-static fw-bold text-info">{{ ucfirst($user->role ?? 'N/A') }}</p>
                    </div>

                    <h4 class="mb-3 text-primary">Cambiar Contraseña</h4>
                    
                    <form method="POST" action="{{ route('perfil.update') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Nueva Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Dejar vacío si no desea cambiar">
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg py-3">
                                <i class="bi bi-save me-2"></i>Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            
            @else
                <div class="alert alert-warning text-center">
                    No se pudo cargar la información del usuario. Por favor, verifica tu autenticación.
                </div>
            @endif

        </div>
    </div>
</div>
@endsection