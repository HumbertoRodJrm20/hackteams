<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Innovatec - Autenticación')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        /* Estilo base para el cuerpo */
        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* Garantiza que el contenido ocupe toda la altura con fondo animado */
        .auth-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 40px 20px;
            background: linear-gradient(-45deg, #667eea, #764ba2, #f093fb, #4facfe);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            position: relative;
        }

        /* Animación del gradiente */
        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        /* Patrón de puntos decorativo */
        .auth-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: radial-gradient(rgba(255, 255, 255, 0.2) 1px, transparent 1px);
            background-size: 50px 50px;
            pointer-events: none;
        }

        /* La tarjeta de login, ahora con ancho compacto garantizado */
        .login-card {
            max-width: 450px;
            width: 100%;
            padding: 40px;
            border-radius: 16px;
            background-color: white;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 1;
            backdrop-filter: blur(10px);
        }
    </style>
    @yield('styles') {{-- Para estilos específicos de login/registro --}}
</head>
<body>
    
    <main class="auth-container">
        @yield('content') {{-- AQUÍ VA LA VISTA DE LOGIN/REGISTRO --}}
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    @yield('scripts')
</body>
</html>