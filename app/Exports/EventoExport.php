<?php

namespace App\Exports;

use App\Models\Evento;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EventoExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    public function __construct(
        protected int $eventoId
    ) {}

    public function collection()
    {
        // Cargar evento con todos los proyectos, equipos y calificaciones en una sola query
        $evento = Evento::with([
            'proyectos.equipo',
            'proyectos.calificaciones.criterio',
            'criterios'
        ])
            ->where('id', $this->eventoId)
            ->first();

        // Calcular promedios y puestos de forma optimizada
        $proyectosConDatos = $evento->proyectos->map(function ($proyecto) {
            return [
                'proyecto' => $proyecto,
                'promedio' => $this->calcularPromedio($proyecto),
            ];
        });

        // Ordenar por promedio descendente para calcular puestos
        $proyectosOrdenados = $proyectosConDatos->sortByDesc('promedio')->values();

        // Asignar puestos
        return $proyectosOrdenados->map(function ($item, $index) {
            $item['puesto'] = $index + 1;
            return $item;
        });
    }

    protected function calcularPromedio($proyecto)
    {
        // Obtener jueces únicos que han calificado
        $juecesConCalificaciones = $proyecto->calificaciones
            ->pluck('juez_user_id')
            ->unique();

        if ($juecesConCalificaciones->isEmpty()) {
            return 0;
        }

        // Calcular promedio ponderado para cada juez
        $sumaPromedios = 0;
        foreach ($juecesConCalificaciones as $juezId) {
            $calificacionesJuez = $proyecto->calificaciones
                ->where('juez_user_id', $juezId);

            $sumaPonderada = 0;
            $sumaPonderaciones = 0;

            foreach ($calificacionesJuez as $calificacion) {
                if ($calificacion->criterio) {
                    $sumaPonderada += $calificacion->puntuacion * ($calificacion->criterio->ponderacion / 100);
                    $sumaPonderaciones += ($calificacion->criterio->ponderacion / 100);
                }
            }

            if ($sumaPonderaciones > 0) {
                $sumaPromedios += $sumaPonderada / $sumaPonderaciones;
            }
        }

        return $juecesConCalificaciones->count() > 0
            ? $sumaPromedios / $juecesConCalificaciones->count()
            : 0;
    }

    public function headings(): array
    {
        return [
            'Puesto',
            'Proyecto',
            'Equipo',
            'Estado',
            'Promedio',
            'Link Repositorio',
            'Fecha Creación',
        ];
    }

    public function map($row): array
    {
        return [
            $row['puesto'],
            $row['proyecto']->titulo,
            $row['proyecto']->equipo->nombre,
            $row['proyecto']->estado,
            number_format($row['promedio'], 2),
            $row['proyecto']->link_repositorio ?? 'N/A',
            $row['proyecto']->created_at->format('d/m/Y'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    public function title(): string
    {
        return 'Proyectos';
    }
}
