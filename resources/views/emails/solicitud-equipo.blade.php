<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Solicitud para Unirse a tu Equipo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .content {
            padding: 30px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            margin: 10px 5px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
        }
        .button-primary {
            background-color: #28a745;
            color: white;
        }
        .button-secondary {
            background-color: #6c757d;
            color: white;
        }
        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Nueva Solicitud de Equipo</h1>
        </div>
        <div class="content">
            <p>Hola,</p>

            <p><strong>{{ $nombreParticipante }}</strong> ha solicitado unirse a tu equipo <strong>{{ $nombreEquipo }}</strong>.</p>

            @if($solicitud->mensaje)
                <div class="info-box">
                    <strong>Mensaje del participante:</strong>
                    <p>{{ $solicitud->mensaje }}</p>
                </div>
            @endif

            <p>Puedes revisar esta solicitud y decidir si aceptarla o rechazarla desde tu panel de gestión de equipos.</p>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('equipos.solicitudes') }}" class="button button-primary">
                    Ver Solicitudes Pendientes
                </a>
            </div>

            <p>Si no esperabas esta solicitud, puedes ignorarla o rechazarla desde tu panel.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} HackTeams. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
