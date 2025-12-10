<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\AdminEquipoController;
use App\Http\Controllers\AdminProyectoController;
use App\Http\Controllers\AvanceController;
use App\Http\Controllers\ConstanciaController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\JuezProyectoController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ProgresoController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ----------------------------------------------------

Route::get('/', function () {
    if (! Auth::check()) {
        return redirect()->route('login');
    }

    $user = Auth::user();
    if ($user->hasRole('Admin')) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('eventos.index');
});

// LOGIN
Route::get('/login', function () {
    return view('Login');
})->name('login');
Route::post('/login/auth', [LoginController::class, 'authenticate'])->name('login.auth');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// REGISTRO
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.create');

// VERIFICACIÓN DE EMAIL
Route::get('/verify-email', [VerificationController::class, 'show'])->name('verification.show');
Route::post('/verify-email', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('/verify-email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

// RECUPERACIÓN DE CONTRASEÑA
Route::get('/forgot-password', [PasswordResetController::class, 'showRequestForm'])->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetCode'])->name('password.email');
Route::get('/reset-password', [PasswordResetController::class, 'showResetForm'])->name('password.reset.show');
Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');

// ----------------------------------------------------
// 2. RUTAS DE APLICACIÓN (Generalmente requieren Auth)
// ----------------------------------------------------

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return redirect()->route('eventos.index');
    })->name('dashboard');

    // EVENTOS (lista)
    Route::get('/eventos', [EventoController::class, 'index'])->name('eventos.index');

    // PERFIL
    Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil.index');
    Route::post('/perfil', [PerfilController::class, 'update'])->name('perfil.update');
    Route::get('/perfil/configuracion', function () {
        return view('ConfiguracionPerfil');
    })->name('perfil.configuracion');

    // RUTAS DE EVENTOS Y PROYECTOS (DETALLES)
    Route::get('/eventos/{evento}', [EventoController::class, 'show'])->name('eventos.show');
    Route::post('/eventos/{evento}/join', [EventoController::class, 'join'])->name('eventos.join');
    Route::post('/eventos/{evento}/leave', [EventoController::class, 'leave'])->name('eventos.leave');
});

// ----------------------------------------------------
// RUTAS SOLO PARA PARTICIPANTES
// ----------------------------------------------------

Route::middleware(['auth', 'participante'])->group(function () {

    // EQUIPOS
    Route::get('/equipos', [EquipoController::class, 'index'])->name('equipos.index');
    Route::get('/equipos/publicos', [EquipoController::class, 'equiposPublicos'])->name('equipos.publicos');
    Route::get('/equipos/registrar', [EquipoController::class, 'create'])->name('equipos.registrar');
    Route::get('/equipos/mis-invitaciones', [\App\Http\Controllers\SolicitudEquipoController::class, 'misInvitaciones'])->name('equipos.invitaciones');
    Route::post('/equipos/store', [EquipoController::class, 'store'])->name('equipos.store');
    Route::get('/equipos/{equipo}', [EquipoController::class, 'show'])->name('equipos.show');
    Route::post('/equipos/{equipo}/invite', [EquipoController::class, 'invite'])->name('equipos.invite');
    Route::post('/equipos/{equipo}/solicitar', [\App\Http\Controllers\SolicitudEquipoController::class, 'solicitar'])->name('equipos.solicitar');
    Route::post('/equipos/{equipo}/invitar', [\App\Http\Controllers\SolicitudEquipoController::class, 'invitar'])->name('equipos.invitar');
    Route::delete('/equipos/{equipo}/members/{participante}', [EquipoController::class, 'removeMember'])->name('equipos.removeMember');
    Route::put('/equipos/{equipo}/members/{participante}/role', [EquipoController::class, 'updateMemberRole'])->name('equipos.updateMemberRole');
    Route::delete('/equipos/{equipo}/leave', [EquipoController::class, 'leave'])->name('equipos.leave');
    Route::delete('/equipos/{equipo}', [EquipoController::class, 'destroy'])->name('equipos.destroy');

    // INVITACIONES DE EQUIPOS (solo del líder)
    Route::post('/invitaciones-equipo/{invitacion}/aceptar', [\App\Http\Controllers\SolicitudEquipoController::class, 'aceptarInvitacion'])->name('equipos.invitaciones.aceptar');
    Route::post('/invitaciones-equipo/{invitacion}/rechazar', [\App\Http\Controllers\SolicitudEquipoController::class, 'rechazarInvitacion'])->name('equipos.invitaciones.rechazar');

    // PROYECTOS (Registrar - solo participantes)
    Route::get('/proyectos/registrar', [ProyectoController::class, 'create'])->name('proyectos.registrar');
    Route::post('/proyectos/store', [ProyectoController::class, 'store'])->name('proyectos.store');
    Route::get('/proyectos/{id}', [ProyectoController::class, 'show'])->name('proyectos.show');

    // PROGRESO
    Route::get('/progreso', [ProgresoController::class, 'index'])->name('progreso.index');

    // AVANCES
    Route::post('/avances/store', [AvanceController::class, 'store'])->name('avances.store');

    // CONSTANCIAS (Participante)
    Route::get('/constancia', [ConstanciaController::class, 'index'])->name('constancia.index');
    Route::get('/constancias/{constancia}/descargar', [ConstanciaController::class, 'downloadCertificate'])->name('constancias.descargar');
    Route::get('/constancias/{id}/generar', [ConstanciaController::class, 'generarPDF'])->name('constancias.generar');
});

