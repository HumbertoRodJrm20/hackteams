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
                                            @if($isLeader && !$miembro['es_lider'])
                                                <form action="{{ route('equipos.updateMemberRole', [$equipo->id, $miembro['id']]) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <select name="perfil_id" class="form-select form-select-sm" onchange="this.form.submit()" style="width: auto; display: inline-block;">
                                                        <option value="">Sin rol</option>
                                                        @foreach($perfiles as $perfil)
                                                            <option value="{{ $perfil->id }}" {{ $miembro['perfil_id'] == $perfil->id ? 'selected' : '' }}>
                                                                {{ $perfil->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </form>
                                            @else
                                                @if($miembro['perfil_nombre'])
                                                    <span class="badge bg-info">{{ $miembro['perfil_nombre'] }}</span>
                                                @else
                                                    <span class="text-muted small">Sin rol</span>
                                                @endif
                                            @endif
                                        </td>
                                        @if($isLeader && !$miembro['es_lider'])
                                            <td>
                                                @if(!$eventoIniciado)
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                            onclick="removeMember({{ $miembro['id'] }}, '{{ $miembro['nombre'] }}')">
                                                        <i class="bi bi-trash me-1"></i>Remover
                                                    </button>
                                                @else
                                                    <span class="text-muted small"><i class="bi bi-lock me-1"></i>Bloqueado</span>
                                                @endif
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
                    @if(!$eventoIniciado)
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
                    @else
                        <div class="mb-4">
                            <div class="alert alert-warning small mb-0">
                                <i class="bi bi-lock me-2"></i>
                                No puedes invitar miembros después de que el evento haya iniciado.
                            </div>
                        </div>
                    @endif

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
                <div class="card shadow-sm p-4 mb-4">
                    <h5 class="mb-3 text-primary">
                        <i class="bi bi-gear me-2"></i>Gestión
                    </h5>

                    <div class="d-grid gap-2">
                        <a href="{{ route('proyectos.registrar') }}" class="btn btn-success btn-sm">
                            <i class="bi bi-plus-circle me-1"></i>Registrar Proyecto
                        </a>
                    </div>
                </div>

                {{-- Zona de Peligro --}}
                @if(!$eventoIniciado)
                    <div class="card shadow-sm p-4 border-danger">
                        <h5 class="mb-3 text-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>Zona de Peligro
                        </h5>

                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDeleteTeam()">
                                <i class="bi bi-trash me-1"></i>Eliminar Equipo
                            </button>
                        </div>
                        <small class="text-muted d-block mt-2">
                            Esta acción eliminará permanentemente el equipo y todos sus datos.
                        </small>
                    </div>
                @else
                    <div class="card shadow-sm p-4 border-warning">
                        <div class="alert alert-warning mb-0">
                            <i class="bi bi-lock me-2"></i>
                            <strong>Evento iniciado</strong><br>
                            <small>No puedes eliminar el equipo una vez que el evento ha iniciado.</small>
                        </div>
                    </div>
                @endif
            @else
                <div class="card shadow-sm p-4 mb-4">
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Eres miembro</strong><br>
                        <small>Solo el líder del equipo puede administrar miembros.</small>
                    </div>
                </div>

                {{-- Opción para salir del equipo --}}
                @if(!$eventoIniciado)
                    <div class="card shadow-sm p-4">
                        <h5 class="mb-3 text-primary">
                            <i class="bi bi-box-arrow-right me-2"></i>Acciones
                        </h5>

                        <div class="d-grid">
                            <button type="button" class="btn btn-outline-warning btn-sm" onclick="confirmLeaveTeam()">
                                <i class="bi bi-box-arrow-right me-1"></i>Salir del Equipo
                            </button>
                        </div>
                        <small class="text-muted d-block mt-2">
                            Al salir, dejarás de ser miembro de este equipo.
                        </small>
                    </div>
                @else
                    <div class="card shadow-sm p-4">
                        <div class="alert alert-warning mb-0">
                            <i class="bi bi-lock me-2"></i>
                            <strong>Evento iniciado</strong><br>
                            <small>No puedes salir del equipo una vez que el evento ha iniciado.</small>
                        </div>
                    </div>
                @endif
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

{{-- Modal para confirmar salida del equipo --}}
<div class="modal fade" id="leaveModal" tabindex="-1" aria-labelledby="leaveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="leaveModalLabel">Salir del Equipo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas salir del equipo <strong>{{ $equipo->nombre }}</strong>?
                Esta acción no se puede deshacer.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('equipos.leave', $equipo->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-warning">Salir del Equipo</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal para confirmar eliminación del equipo --}}
<div class="modal fade" id="deleteTeamModal" tabindex="-1" aria-labelledby="deleteTeamModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteTeamModalLabel">Eliminar Equipo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>¡Advertencia!</strong> Esta acción es irreversible.
                </div>
                <p>¿Estás seguro de que deseas eliminar permanentemente el equipo <strong>{{ $equipo->nombre }}</strong>?</p>
                <p class="mb-0">Se eliminarán:</p>
                <ul>
                    <li>Todos los miembros del equipo</li>
                    <li>Proyectos asociados</li>
                    <li>Toda la información relacionada</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('equipos.destroy', $equipo->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar Equipo</button>
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

function confirmLeaveTeam() {
    const modal = new bootstrap.Modal(document.getElementById('leaveModal'));
    modal.show();
}

function confirmDeleteTeam() {
    const modal = new bootstrap.Modal(document.getElementById('deleteTeamModal'));
    modal.show();
}
</script>
@endsection

@endsection
