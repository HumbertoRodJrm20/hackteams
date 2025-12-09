<?php

namespace App\Http\Controllers;

use App\Mail\RecuperacionPassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class PasswordResetController extends Controller
{
    /**
     * Muestra el formulario para solicitar recuperación.
     */
    public function showRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Envía el código de recuperación por correo.
     */
    public function sendResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Generar código de 6 dígitos
        $codigo = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Guardar o actualizar en password_reset_tokens
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $codigo,
                'created_at' => now(),
            ]
        );

        // Enviar correo
        Mail::to($request->email)->send(new RecuperacionPassword($request->email, $codigo));

        return redirect()->route('password.reset.show')
            ->with('email', $request->email)
            ->with('success', 'Se ha enviado un código de recuperación a tu correo.');
    }

    /**
     * Muestra el formulario para ingresar el código y nueva contraseña.
     */
    public function showResetForm()
    {
        return view('auth.reset-password');
    }

    /**
     * Restablece la contraseña.
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'codigo' => 'required|string|size:6',
            'password' => 'required|confirmed|min:8',
        ]);

        // Verificar código
        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$reset || $reset->token !== $request->codigo) {
            return back()->with('error', 'El código ingresado es incorrecto.');
        }

        // Verificar que no haya expirado (5 minutos)
        if (now()->diffInMinutes($reset->created_at) > 5) {
            return back()->with('error', 'El código ha expirado. Solicita uno nuevo.');
        }

        // Actualizar contraseña
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Eliminar el código usado
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')
            ->with('success', 'Contraseña restablecida exitosamente. Ahora puedes iniciar sesión.');
    }
}
