<?php

namespace App\Http\Controllers;

use App\Models\Constancia;
use App\Models\Evento;
use App\Models\Participante;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ConstanciaController extends Controller
{
    public function index()
    {
        $participante = Auth::user()->participante;

        $constancias = collect();

        if ($participante) {
            $constancias = \App\Models\Constancia::where('participante_id', $participante->user_id)
                ->with('evento')
                ->get();
        }

        return view('Constancia', compact('constancias'));
    }

    public function generarPDF($id)
    {
        $constancia = Constancia::with(['participante.user', 'evento'])
            ->findOrFail($id);

        $participante = $constancia->participante;
        $evento = $constancia->evento;
        $tipo = $constancia->tipo_constancia;
        $lugar = $constancia->lugar;

        $pdf = Pdf::loadView('pdf.constancia-estilo', compact('participante', 'evento', 'tipo', 'lugar'))
            ->setPaper('a4', 'portrait');

        $nombreArchivo = $tipo === 'lugar' && $lugar
            ? "constancia-lugar-{$lugar}-{$participante->user_id}.pdf"
            : "constancia-{$tipo}-{$participante->user_id}.pdf";

        return $pdf->download($nombreArchivo);
    }

    public function downloadCertificate(Constancia $constancia)
    {
        $ruta = storage_path('app/public/'.$constancia->ruta_archivo);

        if (! file_exists($ruta)) {
            return abort(404, 'El archivo no existe.');
        }

        return response()->download($ruta);
    }

    public function manageCertificates(Evento $evento)
    {
        $participantes = $evento->participantes()
            ->with(['user', 'equipo', 'rol'])
            ->get();

        $constanciasGeneradas = Constancia::where('evento_id', $evento->id)
            ->pluck('ruta_archivo', 'participante_id');

        return view('admin.constancia.gestion', compact('evento', 'participantes', 'constanciasGeneradas'));
    }

    public function generateCertificate(Participante $participante, Evento $evento)
    {
        $equipoGanador = $evento->equipo_ganador_id ?? null;
        $esGanador = ($equipoGanador && $participante->equipo_id === $equipoGanador);

        $tipo = $esGanador ? 'ganador' : 'participacion';

        $data = [
            'participante' => $participante->load('user', 'rol', 'equipo'),
            'evento' => $evento,
            'tipo' => $tipo,
        ];

        $pdf = Pdf::loadView('pdf.constancia', $data);

        $participanteNameSlug = Str::slug($participante->user->name);
        $eventNameSlug = Str::slug($evento->nombre);

        $fileName = "constancia-{$participanteNameSlug}-{$eventNameSlug}-{$tipo}.pdf";
        $path = "constancia/{$fileName}";

        Storage::put("public/{$path}", $pdf->output());

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

        $constancias = \App\Models\Constancia::where('participante_id', $juez->id)
            ->with('evento')
            ->get();

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

        $pdf = Pdf::loadView(
            'pdf.constancia-juez',
            compact('juez', 'evento', 'tipo')
        );

        return $pdf->download("constancia-juez-$juez->id.pdf");
    }
}
