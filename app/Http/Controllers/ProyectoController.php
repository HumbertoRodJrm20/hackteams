<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Evento;
use App\Models\Equipo;
use App\Models\Proyecto;
use Illuminate\Support\Facades\DB;

class ProyectoController extends Controller
{
    /**
     * Muestra el formulario para registrar un nuevo proyecto para el equipo del usuario.
     */
    public function create()
    {
        $user = Auth::user();

        // 1. Verificar si el usuario es un participante
        if (!$user->esParticipante()) {
            return redirect()->route('dashboard')->with('error', 'Solo los participantes pueden registrar proyectos.');
        }

        // 2. Obtener el equipo del participante
        $equipo = $user->participante->equipos->first();

        if (!$equipo) {
            return redirect()->route('equipos.index')->with('error', 'Debes estar en un equipo para registrar un proyecto.');
        }

        // 3. Obtener eventos activos (o próximos) para seleccionar
        $eventos = Evento::whereIn('estado', ['activo', 'proximo'])->get();

        // El líder del equipo (o cualquier miembro) puede registrar el proyecto
        return view('CrearProyecto', compact('equipo', 'eventos'));
    }

    /**
     * Almacena el nuevo proyecto en la base de datos.
     */
    public function show($id)
    {
        // Carga el proyecto y todas sus relaciones necesarias (eager loading)
        $proyecto = Proyecto::with([
            'equipo.participantes.user', // Para ver los miembros del equipo
            'evento',
            'avances' => function ($query) {
                // Ordenamos los avances por fecha más reciente primero
                $query->orderBy('fecha', 'desc');
            },
            'calificaciones' // Para mostrar el puntaje promedio
        ])->findOrFail($id); // Si no lo encuentra, Laravel lanza un 404

        // Opcional: Puedes agregar lógica de autorización aquí,
        // por ejemplo, solo permitir ver a miembros del equipo o jueces.

        return view('DetalleProyecto', compact('proyecto'));
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'evento_id' => 'required|exists:eventos,id',
            'titulo' => 'required|string|max:255|unique:proyectos,titulo',
            'resumen' => 'required|string',
            'link_repositorio' => 'nullable|url|max:255',
        ]);

        $user = Auth::user();
        $equipo = $user->participante->equipos->first();

        if (!$equipo) {
            return redirect()->back()->with('error', 'Error: No se encontró el equipo asociado.');
        }

        // Verificar si el equipo ya tiene un proyecto registrado para ese evento (opcional)
        // if (Proyecto::where('equipo_id', $equipo->id)->where('evento_id', $validatedData['evento_id'])->exists()) {
        //     return redirect()->back()->with('error', 'Este equipo ya tiene un proyecto registrado para este evento.');
        // }

        try {
            Proyecto::create([
                'equipo_id' => $equipo->id,
                'evento_id' => $validatedData['evento_id'],
                'titulo' => $validatedData['titulo'],
                'resumen' => $validatedData['resumen'],
                'link_repositorio' => $validatedData['link_repositorio'],
                'estado' => 'en_desarrollo' // Estado inicial
            ]);

            return redirect()->route('equipos.index')->with('success', '¡El proyecto se registró con éxito!');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error al guardar el proyecto: ' . $e->getMessage());
        }
    }
}
