<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        try {
            
            DB::table('users')->insert([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password), 
                
                'role' => 'estudiante', 
                
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            return redirect()->route('login')->with('status', '¡Registro exitoso! Por favor, inicia sesión.');

        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'registrationError' => 'Error al guardar el usuario en la base de datos: ' . $e->getMessage(),
            ])->redirectTo(route('register'));
        }
    }
}