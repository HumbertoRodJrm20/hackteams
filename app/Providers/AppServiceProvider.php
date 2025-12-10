<?php

namespace App\Providers;

use App\Models\Evento;
use App\Observers\EventoObserver;
use App\View\Composers\NavbarComposer;
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
        Evento::observe(EventoObserver::class);

        View::composer('Layout.app', NavbarComposer::class);
    }
}
