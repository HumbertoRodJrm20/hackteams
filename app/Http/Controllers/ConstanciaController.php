<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Constancia;
use App\Models\Evento;
use App\Models\Participante;
use App\Models\Equipo;
use Barryvdh\DomPDF\Facade\Pdf;

class ConstanciaController extends Controller
{
    // ====================================================================
    // 1. RUTAS DEL PARTICIPANTE
    // ====================================================================

    /**
     * Muestra la vista 'Mis Constancias y Certificados' (Participante).
     */
    public function index()
{
    $participante = Auth::user()->participante;

    // Constancias generadas del participante
    $constancias = collect();

    if ($participante) {
        $constancias = \App\Models\Constancia::where('participante_id', $participante->user_id)
                            ->with('evento')
                            ->get();
    }

    // Solicitudes hechas por el participante
    $solicitudes = \App\Models\SolicitudConstancia::where('participante_id', Auth::id())
                    ->with('evento')
                    ->orderBy('created_at', 'desc')
                    ->get();

    return view('Constancia', compact('constancias', 'solicitudes'));
}


    public function generarPDF($id)
    {
        // Cargar la constancia REAL
        $constancia = Constancia::with(['participante.user', 'evento'])
            ->findOrFail($id);

        $participante = $constancia->participante;
        $evento = $constancia->evento;
        $tipo = $constancia->tipo_constancia;

        // Generar PDF
        $pdf = Pdf::loadView('pdf.constancia-estilo', compact('participante', 'evento', 'tipo'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download("constancia-$participante->user_id.pdf");
    }

    /**
     * Permite al usuario descargar la constancia generada (el PDF).
     */
    public function downloadCertificate(Constancia $constancia)
    {
        $ruta = storage_path('app/public/' . $constancia->ruta_archivo);

        if (!file_exists($ruta)) {
            return abort(404, "El archivo no existe.");
        }
    return response()->download($ruta);
    }

    // ====================================================================
    // 2. RUTAS DEL ADMINISTRADOR
    // ====================================================================

    /**
     * Vista de gestión de constancias para un evento específico (Admin).
     */
    public function manageCertificates(Evento $evento)
    {
        // Cargar todos los participantes del evento, con su equipo y rol para la vista
        $participantes = $evento->participantes()
                                ->with(['user', 'equipo', 'rol'])
                                ->get();
        
        // Obtener las constancias ya generadas para el evento
        $constanciasGeneradas = Constancia::where('evento_id', $evento->id)
                                          ->pluck('ruta_archivo', 'participante_id'); // [participante_id => ruta]
        return view('admin.constancia.gestion', compact('evento', 'participantes', 'constanciasGeneradas'));
    }

    /**
     * Genera el PDF, lo guarda y registra la Constancia en la DB (Admin).
     */
    public function generateCertificate(Participante $participante, Evento $evento)
    {
        // 1. Lógica para determinar si es ganador
        $equipoGanador = $evento->equipo_ganador_id ?? null;
        $esGanador = ($equipoGanador && $participante->equipo_id === $equipoGanador);
        
        $tipo = $esGanador ? 'ganador' : 'participacion';

        // 2. Generar el PDF
        $data = [
            'participante' => $participante->load('user', 'rol', 'equipo'),
            'evento' => $evento,
            'tipo' => $tipo,
        ];

        $pdf = PDF::loadView('pdf.constancia', $data);

        // 3. Guardar el archivo
        $participanteNameSlug = Str::slug($participante->user->name);
        $eventNameSlug = Str::slug($evento->nombre);

        $fileName = "constancia-{$participanteNameSlug}-{$eventNameSlug}-{$tipo}.pdf";
        $path = "constancia/{$fileName}";

        // Guarda el archivo en storage/app/public/constancias/
        Storage::put("public/{$path}", $pdf->output());

        // 4. Guardar o actualizar el registro en la base de datos
        Constancia::updateOrCreate(
            ['participante_id' => $participante->id, 'evento_id' => $evento->id],
            [
                'tipo_constancia' => $tipo,
                'ruta_archivo' => $path,
                'generada_por_admin' => Auth::id(),
            ]
        );

        return back()->with('success', "Constancia de {$tipo} generada para {$participante->user->name}.");
    }

    public function indexJuez()
{
    $juez = auth()->user();

    // Buscar constancias donde participante_id = juez_id
    $constancias = \App\Models\Constancia::where('participante_id', $juez->id)->get();

    return view('juez.constancias.index', compact('constancias'));
}


public function generarPDFJuez($id)
{
    $constancia = \App\Models\Constancia::where('id', $id)
        ->where('participante_id', auth()->id())
        ->firstOrFail();

    $juez = auth()->user();
    $evento = $constancia->evento;
    $tipo = $constancia->tipo;

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
        'pdf.constancia-juez',
        compact('juez', 'evento', 'tipo')
    );

    return $pdf->download("constancia-juez-$juez->id.pdf");
}

}