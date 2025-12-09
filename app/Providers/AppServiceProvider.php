<?php

namespace App\Providers;

use App\Models\Evento;
use App\Observers\EventoObserver;
use App\View\Composers\NavbarComposer;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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

        // Registrar observer para eventos
        // Esto creará automáticamente criterios de evaluación cuando se cree un evento
        Evento::observe(EventoObserver::class);

        // Registrar View Composer para el navbar
        View::composer('Layout.app', NavbarComposer::class);
    }
}
