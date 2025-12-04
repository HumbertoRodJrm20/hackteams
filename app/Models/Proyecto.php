<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proyecto extends Model
{
    use SoftDeletes;

    protected $table = 'proyectos';
    protected $fillable = ['equipo_id', 'evento_id', 'titulo', 'resumen', 'link_repositorio', 'estado'];

    // Relación con equipo (N:1)
    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'equipo_id');
    }

    // Relación con evento (N:1)
    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }

    // Relación con avances (1:N)
    public function avances()
    {
        return $this->hasMany(Avance::class, 'proyecto_id');
    }

    // Relación con calificaciones (1:N)
    public function calificaciones()
    {
        return $this->hasMany(Calificacion::class, 'proyecto_id');
    }

    // Relación con jueces (N:M) - Jueces asignados para evaluar este proyecto
    public function jueces()
    {
        return $this->belongsToMany(User::class, 'proyecto_juez', 'proyecto_id', 'juez_user_id')
            ->withTimestamps()
            ->withPivot('asignado_en');
    }

    /**
     * Calcula el promedio ponderado de calificaciones del proyecto
     * Basado en los criterios y sus ponderaciones
     */
    public function obtenerPromedio()
    {
        // Obtener todos los jueces que han calificado este proyecto
        $juecesConCalificaciones = $this->calificaciones()
            ->distinct('juez_user_id')
            ->pluck('juez_user_id');

        if ($juecesConCalificaciones->isEmpty()) {
            return 0;
        }

        $totalPromedios = 0;
        $cantidadJueces = 0;

        // Calcular promedio ponderado para cada juez
        foreach ($juecesConCalificaciones as $juezId) {
            $promedioJuez = 0;
            $ponderacionTotal = 0;

            // Obtener todas las calificaciones de este juez para este proyecto
            $calificacionesJuez = $this->calificaciones()
                ->where('juez_user_id', $juezId)
                ->get();

            foreach ($calificacionesJuez as $calificacion) {
                $criterio = $calificacion->criterio;
                if ($criterio) {
                    $promedioJuez += $calificacion->puntuacion * ($criterio->ponderacion / 100);
                    $ponderacionTotal += $criterio->ponderacion / 100;
                }
            }

            // Si el juez ha calificado criterios
            if ($ponderacionTotal > 0) {
                $totalPromedios += $promedioJuez / $ponderacionTotal;
                $cantidadJueces++;
            }
        }

        // Retornar el promedio de todos los jueces
        return $cantidadJueces > 0 ? $totalPromedios / $cantidadJueces : 0;
    }

    /**
     * Obtiene el puesto del proyecto basado en el promedio de calificaciones
     * Dentro de su evento
     */
    public function obtenerPuesto()
    {
        $promedio = $this->obtenerPromedio();

        // Contar cuántos proyectos del mismo evento tienen promedio mayor
        $puestosArriba = Proyecto::where('evento_id', $this->evento_id)
            ->where('id', '!=', $this->id)
            ->with('calificaciones')
            ->get()
            ->filter(function ($p) {
                return $p->obtenerPromedio() > 0;
            })
            ->filter(function ($p) use ($promedio) {
                return $p->obtenerPromedio() > $promedio;
            })
            ->count();

        return $puestosArriba + 1;
    }
}
