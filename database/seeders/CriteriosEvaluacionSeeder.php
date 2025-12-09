<?php

namespace Database\Seeders;

use App\Models\CriterioEvaluacion;
use App\Models\Evento;
use Illuminate\Database\Seeder;

class CriteriosEvaluacionSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Criterios estándar que se usarán para todos los eventos
        $criteriosEstandar = [
            ['nombre' => 'Innovación', 'ponderacion' => 25],
            ['nombre' => 'Funcionalidad', 'ponderacion' => 25],
            ['nombre' => 'Diseño UI/UX', 'ponderacion' => 20],
            ['nombre' => 'Presentación', 'ponderacion' => 15],
            ['nombre' => 'Trabajo en Equipo', 'ponderacion' => 15],
        ];

        // Obtener todos los eventos
        $eventos = Evento::all();

        foreach ($eventos as $evento) {
            // Verificar si el evento ya tiene criterios
            $tieneCriterios = $evento->criterios()->exists();

            if (! $tieneCriterios) {
                // Crear criterios estándar para este evento
                foreach ($criteriosEstandar as $criterio) {
                    CriterioEvaluacion::create([
                        'evento_id' => $evento->id,
                        'nombre' => $criterio['nombre'],
                        'ponderacion' => $criterio['ponderacion'],
                    ]);
                }
            }
        }

        $this->command->info('Criterios de evaluación creados para todos los eventos.');
    }

    /**
     * Obtener los criterios estándar que se usarán
     */
    public static function getCriteriosEstandar(): array
    {
        return [
            ['nombre' => 'Innovación', 'ponderacion' => 25],
            ['nombre' => 'Funcionalidad', 'ponderacion' => 25],
            ['nombre' => 'Diseño UI/UX', 'ponderacion' => 20],
            ['nombre' => 'Presentación', 'ponderacion' => 15],
            ['nombre' => 'Trabajo en Equipo', 'ponderacion' => 15],
        ];
    }
}
