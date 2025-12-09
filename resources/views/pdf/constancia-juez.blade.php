<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Constancia de Juez</title>
    <style>
        @page {
            size: A4 landscape;
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
            padding: 20px;
            min-height: 185mm;
        }
        .inner-wrapper {
            border: 2px solid #3b82f6;
            padding: 25px 35px;
            text-align: center;
        }
        .header {
            margin-bottom: 18px;
            padding-bottom: 12px;
            border-bottom: 3px solid #1e40af;
        }
        .header img {
            width: 85px;
            margin-bottom: 10px;
        }
        .header h1 {
            color: #1e40af;
            font-size: 24px;
            margin: 0;
            font-weight: bold;
            letter-spacing: 4px;
            font-family: 'Arial', sans-serif;
        }
        .ornament {
            text-align: center;
            margin: 12px 0;
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
            font-size: 28px;
            font-weight: bold;
            margin: 20px 0;
            text-transform: uppercase;
            letter-spacing: 5px;
            font-family: 'Arial', sans-serif;
        }
        .subtitle {
            font-size: 15px;
            color: #4b5563;
            margin-bottom: 20px;
            font-style: italic;
        }
        .name-box {
            margin: 25px auto;
            padding: 18px 25px;
            border-top: 3px solid #1e40af;
            border-bottom: 3px solid #1e40af;
            max-width: 70%;
        }
        .name {
            font-size: 32px;
            font-weight: bold;
            color: #1e40af;
            text-transform: uppercase;
            letter-spacing: 3px;
            font-family: 'Arial', sans-serif;
            margin: 0;
        }
        .body-content {
            font-size: 15px;
            line-height: 1.7;
            color: #1f2937;
            margin: 20px 0;
        }
        .body-content p {
            margin: 10px 0;
        }
        .event-name {
            font-weight: bold;
            color: #1e40af;
            font-size: 17px;
            margin: 12px 0;
        }
        .badge {
            display: inline-block;
            padding: 7px 18px;
            background-color: #1e40af;
            color: white;
            border-radius: 25px;
            font-size: 13px;
            margin: 8px 0;
            font-weight: bold;
            letter-spacing: 2px;
            font-family: 'Arial', sans-serif;
        }
        .date-info {
            font-size: 13px;
            color: #6b7280;
            margin-top: 20px;
        }
        .signatures {
            margin-top: 45px;
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
            width: 65%;
            margin: 0 auto 8px auto;
        }
        .signature-label {
            font-size: 12px;
            color: #374151;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-family: 'Arial', sans-serif;
        }
        .footer {
            font-size: 10px;
            color: #6b7280;
            margin-top: 35px;
            padding-top: 12px;
            border-top: 2px solid #e5e7eb;
            font-family: 'Arial', sans-serif;
        }
    </style>
</head>
<body>
    <div class="certificate-wrapper">
        <div class="inner-wrapper">
            <!-- Header -->
            <div class="header">
                <img src="{{ public_path('images/HackTeams_Logo.png') }}" alt="HackTeams Logo">
                <h1>HACKTEAMS</h1>
            </div>

            <!-- Ornamento decorativo -->
            <div class="ornament">
                <span class="ornament-line"></span>
                <span class="ornament-dot"></span>
                <span class="ornament-line"></span>
            </div>

            <!-- Título -->
            <div class="title">
                CONSTANCIA DE {{ strtoupper($tipo) }}
            </div>

            <!-- Ornamento decorativo -->
            <div class="ornament">
                <span class="ornament-line"></span>
                <span class="ornament-dot"></span>
                <span class="ornament-line"></span>
            </div>

            <!-- Subtítulo -->
            <p class="subtitle">Se otorga la presente constancia a:</p>

            <!-- Nombre del juez -->
            <div class="name-box">
                <p class="name">{{ strtoupper($juez->name) }}</p>
            </div>

            <!-- Contenido -->
            <div class="body-content">
                <p>Por su destacada participación como</p>
                <span class="badge">JUEZ EVALUADOR</span>
                <p>en el evento</p>
                <p class="event-name">"{{ $evento->nombre }}"</p>

                <p class="date-info">
                    Evento realizado del {{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d/m/Y') }}
                    al {{ \Carbon\Carbon::parse($evento->fecha_fin)->format('d/m/Y') }}
                </p>
            </div>

            <!-- Firmas -->
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

            <!-- Footer -->
            <div class="footer">
                <strong>Folio:</strong> {{ \Illuminate\Support\Str::upper(substr(\Illuminate\Support\Str::uuid(), 0, 8)) }}
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <strong>Expedición:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y') }}
            </div>
        </div>
    </div>
</body>
</html>