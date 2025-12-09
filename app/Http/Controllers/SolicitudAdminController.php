<?php

namespace App\Http\Controllers;

use App\Models\SolicitudConstancia;
use Illuminate\Http\Request;

class SolicitudAdminController extends Controller
{
    // Mostrar tabla
    public function index(Request $request)
    {
        // Query base
        $query = SolicitudConstancia::with(['participante', 'evento']);

        // Búsqueda por nombre del participante
        if ($request->filled('search')) {
            $query->whereHas('participante', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // Filtro por evento
        if ($request->filled('evento_id')) {
            $query->where('evento_id', $request->evento_id);
        }

        // Filtro por estado
        if ($request->filled('estatus')) {
            $query->where('estatus', $request->estatus);
        }

        // Ordenar y paginar
        $solicitudes = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Obtener eventos para filtro
        $eventos = \App\Models\Evento::orderBy('nombre')->get();

        return view('admin.solicitudes.index', compact('solicitudes', 'eventos'));
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
