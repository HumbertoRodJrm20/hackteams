@extends('Layout.app')

@section('nav_perfil', 'active')
@section('title', 'Mi Perfil de Usuario')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            
            <div class="d-flex align-items-center mb-4">
                <i class="bi bi-person-circle fs-1 me-3 text-secondary"></i>
                <h1 class="fw-bold mb-0">Mi Cuenta</h1>
            </div>

            {{-- Mensajes de Éxito/Error --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(isset($user))
            
                {{-- 1. SECCIÓN DE DATOS DEL USUARIO (Estáticos) --}}
                <div class="card shadow-sm p-4 mb-4">
                    <h4 class="mb-4 text-primary">Datos del Usuario</h4>

                    {{-- Nombre (Estático) --}}
                    <div class="mb-3 border-bottom pb-3">
                        <label class="form-label text-muted small">Nombre Completo</label>
                        <p class="form-control-static fw-bold fs-5">{{ $user->name ?? 'N/A' }}</p>
                    </div>
                    
                    {{-- Correo Electrónico (Estático) --}}
                    <div class="mb-3 border-bottom pb-3">
                        <label class="form-label text-muted small">Correo Electrónico</label>
                        <p class="form-control-static fw-bold fs-5">{{ $user->email ?? 'N/A' }}</p>
                    </div>

                    {{-- Rol (Estático) --}}
                    <div class="mb-0">
                        <label class="form-label text-muted small">Rol del Sistema</label>
                        <p class="form-control-static fw-bold text-info fs-5">{{ ucfirst($user->role ?? 'N/A') }}</p>
                    </div>
                </div>
                
                {{-- 2. BOTONES DE ACCIÓN --}}
                <div class="d-grid gap-3">
                    
                    {{-- Botón de Configuración de Perfil (Para campos editables o contraseña) --}}
                    <a href="{{ route('perfil.update') }}" class="btn btn-warning btn-lg text-white py-3 fw-bold">
                        <i class="bi bi-gear me-2"></i> Configuración de Perfil
                    </a>
                    
                    {{-- Botón de Cerrar Sesión --}}
                    {{-- DEBE SER UN FORMULARIO POST POR SEGURIDAD --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-lg w-100 py-3 fw-bold">
                            <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                        </button>
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