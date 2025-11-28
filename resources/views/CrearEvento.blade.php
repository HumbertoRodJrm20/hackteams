@extends('Layout.app') 

@section('nav_eventos', 'active') 
@section('title', 'Crear Nuevo Evento')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <div class="d-flex align-items-center mb-4">
                <i class="bi bi-calendar-plus fs-1 me-3 text-info"></i>
                <h1 class="fw-bold mb-0">Crear Nuevo Evento</h1>
            </div>

            <div class="card shadow-sm p-4">
                
                <form method="POST" action="{{ route('eventos.store') }}" enctype="multipart/form-data">
                    @csrf 

                    <h4 class="mb-3 text-primary">Información Básica</h4>
                    
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Evento</label>
                        <input type="text" class="form-control form-control-lg" id="nombre" name="nombre" required value="{{ old('nombre') }}" placeholder="Ej: Hackatec 2026 o Innovatec Challenge">
                    </div>

                    <div class="mb-4">
                        <label for="descripcion" class="form-label">Descripción Detallada</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required placeholder="Describe el propósito, las reglas y los participantes objetivo.">{{ old('descripcion') }}</textarea>
                    </div>

                    <h4 class="mb-3 text-primary">Fechas y Estado</h4>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required value="{{ old('fecha_inicio') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="fecha_fin" class="form-label">Fecha de Finalización</label>
                            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required value="{{ old('fecha_fin') }}">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="estado" class="form-label">Estado Inicial</label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="proximo">Próximo</option>
                            <option value="activo">Activo</option>
                            <option value="finalizado">Finalizado</option>
                        </select>
                        <small class="form-text text-muted">Define si el evento está en planeación, abierto a registro, o si ya terminó.</small>
                    </div>
                    
                    <h4 class="mb-3 text-primary">Imagen Promocional</h4>

                    <div class="mb-4">
                        <label for="imagen" class="form-label">Subir Imagen Promocional (Banner)</label>
                        <input class="form-control" type="file" id="imagen" name="imagen" accept="image/*">
                        <small class="form-text text-muted">Sube una imagen atractiva para el evento (JPG, PNG).</small>
                    </div>

                    <hr class="my-4">

                    <div class="d-grid">
                        <button type="submit" class="btn btn-info btn-lg py-3 text-white fw-bold">
                            <i class="bi bi-calendar-plus me-2"></i>Guardar Evento
                        </button>
                    </div>

                </form>
            </div>
            
        </div>
    </div>
</div>
@endsection