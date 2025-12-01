<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EquipoController extends Controller
{
    /**
     * Muestra la lista de todos los equipos.
     * Corresponde a la ruta GET /equipos.
     */
    public function index()
    {
        // Esto es un ejemplo de cómo podrías obtener los equipos
        // $equipos = Equipo::with('proyecto', 'miembros')->get(); 
        
        // Asumiendo que tu vista para listar equipos es 'ListaEquipos.blade.php' 
        // o similar, o simplemente devuelves la vista base.
        return view('ListaEquipos'); // O el nombre de la vista que uses para listar equipos
    }
    
    // Aquí irían los métodos create, store, show, etc., que tienes definidos en tus rutas.
    public function create()
    {
        // Muestra el formulario para registrar un equipo
        return view('RegistrarEquipo');
    }

    public function store(Request $request)
    {
        // Lógica para guardar el equipo
        // ...
    }
}
