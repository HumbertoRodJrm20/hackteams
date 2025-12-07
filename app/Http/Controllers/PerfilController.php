<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Necesitamos el Facade de autenticación

class PerfilController extends Controller
{
    /**
     * Muestra el perfil del usuario autenticado.
     */
    public function index()
    {
        // 1. Verificar si el usuario está logueado
        if (Auth::check()) {
            // 2. Obtener la información completa del usuario actualmente logueado
            $user = Auth::user();

            // 3. Pasar el objeto $user a la vista
            return view('Perfil', compact('user'));
        }

        // Si por alguna razón no hay usuario autenticado, redirigir al login
        return redirect()->route('login');
    }

    /**
     * Maneja la actualización de los datos del perfil (opcional)
     */
    public function update(Request $request)
    {
        // Aquí iría la lógica para validar y actualizar el nombre, email, o contraseña.

        // Ejemplo de validación
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.Auth::id(), // Ignora el email actual
            // 'password' => 'nullable|string|min:8|confirmed', // Si incluyes cambio de password
        ]);

        $user = Auth::user();
        $user->name = $request->input('name');
        $user->email = $request->input('email');

        // Si tienes más campos (como imagen de perfil, etc.), se actualizarían aquí.

        $user->save();

        // Redirigir de vuelta al perfil con un mensaje de éxito
        return redirect()->route('perfil.index')->with('success', 'Tu perfil ha sido actualizado con éxito.');
    }
}
