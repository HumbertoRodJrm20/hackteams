<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Proyecto;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminProyectoController extends Controller
{
    /**
     * Muestra lista de eventos con sus proyectos para asignación a jueces
     */
    public function index(Request $request)
    {
        // Query base de proyectos
        $query = Proyecto::with('equipo', 'evento', 'calificaciones', 'jueces');

        // Búsqueda por título del proyecto
        if ($request->filled('search')) {
            $query->where('titulo', 'like', '%' . $request->search . '%');
        }

        // Filtro por evento
        if ($request->filled('evento_id')) {
            $query->where('evento_id', $request->evento_id);
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Búsqueda por nombre de equipo
        if ($request->filled('equipo')) {
            $query->whereHas('equipo', function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->equipo . '%');
            });
        }

        // Ordenar y paginar
        $proyectos = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Obtener todos los eventos para el filtro
        $eventos = Evento::orderBy('nombre')->get();

        return view('admin.proyectos.index', compact('proyectos', 'eventos'));
    }

    /**
     * Muestra formulario para asignar jueces a un proyecto
     */
    public function asignarJueces(Proyecto $proyecto)
    {
        $proyecto->load('equipo', 'evento', 'jueces', 'calificaciones');

        // Obtener todos los jueces disponibles (usuarios con rol Juez)
        $rolJuez = Rol::where('nombre', 'Juez')->first();
        $juecesDisponibles = User::whereHas('roles', function ($query) use ($rolJuez) {
            $query->where('rol_id', $rolJuez->id);
        })->get();

        // Jueces ya asignados a este proyecto
        $juecesAsignados = $proyecto->jueces()->pluck('users.id')->toArray();

        return view('admin.proyectos.asignar-jueces', compact('proyecto', 'juecesDisponibles', 'juecesAsignados'));
    }

    /**
     * Guarda la asignación de jueces a un proyecto
     */
    public function guardarAsignacion(Request $request, Proyecto $proyecto)
    {
        $validated = $request->validate([
            'jueces' => 'required|array',
            'jueces.*' => 'required|integer|exists:users,id',
        ]);

        try {
            DB::beginTransaction();

            // Sincronizar jueces asignados al proyecto
            $proyecto->jueces()->sync($validated['jueces']);

            DB::commit();

            return redirect()->route('admin.proyectos.asignar-jueces', $proyecto->id)
                ->with('success', 'Jueces asignados correctamente al proyecto');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error al asignar jueces: '.$e->getMessage());
        }
    }

    /**
     * Elimina la asignación de un juez a un proyecto
     */
    public function eliminarAsignacion(Proyecto $proyecto, User $juez)
    {
        try {
            $proyecto->jueces()->detach($juez->id);

            return redirect()->route('admin.proyectos.asignar-jueces', $proyecto->id)
                ->with('success', 'Juez removido del proyecto');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al remover juez: '.$e->getMessage());
        }
    }

    /**
     * Muestra los rankings de proyectos por evento
     */
    public function rankings()
    {
        $eventos = Evento::with(['proyectos' => function ($query) {
            $query->with('equipo', 'calificaciones')
                ->whereHas('calificaciones')
                ->get();
        }])->get();

        // Calcular rankings para cada evento
        $eventosConRankings = $eventos->map(function ($evento) {
            $proyectos = $evento->proyectos->map(function ($proyecto) {
                return [
                    'id' => $proyecto->id,
                    'titulo' => $proyecto->titulo,
                    'equipo' => $proyecto->equipo ? $proyecto->equipo->nombre : 'Sin equipo',
                    'promedio' => $proyecto->obtenerPromedio(),
                    'calificaciones_count' => $proyecto->calificaciones->count(),
                ];
            })->sortByDesc('promedio')
                ->values()
                ->map(function ($proyecto, $index) {
                    $proyecto['puesto'] = $index + 1;

                    return $proyecto;
                });

            return [
                'id' => $evento->id,
                'nombre' => $evento->nombre,
                'estado' => $evento->estado,
                'proyectos' => $proyectos,
            ];
        });

        return view('admin.proyectos.rankings', compact('eventosConRankings'));
    }

    /**
     * Muestra las calificaciones detalladas de un proyecto (todas las calificaciones de todos los jueces)
     */
    public function verCalificaciones(Proyecto $proyecto)
    {
        $proyecto->load('equipo', 'evento', 'calificaciones.juez', 'jueces');

        // Obtener todas las calificaciones
        $calificaciones = $proyecto->calificaciones()->with('juez')->get();

        // Calcular estadísticas
        $promedio = $proyecto->obtenerPromedio();
        $puesto = $proyecto->obtenerPuesto();
        $cantidadJueces = $proyecto->jueces()->count(); // Contar jueces asignados, no calificaciones

        return view('admin.proyectos.ver-calificaciones', compact(
            'proyecto',
            'calificaciones',
            'promedio',
            'puesto',
            'cantidadJueces'
        ));
    }
}
