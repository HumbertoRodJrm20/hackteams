<?php

// app/Http/Controllers/AvanceController.php

namespace App\Http\Controllers;

use App\Models\Avance;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvanceController extends Controller
{
    /**
     * Almacena un nuevo avance para un proyecto.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'proyecto_id' => 'required|exists:proyectos,id',
            'descripcion' => 'required|string',
            'fecha' => 'required|date',
            'archivo' => 'nullable|mimes:pdf|max:10240',
        ]);

        $user = Auth::user();
        $proyecto = Proyecto::findOrFail($validatedData['proyecto_id']);

        // 1. Verificar si el usuario pertenece al equipo del proyecto
        // Esta línea asume que ya definiste la relación en el modelo User/Participante
        $esMiembro = $proyecto->equipo->participantes->pluck('user_id')->contains($user->id);

        if (! $esMiembro) {
            return redirect()->back()->with('error', 'No tienes permiso para registrar avances en este proyecto.');
        }

        // 2. Manejar la subida de archivo si existe
        $archivoPath = null;
        if ($request->hasFile('archivo')) {
            $archivoPath = $request->file('archivo')->store('avances/archivos', 'public');
        }

        // 3. Crear el Avance
        Avance::create([
            'proyecto_id' => $proyecto->id,
            'descripcion' => $validatedData['descripcion'],
            'fecha' => $validatedData['fecha'],
            'archivo_path' => $archivoPath,
        ]);

        return redirect()->route('proyectos.show', $proyecto->id)->with('success', '¡Avance registrado con éxito!');
    }
}
