@extends('Layout.app')

@section('title', 'Gestión de Equipos')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <i class="bi bi-people fs-1 me-3 text-info"></i>
            <h1 class="fw-bold mb-0">Gestión de Equipos</h1>
        </div>
        <a href="{{ route('admin.equipos.create') }}" class="btn btn-success btn-lg">
            <i class="bi bi-plus-circle me-2"></i>Crear Equipo
        </a>
    </div>

    {{-- Mensajes de éxito --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tabla de Equipos --}}
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nombre del Equipo</th>
                        <th>Evento</th>
                        <th>Miembros</th>
                        <th>Proyectos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($equipos as $equipo)
                        <tr>
                            <td>
                                <strong>{{ $equipo->nombre }}</strong>
                            </td>
                            <td>{{ $equipo->evento?->nombre ?? 'Sin evento' }}</td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $equipo->participantes->count() }} miembro(s)
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ $equipo->proyectos->count() }} proyecto(s)
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.equipos.show', $equipo->id) }}" class="btn btn-sm btn-outline-primary" title="Ver">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.equipos.edit', $equipo->id) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.equipos.destroy', $equipo->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="return confirm('¿Deseas eliminar este equipo? Esta acción es irreversible.');">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                No hay equipos registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Paginación --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $equipos->links() }}
    </div>
</div>
@endsection