// ----------------------------------------------------
// RUTAS SOLO PARA JUECES
// ----------------------------------------------------

Route::middleware(['auth', 'juez'])->group(function () {

    Route::get('/juez/proyectos', [JuezProyectoController::class, 'index'])->name('juez.proyectos.index');
    Route::get('/juez/proyectos/{proyecto}', [JuezProyectoController::class, 'show'])->name('juez.proyectos.show');
    Route::post('/juez/proyectos/{proyecto}/calificar', [JuezProyectoController::class, 'guardarCalificacion'])->name('juez.proyectos.calificar');
    Route::get('/juez/mis-calificaciones', [JuezProyectoController::class, 'misCalificaciones'])->name('juez.mis-calificaciones');

    Route::get('/evaluacion', function () {
        return redirect()->route('juez.proyectos.index');
    })->name('proyectos.evaluacion');

    Route::get('/seguimiento-proyectos', function () {
        return redirect()->route('juez.mis-calificaciones');
    })->name('proyectos.seguimiento');

    // CONSTANCIAS JUEZ
    Route::get('/juez/constancias', [ConstanciaController::class, 'indexJuez'])->name('constancia.juez.index');
    Route::get('/juez/constancias/{id}/generar', [ConstanciaController::class, 'generarPDFJuez'])->name('constancia.juez.generar');
});

// ----------------------------------------------------
// 3. RUTAS ADMIN
// ----------------------------------------------------

