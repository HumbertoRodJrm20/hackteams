<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Calificacion; // Asegúrate de crear este modelo si no existe
use App\Models\Proyecto;
use App\Models\CriterioEvaluacion; // Asegúrate de que el modelo coincida con tu migración

class CalificacionController extends Controller
{
    /**
     * Guarda las calificaciones enviadas por el juez.
     */
    public function store(Request $request)
    {
        // 1. Validación de la solicitud
        $validated = $request->validate([
            'proyecto_id' => 'required|exists:proyectos,id',
            'puntaje' => 'required|array',
            'puntaje.*' => 'required|integer|min:0|max:100', // Cada puntaje debe ser 0-100
        ]);

        $juez_user_id = Auth::id();
        $proyecto_id = $validated['proyecto_id'];
        $puntajes = $validated['puntaje'];

        // 2. Obtener los criterios de la base de datos
        // NOTA: Asumimos que el nombre del criterio en la DB (columna 'nombre') 
        // coincide con el slug usado en el formulario ('innovacion', 'diseno', etc.)
        
        $criteriosDb = CriterioEvaluacion::whereIn('nombre', array_keys($puntajes))->get()->keyBy(function ($criterio) {
            return \Str::slug($criterio->nombre);
        });

        if ($criteriosDb->count() !== count($puntajes)) {
            return back()->with('error', 'Uno o más criterios de evaluación son inválidos.')->withInput();
        }

        try {
            DB::beginTransaction();

            foreach ($puntajes as $criterio_slug => $puntuacion) {
                $criterio = $criteriosDb->get($criterio_slug);
                
                if (!$criterio) {
                    continue; // Saltar si no se encuentra el criterio por alguna razón
                }

                // 3. Guardar o Actualizar la calificación (Usando updateOrCreate)
                // El campo 'criterio_id' en tu migración es FK, así que lo usamos.
                
                // NOTA: Tuvimos que crear el modelo Calificacion.php para que esto funcione.
                Calificacion::updateOrCreate(
                    [
                        'proyecto_id' => $proyecto_id,
                        'juez_user_id' => $juez_user_id,
                        'criterio_id' => $criterio->id,
                    ],
                    [
                        'puntuacion' => $puntuacion,
                        // Se actualizan automáticamente timestamps
                    ]
                );
            }
            
            DB::commit();

            // 4. Redirigir al seguimiento después de guardar exitosamente
            return redirect()->route('proyectos.seguimiento')->with('success', 'Calificaciones guardadas exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Puedes registrar el error en logs: \Log::error($e);
            return back()->with('error', 'Error al guardar las calificaciones: ' . $e->getMessage())->withInput();
        }
    }
}