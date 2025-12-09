<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EventoController extends Controller
{
    /**
     * Muestra la lista de todos los eventos.
     */
    public function index(Request $request)
    {
        // Query base
        $query = Evento::query();

        // Búsqueda por nombre
        if ($request->filled('search')) {
            $query->where('nombre', 'like', '%' . $request->search . '%');
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Ordenar y paginar
        $eventos = $query->orderBy('fecha_inicio', 'desc')->paginate(12)->withQueryString();

        return view('Eventos', compact('eventos'));
    }

    /**
     * Muestra el formulario para crear un nuevo evento.
     */
    public function create()
    {
        // Nota: Se debe proteger esta ruta con un middleware 'admin'
        $categorias = Categoria::all();

        return view('CrearEvento', compact('categorias'));
    }

    /**
     * Almacena un nuevo evento en la base de datos.
     */
    public function store(Request $request)
    {
        // Nota: Se debe proteger esta ruta con un middleware 'admin'
        try {
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:255|unique:eventos,nombre',
                'descripcion' => 'required|string',
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
                'max_equipos' => 'required|integer|min:1',
                'categoria_id' => 'nullable|exists:categorias,id',
                'imagen' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            ]);

            // Calcular estado automáticamente basándose en las fechas
            $fechaInicio = \Carbon\Carbon::parse($validatedData['fecha_inicio']);
            $fechaFin = \Carbon\Carbon::parse($validatedData['fecha_fin']);
            $hoy = \Carbon\Carbon::now();

            if ($hoy->lt($fechaInicio)) {
                $validatedData['estado'] = 'proximo';
            } elseif ($hoy->between($fechaInicio, $fechaFin)) {
                $validatedData['estado'] = 'activo';
            } else {
                $validatedData['estado'] = 'finalizado';
            }

            // Manejar la carga de imagen si existe
            if ($request->hasFile('imagen')) {
                $imagenPath = $request->file('imagen')->store('eventos', 'public');
                $validatedData['imagen'] = $imagenPath;
            }

            $evento = Evento::create($validatedData);

            return redirect()->route('eventos.index')->with('success', '¡El evento "'.$evento->nombre.'" ha sido creado con éxito!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Error al crear evento: '.$e->getMessage());

            return redirect()->back()
                ->with('error', 'Hubo un error al crear el evento: '.$e->getMessage())
                ->withInput();
        }
    }

    /**
     * Muestra el detalle de un evento específico (Infeventos.blade.php).
     */
    public function show(Evento $evento)
    {
        // Route Model Binding inyecta la instancia de Evento automáticamente
        return view('Infeventos', compact('evento'));
    }

    /**
     * Muestra el formulario para editar un evento existente.
     */
    public function edit(Evento $evento)
    {
        // Nota: Se debe proteger esta ruta con un middleware 'admin'
        $categorias = Categoria::all();

        return view('EditarEvento', compact('evento', 'categorias'));
    }

    /**
     * Actualiza un evento existente en la base de datos.
     */
    public function update(Request $request, Evento $evento)
    {
        // Nota: Se debe proteger esta ruta con un middleware 'admin'
        $validatedData = $request->validate([
            'nombre' => ['required', 'string', 'max:255', Rule::unique('eventos', 'nombre')->ignore($evento->id)],
            'descripcion' => 'required|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'max_equipos' => 'required|integer|min:1',
            'categoria_id' => 'nullable|exists:categorias,id',
            'imagen' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        // Calcular estado automáticamente basándose en las fechas
        $fechaInicio = \Carbon\Carbon::parse($validatedData['fecha_inicio']);
        $fechaFin = \Carbon\Carbon::parse($validatedData['fecha_fin']);
        $hoy = \Carbon\Carbon::now();

        if ($hoy->lt($fechaInicio)) {
            $validatedData['estado'] = 'proximo';
        } elseif ($hoy->between($fechaInicio, $fechaFin)) {
            $validatedData['estado'] = 'activo';
        } else {
            $validatedData['estado'] = 'finalizado';
        }

        // Manejar la carga de imagen si existe
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($evento->imagen && \Storage::disk('public')->exists($evento->imagen)) {
                \Storage::disk('public')->delete($evento->imagen);
            }

            $imagenPath = $request->file('imagen')->store('eventos', 'public');
            $validatedData['imagen'] = $imagenPath;
        }

        $evento->update($validatedData);

        return redirect()->route('eventos.show', $evento->id)->with('success', '¡El evento "'.$evento->nombre.'" ha sido actualizado con éxito!');
    }

    /**
     * Elimina un evento de la base de datos.
     */
    public function destroy(Evento $evento)
    {
        // Nota: Se debe proteger esta ruta con un middleware 'admin'
        $nombre = $evento->nombre;
        $evento->delete();

        return redirect()->route('eventos.index')->with('success', 'El evento "'.$nombre.'" ha sido eliminado exitosamente.');
    }

    /**
     * Permite que un participante se una a un evento.
     */
    public function join(Evento $evento)
    {
        $user = Auth::user();
        $participante = $user->participante;

        if (! $participante) {
            return redirect()->route('eventos.show', $evento->id)
                ->with('error', 'Debes estar registrado como participante para unirte a un evento.');
        }

        // Validar estado del evento
        if ($evento->estado === 'finalizado') {
            return redirect()->route('eventos.show', $evento->id)
                ->with('error', 'No puedes unirte a un evento que ya ha finalizado.');
        }

        // Verificar si ya está unido
        if ($evento->hasParticipante($user->id)) {
            return redirect()->route('eventos.show', $evento->id)
                ->with('info', 'Ya estás registrado en este evento.');
        }

        // Agregar participante al evento
        $evento->participantes()->attach($participante->user_id);

        return redirect()->route('eventos.show', $evento->id)
            ->with('success', 'Te has unido exitosamente al evento "'.$evento->nombre.'".');
    }

    /**
     * Permite que un participante abandone un evento.
     */
    public function leave(Evento $evento)
    {
        $user = Auth::user();
        $participante = $user->participante;

        if (! $participante) {
            return redirect()->route('eventos.index')
                ->with('error', 'Error al abandonar el evento.');
        }

        $evento->participantes()->detach($participante->user_id);

        return redirect()->route('eventos.index')
            ->with('success', 'Te has salido del evento "'.$evento->nombre.'".');
    }
}
