<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Constancia - {{ $participante->user->name }}</title>
    <style>
        @page {
            size: A4 landscape; /* Formato horizontal */
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }
        .container {
            width: 90%;
            height: 90%;
            margin: 5% auto;
            border: 5px solid #0044AA; /* Borde del HackTeams */
            padding: 20px;
            text-align: center;
        }
        .title {
            color: #0044AA;
            font-size: 2em;
            margin-bottom: 30px;
        }
        .name {
            font-size: 2.5em;
            font-weight: bold;
            color: #0044AA;
            margin: 15px 0;
        }
        .body-text {
            font-size: 1.2em;
            margin-bottom: 40px;
            line-height: 1.6;
        }
        .signature-area {
            margin-top: 60px;
            /* Usar tabla para layout seguro en DomPDF */
        }
        .signature-line {
            width: 30%;
            margin: 0 5%;
            border-top: 1px solid #333;
            display: inline-block;
        }
        .signature-label {
            font-size: 0.9em;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            {{-- Puedes usar la ruta absoluta o base64 para imágenes en DomPDF --}}
            <img src="{{ public_path('images/hackteams_logo.png') }}" style="width: 150px; margin-bottom: 20px;">
            <h1>HackTeams</h1>
        </div>

        <div class="title">
            CONSTANCIA DE {{ strtoupper($tipo) }}
        </div>

        <div class="body-text">
            Se otorga la presente constancia a:
            <p class="name">
                {{ $participante->user->name }}
            </p>

            @if ($tipo === 'ganador')
                <p>Por haber obtenido un reconocimiento especial como **GANADOR** en el evento **"{{ $evento->nombre }}"**.</p>
            @else
                <p>Por su valiosa participación como **{{ $participante->rol->nombre ?? 'Participante' }}** en el evento **"{{ $evento->nombre }}"**.</p>
            @endif

            <p>Evento realizado por Innovatec del {{ $evento->fecha_inicio }} al {{ $evento->fecha_fin }}.</p>
        </div>

        <div class="signature-area">
            <table style="width: 100%; margin-top: 50px;">
                <tr>
                    <td style="width: 50%; text-align: center;">
                        <span class="signature-line"></span><br>
                        <span class="signature-label">Jefe de Departamento / Director</span>
                    </td>
                    <td style="width: 50%; text-align: center;">
                        <span class="signature-line"></span><br>
                        <span class="signature-label">Coordinador del Evento</span>
                    </td>
                </tr>
            </table>
        </div>
        <p style="margin-top: 40px; font-size: 0.8em; color: #999;">
            Folio: {{ \Illuminate\Support\Str::uuid() }} - Expedición: {{ \Carbon\Carbon::now()->format('d/m/Y') }}
        </p>
    </div>
</body>
</html>