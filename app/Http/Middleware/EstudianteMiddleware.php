<?php
// app/Http/Middleware/EstudianteMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EstudianteMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // 🛑 CORRECCIÓN: Usamos hasRole('Participante') en lugar de ->role === 'estudiante'
        if (Auth::check() && Auth::user()->hasRole('Participante')) { 
            return $next($request);
        }

        // Si no es Participante, lo redirige con un error
        return redirect()->route('eventos.index')
            ->with('error', 'Acceso denegado. Solo los participantes (estudiantes) pueden realizar esta acción.');
    }
}