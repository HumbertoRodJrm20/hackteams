<?php

namespace App\Observers;

use App\Models\CriterioEvaluacion;
use App\Models\Evento;

class EventoObserver
{
    /**
     * Handle the Evento "created" event.
     */
    public function created(Evento $evento): void
    {
        // Crear criterios de evaluación estándar para cada evento nuevo
        $criteriosEstandar = [
            ['nombre' => 'Innovación', 'ponderacion' => 25],
            ['nombre' => 'Funcionalidad', 'ponderacion' => 25],
            ['nombre' => 'Diseño UI/UX', 'ponderacion' => 20],
            ['nombre' => 'Presentación', 'ponderacion' => 15],
            ['nombre' => 'Trabajo en Equipo', 'ponderacion' => 15],
        ];

        foreach ($criteriosEstandar as $criterio) {
            CriterioEvaluacion::create([
                'evento_id' => $evento->id,
                'nombre' => $criterio['nombre'],
                'ponderacion' => $criterio['ponderacion'],
            ]);
        }
    }

    /**
     * Handle the Evento "updated" event.
     */
    public function updated(Evento $evento): void
    {
        //
    }

    /**
     * Handle the Evento "deleted" event.
     */
    public function deleted(Evento $evento): void
    {
        //
    }

    /**
     * Handle the Evento "restored" event.
     */
    public function restored(Evento $evento): void
    {
        //
    }

    /**
     * Handle the Evento "force deleted" event.
     */
    public function forceDeleted(Evento $evento): void
    {
        //
    }
}
