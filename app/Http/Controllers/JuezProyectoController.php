<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\Calificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JuezProyectoController extends Controller
{
    /**
     * Muestra los proyectos asignados al juez actual para evaluar
     */
    public function index()
    {
        $juez = Auth::user();

        // Obtener solo los proyectos asignados a este juez
        $proyectos = Proyecto::whereHas('jueces', function ($query) use ($juez) {
            $query->where('users.id', $juez->id);
        })->with('equipo', 'evento', 'calificaciones')
          ->orderBy('created_at', 'desc')
          ->paginate(10);

        return view('juez.proyectos-asignados', compact('proyectos'));
    }

    /**
     * Muestra el detalle de un proyecto asignado para calificar
     */
    public function show(Proyecto $proyecto)
    {
        $juez = Auth::user();

        // Verificar que el proyecto está asignado a este juez
        $asignado = $proyecto->jueces()->where('users.id', $juez->id)->exists();
        if (!$asignado) {
            return redirect()->route('juez.proyectos.index')
                ->with('error', 'No tienes permiso para ver este proyecto');
        }

        $proyecto->load('equipo', 'evento', 'avances', 'calificaciones');

        // Obtener los criterios del evento
        $criterios = $proyecto->evento->criterios ?? collect();

        // Obtener las calificaciones del juez actual para cada criterio
        $misCalificaciones = $proyecto->calificaciones()
            ->where('juez_user_id', $juez->id)
            ->get()
            ->keyBy('criterio_id');

        return view('juez.proyecto-detalle', compact('proyecto', 'criterios', 'misCalificaciones'));
    }

    /**
     * Guarda la calificación del proyecto para un criterio específico
     */
    public function guardarCalificacion(Request $request, Proyecto $proyecto)
    {
        $juez = Auth::user();

        // Verificar que el proyecto está asignado a este juez
        $asignado = $proyecto->jueces()->where('users.id', $juez->id)->exists();
        if (!$asignado) {
            return redirect()->route('juez.proyectos.index')
                ->with('error', 'No tienes permiso para calificar este proyecto');
        }

        $validated = $request->validate([
            'criterio_id' => 'required|exists:criterio_evaluacion,id',
            'puntuacion' => 'required|numeric|min:0|max:100',
        ]);

        try {
            DB::beginTransaction();

            // Verificar que el criterio pertenece al evento del proyecto
            $criterio = $proyecto->evento->criterios()->find($validated['criterio_id']);
            if (!$criterio) {
                return redirect()->back()
                    ->with('error', 'El criterio no pertenece a este evento');
            }

            // Crear o actualizar calificación del juez para este proyecto y criterio
            Calificacion::updateOrCreate(
                [
                    'proyecto_id' => $proyecto->id,
                    'juez_user_id' => $juez->id,
                    'criterio_id' => $validated['criterio_id'],
                ],
                [
                    'puntuacion' => $validated['puntuacion'],
                ]
            );

            DB::commit();

            return redirect()->route('juez.proyectos.show', $proyecto->id)
                ->with('success', 'Calificación guardada correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al guardar calificación: ' . $e->getMessage());
        }
    }

    /**
     * Muestra el ranking de proyectos que el juez ha calificado
     */
    public function misCalificaciones()
    {
        $juez = Auth::user();

        // Proyectos asignados a este juez
        $proyectos = Proyecto::whereHas('jueces', function ($query) use ($juez) {
            $query->where('users.id', $juez->id);
        })->with('equipo', 'evento', 'calificaciones')
          ->get();

        // Agrupar por evento
        $eventosPorCalificacion = $proyectos->groupBy('evento_id')->map(function ($proyectosDelEvento) {
            $evento = $proyectosDelEvento->first()->evento;

            $proyectosOrdenados = $proyectosDelEvento->map(function ($proyecto) {
                return [
                    'id' => $proyecto->id,
                    'titulo' => $proyecto->titulo,
                    'equipo' => $proyecto->equipo->nombre,
                    'promedio' => $proyecto->obtenerPromedio(),
                    'mi_calificacion' => $proyecto->calificaciones()
                        ->where('juez_user_id', Auth::id())
                        ->first()?->puntuacion,
                    'total_calificaciones' => $proyecto->calificaciones->count(),
                ];
            })->sortByDesc('promedio')->values();

            return [
                'evento' => $evento,
                'proyectos' => $proyectosOrdenados,
            ];
        })->values();

        return view('juez.mis-calificaciones', compact('eventosPorCalificacion'));
    }
}
