<?php

namespace App\Http\Controllers;

use App\Models\SolicitudConstancia;
use Illuminate\Http\Request;

class SolicitudAdminController extends Controller
{
    // Mostrar tabla
    public function index()
    {
        $solicitudes = SolicitudConstancia::with(['participante', 'evento'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.solicitudes.index', compact('solicitudes'));
    }

    // Aprobar solicitud
    public function aprobar($id)
    {
        // Obtener la solicitud
        $solicitud = SolicitudConstancia::findOrFail($id);

        // Marcar como aprobada
        $solicitud->estatus = 'Aprobado';
        $solicitud->save();

        // Normalizar el tipo para que cumpla con el enum de la tabla constancias
        $tipo = strtolower($solicitud->tipo ?? 'asistente'); // minúsculas por seguridad
        $valoresPermitidos = ['asistente', 'ponente', 'ganador'];

        // Si el tipo no está permitido, se asigna 'asistente' por default
        if (! in_array($tipo, $valoresPermitidos)) {
            $tipo = 'asistente';
        }

        // Crear constancia
        \App\Models\Constancia::create([
            'participante_id' => $solicitud->participante_id,
            'evento_id' => $solicitud->evento_id,
            'tipo' => $tipo,
            'archivo_path' => 'pendiente',   // luego se reemplaza cuando se genere el PDF
            'codigo_qr' => \Illuminate\Support\Str::uuid(),
        ]);

        return back()->with('success', 'Solicitud aprobada y constancia creada.');
    }

    // Rechazar solicitud
    public function rechazar(Request $request, $id)
    {
        $solicitud = SolicitudConstancia::findOrFail($id);
        $solicitud->estatus = 'Rechazado';
        $solicitud->comentario = $request->comentario_rechazo;
        $solicitud->save();

        return back()->with('error', 'Solicitud rechazada.');
    }
}
