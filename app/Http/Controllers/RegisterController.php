<?php

namespace App\Http\Controllers;

use App\Mail\CodigoVerificacion;
use App\Models\Carrera;
use App\Models\Participante;
use App\Models\Rol; // <-- CRUCIAL: Asegúrate que esta línea esté descomentada.
use App\Models\User; // <-- CRUCIAL: Asegúrate que esta línea esté descomentada.
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException; // Añadido para manejo de transacciones

class RegisterController extends Controller
{
    /**
     * Muestra el formulario de registro.
     */
    public function create()
    {
        $carreras = Carrera::all();
        return view('Registrar', compact('carreras'));
    }

    /**
     * Procesa y almacena los datos de un nuevo usuario.
     * IMPORTANTE: El registro público es SOLO para Participantes.
     * Los Jueces y Admins deben ser creados por administradores en el panel de admin.
     */
    public function store(Request $request)
    {
        // 1. VALIDACIÓN
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8'],
            'matricula' => ['required', 'string', 'max:20', 'unique:participantes,matricula'],
            'carrera_id' => ['required', 'exists:carreras,id'],
        ]);

        // Usamos una transacción para asegurar que si falla Participante o Rol, nada se guarde.
        try {
            DB::beginTransaction();

            // 2. CREACIÓN DEL USUARIO (Tabla 'users')
            // Generar código de verificación de 6 dígitos
            $codigoVerificacion = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'verification_code' => $codigoVerificacion,
                'verification_code_expires_at' => now()->addMinutes(5),
            ]);

            // 3. CREACIÓN DE PARTICIPANTE (Tabla 'participantes')
            // Solo los usuarios que se registran públicamente son participantes
            Participante::create([
                'user_id' => $user->id,
                'carrera_id' => $request->carrera_id,
                'matricula' => $request->matricula,
            ]);

            // 4. ASIGNACIÓN DE ROL (Tabla 'user_rol')
            // Solo asignamos rol de Participante en registro público
            $rolParticipante = Rol::where('nombre', 'Participante')->first();

            if (! $rolParticipante) {
                // Si el rol no existe (porque el seeder no se ejecutó), lanzamos una excepción.
                throw new \Exception("El rol 'Participante' no existe en la base de datos.");
            }

            // Asignar SOLO el rol de Participante
            $user->roles()->attach($rolParticipante->id);

            // Enviar correo de verificación
            Mail::to($user->email)->send(new CodigoVerificacion($user, $codigoVerificacion));

            DB::commit(); // Si todo es exitoso, confirmamos la transacción.

        } catch (\Exception $e) {
            DB::rollBack(); // Si algo falla (modelo, rol, DB), se revierte todo.
            \Log::error('Error en registro: ' . $e->getMessage());

            // Lanza un error genérico para el usuario
            throw ValidationException::withMessages([
                'registro_error' => ['No se pudo completar el registro. Por favor, contacte al administrador. (Fallo en Rol o Participante)'],
            ])->redirectTo(route('register'));
        }

        // 5. REDIRECCIÓN A PÁGINA DE VERIFICACIÓN (NO login automático)
        return redirect()->route('verification.show')
            ->with('email', $user->email)
            ->with('success', 'Registro exitoso. Revisa tu correo para obtener el código de verificación.');
    }
}
