<?php

// app/Http/Middleware/AdminMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $isAdmin = $user->hasRole('Admin');

        if ($isAdmin) {
            return $next($request);
        }

        // Si no es admin, redirige
        return redirect()->route('eventos.index')
            ->with('error', 'Acceso denegado. Solo los administradores pueden realizar esta acción.');
    }
}
