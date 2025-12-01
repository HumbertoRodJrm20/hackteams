<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use Illuminate\Validation\Rule;

class EventoController extends Controller
{
    /**
     * Muestra la lista de todos los eventos.
     */
    public function index()
    {
        // Se extraen todos los eventos, ordenados por fecha de inicio para mostrar los próximos primero
        $eventos = Evento::orderBy('fecha_inicio', 'desc')->get();
        return view('Eventos', compact('eventos'));
    }

    /**
     * Muestra el formulario para crear un nuevo evento.
     */
    public function create()
    {
        // Nota: Se debe proteger esta ruta con un middleware 'admin'
        return view('CrearEvento');
    }

    /**
     * Almacena un nuevo evento en la base de datos.
     */
    public function store(Request $request)
    {
        // Nota: Se debe proteger esta ruta con un middleware 'admin'
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255|unique:eventos,nombre',
            'descripcion' => 'required|string',
            'fecha_inicio' => 'required|date|after_or_equal:today',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'estado' => ['sometimes', 'string', Rule::in(['proximo', 'activo', 'finalizado'])], 
        ]);

        $evento = Evento::create($validatedData);

        return redirect()->route('eventos.index')->with('success', '¡El evento "' . $evento->nombre . '" ha sido creado con éxito!');
    }

    /**
     * Muestra el detalle de un evento específico (Infeventos.blade.php).
     */
    public function show(Evento $evento)
    {
        // Route Model Binding inyecta la instancia de Evento automáticamente
        return view('Infeventos', compact('evento'));
    }

    /**
     * Elimina un evento de la base de datos.
     */
    public function destroy(Evento $evento)
    {
        // Nota: Se debe proteger esta ruta con un middleware 'admin'
        $nombre = $evento->nombre;
        $evento->delete();

        return redirect()->route('eventos.index')->with('success', 'El evento "' . $nombre . '" ha sido eliminado exitosamente.');
    }
}