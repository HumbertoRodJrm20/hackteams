<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\SolicitudConstancia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SolicitudController extends Controller
{
    // Mostrar formulario
    public function create()
    {
        $eventos = Evento::all();
        return view('juez.constancias.solicitar', compact('eventos'));
    }

    // Guardar solicitud
    public function store(Request $request)
    {
        $request->validate([
            'evento_id' => 'required',
            'rol' => 'required',
            'fecha_evento' => 'required|date',
            'tipo' => 'required',
            'evidencia' => 'nullable|mimes:pdf,jpg,png,jpeg|max:2000',
        ]);

        // Guardar evidencia
        $path = null;
        if ($request->hasFile('evidencia')) {
            $path = $request->file('evidencia')->store('evidencias', 'public');
        }

        SolicitudConstancia::create([
            'participante_id' => Auth::id(),
            'evento_id' => $request->evento_id,
            'rol' => $request->rol,
            'fecha_evento' => $request->fecha_evento,
            'tipo' => $request->tipo,
            'motivo' => $request->motivo,
            'comentario' => $request->comentario,
            'evidencia_path' => $path,
            'estatus' => 'Pendiente',
        ]);

        return redirect()->route('dashboard')->with('success', 'Solicitud enviada correctamente');
    }
}
