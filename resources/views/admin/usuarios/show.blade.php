@extends('Layout.app')

@section('title', 'Detalle del Usuario: ' . $usuario->nombre)

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="d-flex align-items-center mb-4">
                <i class="bi bi-person-circle fs-1 me-3 text-info"></i>
                <h1 class="fw-bold mb-0">{{ $usuario->nombre }}</h1>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4 fw-bold">Nombre:</dt>
                        <dd class="col-sm-8">{{ $usuario->nombre }}</dd>

                        <dt class="col-sm-4 fw-bold">Email:</dt>
                        <dd class="col-sm-8">
                            <a href="mailto:{{ $usuario->email }}">{{ $usuario->email }}</a>
                        </dd>

                        <dt class="col-sm-4 fw-bold">Rol:</dt>
                        <dd class="col-sm-8">
                            @php
                                $rol = $usuario->roles()->first();
                                $badgeClass = match($rol?->nombre ?? 'Sin rol') {
                                    'Admin' => 'bg-danger',
                                    'Participante' => 'bg-info',
                                    'Juez' => 'bg-warning text-dark',
                                    default => 'bg-secondary',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }} fs-6">
                                {{ $rol?->nombre ?? 'Sin rol' }}
                            </span>
                        </dd>
                    </dl>
                </div>
            </div>

            <div class="d-grid gap-2 mt-4">
                <a href="{{ route('admin.usuarios.edit', $usuario->id) }}" class="btn btn-warning btn-lg text-white">
                    <i class="bi bi-pencil-square me-2"></i>Editar Usuario
                </a>
                <form action="{{ route('admin.usuarios.destroy', $usuario->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('¿Deseas eliminar este usuario? Esta acción es irreversible.');">
                        <i class="bi bi-trash me-2"></i>Eliminar Usuario
                    </button>
                </form>
                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Volver a la Lista
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
