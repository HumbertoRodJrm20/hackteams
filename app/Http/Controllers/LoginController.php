<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Procesa la solicitud POST del formulario de login usando el sistema de Auth de Laravel.
     */
    public function authenticate(Request $request)
    {
        // 1. Validar las credenciales
        $credentials = $request->validate([
            // Usamos 'username' en el formulario para capturar email
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // 2. Intentar autenticar por 'email' (asumiendo que tu columna de login es 'email')
        $authAttempt = Auth::attempt([
            'email' => $request->username,
            'password' => $request->password,
        ]);

        if ($authAttempt) {
            // Regenera la sesión (buena práctica de seguridad)
            $request->session()->regenerate();

            // Redirigir según el rol del usuario
            $user = Auth::user();

            if ($user->hasRole('Admin')) {
                return redirect()->route('admin.dashboard');
            }

            // Para participantes y jueces, ir a eventos
            return redirect()->route('eventos.index');
        }

        // 3. Fallo de autenticación
        throw ValidationException::withMessages([
            'loginError' => 'Credenciales inválidas. Por favor, verifica tu usuario y contraseña.',
        ])->redirectTo(route('login'));
    }

    /**
     * Cierra la sesión del usuario.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
