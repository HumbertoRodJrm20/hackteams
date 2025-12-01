<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 🛑 REGISTRO DE ALIAS DE MIDDLEWARE PERSONALIZADO
        // Usamos la fachada Route para registrar los alias
        Route::aliasMiddleware('admin', \App\Http\Middleware\AdminMiddleware::class);
        Route::aliasMiddleware('participante', \App\Http\Middleware\EstudianteMiddleware::class);
        
        // NOTA: Si tienes un 'JuezMiddleware', también lo registrarías aquí:
        // Route::aliasMiddleware('juez', \App\Http\Middleware\JuezMiddleware::class);
    }
}
