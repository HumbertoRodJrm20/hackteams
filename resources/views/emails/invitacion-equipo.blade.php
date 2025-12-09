<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitación a Equipo</title>
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
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .content {
            padding: 30px 20px;
        }
        .content h2 {
            color: #667eea;
            margin-top: 0;
        }
        .team-info {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
        }
        .team-info strong {
            color: #667eea;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            opacity: 0.9;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎯 HackTeams</h1>
            <p>¡Tienes una nueva invitación!</p>
        </div>

        <div class="content">
            <h2>Hola, {{ $invitado->name }}!</h2>

            <p>
                <strong>{{ $lider->name }}</strong> te ha invitado a unirte a su equipo en HackTeams.
            </p>

            <div class="team-info">
                <p><strong>Equipo:</strong> {{ $equipo->nombre }}</p>
                @if($equipo->evento)
                    <p><strong>Evento:</strong> {{ $equipo->evento->nombre }}</p>
                    <p><strong>Fecha del evento:</strong> {{ \Carbon\Carbon::parse($equipo->evento->fecha_inicio)->isoFormat('D [de] MMMM [de] YYYY') }}</p>
                @endif
                <p><strong>Tipo:</strong> {{ $equipo->es_publico ? 'Público' : 'Privado' }}</p>
                <p><strong>Miembros actuales:</strong> {{ $equipo->contarMiembros() }}</p>
            </div>

            <p>
                Ya has sido agregado al equipo. Puedes iniciar sesión en la plataforma para ver los detalles del equipo y comenzar a colaborar con tus compañeros.
            </p>

            <center>
                <a href="{{ route('equipos.show', $equipo->id) }}" class="button">
                    Ver Detalles del Equipo
                </a>
            </center>

            <p style="margin-top: 30px; font-size: 14px; color: #666;">
                Si tienes alguna pregunta, no dudes en contactar con el líder del equipo o con el administrador de la plataforma.
            </p>
        </div>

        <div class="footer">
            <p>Este es un correo automático de HackTeams</p>
            <p>&copy; {{ date('Y') }} HackTeams. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
