@extends('Layout.app')

@section('content')
@section('nav_solicitudes_admin', 'active')

<div class="container mt-4">

    <h2 class="mb-4">Solicitudes de Constancias</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Participante</th>
                <th>Evento</th>
                <th>Rol</th>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Evidencia</th>
                <th>Estatus</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($solicitudes as $s)
            <tr>
                <td>{{ $s->id }}</td>
                <td>{{ $s->participante->name }}</td>
                <td>{{ $s->evento->nombre }}</td>
                <td>{{ $s->rol }}</td>
                <td>{{ $s->fecha_evento }}</td>
                <td>{{ $s->tipo }}</td>

                <td>
                    @if ($s->evidencia_path)
                        <a href="{{ asset('storage/' . $s->evidencia_path) }}" target="_blank" class="btn btn-sm btn-primary">
                            Ver archivo
                        </a>
                    @else
                        No subió
                    @endif
                </td>

                <td>
                    <span class="badge 
                        @if($s->estatus == 'Pendiente') bg-warning 
                        @elseif($s->estatus == 'Aprobado') bg-success 
                        @else bg-danger @endif">
                        {{ $s->estatus }}
                    </span>
                </td>

                <td class="text-center">
                    @if ($s->estatus == 'Pendiente')
                        <form action="{{ route('admin.solicitudes.aprobar', $s->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-success btn-sm">Aprobar</button>
                        </form>

                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rechazoModal{{ $s->id }}">
                            Rechazar
                        </button>
                    @else
                        <em>No disponible</em>
                    @endif
                </td>
            </tr>

            <!-- MODAL DE RECHAZO -->
            <div class="modal fade" id="rechazoModal{{ $s->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('admin.solicitudes.rechazar', $s->id) }}">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">Rechazar Solicitud</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <label>Motivo del rechazo:</label>
                                <textarea name="comentario_rechazo" class="form-control" required></textarea>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-danger">Rechazar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @endforeach
        </tbody>
    </table>

</div>
@endsection
