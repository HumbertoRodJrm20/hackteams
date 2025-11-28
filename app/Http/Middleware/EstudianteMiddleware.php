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
        // Verifica si el usuario está autenticado Y si su rol es 'estudiante'
        if (Auth::check() && Auth::user()->role === 'estudiante') {
            return $next($request);
        }

        // Si no es estudiante (es admin o juez), lo redirige con un error
        return redirect()->route('eventos.index')
            ->with('error', 'Acceso denegado. Solo los estudiantes pueden realizar esta acción.');
    }
}