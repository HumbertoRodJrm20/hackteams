<?php

namespace App\Http\Controllers;

use App\Mail\CodigoVerificacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class VerificationController extends Controller
{
    /**
     * Muestra el formulario de verificación de email.
     */
    public function show()
    {
        return view('verification');
    }

    /**
     * Verifica el código ingresado por el usuario.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'codigo' => 'required|string|size:6',
        ]);

        $user = User::where('email', $request->email)->first();

        // Verificar si el código es correcto
        if ($user->verification_code !== $request->codigo) {
            return back()->with('error', 'El código ingresado es incorrecto.');
        }

        // Verificar si el código ha expirado
        if (now()->gt($user->verification_code_expires_at)) {
            return back()->with('error', 'El código ha expirado. Solicita un nuevo código.');
        }

        // Marcar como verificado
        $user->email_verified_at = now();
        $user->verification_code = null;
        $user->verification_code_expires_at = null;
        $user->save();

        // Iniciar sesión automáticamente
        Auth::login($user);

        return redirect()->route('perfil.index')
            ->with('success', '¡Email verificado exitosamente! Bienvenido a HackTeams.');
    }

    /**
     * Reenvía el código de verificación.
     */
    public function resend(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        // Verificar si ya está verificado
        if ($user->email_verified_at) {
            return back()->with('info', 'Este correo ya ha sido verificado.');
        }

        // Generar nuevo código
        $codigoVerificacion = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->verification_code = $codigoVerificacion;
        $user->verification_code_expires_at = now()->addMinutes(5);
        $user->save();

        // Enviar correo
        Mail::to($user->email)->send(new CodigoVerificacion($user, $codigoVerificacion));

        return back()->with('success', 'Se ha enviado un nuevo código a tu correo.');
    }
}
