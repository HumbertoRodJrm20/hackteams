<?php

namespace App\Http\Controllers;

use App\Models\Participante;
use App\Models\Rol; // <-- CRUCIAL: Asegúrate que esta línea esté descomentada.
use App\Models\User; // <-- CRUCIAL: Asegúrate que esta línea esté descomentada.
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException; // Añadido para manejo de transacciones

class RegisterController extends Controller
{
    /**
     * Muestra el formulario de registro.
     */
    public function create()
    {
        return view('Register');
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
        ]);

        // Usamos una transacción para asegurar que si falla Participante o Rol, nada se guarde.
        try {
            DB::beginTransaction();

            // 2. CREACIÓN DEL USUARIO (Tabla 'users')
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // 3. CREACIÓN DE PARTICIPANTE (Tabla 'participantes')
            // Solo los usuarios que se registran públicamente son participantes
            Participante::create([
                'user_id' => $user->id,
                // 'carrera_id' => $request->carrera_id ?? null,
                // 'matricula' => $request->matricula ?? null,
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

            DB::commit(); // Si todo es exitoso, confirmamos la transacción.

        } catch (\Exception $e) {
            DB::rollBack(); // Si algo falla (modelo, rol, DB), se revierte todo.
            // Para el debugging, puedes usar dd($e->getMessage());

            // Lanza un error genérico para el usuario
            throw ValidationException::withMessages([
                'registro_error' => ['No se pudo completar el registro. Por favor, contacte al administrador. (Fallo en Rol o Participante)'],
            ])->redirectTo(route('register'));
        }

        // 5. AUTENTICACIÓN Y REDIRECCIÓN
        Auth::login($user);

        return redirect()->route('perfil.index');
    }
}
