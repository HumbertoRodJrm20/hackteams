@extends('Layout.app')

@section('content')
<div class="container mt-4">

    <div class="card shadow-sm p-4" style="border-radius: 12px; max-width: 700px; margin: auto;">
        
        <h2 class="mb-2">Solicitar nueva constancia</h2>
        <p class="text-muted">Completa la información para solicitar tu constancia. Un administrador revisará tu solicitud.</p>

        {{-- Errores --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('solicitudes.store') }}" method="POST" enctype="multipart/form-data" class="mt-3">
            @csrf

            {{-- Evento --}}
            <div class="mb-3">
                <label for="evento_id" class="form-label">Evento en el que participaste:</label>
                <select name="evento_id" id="evento_id" class="form-select" required>
                    <option value="">Seleccione un evento...</option>
                    @foreach($eventos as $evento)
                        <option value="{{ $evento->id }}">{{ $evento->nombre }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Rol --}}
            <div class="mb-3">
                <label for="rol" class="form-label">Rol desempeñado:</label>
                <select name="rol" id="rol" class="form-select" required>
                    <option value="">Seleccione...</option>
                    <option value="Participante">Participante</option>
                    <option value="Ponente">Ponente</option>
                    <option value="Asistente">Asistente</option>
                    <option value="Tallerista">Tallerista</option>
                    <option value="Otro">Otro</option>
                </select>
            </div>

            {{-- Fecha --}}
            <div class="mb-3">
                <label for="fecha_evento" class="form-label">Fecha del evento:</label>
                <input type="date" name="fecha_evento" id="fecha_evento" class="form-control" required>
            </div>

            {{-- Tipo --}}
            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo de constancia:</label>
                <select name="tipo" id="tipo" class="form-select" required>
                    <option value="">Seleccione...</option>
                    <option value="Participación">Participación</option>
                    <option value="Asistencia">Asistencia</option>
                    <option value="Reconocimiento">Reconocimiento</option>
                    <option value="Ponencia">Ponencia</option>
                    <option value="Taller">Taller</option>
                </select>
            </div>

            {{-- Motivo --}}
            <div class="mb-3">
                <label for="motivo" class="form-label">¿Para qué solicitas esta constancia?</label>
                <textarea name="motivo" id="motivo" class="form-control" rows="2"
                          placeholder="Ejemplo: Presentar en servicio social, créditos complementarios, etc."></textarea>
            </div>

            {{-- Comentarios --}}
            <div class="mb-3">
                <label for="comentario" class="form-label">Comentarios adicionales (opcional):</label>
                <textarea name="comentario" id="comentario" class="form-control" rows="2"
                          placeholder="Información extra relevante"></textarea>
            </div>

            {{-- Evidencia --}}
            <div class="mb-4">
                <label for="evidencia" class="form-label">Subir evidencia (opcional):</label>

                <div class="input-group">
                    <label class="input-group-text" for="evidencia">
                        📎
                    </label>
                    <input type="file" name="evidencia" id="evidencia" class="form-control"
                        accept=".pdf,.jpg,.png,.jpeg">
                </div>

                <small class="text-muted">Formatos permitidos: PDF, JPG, PNG. Tamaño máx: 2MB</small>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2" style="font-size: 16px;">
                Enviar solicitud
            </button>
        </form>

    </div>
</div>
@endsection
