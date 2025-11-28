<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController; 
use App\Http\Controllers\RegisterController; 
use App\Http\Controllers\EventoController;
use App\Http\Controllers\PerfilController;

Route::get('/', function () {
    return redirect()->route('login'); 
});

Route::get('/login', function () {
    return view('Login'); 
})->name('login'); 

Route::post('/login/auth', [LoginController::class, 'authenticate'])
    ->name('login.auth');

Route::get('/register', function () {
    return view('Register'); 
})->name('register'); 

Route::post('/register', [RegisterController::class, 'store']) 
    ->name('register.create'); 

Route::get('/forgot-password', function () {
    return view('welcome'); 
})->name('password.request');



Route::get('/a', function () {
    return view('Layout.app'); 
});

// Eventos
Route::get('/eventos', function () {
    return view('Eventos'); 
})->name('eventos.index');

Route::get('/eventos/info', function () {
    return view('Infeventos'); 
})->name('eventos.info');

// Equipos
Route::get('/equipos', function () {
    return view('ListaEquipos'); 
})->name('equipos.index');

Route::get('/equipos/registrar', function () {
    return view('RegistrarEquipo'); 
})->name('equipos.registrar');

// Evaluación y Progreso
Route::get('/evaluacion', function () {
    return view('EvaluacionProyectos'); 
})->name('proyectos.evaluacion');

Route::get('/progreso', function () {
    return view('Progreso'); 
})->name('progreso.index');

// Perfil
Route::get('/perfil', function () {
    return view('Perfil'); 
})->name('perfil.index');



// Constancias
Route::get('/const', function () {
    return view('Constancia'); 
})->name('constancia.index');

Route::get('/register', function () {
    return view('Registrar'); 
})->name('register'); 

// 2. RUTA POST: Procesa el formulario y guarda los datos (incluyendo la imagen)
// Apunta al método 'store' de RegisterController
Route::post('/register', [RegisterController::class, 'store']) 
    ->name('register.create');
    
Route::get('/eventos/crear', function () {
    return view('CrearEvento'); 
})->name('eventos.crear'); 

// Ruta que procesa el formulario (POST)
Route::post('/eventos', [EventoController::class, 'store'])
    ->name('eventos.store');

// 1. RUTA GET: Muestra el perfil del usuario (Llama a PerfilController@index)
Route::get('/perfil', [PerfilController::class, 'index'])
    ->name('perfil.index');

// 2. RUTA POST: Actualiza los datos del perfil
Route::post('/perfil', [PerfilController::class, 'update'])
    ->name('perfil.update');
    