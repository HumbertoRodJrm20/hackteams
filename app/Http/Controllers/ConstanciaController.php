<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // Para generar slugs para el nombre del archivo
use App\Models\Constancia;
use App\Models\Evento;
use App\Models\Participante;
use App\Models\Equipo; // Lo necesitas para la lógica de ganador
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
        // 1. Inicializar $constancias antes de los bloques if/else
        $constancias = collect(); 

        $participante = Auth::user()->participante;
        
        // La variable que contiene la COPIA (la colección) debe ser plural: $constancias
        if ($participante) {
            // Cargar todas las constancias generadas para este participante, incluyendo el evento asociado
            $constancias = Constancia::where('participante_id', $participante->user_id) // 🚨 CORREGIDO: Usamos user_id si es primary key
                                     ->with('evento')
                                     ->get();
        }

        // 🚨 ENVIAMOS LA COLECCIÓN PLURAL $constancias
        return view('Constancia', compact('constancias'));
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
        // ESTO ES CLAVE: Asumo que en la tabla 'calificaciones' o en 'equipo' puedes determinar esto.
        // Opción 1 (Simple): Revisa si el equipo del participante es el equipo ganador del evento.
        // Necesitarás un campo 'equipo_ganador_id' en la tabla 'eventos' o 'proyecto'.
        $equipoGanador = $evento->equipo_ganador_id ?? null; // Si tienes este campo en eventos
        $esGanador = ($equipoGanador && $participante->equipo_id === $equipoGanador);
        
        // Si no tienes el campo, necesitas una lógica de consulta más compleja aquí.

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
}