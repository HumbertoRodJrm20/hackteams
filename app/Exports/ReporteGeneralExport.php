<?php

namespace App\Exports;

use App\Models\Evento;
use App\Models\Participante;
use App\Models\Proyecto;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReporteGeneralExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new EventosSheet,
            new ProyectosSheet,
            new ParticipantesSheet,
        ];
    }
}

class EventosSheet implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    public function collection()
    {
        return Evento::withCount(['proyectos', 'participantes', 'equipos'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Estado',
            'Fecha Inicio',
            'Fecha Fin',
            'Participantes',
            'Equipos',
            'Proyectos',
        ];
    }

    public function map($evento): array
    {
        return [
            $evento->id,
            $evento->nombre,
            ucfirst($evento->estado),
            $evento->fecha_inicio->format('d/m/Y'),
            $evento->fecha_fin->format('d/m/Y'),
            $evento->participantes_count,
            $evento->equipos_count,
            $evento->proyectos_count,
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
        return 'Eventos';
    }
}

class ProyectosSheet implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $promediosCache = [];

    public function collection()
    {
        // Cargar todos los proyectos con sus relaciones de una sola vez
        $proyectos = Proyecto::with([
            'equipo',
            'evento',
            'calificaciones.criterio',
        ])->get();

        // Pre-calcular todos los promedios
        foreach ($proyectos as $proyecto) {
            $this->promediosCache[$proyecto->id] = $this->calcularPromedio($proyecto);
        }

        return $proyectos;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Título',
            'Equipo',
            'Evento',
            'Estado',
            'Promedio',
            'Link Repositorio',
        ];
    }

    public function map($proyecto): array
    {
        return [
            $proyecto->id,
            $proyecto->titulo,
            $proyecto->equipo->nombre,
            $proyecto->evento->nombre,
            ucfirst($proyecto->estado),
            number_format($this->promediosCache[$proyecto->id] ?? 0, 2),
            $proyecto->link_repositorio ?? 'N/A',
        ];
    }

    protected function calcularPromedio($proyecto)
    {
        $juecesConCalificaciones = $proyecto->calificaciones
            ->pluck('juez_user_id')
            ->unique();

        if ($juecesConCalificaciones->isEmpty()) {
            return 0;
        }

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

class ParticipantesSheet implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    public function collection()
    {
        return Participante::with(['user', 'carrera'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Email',
            'Carrera',
            'Matrícula',
        ];
    }

    public function map($participante): array
    {
        return [
            $participante->user_id,
            $participante->user->nombre ?? 'N/A',
            $participante->user->email ?? 'N/A',
            $participante->carrera->nombre ?? 'N/A',
            $participante->matricula ?? 'N/A',
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
        return 'Participantes';
    }
}
