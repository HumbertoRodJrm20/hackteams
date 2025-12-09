<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifica tu correo</title>
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
            text-align: center;
        }
        .content h2 {
            color: #667eea;
            margin-top: 0;
        }
        .code-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 36px;
            font-weight: bold;
            letter-spacing: 8px;
            padding: 20px;
            border-radius: 10px;
            margin: 30px 0;
            display: inline-block;
        }
        .warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            text-align: left;
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
            <p>Verificación de Correo Electrónico</p>
        </div>

        <div class="content">
            <h2>¡Bienvenido, {{ $user->name }}!</h2>

            <p>Gracias por registrarte en HackTeams. Para completar tu registro, por favor verifica tu correo electrónico usando el siguiente código:</p>

            <div class="code-box">
                {{ $codigo }}
            </div>

            <div class="warning">
                <strong>⏰ Importante:</strong> Este código expirará en <strong>5 minutos</strong>.
            </div>

            <p style="margin-top: 30px;">
                Ingresa este código en la página de verificación para activar tu cuenta.
            </p>

            <p style="font-size: 14px; color: #666; margin-top: 30px;">
                Si no solicitaste este registro, puedes ignorar este correo de forma segura.
            </p>
        </div>

        <div class="footer">
            <p>Este es un correo automático de HackTeams</p>
            <p>&copy; {{ date('Y') }} HackTeams. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
