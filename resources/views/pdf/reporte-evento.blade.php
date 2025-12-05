<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Evento - {{ $evento->nombre }}</title>
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
        .info {
            margin: 20px 0;
        }
        .info-item {
            margin: 5px 0;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-gold { background-color: #ffd700; color: #000; }
        .badge-silver { background-color: #c0c0c0; color: #000; }
        .badge-bronze { background-color: #cd7f32; color: #fff; }
    </style>
</head>
<body>
    <h1>Reporte de Evento</h1>

    <div class="info">
        <h2>Información del Evento</h2>
        <div class="info-item"><strong>Nombre:</strong> {{ $evento->nombre }}</div>
        <div class="info-item"><strong>Estado:</strong> {{ ucfirst($evento->estado) }}</div>
        <div class="info-item"><strong>Fecha Inicio:</strong> {{ $evento->fecha_inicio->format('d/m/Y') }}</div>
        <div class="info-item"><strong>Fecha Fin:</strong> {{ $evento->fecha_fin->format('d/m/Y') }}</div>
        <div class="info-item"><strong>Participantes:</strong> {{ $evento->participantes->count() }}</div>
        <div class="info-item"><strong>Proyectos:</strong> {{ $proyectos->count() }}</div>
    </div>

    <h2>Ranking de Proyectos</h2>
    <table>
        <thead>
            <tr>
                <th>Puesto</th>
                <th>Proyecto</th>
                <th>Equipo</th>
                <th>Estado</th>
                <th>Promedio</th>
            </tr>
        </thead>
        <tbody>
            @foreach($proyectos as $proyecto)
            <tr>
                <td>
                    {{ $proyecto['puesto'] }}º
                    @if($proyecto['puesto'] == 1)
                        <span class="badge badge-gold">🥇</span>
                    @elseif($proyecto['puesto'] == 2)
                        <span class="badge badge-silver">🥈</span>
                    @elseif($proyecto['puesto'] == 3)
                        <span class="badge badge-bronze">🥉</span>
                    @endif
                </td>
                <td>{{ $proyecto['titulo'] }}</td>
                <td>{{ $proyecto['equipo'] }}</td>
                <td>{{ ucfirst($proyecto['estado']) }}</td>
                <td>{{ number_format($proyecto['promedio'], 2) }}/100</td>
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
