@extends('Layout.app')

@section('nav_equipos', 'active')
@section('title', 'Detalles: ' . $equipo->nombre)

@section('content')
<div class="container py-4">
    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <i class="bi bi-people fs-1 me-3 text-primary"></i>
            <div>
                <h1 class="fw-bold mb-0">{{ $equipo->nombre }}</h1>
                @if($isLeader)
                    <small class="text-success"><i class="bi bi-crown me-1"></i>Eres el líder</small>
                @endif
            </div>
        </div>
        <a href="{{ route('equipos.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Volver
        </a>
    </div>

    {{-- Mensajes --}}
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

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error:</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        {{-- Información del Equipo --}}
        <div class="col-lg-8">
            {{-- Tarjeta de Información --}}
            <div class="card shadow-sm p-4 mb-4">
                <h4 class="mb-4 text-primary">Información del Equipo</h4>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Número de Miembros</label>
                        <p class="fs-5 fw-bold">
                            <i class="bi bi-people-fill me-2"></i>{{ count($miembros) }} miembro(s)
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Proyecto</label>
                        <p class="fs-5 fw-bold">
                            @if($proyecto)
                                <a href="{{ route('proyectos.show', $proyecto->id) }}">{{ $proyecto->titulo }}</a>
                            @else
                                <span class="text-muted">Sin proyecto asignado</span>
                            @endif
                        </p>
                    </div>
                </div>

                <hr>

                <div>
                    <label class="form-label text-muted small">Descripción</label>
                    <p class="fs-5">{{ $equipo->nombre }} es un equipo dedicado a la innovación y desarrollo.</p>
                </div>
            </div>

            {{-- Miembros del Equipo --}}
            <div class="card shadow-sm p-4">
                <h4 class="mb-4 text-primary">
                    <i class="bi bi-people-fill me-2"></i>Miembros del Equipo
                </h4>

                @if($miembros->isEmpty())
                    <p class="text-muted">El equipo no tiene miembros aún.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Rol</th>
                                    @if($isLeader)
                                        <th>Acciones</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($miembros as $miembro)
                                    <tr>
                                        <td>
                                            <strong>{{ $miembro['nombre'] }}</strong>
                                            @if($miembro['es_lider'])
                                                <span class="badge bg-success ms-2">
                                                    <i class="bi bi-crown me-1"></i>Líder
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ $miembro['email'] }}</td>
                                        <td>
                                            @if($miembro['perfil'])
                                                <span class="badge bg-info">{{ $miembro['perfil'] }}</span>
                                            @else
                                                <span class="text-muted small">Sin rol</span>
                                            @endif
                                        </td>
                                        @if($isLeader && !$miembro['es_lider'])
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                        onclick="removeMember({{ $miembro['id'] }}, '{{ $miembro['nombre'] }}')">
                                                    <i class="bi bi-trash me-1"></i>Remover
                                                </button>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- Panel Lateral - Acciones del Líder --}}
        <div class="col-lg-4">
            @if($isLeader)
                <div class="card shadow-sm p-4 mb-4">
                    <h5 class="mb-4 text-primary">
                        <i class="bi bi-tools me-2"></i>Panel del Líder
                    </h5>

                    {{-- Invitar Miembro --}}
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Invitar Miembro</h6>
                        <form action="{{ route('equipos.invite', $equipo->id) }}" method="POST">
                            @csrf
                            <div class="input-group">
                                <input type="email" name="email" class="form-control form-control-sm"
                                       placeholder="ejemplo@correo.com" required>
                                <button class="btn btn-primary btn-sm" type="submit">
                                    <i class="bi bi-plus-circle me-1"></i>Invitar
                                </button>
                            </div>
                            <small class="text-muted d-block mt-2">
                                Ingresa el email del participante que deseas invitar
                            </small>
                        </form>
                    </div>

                    <hr>

                    {{-- Información Útil --}}
                    <div>
                        <h6 class="fw-bold mb-3">Información Útil</h6>
                        <div class="alert alert-info small mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            Como líder del equipo, puedes:
                            <ul class="mb-0 mt-2 ms-3">
                                <li>Invitar nuevos miembros</li>
                                <li>Remover miembros</li>
                                <li>Gestionar roles</li>
                                <li>Registrar proyectos</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Gestión General --}}
                <div class="card shadow-sm p-4">
                    <h5 class="mb-3 text-primary">
                        <i class="bi bi-gear me-2"></i>Gestión
                    </h5>

                    <div class="d-grid gap-2">
                        <a href="{{ route('proyectos.registrar') }}" class="btn btn-success btn-sm">
                            <i class="bi bi-plus-circle me-1"></i>Registrar Proyecto
                        </a>
                    </div>
                </div>
            @else
                <div class="card shadow-sm p-4">
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Eres miembro</strong><br>
                        <small>Solo el líder del equipo puede administrar miembros.</small>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Modal para confirmar eliminación --}}
<div class="modal fade" id="removeModal" tabindex="-1" aria-labelledby="removeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="removeModalLabel">Remover Miembro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas remover a <strong id="memberName"></strong> del equipo?
                Esta acción no se puede deshacer.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="removeForm" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Remover</button>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
function removeMember(memberId, memberName) {
    document.getElementById('memberName').textContent = memberName;
    document.getElementById('removeForm').action = `/equipos/{{ $equipo->id }}/members/${memberId}`;

    const modal = new bootstrap.Modal(document.getElementById('removeModal'));
    modal.show();
}
</script>
@endsection

@endsection
