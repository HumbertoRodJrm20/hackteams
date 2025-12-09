@extends('Layout.app')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <i class="bi bi-people-fill fs-1 me-3 text-primary"></i>
            <h1 class="fw-bold mb-0">Gestión de Usuarios</h1>
        </div>
        <a href="{{ route('admin.usuarios.create') }}" class="btn btn-success btn-lg">
            <i class="bi bi-plus-circle me-2"></i>Crear Usuario
        </a>
    </div>

    {{-- Mensajes de éxito --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Formulario de Búsqueda y Filtros --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.usuarios.index') }}" class="row g-3">
                <div class="col-md-6">
                    <label for="search" class="form-label">
                        <i class="bi bi-search me-1"></i>Buscar usuario
                    </label>
                    <input type="text" class="form-control" id="search" name="search"
                           placeholder="Nombre o email..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <label for="rol_id" class="form-label">
                        <i class="bi bi-person-badge me-1"></i>Rol
                    </label>
                    <select class="form-select" id="rol_id" name="rol_id">
                        <option value="">Todos los roles</option>
                        @foreach($roles as $rol)
                            <option value="{{ $rol->id }}" {{ request('rol_id') == $rol->id ? 'selected' : '' }}>
                                {{ $rol->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                    @if(request('search') || request('rol_id'))
                        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla de Usuarios --}}
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">
                <i class="bi bi-list-ul me-2"></i>Usuarios Registrados
            </h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Creado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $usuario)
                        <tr>
                            <td>
                                <strong>{{ $usuario->name }}</strong>
                            </td>
                            <td>{{ $usuario->email }}</td>
                            <td>
                                @php
                                    $rol = $usuario->roles()->first();
                                    $badgeClass = match($rol?->nombre ?? 'Sin rol') {
                                        'Admin' => 'bg-danger',
                                        'Participante' => 'bg-info',
                                        'Juez' => 'bg-warning text-dark',
                                        default => 'bg-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">
                                    {{ $rol?->nombre ?? 'Sin rol' }}
                                </span>
                            </td>
                            <td>{{ $usuario->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.usuarios.show', $usuario->id) }}" class="btn btn-sm btn-outline-primary" title="Ver">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.usuarios.edit', $usuario->id) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.usuarios.destroy', $usuario->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="return confirm('¿Deseas eliminar este usuario? Esta acción es irreversible.');">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                No hay usuarios registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($usuarios->hasPages())
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        @if ($usuarios->onFirstPage())
                            <span class="btn btn-outline-secondary disabled">
                                <i class="bi bi-chevron-left me-1"></i>Anterior
                            </span>
                        @else
                            <a href="{{ $usuarios->previousPageUrl() }}" class="btn btn-outline-primary">
                                <i class="bi bi-chevron-left me-1"></i>Anterior
                            </a>
                        @endif
                    </div>
                    <div>
                        @if ($usuarios->hasMorePages())
                            <a href="{{ $usuarios->nextPageUrl() }}" class="btn btn-outline-primary">
                                Siguiente<i class="bi bi-chevron-right ms-1"></i>
                            </a>
                        @else
                            <span class="btn btn-outline-secondary disabled">
                                Siguiente<i class="bi bi-chevron-right ms-1"></i>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
