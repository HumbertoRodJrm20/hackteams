<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Equipo;
use App\Models\Evento;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProyectoController extends Controller
{
    /**
     * Muestra el formulario para registrar un nuevo proyecto para el equipo del usuario.
     */
    public function create()
    {
        $user = Auth::user();
        $participante = $user->participante;

        // 1. Verificar si el usuario es un participante
        if (! $participante) {
            return redirect()->route('dashboard')->with('error', 'Solo los participantes pueden registrar proyectos.');
        }

        // 2. Obtener el primer equipo del participante
        $equipo = Equipo::whereHas('participantes', function ($query) use ($participante) {
            $query->where('participantes.user_id', $participante->user_id);
        })->first();

        if (! $equipo) {
            return redirect()->route('equipos.index')->with('error', 'Debes estar en un equipo para registrar un proyecto.');
        }

        // 3. Obtener eventos activos (o próximos) para seleccionar
        $eventos = Evento::whereIn('estado', ['activo', 'proximo'])->get();

        // 4. Obtener todas las categorías disponibles
        $categorias = Categoria::all();

        // El líder del equipo (o cualquier miembro) puede registrar el proyecto
        return view('CrearProyecto', compact('equipo', 'eventos', 'categorias'));
    }

    /**
     * Almacena el nuevo proyecto en la base de datos.
     */
    public function show($id)
    {
        // Carga el proyecto y todas sus relaciones necesarias (eager loading)
        $proyecto = Proyecto::with([
            'equipo.participantes.user', // Para ver los miembros del equipo
            'evento',
            'avances' => function ($query) {
                // Ordenamos los avances por fecha más reciente primero
                $query->orderBy('fecha', 'desc');
            },
            'calificaciones', // Para mostrar el puntaje promedio
        ])->findOrFail($id); // Si no lo encuentra, Laravel lanza un 404

        // Opcional: Puedes agregar lógica de autorización aquí,
        // por ejemplo, solo permitir ver a miembros del equipo o jueces.

        return view('DetalleProyecto', compact('proyecto'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'evento_id' => 'required|exists:eventos,id',
            'categoria_id' => 'required|exists:categorias,id',
            'titulo' => 'required|string|max:255|unique:proyectos,titulo',
            'resumen' => 'required|string',
            'link_repositorio' => 'nullable|url|max:255',
            'imagen' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'documento' => 'nullable|mimes:pdf|max:10240',
        ]);

        $user = Auth::user();
        $participante = $user->participante;

        if (! $participante) {
            return redirect()->back()->with('error', 'Solo los participantes pueden registrar proyectos.');
        }

        // Obtener el equipo del participante
        $equipo = Equipo::whereHas('participantes', function ($query) use ($participante) {
            $query->where('participantes.user_id', $participante->user_id);
        })->first();

        if (! $equipo) {
            return redirect()->back()->with('error', 'Debes estar en un equipo para registrar un proyecto.');
        }

        try {
            // Manejar la subida de imagen si existe
            $imagenPath = null;
            if ($request->hasFile('imagen')) {
                $imagenPath = $request->file('imagen')->store('proyectos/imagenes', 'public');
            }

            // Manejar la subida de documento si existe
            $documentoPath = null;
            if ($request->hasFile('documento')) {
                $documentoPath = $request->file('documento')->store('proyectos/documentos', 'public');
            }

            Proyecto::create([
                'equipo_id' => $equipo->id,
                'evento_id' => $validatedData['evento_id'],
                'categoria_id' => $validatedData['categoria_id'],
                'titulo' => $validatedData['titulo'],
                'resumen' => $validatedData['resumen'],
                'link_repositorio' => $validatedData['link_repositorio'],
                'imagen_path' => $imagenPath,
                'documento_path' => $documentoPath,
                'estado' => 'pendiente', // Estado inicial
            ]);

            return redirect()->route('equipos.show', $equipo->id)
                ->with('success', '¡El proyecto "'.$validatedData['titulo'].'" se registró con éxito!');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error al guardar el proyecto: '.$e->getMessage());
        }
    }
}
