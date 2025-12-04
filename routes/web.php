<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; 
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\AvanceController;
use App\Http\Controllers\ProgresoController;
use App\Http\Controllers\ConstanciaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminEquipoController;
use App\Http\Controllers\AdminProyectoController;
use App\Http\Controllers\JuezProyectoController;

// ----------------------------------------------------
// 1. AUTENTICACIÓN (Rutas sin protección de sesión)
// ----------------------------------------------------

Route::get('/', function () {
    // Redirige la raíz al login o a eventos si está logueado
    return Auth::check() ? redirect()->route('eventos.index') : redirect()->route('login');
});

// LOGIN
Route::get('/login', function () {
    return view('Login');
})->name('login');
Route::post('/login/auth', [LoginController::class, 'authenticate'])->name('login.auth');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout'); 

// REGISTRO
Route::get('/register', function () {
    return view('Registrar');
})->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.create');

// Recuperación de Contraseña (vista temporal)
Route::get('/forgot-password', function () {
    return view('welcome');
})->name('password.request');

// ----------------------------------------------------
// 2. RUTAS DE APLICACIÓN (Generalmente requieren Auth middleware)
// ----------------------------------------------------

Route::middleware(['auth'])->group(function () {

    // DASHBOARD (Suele ser una redirección o el índice de eventos)
    Route::get('/dashboard', function () {
        return redirect()->route('eventos.index');
    })->name('dashboard');

    // EVENTOS (Visible para todos)
    Route::get('/eventos', [EventoController::class, 'index'])->name('eventos.index');

    // PERFIL (Visible para todos)
    Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil.index');
    Route::post('/perfil', [PerfilController::class, 'update'])->name('perfil.update');
    Route::get('/perfil/configuracion', function () {
        return view('ConfiguracionPerfil');
    })->name('perfil.configuracion');
});

// RUTAS SOLO PARA PARTICIPANTES
Route::middleware(['auth', 'participante'])->group(function () {

    // EQUIPOS
    Route::get('/equipos', [EquipoController::class, 'index'])->name('equipos.index');
    Route::get('/equipos/registrar', [EquipoController::class, 'create'])->name('equipos.registrar');
    Route::post('/equipos/store', [EquipoController::class, 'store'])->name('equipos.store');
    Route::get('/equipos/{equipo}', [EquipoController::class, 'show'])->name('equipos.show');
    Route::post('/equipos/{equipo}/invite', [EquipoController::class, 'invite'])->name('equipos.invite');
    Route::delete('/equipos/{equipo}/members/{participante}', [EquipoController::class, 'removeMember'])->name('equipos.removeMember');
    Route::put('/equipos/{equipo}/members/{participante}/role', [EquipoController::class, 'updateMemberRole'])->name('equipos.updateMemberRole');

    // PROYECTOS (Las rutas específicas deben ir antes de las parametrizadas)
    Route::get('/proyectos/registrar', [ProyectoController::class, 'create'])->name('proyectos.registrar');
    Route::post('/proyectos/store', [ProyectoController::class, 'store'])->name('proyectos.store');

    // PROGRESO
    Route::get('/progreso', [ProgresoController::class, 'index'])->name('progreso.index');

    // AVANCES
    Route::post('/avances/store', [AvanceController::class, 'store'])->name('avances.store');

    // CONSTANCIAS (Rutas del Participante)
    // 1. Vista principal de constancias (donde se ven la lista)
    Route::get('/constancia', [ConstanciaController::class, 'index'])->name('constancia.index');

    // 2. Ruta para descargar el archivo PDF (utiliza el modelo Constancia)
    Route::get('/constancias/{constancia}/descargar', [ConstanciaController::class, 'downloadCertificate'])->name('constancias.descargar');

    Route::get('/constancias/{id}/generar', [ConstanciaController::class, 'generarPDF'])->name('constancias.generar');
});

// RUTAS SOLO PARA JUECES
Route::middleware(['auth', 'juez'])->group(function () {
    // PROYECTOS ASIGNADOS PARA CALIFICAR
    Route::get('/juez/proyectos', [JuezProyectoController::class, 'index'])->name('juez.proyectos.index');
    Route::get('/juez/proyectos/{proyecto}', [JuezProyectoController::class, 'show'])->name('juez.proyectos.show');
    Route::post('/juez/proyectos/{proyecto}/calificar', [JuezProyectoController::class, 'guardarCalificacion'])->name('juez.proyectos.calificar');
    Route::get('/juez/mis-calificaciones', [JuezProyectoController::class, 'misCalificaciones'])->name('juez.mis-calificaciones');

    // EVALUACIÓN (JUECES) - LEGACY
    Route::get('/evaluacion', function () {
        return redirect()->route('juez.proyectos.index');
    })->name('proyectos.evaluacion');

    Route::get('/seguimiento-proyectos', function () {
        return redirect()->route('juez.mis-calificaciones');
    })->name('proyectos.seguimiento');

    // CONSTANCIAS
    Route::get('/juez/constancias', [ConstanciaController::class, 'indexJuez'])
        ->name('constancia.juez.index');

    Route::get('/juez/constancias/{id}/generar', [ConstanciaController::class, 'generarPDFJuez'])
        ->name('constancia.juez.generar');
});

