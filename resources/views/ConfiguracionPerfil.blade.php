@extends('Layout.app')

@section('nav_perfil', 'active')
@section('title', 'Configuración de Perfil')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">

            <div class="d-flex align-items-center mb-4">
                <i class="bi bi-gear fs-1 me-3 text-warning"></i>
                <h1 class="fw-bold mb-0">Configuración de Perfil</h1>
            </div>

            {{-- Mensajes de Éxito/Error --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error al actualizar:</strong>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm p-4">
                <form method="POST" action="{{ route('perfil.update') }}">
                    @csrf

                    <h4 class="mb-3 text-primary">Información Personal</h4>

                    {{-- Nombre Completo --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre Completo</label>
                        <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Correo Electrónico --}}
                    <div class="mb-4">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">

                    <div class="d-grid gap-3">
                        <button type="submit" class="btn btn-warning btn-lg py-3 text-white fw-bold">
                            <i class="bi bi-check-circle me-2"></i>Guardar Cambios
                        </button>

                        <a href="{{ route('perfil.index') }}" class="btn btn-outline-secondary btn-lg py-3 fw-bold">
                            <i class="bi bi-arrow-left me-2"></i>Volver al Perfil
                        </a>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
@endsection
