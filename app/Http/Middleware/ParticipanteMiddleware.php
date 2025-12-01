<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParticipanteMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->hasRole('Participante')) {
            return $next($request);
        }

        return redirect()->route('eventos.index')
            ->with('error', 'Acceso denegado. Solo los participantes pueden realizar esta acción.');
    }
}