// ----------------------------------------------------
// 3. RUTAS DE ADMINISTRACIÓN (Requieren el middleware 'admin')
// ----------------------------------------------------

Route::middleware(['auth', 'admin'])->group(function () {
    // Crear Evento (solo Admins) - DEBE IR ANTES DE /eventos/{evento}
    Route::get('/eventos/crear', [EventoController::class, 'create'])->name('eventos.crear');
    Route::post('/eventos', [EventoController::class, 'store'])->name('eventos.store');

    // Eliminar Evento (solo Admins)
    Route::delete('/eventos/{evento}', [EventoController::class, 'destroy'])->name('eventos.destroy');

    // GESTIÓN DE USUARIOS (ADMIN)
    Route::get('/admin/usuarios', [UserController::class, 'index'])->name('admin.usuarios.index');
    Route::get('/admin/usuarios/crear', [UserController::class, 'create'])->name('admin.usuarios.create');
    Route::post('/admin/usuarios', [UserController::class, 'store'])->name('admin.usuarios.store');
    Route::get('/admin/usuarios/{usuario}', [UserController::class, 'show'])->name('admin.usuarios.show');
    Route::get('/admin/usuarios/{usuario}/editar', [UserController::class, 'edit'])->name('admin.usuarios.edit');
    Route::put('/admin/usuarios/{usuario}', [UserController::class, 'update'])->name('admin.usuarios.update');
    Route::delete('/admin/usuarios/{usuario}', [UserController::class, 'destroy'])->name('admin.usuarios.destroy');

    // GESTIÓN DE EQUIPOS (ADMIN)
    Route::get('/admin/equipos', [AdminEquipoController::class, 'index'])->name('admin.equipos.index');
    Route::get('/admin/equipos/crear', [AdminEquipoController::class, 'create'])->name('admin.equipos.create');
    Route::post('/admin/equipos', [AdminEquipoController::class, 'store'])->name('admin.equipos.store');
    Route::get('/admin/equipos/{equipo}', [AdminEquipoController::class, 'show'])->name('admin.equipos.show');
    Route::get('/admin/equipos/{equipo}/editar', [AdminEquipoController::class, 'edit'])->name('admin.equipos.edit');
    Route::put('/admin/equipos/{equipo}', [AdminEquipoController::class, 'update'])->name('admin.equipos.update');
    Route::delete('/admin/equipos/{equipo}', [AdminEquipoController::class, 'destroy'])->name('admin.equipos.destroy');
    Route::delete('/admin/equipos/{equipo}/participantes/{participanteId}', [AdminEquipoController::class, 'removeParticipant'])->name('admin.equipos.removeParticipant');

    // GESTIÓN DE PROYECTOS Y ASIGNACIÓN A JUECES (ADMIN)
    Route::get('/admin/proyectos', [AdminProyectoController::class, 'index'])->name('admin.proyectos.index');
    Route::get('/admin/proyectos/{proyecto}/asignar-jueces', [AdminProyectoController::class, 'asignarJueces'])->name('admin.proyectos.asignar-jueces');
    Route::post('/admin/proyectos/{proyecto}/asignacion', [AdminProyectoController::class, 'guardarAsignacion'])->name('admin.proyectos.guardar-asignacion');
    Route::delete('/admin/proyectos/{proyecto}/jueces/{juez}', [AdminProyectoController::class, 'eliminarAsignacion'])->name('admin.proyectos.eliminar-asignacion');
    Route::get('/admin/rankings', [AdminProyectoController::class, 'rankings'])->name('admin.rankings');

    // CONSTANCIAS (Rutas del Administrador)
    // 1. Vista de Gestión (Admin ve la lista de participantes de un evento finalizado)
    Route::get('/admin/eventos/{evento}/constancia', [ConstanciaController::class, 'manageCertificates'])->name('constancia.gestion');

    // 2. Generar y Guardar Constancia PDF (Recibe participante y evento IDs)
    Route::post('/admin/participante/{participante}/evento/{evento}/generar-constancia', [ConstanciaController::class, 'generateCertificate'])->name('constancia.generar');
});

// RUTAS DE EVENTOS Y PROYECTOS (Detalles - visible para todos autenticados)
Route::middleware(['auth'])->group(function () {
    // Proyectos
    Route::get('/proyectos/{id}', [ProyectoController::class, 'show'])->name('proyectos.show');

    // Eventos
    Route::get('/eventos/{evento}', [EventoController::class, 'show'])->name('eventos.show');
    Route::post('/eventos/{evento}/join', [EventoController::class, 'join'])->name('eventos.join');
    Route::post('/eventos/{evento}/leave', [EventoController::class, 'leave'])->name('eventos.leave');
});