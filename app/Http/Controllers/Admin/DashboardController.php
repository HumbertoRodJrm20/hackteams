<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Calificacion;
use App\Models\Constancia;
use App\Models\Equipo;
use App\Models\Evento;
use App\Models\Participante;
use App\Models\Proyecto;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function index()
    {
        // Estadísticas generales
        $totalEventos = Evento::count();
        $eventosActivos = Evento::where('estado', 'activo')->count();
        $totalParticipantes = Participante::count();
        $totalProyectos = Proyecto::count();
        $totalEquipos = Equipo::count();
        $totalJueces = User::whereHas('roles', function ($query) {
            $query->where('nombre', 'juez');
        })->count();
        $totalCalificaciones = Calificacion::count();
        $totalConstancias = Constancia::count();

        // Eventos con estadísticas
        $eventos = Evento::withCount(['proyectos', 'participantes', 'equipos'])
            ->with('criterios')
            ->orderBy('fecha_inicio', 'desc')
            ->get()
            ->map(function ($evento) {
                $calificaciones = Calificacion::whereHas('proyecto', function ($q) use ($evento) {
                    $q->where('evento_id', $evento->id);
                })->count();

                return [
                    'id' => $evento->id,
                    'nombre' => $evento->nombre,
                    'estado' => $evento->estado,
                    'fecha_inicio' => $evento->fecha_inicio,
                    'fecha_fin' => $evento->fecha_fin,
                    'participantes' => $evento->participantes_count,
                    'equipos' => $evento->equipos_count,
                    'proyectos' => $evento->proyectos_count,
                    'calificaciones' => $calificaciones,
                    'criterios' => $evento->criterios->count(),
                ];
            });

        // Proyectos por estado
        $proyectosPorEstado = Proyecto::select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->estado => $item->total];
            });

        // Top 10 proyectos mejor calificados (global)
        $proyectos = Proyecto::with(['equipo', 'evento', 'calificaciones.criterio'])
            ->get();

        // Calcular promedios de forma optimizada
        $proyectosConPromedio = $proyectos
            ->filter(function ($proyecto) {
                // Filtrar proyectos sin equipo o evento
                return $proyecto->equipo !== null && $proyecto->evento !== null;
            })
            ->map(function ($proyecto) {
                return [
                    'id' => $proyecto->id,
                    'titulo' => $proyecto->titulo,
                    'equipo' => $proyecto->equipo->nombre,
                    'evento' => $proyecto->evento->nombre,
                    'promedio' => $this->calcularPromedioProyecto($proyecto),
                    'evento_id' => $proyecto->evento_id,
                ];
            })->filter(function ($proyecto) {
                return $proyecto['promedio'] > 0;
            });

        // Agrupar por evento y calcular puestos
        $proyectosPorEvento = $proyectosConPromedio->groupBy('evento_id');

        $proyectosConPuesto = collect();
        foreach ($proyectosPorEvento as $eventoId => $proyectosEvento) {
            $ordenados = $proyectosEvento->sortByDesc('promedio')->values();
            foreach ($ordenados as $index => $proyecto) {
                $proyecto['puesto'] = $index + 1;
                $proyectosConPuesto->push($proyecto);
            }
        }

        $topProyectos = $proyectosConPuesto
            ->sortByDesc('promedio')
            ->take(10)
            ->values();

        // Participación por evento (para gráfica comparativa)
        $participacionPorEvento = Evento::withCount(['participantes', 'proyectos'])
            ->orderBy('fecha_inicio', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($evento) {
                return [
                    'nombre' => $evento->nombre,
                    'participantes' => $evento->participantes_count,
                    'proyectos' => $evento->proyectos_count,
                ];
            });

        // Eventos por estado
        $eventosPorEstado = Evento::select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->estado => $item->total];
            });

        // Participación por carrera (si existe la relación)
        try {
            $participantesPorCarrera = Participante::with('carrera')
                ->get()
                ->filter(function ($participante) {
                    return $participante->carrera !== null;
                })
                ->groupBy(function ($participante) {
                    return $participante->carrera->nombre ?? 'Sin Carrera';
                })
                ->map(function ($group) {
                    return $group->count();
                })
                ->sortDesc()
                ->take(10);
        } catch (\Exception $e) {
            $participantesPorCarrera = collect();
        }

        return view('admin.dashboard', compact(
            'totalEventos',
            'eventosActivos',
            'totalParticipantes',
            'totalProyectos',
            'totalEquipos',
            'totalJueces',
            'totalCalificaciones',
            'totalConstancias',
            'eventos',
            'proyectosPorEstado',
            'topProyectos',
            'participacionPorEvento',
            'eventosPorEstado',
            'participantesPorCarrera'
        ));
    }

    public function exportPdf(Request $request)
    {
        $evento_id = $request->get('evento_id');

        if ($evento_id) {
            // Reporte de un evento específico
            $evento = Evento::with(['proyectos.equipo', 'criterios', 'participantes'])->findOrFail($evento_id);

            $proyectos = $evento->proyectos
                ->filter(function ($proyecto) {
                    return $proyecto->equipo !== null;
                })
                ->map(function ($proyecto) {
                    return [
                        'titulo' => $proyecto->titulo,
                        'equipo' => $proyecto->equipo->nombre,
                        'estado' => $proyecto->estado,
                        'promedio' => $proyecto->obtenerPromedio(),
                        'puesto' => $proyecto->obtenerPuesto(),
                    ];
                })->sortBy('puesto');

            $data = [
                'evento' => $evento,
                'proyectos' => $proyectos,
                'fecha' => now()->format('d/m/Y H:i'),
            ];

            $pdf = Pdf::loadView('pdf.reporte-evento', $data);

            return $pdf->download('reporte-evento-'.$evento->id.'.pdf');
        } else {
            // Reporte general
            $data = $this->getReporteGeneral();
            $pdf = Pdf::loadView('pdf.reporte-general', $data);

            return $pdf->download('reporte-general-'.now()->format('Y-m-d').'.pdf');
        }
    }

    public function exportExcel(Request $request)
    {
        $evento_id = $request->get('evento_id');

        if ($evento_id) {
            // Exportar un evento específico
            $evento = Evento::findOrFail($evento_id);

            return Excel::download(
                new \App\Exports\EventoExport($evento_id),
                'reporte-evento-'.$evento->id.'.xlsx'
            );
        } else {
            // Exportar reporte general
            return Excel::download(
                new \App\Exports\ReporteGeneralExport,
                'reporte-general-'.now()->format('Y-m-d').'.xlsx'
            );
        }
    }

    private function getReporteGeneral(): array
    {
        return [
            'totalEventos' => Evento::count(),
            'totalParticipantes' => Participante::count(),
            'totalProyectos' => Proyecto::count(),
            'totalEquipos' => Equipo::count(),
            'eventos' => Evento::with(['proyectos', 'participantes'])->get(),
            'fecha' => now()->format('d/m/Y H:i'),
        ];
    }

    private function calcularPromedioProyecto($proyecto)
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
}
