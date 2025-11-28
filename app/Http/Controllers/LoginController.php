<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth; 

class LoginController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);
        
        $authAttempt = Auth::attempt([
            'email' => $request->username, 
            'password' => $request->password,
        ]);

        if ($authAttempt) {
            $request->session()->regenerate();
            
            return redirect()->route('eventos.index'); 
        }

        throw ValidationException::withMessages([
            'loginError' => 'Credenciales inválidas. Por favor, verifica tu usuario y contraseña.',
        ])->redirectTo(route('login'));
    }
}