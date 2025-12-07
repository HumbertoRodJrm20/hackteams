<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Constancia - {{ $participante->user->name }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            background-color: #ffffff;
        }
        .container {
            width: 100%;
            border: 8px solid #0044AA;
            padding: 35px 30px 25px 30px;
            text-align: center;
            background-color: #ffffff;
        }
        .header {
            margin-bottom: 20px;
        }
        .header img {
            width: 100px;
            margin-bottom: 8px;
        }
        .header h1 {
            color: #0044AA;
            font-size: 1.6em;
            margin: 5px 0;
        }
        .title {
            color: #0044AA;
            font-size: 2em;
            font-weight: bold;
            margin: 20px 0 30px 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .body-text {
            font-size: 1em;
            margin-bottom: 25px;
            line-height: 1.7;
            color: #333;
        }
        .name {
            font-size: 1.8em;
            font-weight: bold;
            color: #0044AA;
            margin: 20px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .signature-area {
            margin-top: 50px;
        }
        .signature-table {
            width: 100%;
            margin-top: 40px;
        }
        .signature-cell {
            width: 50%;
            text-align: center;
            padding: 0 20px;
        }
        .signature-line {
            width: 70%;
            border-top: 2px solid #333;
            display: inline-block;
            margin: 0 auto;
        }
        .signature-label {
            font-size: 0.85em;
            margin-top: 8px;
            display: block;
            color: #555;
        }
        .footer {
            font-size: 0.7em;
            color: #999;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ public_path('images/HackTeams_Logo.png') }}" alt="HackTeams Logo">
            <h1>HackTeams</h1>
        </div>

        <div class="title">
            @if($tipo === 'lugar' && isset($lugar))
                @if($lugar == 1)
                    Constancia de Primer Lugar
                @elseif($lugar == 2)
                    Constancia de Segundo Lugar
                @elseif($lugar == 3)
                    Constancia de Tercer Lugar
                @endif
            @else
                Constancia de {{ $tipo === 'ganador' ? 'Reconocimiento' : 'Participación' }}
            @endif
        </div>

        <div class="body-text">
            <p>Se otorga la presente constancia a:</p>
            <p class="name">{{ $participante->user->name }}</p>

            @if($tipo === 'lugar' && isset($lugar))
                @if($lugar == 1)
                    <p>Por haber obtenido el <strong>PRIMER LUGAR</strong><br>
                    en el evento <strong>"{{ $evento->nombre }}"</strong>.</p>
                @elseif($lugar == 2)
                    <p>Por haber obtenido el <strong>SEGUNDO LUGAR</strong><br>
                    en el evento <strong>"{{ $evento->nombre }}"</strong>.</p>
                @elseif($lugar == 3)
                    <p>Por haber obtenido el <strong>TERCER LUGAR</strong><br>
                    en el evento <strong>"{{ $evento->nombre }}"</strong>.</p>
                @endif
            @elseif ($tipo === 'ganador')
                <p>Por haber obtenido un reconocimiento especial como <strong>GANADOR</strong><br>
                en el evento <strong>"{{ $evento->nombre }}"</strong>.</p>
            @else
                <p>Por su valiosa participación en el evento<br>
                <strong>"{{ $evento->nombre }}"</strong>.</p>
            @endif

            <p style="margin-top: 25px;">
                Evento realizado del {{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d/m/Y') }}
                al {{ \Carbon\Carbon::parse($evento->fecha_fin)->format('d/m/Y') }}.
            </p>
        </div>

        <div class="signature-area">
            <table class="signature-table">
                <tr>
                    <td class="signature-cell">
                        <span class="signature-line"></span>
                        <span class="signature-label">Director General</span>
                    </td>
                    <td class="signature-cell">
                        <span class="signature-line"></span>
                        <span class="signature-label">Coordinador del Evento</span>
                    </td>
                </tr>
            </table>
        </div>

        <div class="footer">
            Folio: {{ \Illuminate\Support\Str::upper(substr(\Illuminate\Support\Str::uuid(), 0, 8)) }} |
            Expedición: {{ \Carbon\Carbon::parse($evento->fecha_fin)->format('d/m/Y') }}
        </div>
    </div>
</body>
</html>