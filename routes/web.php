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
use App\Http\Controllers\ProgresoController; // <--- ¡AÑADIDO!

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
Route::post('/logout', [LoginController::class, 'logout'])->name('logout'); // Corregida la definición de Logout

// REGISTRO
Route::get('/register', function () {
    return view('Register'); // Usar 'Register' si esa es la vista Blade
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
    
    // EVENTOS
    Route::get('/eventos', [EventoController::class, 'index'])->name('eventos.index');
    // RUTA DE DETALLE DE EVENTO (Para Infeventos.blade.php)
    Route::get('/eventos/{evento}', [EventoController::class, 'show'])->name('eventos.show'); // <--- ¡AÑADIDA!

    // EQUIPOS
    Route::get('/equipos', [EquipoController::class, 'index'])->name('equipos.index');
    Route::get('/equipos/registrar', [EquipoController::class, 'create'])->name('equipos.registrar');
    Route::post('/equipos/store', [EquipoController::class, 'store'])->name('equipos.store');

    // PROYECTOS
    Route::get('/proyectos/registrar', [ProyectoController::class, 'create'])->name('proyectos.registrar');
    Route::post('/proyectos/store', [ProyectoController::class, 'store'])->name('proyectos.store');
    Route::get('/proyectos/{id}', [ProyectoController::class, 'show'])->name('proyectos.show');

    // EVALUACIÓN (JUECES)
    Route::get('/evaluacion', function () {
        return view('EvaluacionProyectos');
    })->name('proyectos.evaluacion');
    
    // PROGRESO (Ruta faltante que causaba el error 500)
    Route::get('/progreso', [ProgresoController::class, 'index'])->name('progreso.index'); // <--- ¡AÑADIDA Y SOLUCIONADA!

    // PERFIL
    Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil.index');
    Route::post('/perfil', [PerfilController::class, 'update'])->name('perfil.update');
    Route::get('/perfil/configuracion', function () {
        return view('ConfiguracionPerfil');
    })->name('perfil.configuracion'); // Usé 'configuracion' en lugar de 'update' para la vista GET

    // CONSTANCIAS
    Route::get('/const', function () {
        return view('Constancia');
    })->name('constancia.index');

    // AVANCES
    Route::post('/avances/store', [AvanceController::class, 'store'])->name('avances.store');

});

// ----------------------------------------------------
// 3. RUTAS DE ADMINISTRACIÓN (Requieren el middleware 'admin')
// ----------------------------------------------------

Route::middleware(['auth', 'admin'])->group(function () {
    // Crear Evento
    Route::get('/eventos/crear', [EventoController::class, 'create'])->name('eventos.crear');
    Route::post('/eventos', [EventoController::class, 'store'])->name('eventos.store');
    
    // ... (Aquí irían las rutas de CRUD de Roles, Carreras, etc.)
});

Route::controller(EventoController::class)->group(function () {
    Route::get('/eventos', 'index')->name('eventos.index');
    Route::get('/eventos/crear', 'create')->name('eventos.create'); // Muestra formulario
    Route::post('/eventos', 'store')->name('eventos.store'); // Guarda formulario
    Route::get('/eventos/{evento}', 'show')->name('eventos.show'); // Detalle de un evento (Leer Más)
    Route::delete('/eventos/{evento}', 'destroy')->name('eventos.destroy'); // Eliminar
});