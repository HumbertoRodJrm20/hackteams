<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Constancia de Juez</title>
    <style>
        @page {
            size: A4 landscape;
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
            border: 5px solid #0044AA;
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
            <img src="{{ public_path('images/HackTeams_Logo.png') }}" style="width: 150px; margin-bottom: 20px;">
            <h1>HackTeams</h1>
        </div>

        <div class="title">
            CONSTANCIA DE {{ strtoupper($tipo) }}
        </div>

    <p class="contenido">
        Se otorga la presente constancia a:<br>
        <strong>{{ $juez->name }}</strong><br><br>

        Por su participación como <strong>Juez</strong> en el evento:<br>
        <strong>{{ $evento->nombre }}</strong><br><br>

        <p>Evento realizado por Innovatec del {{ $evento->fecha_inicio }} al {{ $evento->fecha_fin }}.</p>    </p>

        <div class="signature-area">
            <table style="width: 100%; margin-top: 50px;">
                <tr>
                    <td style="width: 50%; text-align: center;">
                        <span class="signature-line"></span><br>
                        <span class="signature-label">Director</span>
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