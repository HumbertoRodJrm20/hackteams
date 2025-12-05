<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'max_equipos' => 'required|integer|min:1',
        ]);

        $evento = Evento::create($validatedData);

        return redirect()->route('eventos.index')->with('success', '¡El evento "'.$evento->nombre.'" ha sido creado con éxito!');
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
     * Muestra el formulario para editar un evento existente.
     */
    public function edit(Evento $evento)
    {
        // Nota: Se debe proteger esta ruta con un middleware 'admin'
        return view('EditarEvento', compact('evento'));
    }

    /**
     * Actualiza un evento existente en la base de datos.
     */
    public function update(Request $request, Evento $evento)
    {
        // Nota: Se debe proteger esta ruta con un middleware 'admin'
        $validatedData = $request->validate([
            'nombre' => ['required', 'string', 'max:255', Rule::unique('eventos', 'nombre')->ignore($evento->id)],
            'descripcion' => 'required|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'estado' => ['sometimes', 'string', Rule::in(['proximo', 'activo', 'finalizado'])],
            'max_equipos' => 'required|integer|min:1',
        ]);

        $evento->update($validatedData);

        return redirect()->route('eventos.show', $evento->id)->with('success', '¡El evento "'.$evento->nombre.'" ha sido actualizado con éxito!');
    }

    /**
     * Elimina un evento de la base de datos.
     */
    public function destroy(Evento $evento)
    {
        // Nota: Se debe proteger esta ruta con un middleware 'admin'
        $nombre = $evento->nombre;
        $evento->delete();

        return redirect()->route('eventos.index')->with('success', 'El evento "'.$nombre.'" ha sido eliminado exitosamente.');
    }

    /**
     * Permite que un participante se una a un evento.
     */
    public function join(Evento $evento)
    {
        $user = Auth::user();
        $participante = $user->participante;

        if (! $participante) {
            return redirect()->route('eventos.show', $evento->id)
                ->with('error', 'Debes estar registrado como participante para unirte a un evento.');
        }

        // Validar estado del evento
        if ($evento->estado === 'finalizado') {
            return redirect()->route('eventos.show', $evento->id)
                ->with('error', 'No puedes unirte a un evento que ya ha finalizado.');
        }

        // Verificar si ya está unido
        if ($evento->hasParticipante($user->id)) {
            return redirect()->route('eventos.show', $evento->id)
                ->with('info', 'Ya estás registrado en este evento.');
        }

        // Agregar participante al evento
        $evento->participantes()->attach($participante->user_id);

        return redirect()->route('eventos.show', $evento->id)
            ->with('success', 'Te has unido exitosamente al evento "'.$evento->nombre.'".');
    }

    /**
     * Permite que un participante abandone un evento.
     */
    public function leave(Evento $evento)
    {
        $user = Auth::user();
        $participante = $user->participante;

        if (! $participante) {
            return redirect()->route('eventos.index')
                ->with('error', 'Error al abandonar el evento.');
        }

        $evento->participantes()->detach($participante->user_id);

        return redirect()->route('eventos.index')
            ->with('success', 'Te has salido del evento "'.$evento->nombre.'".');
    }
}
