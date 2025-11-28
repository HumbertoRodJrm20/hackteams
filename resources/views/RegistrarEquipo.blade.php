@extends('Layout.app')

@section('nav_equipos', 'active')
@section('title', 'Registrar Nuevo Equipo')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <div class="d-flex align-items-center mb-4">
                <i class="bi bi-people fs-1 me-3 text-primary"></i>
                <h1 class="fw-bold mb-0">Registrar Equipo</h1>
            </div>

            <form method="POST" >
                @csrf

                <div class="card shadow-sm p-4 mb-4">
                    <h5 class="text-primary mb-3">Información General</h5>

                    <div class="mb-3">
                        <label for="nombre_equipo" class="form-label">Nombre del Equipo</label>
                        <input type="text" class="form-control form-control-lg" id="nombre_equipo" name="nombre_equipo" required placeholder="Nombre creativo para tu equipo">
                    </div>

                    <div class="mb-3">
                        <label for="evento" class="form-label">Evento</label>
                        
                        <select class="form-select form-control-lg" id="evento" name="evento_id" required>
                            <option value="">Selecciona el evento...</option>
                            <option value="1">Hackatec (Activo)</option>
                            <option value="2">Innovatec Challenge (Próximo)</option>
                        </select>
                    </div>
                </div>

                <div class="card shadow-sm p-4 mb-4">
                    <h5 class="text-primary mb-3">Asignación de Roles y Miembros</h5>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Programador Frontend</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="Nierika Galindo Sanchez" disabled> 
                            <button type="button" class="btn btn-outline-danger"><i class="bi bi-x-circle"></i></button>
                            <button type="button" class="btn btn-info text-white">Solicitar</button>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Programador Backend</label>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Buscar usuario para Backend...">
                            <button type="button" class="btn btn-outline-secondary"><i class="bi bi-plus-circle"></i></button>
                            <button type="button" class="btn btn-info text-white">Solicitar</button>
                        </div>
                    </div>
                    
                    <div class="mb-4 p-3 border rounded">
                        <label class="form-label fw-bold">Diseñador</label>
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" placeholder="Buscar/Invitar usuario...">
                        </div>
                        
                        <div class="d-flex align-items-center gap-3">

                            <div class="input-group input-group-sm w-50">
                                <span class="input-group-text">Fecha Nac.</span>
                                <input type="text" class="form-control" placeholder="DD/MM/AAAA" disabled>
                            </div>
                            
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="enviar_correo_diseno">
                                <label class="form-check-label small" for="enviar_correo_diseno">Enviar correo de invitación</label>
                            </div>
                            <button type="button" class="btn btn-success btn-sm ms-auto">Aceptar</button>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Analista de Datos</label>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Buscar usuario...">
                            <button type="button" class="btn btn-outline-secondary"><i class="bi bi-plus-circle"></i></button>
                            <button type="button" class="btn btn-info text-white">Solicitar</button>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Analista de Negocios</label>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Buscar usuario...">
                            <button type="button" class="btn btn-outline-secondary"><i class="bi bi-plus-circle"></i></button>
                            <button type="button" class="btn btn-info text-white">Solicitar</button>
                        </div>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-success btn-lg py-3">
                        <i class="bi bi-people-fill me-2"></i>Crear equipo
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection