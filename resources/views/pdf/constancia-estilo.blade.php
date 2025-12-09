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
            font-family: 'Georgia', 'Times New Roman', serif;
            background-color: #ffffff;
        }
        .certificate-wrapper {
            border: 10px solid #1e40af;
            border-style: double;
            padding: 25px;
            min-height: 260mm;
        }
        .inner-wrapper {
            border: 2px solid #3b82f6;
            padding: 30px 40px;
            text-align: center;
        }
        .header {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #1e40af;
        }
        .header img {
            width: 100px;
            margin-bottom: 12px;
        }
        .header h1 {
            color: #1e40af;
            font-size: 28px;
            margin: 0;
            font-weight: bold;
            letter-spacing: 4px;
            font-family: 'Arial', sans-serif;
        }
        .ornament {
            text-align: center;
            margin: 15px 0;
        }
        .ornament-line {
            display: inline-block;
            width: 80px;
            height: 3px;
            background-color: #3b82f6;
            vertical-align: middle;
        }
        .ornament-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            background-color: #1e40af;
            border-radius: 50%;
            margin: 0 10px;
            vertical-align: middle;
        }
        .title {
            color: #1e3a8a;
            font-size: 32px;
            font-weight: bold;
            margin: 25px 0;
            text-transform: uppercase;
            letter-spacing: 5px;
            font-family: 'Arial', sans-serif;
        }
        .subtitle {
            font-size: 16px;
            color: #4b5563;
            margin-bottom: 25px;
            font-style: italic;
        }
        .name-box {
            margin: 30px auto;
            padding: 20px 30px;
            border-top: 3px solid #1e40af;
            border-bottom: 3px solid #1e40af;
            max-width: 80%;
        }
        .name {
            font-size: 36px;
            font-weight: bold;
            color: #1e40af;
            text-transform: uppercase;
            letter-spacing: 3px;
            font-family: 'Arial', sans-serif;
            margin: 0;
        }
        .body-content {
            font-size: 16px;
            line-height: 1.8;
            color: #1f2937;
            margin: 25px 0;
        }
        .body-content p {
            margin: 12px 0;
        }
        .event-name {
            font-weight: bold;
            color: #1e40af;
            font-size: 18px;
            margin: 15px 0;
        }
        .badge {
            display: inline-block;
            padding: 8px 20px;
            background-color: #1e40af;
            color: white;
            border-radius: 25px;
            font-size: 14px;
            margin: 10px 0;
            font-weight: bold;
            letter-spacing: 2px;
            font-family: 'Arial', sans-serif;
        }
        .date-info {
            font-size: 14px;
            color: #6b7280;
            margin-top: 25px;
        }
        .signatures {
            margin-top: 60px;
        }
        .signatures table {
            width: 100%;
            border-collapse: collapse;
        }
        .signatures td {
            width: 50%;
            text-align: center;
            padding: 0 20px;
        }
        .signature-line {
            border-top: 2px solid #1e40af;
            width: 70%;
            margin: 0 auto 10px auto;
        }
        .signature-label {
            font-size: 13px;
            color: #374151;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-family: 'Arial', sans-serif;
        }
        .footer {
            font-size: 11px;
            color: #6b7280;
            margin-top: 40px;
            padding-top: 15px;
            border-top: 2px solid #e5e7eb;
            font-family: 'Arial', sans-serif;
        }
    </style>
</head>
<body>
    <div class="certificate-wrapper">
        <div class="inner-wrapper">
            <div class="header">
                <img src="{{ public_path('images/HackTeams_Logo.png') }}" alt="HackTeams Logo">
                <h1>HACKTEAMS</h1>
            </div>

            <div class="ornament">
                <span class="ornament-line"></span>
                <span class="ornament-dot"></span>
                <span class="ornament-line"></span>
            </div>

            <div class="title">
                @if($tipo === 'lugar' && isset($lugar))
                    @if($lugar == 1)
                        PRIMER LUGAR
                    @elseif($lugar == 2)
                        SEGUNDO LUGAR
                    @elseif($lugar == 3)
                        TERCER LUGAR
                    @endif
                @else
                    {{ $tipo === 'ganador' ? 'RECONOCIMIENTO' : 'CONSTANCIA DE PARTICIPACIÓN' }}
                @endif
            </div>

            <div class="ornament">
                <span class="ornament-line"></span>
                <span class="ornament-dot"></span>
                <span class="ornament-line"></span>
            </div>

            <p class="subtitle">Se otorga la presente constancia a:</p>

            <div class="name-box">
                <p class="name">{{ strtoupper($participante->user->name) }}</p>
            </div>

            <div class="body-content">
                @if($tipo === 'lugar' && isset($lugar))
                    <p>Por haber obtenido el</p>
                    <span class="badge">
                        @if($lugar == 1)
                            PRIMER LUGAR
                        @elseif($lugar == 2)
                            SEGUNDO LUGAR
                        @elseif($lugar == 3)
                            TERCER LUGAR
                        @endif
                    </span>
                    <p>en el evento</p>
                    <p class="event-name">"{{ $evento->nombre }}"</p>
                @elseif ($tipo === 'ganador')
                    <p>Por haber obtenido un</p>
                    <span class="badge">RECONOCIMIENTO ESPECIAL</span>
                    <p>en el evento</p>
                    <p class="event-name">"{{ $evento->nombre }}"</p>
                @else
                    <p>Por su destacada participación en el evento</p>
                    <p class="event-name">"{{ $evento->nombre }}"</p>
                @endif

                <p class="date-info">
                    Evento realizado del {{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d/m/Y') }}
                    al {{ \Carbon\Carbon::parse($evento->fecha_fin)->format('d/m/Y') }}
                </p>
            </div>

            <div class="signatures">
                <table>
                    <tr>
                        <td>
                            <div class="signature-line"></div>
                            <p class="signature-label">Director General</p>
                        </td>
                        <td>
                            <div class="signature-line"></div>
                            <p class="signature-label">Coordinador del Evento</p>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="footer">
                <strong>Folio:</strong> {{ \Illuminate\Support\Str::upper(substr(\Illuminate\Support\Str::uuid(), 0, 8)) }}
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <strong>Expedición:</strong> {{ \Carbon\Carbon::parse($evento->fecha_fin)->format('d/m/Y') }}
            </div>
        </div>
    </div>
</body>
</html>