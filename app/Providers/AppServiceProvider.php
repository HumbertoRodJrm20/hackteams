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
        // 🚨 REGISTRA EL ALIAS DEL MIDDLEWARE AQUÍ
        Route::aliasMiddleware('admin', AdminMiddleware::class); 

        // 🚨 REGISTRA LOS ALIAS DEL MIDDLEWARE AQUÍ
        Route::aliasMiddleware('admin', AdminMiddleware::class);
        Route::aliasMiddleware('estudiante', EstudianteMiddleware::class);
    }
}
