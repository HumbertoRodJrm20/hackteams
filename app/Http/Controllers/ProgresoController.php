<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use Illuminate\Support\Facades\Auth;

class ProgresoController extends Controller
{
    /**
     * Muestra el progreso de los proyectos del usuario actual.
     */
    public function index()
    {
        $user = Auth::user();
        $participante = $user->participante;

        // Si el usuario no es participante, mostrar progreso vacío
        if (!$participante) {
            return view('Progreso', ['proyectos' => collect()]);
        }

        // Obtener los equipos del participante
        $equipos = Equipo::whereHas('participantes', function ($query) use ($participante) {
            $query->where('participantes.user_id', $participante->user_id);
        })
        ->with('proyectos.evento')
        ->get();

        // Extraer todos los proyectos de los equipos
        $proyectos = $equipos->flatMap(function ($equipo) {
            return $equipo->proyectos->map(function ($proyecto) use ($equipo) {
                return [
                    'id' => $proyecto->id,
                    'titulo' => $proyecto->titulo,
                    'resumen' => $proyecto->resumen,
                    'equipo_nombre' => $equipo->nombre,
                    'equipo_id' => $equipo->id,
                    'evento_nombre' => $proyecto->evento->nombre,
                    'estado' => $proyecto->estado,
                    'link_repositorio' => $proyecto->link_repositorio,
                    'fecha_creacion' => $proyecto->created_at->format('d/m/Y'),
                ];
            });
        });

        return view('Progreso', compact('proyectos'));
    }
}
