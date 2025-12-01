<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use Illuminate\Http\Request;

class EquipoController extends Controller
{
    /**
     * Muestra la lista de todos los equipos con sus proyectos y miembros.
     * Corresponde a la ruta GET /equipos.
     */
    public function index()
    {
        $equipos = Equipo::with('participantes', 'proyectos.evento')
            ->get()
            ->map(function ($equipo) {
                return [
                    'id' => $equipo->id,
                    'nombre' => $equipo->nombre,
                    'logo_path' => $equipo->logo_path,
                    'proyecto' => $equipo->proyectoActual()?->titulo ?? 'Sin proyecto',
                    'evento' => $equipo->proyectoActual()?->evento?->nombre ?? 'N/A',
                    'miembros' => $equipo->contarMiembros(),
                    'estado' => $equipo->proyectoActual()?->estado ?? 'pendiente'
                ];
            });

        return view('ListaEquipos', ['equipos' => $equipos]);
    }

    /**
     * Muestra el formulario para registrar un nuevo equipo.
     */
    public function create()
    {
        return view('RegistrarEquipo');
    }

    /**
     * Guarda un nuevo equipo en la base de datos.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|unique:equipos|max:255',
            'logo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $equipo = new Equipo($validated);

        if ($request->hasFile('logo_path')) {
            $path = $request->file('logo_path')->store('equipos', 'public');
            $equipo->logo_path = $path;
        }

        $equipo->save();

        return redirect()->route('equipos.index')
            ->with('success', 'Equipo creado exitosamente');
    }
}
