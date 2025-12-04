<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParticipanteMiddleware
{
    /**
     * Middleware que verifica si el usuario tiene SOLO el rol de Participante.
     * Los Jueces y Admins son rechazados automáticamente.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Verificar que el usuario SOLO tenga el rol de Participante
        // No debe tener múltiples roles
        if (Auth::user()->hasRole('Participante')) {
            return $next($request);
        }

        // Si llega aquí, el usuario NO es Participante (es Juez o Admin)
        return redirect()->route('eventos.index')
            ->with('error', 'Acceso denegado. Esta sección es solo para participantes.');
    }
}
