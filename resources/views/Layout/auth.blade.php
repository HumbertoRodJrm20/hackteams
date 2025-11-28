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
            background-color: #f8f9fa;
        }
        /* Garantiza que el contenido ocupe toda la altura */
        .auth-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh; 
            padding: 40px 0;
            background-color: #f8f9fa;
        }
        /* La tarjeta de login, ahora con ancho compacto garantizado */
        .login-card {
            max-width: 450px;
            width: 100%;
            padding: 40px;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
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