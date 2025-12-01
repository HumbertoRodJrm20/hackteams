<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JuezMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->hasRole('Juez')) {
            return $next($request);
        }

        return redirect()->route('eventos.index')
            ->with('error', 'Acceso denegado. Solo los jueces pueden realizar esta acción.');
    }
}