Route::middleware(['auth', 'admin'])->group(function () {

    // Dashboard Admin
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/dashboard/export-pdf', [DashboardController::class, 'exportPdf'])->name('admin.dashboard.export-pdf');
    Route::get('/admin/dashboard/export-excel', [DashboardController::class, 'exportExcel'])->name('admin.dashboard.export-excel');

    // Eventos Admin
    Route::get('/admin/eventos/crear', [EventoController::class, 'create'])->name('eventos.crear');
    Route::post('/admin/eventos', [EventoController::class, 'store'])->name('eventos.store');
    Route::get('/admin/eventos/{evento}/editar', [EventoController::class, 'edit'])->name('eventos.edit');
    Route::put('/admin/eventos/{evento}', [EventoController::class, 'update'])->name('eventos.update');
    Route::delete('/admin/eventos/{evento}', [EventoController::class, 'destroy'])->name('eventos.destroy');

    // Usuarios Admin
    Route::get('/admin/usuarios', [UserController::class, 'index'])->name('admin.usuarios.index');
    Route::get('/admin/usuarios/crear', [UserController::class, 'create'])->name('admin.usuarios.create');
    Route::post('/admin/usuarios', [UserController::class, 'store'])->name('admin.usuarios.store');
    Route::get('/admin/usuarios/{usuario}', [UserController::class, 'show'])->name('admin.usuarios.show');
    Route::get('/admin/usuarios/{usuario}/editar', [UserController::class, 'edit'])->name('admin.usuarios.edit');
    Route::put('/admin/usuarios/{usuario}', [UserController::class, 'update'])->name('admin.usuarios.update');
    Route::delete('/admin/usuarios/{usuario}', [UserController::class, 'destroy'])->name('admin.usuarios.destroy');

    // Equipos Admin
    Route::get('/admin/equipos', [AdminEquipoController::class, 'index'])->name('admin.equipos.index');
    Route::get('/admin/equipos/crear', [AdminEquipoController::class, 'create'])->name('admin.equipos.create');
    Route::post('/admin/equipos', [AdminEquipoController::class, 'store'])->name('admin.equipos.store');
    Route::get('/admin/equipos/{equipo}', [AdminEquipoController::class, 'show'])->name('admin.equipos.show');
    Route::get('/admin/equipos/{equipo}/editar', [AdminEquipoController::class, 'edit'])->name('admin.equipos.edit');
    Route::put('/admin/equipos/{equipo}', [AdminEquipoController::class, 'update'])->name('admin.equipos.update');
    Route::delete('/admin/equipos/{equipo}', [AdminEquipoController::class, 'destroy'])->name('admin.equipos.destroy');
    Route::delete('/admin/equipos/{equipo}/participantes/{participanteId}', [AdminEquipoController::class, 'removeParticipant'])->name('admin.equipos.removeParticipant');

    // Proyectos Admin
    Route::get('/admin/proyectos', [AdminProyectoController::class, 'index'])->name('admin.proyectos.index');
    Route::get('/admin/proyectos/{proyecto}/asignar-jueces', [AdminProyectoController::class, 'asignarJueces'])->name('admin.proyectos.asignar-jueces');
    Route::post('/admin/proyectos/{proyecto}/asignacion', [AdminProyectoController::class, 'guardarAsignacion'])->name('admin.proyectos.guardar-asignacion');
    Route::delete('/admin/proyectos/{proyecto}/jueces/{juez}', [AdminProyectoController::class, 'eliminarAsignacion'])->name('admin.proyectos.eliminar-asignacion');
    Route::get('/admin/proyectos/{proyecto}/calificaciones', [AdminProyectoController::class, 'verCalificaciones'])->name('admin.proyectos.ver-calificaciones');
    Route::get('/admin/rankings', [AdminProyectoController::class, 'rankings'])->name('admin.rankings');

    // CONSTANCIAS ADMIN
    Route::get('/admin/eventos/{evento}/constancia', [ConstanciaController::class, 'manageCertificates'])->name('constancia.gestion');
    Route::post('/admin/participante/{participante}/evento/{evento}/generar-constancia', [ConstanciaController::class, 'generateCertificate'])->name('constancia.generar');

    // Solicitudes Admin (del main)
    Route::get('/admin/solicitudes', [App\Http\Controllers\SolicitudAdminController::class, 'index'])->name('admin.solicitudes');
    Route::post('/admin/solicitudes/{id}/aprobar', [App\Http\Controllers\SolicitudAdminController::class, 'aprobar'])->name('admin.solicitudes.aprobar');
    Route::post('/admin/solicitudes/{id}/rechazar', [App\Http\Controllers\SolicitudAdminController::class, 'rechazar'])->name('admin.solicitudes.rechazar');
});
