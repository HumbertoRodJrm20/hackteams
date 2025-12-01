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
use PDF; // Asegúrate de haber instalado DomPDF: composer require barryvdh/laravel-dompdf

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
        // Obtener el participante asociado al usuario logueado
        $participante = Auth::user()->participante;
        
        // Si no es un participante (ej. Juez, Admin), cargará una lista vacía
        if (!$participante) {
            $constancias = collect();
        } else {
            // Cargar todas las constancias generadas para este participante, incluyendo el evento asociado
            $constancias = Constancia::where('participante_id', $participante->id)
                                     ->with('evento')
                                     ->get();
        }
        
        // La vista de constancias está en Layout/Constancia.blade.php
        return view('Layout.Constancia', compact('constancias'));
    }

    /**
     * Permite al usuario descargar la constancia generada (el PDF).
     */
    public function downloadCertificate(Constancia $constancia)
    {
        // REGLA DE SEGURIDAD CRÍTICA:
        // Asegúrate de que el usuario logueado es el dueño de la constancia (o un admin).
        $esAdmin = Auth::user()->hasRole('admin'); // Debes tener un helper o método para verificar roles
        $esDueño = Auth::user()->participante && $constancia->participante_id === Auth::user()->participante->id;

        if (!$esAdmin && !$esDueño) {
            // Aborta si no tiene permisos
            abort(403, 'No tienes permiso para descargar este documento.');
        }

        // Descargar el archivo desde la ruta almacenada
        // 'public/' se refiere al disco configurado en filesystems.php (storage/app/public)
        if (Storage::exists("public/{$constancia->ruta_archivo}")) {
            return Storage::download("public/{$constancia->ruta_archivo}");
        }

        return back()->with('error', 'El archivo de constancia no fue encontrado.');
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

        return view('admin.constancias.gestion', compact('evento', 'participantes', 'constanciasGeneradas'));
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
        $path = "constancias/{$fileName}";

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