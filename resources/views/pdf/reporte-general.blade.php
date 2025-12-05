<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte General</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        h1 {
            text-align: center;
            color: #667eea;
            font-size: 24px;
        }
        h2 {
            color: #764ba2;
            border-bottom: 2px solid #764ba2;
            padding-bottom: 5px;
            margin-top: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #667eea;
            color: white;
        }
        .stats {
            display: table;
            width: 100%;
            margin: 20px 0;
        }
        .stat-item {
            display: table-cell;
            width: 25%;
            padding: 15px;
            text-align: center;
            background-color: #f5f5f5;
            border: 1px solid #ddd;
        }
        .stat-value {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
        }
        .stat-label {
            font-size: 12px;
            color: #666;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <h1>Reporte General del Sistema</h1>

    <div class="stats">
        <div class="stat-item">
            <div class="stat-value">{{ $totalEventos }}</div>
            <div class="stat-label">Eventos</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $totalParticipantes }}</div>
            <div class="stat-label">Participantes</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $totalEquipos }}</div>
            <div class="stat-label">Equipos</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $totalProyectos }}</div>
            <div class="stat-label">Proyectos</div>
        </div>
    </div>

    <h2>Eventos Registrados</h2>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Estado</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Proyectos</th>
                <th>Participantes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($eventos as $evento)
            <tr>
                <td>{{ $evento->nombre }}</td>
                <td>{{ ucfirst($evento->estado) }}</td>
                <td>{{ $evento->fecha_inicio->format('d/m/Y') }}</td>
                <td>{{ $evento->fecha_fin->format('d/m/Y') }}</td>
                <td>{{ $evento->proyectos->count() }}</td>
                <td>{{ $evento->participantes->count() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generado el {{ $fecha }}<br>
        Sistema de Gestión de Hackathons
    </div>
</body>
</html>
