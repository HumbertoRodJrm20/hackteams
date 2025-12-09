<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Evento;
use App\Models\Participante;
use Illuminate\Http\Request;

class AdminEquipoController extends Controller
{
    /**
     * Muestra la lista de todos los equipos.
     */
    public function index(Request $request)
    {
        // Query base
        $query = Equipo::with(['participantes.user', 'proyectos', 'evento']);

        // Búsqueda por nombre de equipo
        if ($request->filled('search')) {
            $query->where('nombre', 'like', '%' . $request->search . '%');
        }

        // Filtro por evento
        if ($request->filled('evento_id')) {
            $query->where('evento_id', $request->evento_id);
        }

        // Ordenar y paginar
        $equipos = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Obtener eventos para filtro
        $eventos = Evento::orderBy('nombre')->get();

        return view('admin.equipos.index', compact('equipos', 'eventos'));
    }

    /**
     * Muestra el formulario para crear un nuevo equipo.
     */
    public function create()
    {
        $eventos = Evento::where('estado', '!=', 'finalizado')->get();
        $participantes = Participante::with('user')->get();

        return view('admin.equipos.create', compact('eventos', 'participantes'));
    }

    /**
     * Almacena un nuevo equipo en la base de datos.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255|unique:equipos,nombre',
            'evento_id' => 'required|exists:eventos,id',
            'participante_lider_id' => 'required|exists:participantes,user_id',
        ]);

        $equipo = Equipo::create([
            'nombre' => $validatedData['nombre'],
            'evento_id' => $validatedData['evento_id'],
        ]);

        // Agregar al líder del equipo
        $equipo->participantes()->attach($validatedData['participante_lider_id'], ['es_lider' => true]);

        return redirect()->route('admin.equipos.index')
            ->with('success', '¡El equipo "'.$equipo->nombre.'" ha sido creado con éxito!');
    }

    /**
     * Muestra el detalle de un equipo.
     */
    public function show(Equipo $equipo)
    {
        $equipo->load(['participantes.user', 'proyectos', 'evento']);

        return view('admin.equipos.show', compact('equipo'));
    }

    /**
     * Muestra el formulario para editar un equipo.
     */
    public function edit(Equipo $equipo)
    {
        $equipo->load('evento');
        $eventos = Evento::where('estado', '!=', 'finalizado')->get();

        return view('admin.equipos.edit', compact('equipo', 'eventos'));
    }

    /**
     * Actualiza los datos de un equipo.
     */
    public function update(Request $request, Equipo $equipo)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255|unique:equipos,nombre,'.$equipo->id,
            'evento_id' => 'required|exists:eventos,id',
        ]);

        $equipo->update($validatedData);

        return redirect()->route('admin.equipos.index')
            ->with('success', '¡El equipo "'.$equipo->nombre.'" ha sido actualizado con éxito!');
    }

    /**
     * Elimina un equipo de la base de datos.
     */
    public function destroy(Equipo $equipo)
    {
        $nombre = $equipo->nombre;
        $equipo->delete();

        return redirect()->route('admin.equipos.index')
            ->with('success', 'El equipo "'.$nombre.'" ha sido eliminado exitosamente.');
    }

    /**
     * Agrega un participante al equipo.
     */
    public function addParticipant(Request $request, Equipo $equipo)
    {
        $validatedData = $request->validate([
            'participante_id' => 'required|exists:participantes,user_id',
        ]);

        if ($equipo->participantes()->where('participante_id', $validatedData['participante_id'])->exists()) {
            return redirect()->route('admin.equipos.show', $equipo->id)
                ->with('error', 'Este participante ya está en el equipo.');
        }

        $equipo->participantes()->attach($validatedData['participante_id']);

        return redirect()->route('admin.equipos.show', $equipo->id)
            ->with('success', 'Participante agregado al equipo exitosamente.');
    }

    /**
     * Remueve un participante del equipo.
     */
    public function removeParticipant(Equipo $equipo, $participanteId)
    {
        $equipo->participantes()->detach($participanteId);

        return redirect()->route('admin.equipos.show', $equipo->id)
            ->with('success', 'Participante removido del equipo exitosamente.');
    }
}
