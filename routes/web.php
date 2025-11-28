<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // Añadir para la verificación de autenticación
use App\Http\Controllers\LoginController; 
use App\Http\Controllers\RegisterController; 
use App\Http\Controllers\EventoController;
use App\Http\Controllers\PerfilController;

// ----------------------------------------------------
// 1. AUTENTICACIÓN (Rutas sin protección de sesión)
// ----------------------------------------------------

// Redirige la raíz al login
Route::get('/', function () {
    // Si el usuario ya está logueado, lo manda a eventos, si no, a login
    return Auth::check() ? redirect()->route('eventos.index') : redirect()->route('login');
});

// LOGIN
Route::get('/login', function () {
    return view('Login'); 
})->name('login'); 
// Procesa el login
Route::post('/login/auth', [LoginController::class, 'authenticate'])
    ->name('login.auth');
// Simulación de logout (si se implementa en LoginController)
// Route::post('/logout', [LoginController::class, 'logout'])->name('logout'); 

// REGISTRO
// Muestra la vista de registro (corregida la duplicación)
Route::get('/register', function () {
    return view('Registrar'); // Asumiendo que tu vista es 'Register.blade.php'
})->name('register'); 
// Procesa el formulario y guarda los datos
Route::post('/register', [RegisterController::class, 'store']) 
    ->name('register.create'); 

// Recuperación de Contraseña (vista temporal)
Route::get('/forgot-password', function () {
    return view('welcome'); 
})->name('password.request');


// ----------------------------------------------------
// 2. RUTAS DE APLICACIÓN (Generalmente requieren Auth middleware)
// ----------------------------------------------------

// Agrupamos todas las rutas que requieren inicio de sesión
Route::middleware(['auth'])->group(function () {
    
    // EVENTOS
    Route::get('/eventos', function () {
        return view('Eventos'); 
    })->name('eventos.index');

    Route::get('/eventos/info', function () {
        return view('Infeventos'); 
    })->name('eventos.info');

    // CRUD DE EVENTOS (Rutas de administración/creación)
    Route::get('/eventos/crear', function () {
        return view('CrearEvento'); 
    })->name('eventos.crear'); 
    // Procesa el formulario (POST)
    Route::post('/eventos', [EventoController::class, 'store'])
        ->name('eventos.store');

    // EQUIPOS
    Route::get('/equipos', function () {
        return view('ListaEquipos'); 
    })->name('equipos.index');

    Route::get('/equipos/registrar', function () {
        return view('RegistrarEquipo'); 
    })->name('equipos.registrar');

    // EVALUACIÓN y PROGRESO
    Route::get('/evaluacion', function () {
        return view('EvaluacionProyectos'); 
    })->name('proyectos.evaluacion');

    Route::get('/progreso', function () {
        return view('Progreso'); 
    })->name('progreso.index');

    // PERFIL
    // Muestra el perfil (CORREGIDO: Llama al método index del controlador)
    Route::get('/perfil', [PerfilController::class, 'index'])
        ->name('perfil.index');
    // Actualiza los datos del perfil
    Route::post('/perfil', [PerfilController::class, 'update'])
        ->name('perfil.update');

    // CONSTANCIAS
    Route::get('/const', function () {
        return view('Constancia'); 
    })->name('constancia.index');

});

// ----------------------------------------------------
// RUTAS DE PRUEBA Y ERRORES (Eliminadas)
// ----------------------------------------------------
// Se eliminó la ruta /a que apuntaba a Layout.app

Route::post('/logout', [LoginController::class, 'logout']) 
    ->name('logout'); 

// RUTA TEMPORAL: El botón de configuración ahora apunta a esta ruta
// Deberás crear una vista 'ConfiguracionPerfil.blade.php' para esta ruta
Route::get('/perfil/configuracion', function () {
    return view('ConfiguracionPerfil'); 
})->name('perfil.update');

Route::middleware(['auth', 'admin'])->group(function () {
    // Crear Evento
    Route::get('/eventos/crear', function () {
        return view('CrearEvento'); 
    })->name('eventos.crear'); 
    
    // Procesa el formulario (POST)
    Route::post('/eventos', [EventoController::class, 'store'])
        ->name('eventos.store');
        
    // ...
});

Route::middleware(['auth'])->group(function () {
    
    // EQUIPOS (VISUALIZACIÓN: accesible para todos los logueados)
    Route::get('/equipos', function () {
        return view('ListaEquipos'); 
    })->name('equipos.index');

    // ----------------------------------------------------
    // CREACIÓN DE EQUIPOS (SOLO ESTUDIANTES)
    // ----------------------------------------------------
    Route::middleware(['estudiante'])->group(function () {
        // Muestra el formulario para registrar equipo
        Route::get('/equipos/registrar', function () {
            return view('RegistrarEquipo'); 
        })->name('equipos.registrar');

        // Procesa el formulario y crea el equipo
        // (Deberás crear este controlador EquipoController@store)
        // Route::post('/equipos', [EquipoController::class, 'store'])->name('equipos.store'); 
    });
    
    // ... resto de rutas (perfil, progreso, etc.)
});