<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rol;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Muestra la lista de todos los usuarios.
     */
    public function index()
    {
        $usuarios = User::with('roles')->paginate(15);
        return view('admin.usuarios.index', compact('usuarios'));
    }

    /**
     * Muestra el formulario para crear un nuevo usuario.
     */
    public function create()
    {
        $roles = Rol::all();
        return view('admin.usuarios.create', compact('roles'));
    }

    /**
     * Almacena un nuevo usuario en la base de datos.
     * IMPORTANTE: Un usuario puede tener SOLO UN rol en este sistema.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'rol_id' => 'required|exists:roles,id',
        ]);

        $usuario = User::create([
            'nombre' => $validatedData['nombre'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        // Asignar UN ÚNICO rol al usuario
        $usuario->roles()->attach($validatedData['rol_id']);

        // Si es Participante, crear registro en tabla participantes
        if ($validatedData['rol_id'] == Rol::where('nombre', 'Participante')->first()?->id) {
            \App\Models\Participante::create([
                'user_id' => $usuario->id,
            ]);
        }

        return redirect()->route('admin.usuarios.index')
            ->with('success', '¡El usuario "' . $usuario->nombre . '" ha sido creado con éxito!');
    }

    /**
     * Muestra el formulario para editar un usuario.
     */
    public function edit(User $usuario)
    {
        $roles = Rol::all();
        $usuarioRol = $usuario->roles()->first();
        return view('admin.usuarios.edit', compact('usuario', 'roles', 'usuarioRol'));
    }

    /**
     * Actualiza los datos de un usuario.
     * IMPORTANTE: Usa sync() para asegurar que solo tiene UN rol, no múltiples.
     */
    public function update(Request $request, User $usuario)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'password' => 'nullable|string|min:8|confirmed',
            'rol_id' => 'required|exists:roles,id',
        ]);

        $usuario->nombre = $validatedData['nombre'];
        $usuario->email = $validatedData['email'];

        if ($validatedData['password']) {
            $usuario->password = bcrypt($validatedData['password']);
        }

        $usuario->save();

        // Reemplazar rol del usuario (sync elimina roles anteriores y asigna solo uno)
        $usuario->roles()->sync([$validatedData['rol_id']]);

        // Si es Participante, asegurar que existe registro en tabla participantes
        $rolParticipante = Rol::where('nombre', 'Participante')->first();
        if ($validatedData['rol_id'] == $rolParticipante?->id) {
            \App\Models\Participante::firstOrCreate([
                'user_id' => $usuario->id,
            ]);
        } else {
            // Si NO es Participante, eliminar registro de participante si existe
            \App\Models\Participante::where('user_id', $usuario->id)->delete();
        }

        return redirect()->route('admin.usuarios.index')
            ->with('success', '¡El usuario "' . $usuario->nombre . '" ha sido actualizado con éxito!');
    }

    /**
     * Elimina un usuario de la base de datos.
     */
    public function destroy(User $usuario)
    {
        $nombre = $usuario->nombre;
        $usuario->delete();

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'El usuario "' . $nombre . '" ha sido eliminado exitosamente.');
    }

    /**
     * Muestra el detalle de un usuario.
     */
    public function show(User $usuario)
    {
        $usuario->load('roles');
        return view('admin.usuarios.show', compact('usuario'));
    }
}
