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
// ---> AÑADIR EL NUEVO CONTROLADOR DE CONSTANCIAS
use App\Http\Controllers\ConstanciaController; 


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
    
    // RUTAS DE EVENTOS (Detalles - visible para todos)
    Route::get('/eventos/{evento}', [EventoController::class, 'show'])->name('eventos.show');
    Route::post('/eventos/{evento}/join', [EventoController::class, 'join'])->name('eventos.join');
    Route::post('/eventos/{evento}/leave', [EventoController::class, 'leave'])->name('eventos.leave');
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

    // PROYECTOS
    Route::get('/proyectos/registrar', [ProyectoController::class, 'create'])->name('proyectos.registrar');
    Route::post('/proyectos/store', [ProyectoController::class, 'store'])->name('proyectos.store');
    Route::get('/proyectos/{id}', [ProyectoController::class, 'show'])->name('proyectos.show');

    // PROGRESO
    Route::get('/progreso', [ProgresoController::class, 'index'])->name('progreso.index');

    // AVANCES
    Route::post('/avances/store', [AvanceController::class, 'store'])->name('avances.store');


    // CONSTANCIAS (Rutas del Participante)
    // 1. Vista principal de constancias (donde se ven la lista)
    Route::get('/constancias', [ConstanciaController::class, 'index'])->name('constancias.index');
    
    // 2. Ruta para descargar el archivo PDF (utiliza el modelo Constancia)
    Route::get('/constancias/{constancia}/descargar', [ConstanciaController::class, 'downloadCertificate'])->name('constancias.descargar');

    // Nota: Eliminé la ruta temporal Route::get('/const', ...)
});

// RUTAS SOLO PARA JUECES
Route::middleware(['auth', 'juez'])->group(function () {
    // EVALUACIÓN (JUECES)
    Route::get('/evaluacion', function () {
        return view('EvaluacionProyectos');
    })->name('proyectos.evaluacion');

    Route::get('/seguimiento-proyectos', function () {
        return view('juez.seguimientoProyectos');
    })->name('proyectos.seguimiento');
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
    
    // CONSTANCIAS (Rutas del Administrador)
    // 1. Vista de Gestión (Admin ve la lista de participantes de un evento finalizado)
    Route::get('/admin/eventos/{evento}/constancias', [ConstanciaController::class, 'manageCertificates'])->name('constancias.gestion');
    
    // 2. Generar y Guardar Constancia PDF (Recibe participante y evento IDs)
    Route::post('/admin/participante/{participante}/evento/{evento}/generar-constancia', [ConstanciaController::class, 'generateCertificate'])->name('constancias.generar');
});