@extends('Layout.app')

@section('title', 'Dashboard Administrativo')

@section('content')
<style>
    main {
        margin: 0;
        padding: 0;
    }

    .dashboard-container {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: calc(100vh - 56px);
        padding: 20px;
        margin: 0;
    }

    [data-theme="dark"] .dashboard-container {
        background: linear-gradient(135deg, #1a202c 0%, #2d3748 100%);
    }

    .grid-stack {
        background: transparent;
    }

    .grid-stack-item-content {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        padding: 20px;
        overflow: visible;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    [data-theme="dark"] .grid-stack-item-content {
        background: #1e1e1e;
        box-shadow: 0 4px 12px rgba(0,0,0,0.4);
    }

    .widget-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        min-height: 0;
    }

    .widget-body canvas {
        max-height: 100%;
    }

    .table-responsive {
        max-height: 100%;
        overflow-y: auto;
    }

    .table-responsive::-webkit-scrollbar {
        width: 8px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #f1f3f5;
        border-radius: 4px;
    }

    [data-theme="dark"] .table-responsive::-webkit-scrollbar-track {
        background: #2a2a2a;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #cbd5e0;
        border-radius: 4px;
    }

    [data-theme="dark"] .table-responsive::-webkit-scrollbar-thumb {
        background: #444444;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #a0aec0;
    }

    [data-theme="dark"] .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #666666;
    }

    .widget-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        cursor: move;
    }

    .widget-title {
        font-size: 1rem;
        font-weight: 600;
        color: #2d3748;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    [data-theme="dark"] .widget-title {
        color: #e0e0e0;
    }

    .widget-drag-handle {
        cursor: move;
        color: #cbd5e0;
        font-size: 1.2rem;
    }

    [data-theme="dark"] .widget-drag-handle {
        color: #666666;
    }

    .stat-card {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        flex-shrink: 0;
    }

    .stat-info h3 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
        color: #2d3748;
    }

    [data-theme="dark"] .stat-info h3 {
        color: #e0e0e0;
    }

    .stat-info p {
        margin: 0;
        color: #718096;
        font-size: 0.9rem;
    }

    [data-theme="dark"] .stat-info p {
        color: #a0a0a0;
    }

    .stat-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-top: 5px;
    }

    .dashboard-header {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    [data-theme="dark"] .dashboard-header {
        background: #1e1e1e;
        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
    }

    .dashboard-header h2 {
        color: #2d3748;
    }

    [data-theme="dark"] .dashboard-header h2 {
        color: #e0e0e0;
    }

    .dashboard-header p {
        color: #6c757d;
    }

    [data-theme="dark"] .dashboard-header p {
        color: #a0a0a0;
    }

    .btn-export {
        padding: 8px 16px;
        border-radius: 8px;
        border: none;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-export:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    /* Tablas en Dark Mode */
    .table thead {
        background-color: #f8f9fa;
    }

    [data-theme="dark"] .table thead {
        background-color: #2a2a2a;
    }

    .table thead th {
        color: #6c757d;
        border-bottom: 2px solid #dee2e6;
    }

    [data-theme="dark"] .table thead th {
        color: #a0a0a0;
        border-bottom: 2px solid #444444;
    }

    .table tbody tr {
        border-bottom: 1px solid #f1f3f5;
    }

    [data-theme="dark"] .table tbody tr {
        border-bottom: 1px solid #333333;
    }

    [data-theme="dark"] .table tbody tr:hover {
        background-color: #2a2a2a;
    }

    .table tbody td {
        color: #2d3748;
    }

    [data-theme="dark"] .table tbody td {
        color: #e0e0e0;
    }

    .table tbody td.text-muted,
    .table tbody td small {
        color: #6c757d !important;
    }

    [data-theme="dark"] .table tbody td.text-muted,
    [data-theme="dark"] .table tbody td small {
        color: #a0a0a0 !important;
    }
</style>

<div class="dashboard-container">
    {{-- Header --}}
    <div class="dashboard-header">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-speedometer2 me-2" style="color: #667eea;"></i>
                Dashboard Administrativo
            </h2>
            <p class="mb-0" style="font-size: 0.95rem;">Widgets personalizables - Arrastra para reorganizar</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.dashboard.export-pdf') }}" class="btn-export" style="background-color: #e8eaf6; color: #5e72e4;">
                <i class="bi bi-file-earmark-pdf me-1"></i>
                Exportar PDF
            </a>
            <a href="{{ route('admin.dashboard.export-excel') }}" class="btn-export" style="background-color: #e0f2f1; color: #2dce89;">
                <i class="bi bi-file-earmark-excel me-1"></i>
                Exportar Excel
            </a>
        </div>
    </div>

    {{-- GridStack Container --}}
    <div class="grid-stack">
        {{-- Fila 1: Tarjetas de estadísticas (y=0 a y=3) --}}
        {{-- Widget: Total Eventos --}}
        <div class="grid-stack-item" gs-w="3" gs-h="3" gs-x="0" gs-y="0">
            <div class="grid-stack-item-content">
                <div class="widget-header">
                    <span class="widget-title">
                        <i class="bi bi-calendar-event" style="color: #667eea;"></i>
                        Eventos
                    </span>
                    <span class="widget-drag-handle"><i class="bi bi-grip-vertical"></i></span>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="bi bi-calendar-check text-white"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $totalEventos }}</h3>
                        <p>Total de eventos</p>
                        <span class="stat-badge" style="background-color: #c3e6cb; color: #155724;">
                            {{ $eventosActivos }} activos
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Widget: Participantes --}}
        <div class="grid-stack-item" gs-w="3" gs-h="3" gs-x="3" gs-y="0">
            <div class="grid-stack-item-content">
                <div class="widget-header">
                    <span class="widget-title">
                        <i class="bi bi-people-fill" style="color: #f59e0b;"></i>
                        Participantes
                    </span>
                    <span class="widget-drag-handle"><i class="bi bi-grip-vertical"></i></span>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #fb923c 100%);">
                        <i class="bi bi-person-check text-white"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $totalParticipantes }}</h3>
                        <p>Registrados</p>
                        <span class="stat-badge" style="background-color: #fff3cd; color: #856404;">
                            Todos los eventos
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Widget: Proyectos --}}
        <div class="grid-stack-item" gs-w="3" gs-h="3" gs-x="6" gs-y="0">
            <div class="grid-stack-item-content">
                <div class="widget-header">
                    <span class="widget-title">
                        <i class="bi bi-kanban-fill" style="color: #0dcaf0;"></i>
                        Proyectos
                    </span>
                    <span class="widget-drag-handle"><i class="bi bi-grip-vertical"></i></span>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #0dcaf0 0%, #38b2ac 100%);">
                        <i class="bi bi-lightbulb text-white"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $totalProyectos }}</h3>
                        <p>Total proyectos</p>
                        <span class="stat-badge" style="background-color: #d1ecf1; color: #0c5460;">
                            {{ $totalEquipos }} equipos
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Widget: Evaluaciones --}}
        <div class="grid-stack-item" gs-w="3" gs-h="3" gs-x="9" gs-y="0">
            <div class="grid-stack-item-content">
                <div class="widget-header">
                    <span class="widget-title">
                        <i class="bi bi-star-fill" style="color: #28a745;"></i>
                        Evaluaciones
                    </span>
                    <span class="widget-drag-handle"><i class="bi bi-grip-vertical"></i></span>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #28a745 0%, #48bb78 100%);">
                        <i class="bi bi-clipboard-check text-white"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $totalCalificaciones }}</h3>
                        <p>Total evaluaciones</p>
                        <span class="stat-badge" style="background-color: #d4edda; color: #155724;">
                            {{ $totalJueces }} jueces
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Fila 2: Gráficas (y=4 a y=10, dejando 1 unidad de espacio) --}}
        {{-- Widget: Estado de Eventos --}}
        <div class="grid-stack-item" gs-w="7" gs-h="6" gs-x="0" gs-y="4">
            <div class="grid-stack-item-content">
                <div class="widget-header">
                    <span class="widget-title">
                        <i class="bi bi-calendar-check" style="color: #667eea;"></i>
                        Estado de Eventos
                    </span>
                    <span class="widget-drag-handle"><i class="bi bi-grip-vertical"></i></span>
                </div>
                <div class="widget-body">
                    <canvas id="eventosEstadoChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Widget: Proyectos por Estado --}}
        <div class="grid-stack-item" gs-w="5" gs-h="6" gs-x="7" gs-y="4">
            <div class="grid-stack-item-content">
                <div class="widget-header">
                    <span class="widget-title">
                        <i class="bi bi-kanban" style="color: #0dcaf0;"></i>
                        Proyectos
                    </span>
                    <span class="widget-drag-handle"><i class="bi bi-grip-vertical"></i></span>
                </div>
                <div class="widget-body">
                    <canvas id="proyectosEstadoChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Fila 3: Participación por Evento (y=11 a y=17, dejando 1 unidad de espacio) --}}
        {{-- Widget: Participación por Evento --}}
        <div class="grid-stack-item" gs-w="12" gs-h="6" gs-x="0" gs-y="11">
            <div class="grid-stack-item-content">
                <div class="widget-header">
                    <span class="widget-title">
                        <i class="bi bi-bar-chart-line" style="color: #0dcaf0;"></i>
                        Participación por Evento
                    </span>
                    <span class="widget-drag-handle"><i class="bi bi-grip-vertical"></i></span>
                </div>
                <div class="widget-body">
                    <canvas id="participacionChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Fila 4: Participación por Carrera (y=18 a y=24, dejando 1 unidad de espacio) --}}
        {{-- Widget: Participación por Carrera --}}
        @if($participantesPorCarrera->isNotEmpty())
        <div class="grid-stack-item" gs-w="12" gs-h="6" gs-x="0" gs-y="18">
            <div class="grid-stack-item-content">
                <div class="widget-header">
                    <span class="widget-title">
                        <i class="bi bi-mortarboard" style="color: #f59e0b;"></i>
                        Participación por Carrera
                    </span>
                    <span class="widget-drag-handle"><i class="bi bi-grip-vertical"></i></span>
                </div>
                <div class="widget-body">
                    <canvas id="carrerasChart"></canvas>
                </div>
            </div>
        </div>
        @endif

        {{-- Fila 5: Top 10 Proyectos (y=25 a y=32, dejando 1 unidad de espacio) --}}
        {{-- Widget: Top 10 Proyectos --}}
        @if($topProyectos->isNotEmpty())
        <div class="grid-stack-item" gs-w="12" gs-h="7" gs-x="0" gs-y="25">
            <div class="grid-stack-item-content">
                <div class="widget-header">
                    <span class="widget-title">
                        <i class="bi bi-trophy" style="color: #f59e0b;"></i>
                        Top 10 Proyectos Mejor Evaluados
                    </span>
                    <span class="widget-drag-handle"><i class="bi bi-grip-vertical"></i></span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle" style="border-collapse: separate; border-spacing: 0;">
                        <thead>
                            <tr>
                                <th style="border: none; padding: 12px; font-size: 0.85rem; font-weight: 600;">Puesto</th>
                                <th style="border: none; padding: 12px; font-size: 0.85rem; font-weight: 600;">Proyecto</th>
                                <th style="border: none; padding: 12px; font-size: 0.85rem; font-weight: 600;">Equipo</th>
                                <th style="border: none; padding: 12px; font-size: 0.85rem; font-weight: 600;">Evento</th>
                                <th class="text-center" style="border: none; padding: 12px; font-size: 0.85rem; font-weight: 600;">Calificación</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topProyectos as $index => $proyecto)
                            <tr>
                                <td style="border: none; padding: 12px;">
                                    @if($index == 0)
                                        <span class="badge" style="background-color: #ffd700; color: #333; font-weight: 600; padding: 6px 12px;">
                                            <i class="bi bi-trophy-fill me-1"></i>1º
                                        </span>
                                    @elseif($index == 1)
                                        <span class="badge" style="background-color: #c0c0c0; color: #333; font-weight: 600; padding: 6px 12px;">
                                            <i class="bi bi-trophy-fill me-1"></i>2º
                                        </span>
                                    @elseif($index == 2)
                                        <span class="badge" style="background-color: #cd7f32; color: white; font-weight: 600; padding: 6px 12px;">
                                            <i class="bi bi-trophy-fill me-1"></i>3º
                                        </span>
                                    @else
                                        <span class="badge" style="background-color: #e9ecef; color: #495057; font-weight: 600; padding: 6px 12px;">
                                            {{ $index + 1 }}º
                                        </span>
                                    @endif
                                </td>
                                <td style="border: none; padding: 12px; font-weight: 600;">{{ $proyecto['titulo'] }}</td>
                                <td style="border: none; padding: 12px;" class="text-muted">{{ $proyecto['equipo'] }}</td>
                                <td style="border: none; padding: 12px;">
                                    <span class="badge" style="background-color: #e8eaf6; color: #667eea; font-weight: 500;">
                                        {{ $proyecto['evento'] }}
                                    </span>
                                </td>
                                <td class="text-center" style="border: none; padding: 12px;">
                                    <span class="badge" style="background-color: #d4edda; color: #155724; font-weight: 600; padding: 6px 12px; font-size: 0.9rem;">
                                        {{ number_format($proyecto['promedio'], 1) }}/100
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        {{-- Fila 6: Listado de Eventos (y=33 a y=40, dejando 1 unidad de espacio) --}}
        {{-- Widget: Listado de Eventos --}}
        <div class="grid-stack-item" gs-w="12" gs-h="7" gs-x="0" gs-y="33">
            <div class="grid-stack-item-content">
                <div class="widget-header">
                    <span class="widget-title">
                        <i class="bi bi-table" style="color: #667eea;"></i>
                        Listado de Eventos
                    </span>
                    <span class="widget-drag-handle"><i class="bi bi-grip-vertical"></i></span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle" style="border-collapse: separate; border-spacing: 0;">
                        <thead>
                            <tr>
                                <th style="border: none; padding: 12px; font-size: 0.85rem; font-weight: 600;">Evento</th>
                                <th style="border: none; padding: 12px; font-size: 0.85rem; font-weight: 600;">Estado</th>
                                <th style="border: none; padding: 12px; font-size: 0.85rem; font-weight: 600;">Fechas</th>
                                <th class="text-center" style="border: none; padding: 12px; font-size: 0.85rem; font-weight: 600;">Participantes</th>
                                <th class="text-center" style="border: none; padding: 12px; font-size: 0.85rem; font-weight: 600;">Equipos</th>
                                <th class="text-center" style="border: none; padding: 12px; font-size: 0.85rem; font-weight: 600;">Proyectos</th>
                                <th class="text-center" style="border: none; padding: 12px; font-size: 0.85rem; font-weight: 600;">Evaluaciones</th>
                                <th style="border: none; padding: 12px; font-size: 0.85rem; font-weight: 600;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($eventos as $evento)
                            <tr>
                                <td style="border: none; padding: 12px; font-weight: 600;">{{ $evento['nombre'] }}</td>
                                <td style="border: none; padding: 12px;">
                                    @if($evento['estado'] == 'activo')
                                        <span class="badge" style="background-color: #d4edda; color: #155724; font-weight: 500; padding: 4px 10px;">
                                            <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i>Activo
                                        </span>
                                    @elseif($evento['estado'] == 'proximo')
                                        <span class="badge" style="background-color: #d1ecf1; color: #0c5460; font-weight: 500; padding: 4px 10px;">
                                            <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i>Próximo
                                        </span>
                                    @else
                                        <span class="badge" style="background-color: #e2e3e5; color: #383d41; font-weight: 500; padding: 4px 10px;">
                                            <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i>Finalizado
                                        </span>
                                    @endif
                                </td>
                                <td style="border: none; padding: 12px;">
                                    <small>
                                        <i class="bi bi-calendar3 me-1"></i>{{ $evento['fecha_inicio']->format('d/m/Y') }}<br>
                                        <i class="bi bi-calendar-check me-1"></i>{{ $evento['fecha_fin']->format('d/m/Y') }}
                                    </small>
                                </td>
                                <td class="text-center" style="border: none; padding: 12px; font-weight: 500;">{{ $evento['participantes'] }}</td>
                                <td class="text-center" style="border: none; padding: 12px; font-weight: 500;">{{ $evento['equipos'] }}</td>
                                <td class="text-center" style="border: none; padding: 12px; font-weight: 500;">{{ $evento['proyectos'] }}</td>
                                <td class="text-center" style="border: none; padding: 12px; font-weight: 500;">{{ $evento['calificaciones'] }}</td>
                                <td style="border: none; padding: 12px;">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.dashboard.export-pdf', ['evento_id' => $evento['id']]) }}"
                                           class="btn btn-sm" style="background-color: #e8eaf6; color: #5e72e4; border: none;" title="Exportar PDF">
                                            <i class="bi bi-file-pdf"></i>
                                        </a>
                                        <a href="{{ route('admin.dashboard.export-excel', ['evento_id' => $evento['id']]) }}"
                                           class="btn btn-sm" style="background-color: #e0f2f1; color: #2dce89; border: none;" title="Exportar Excel">
                                            <i class="bi bi-file-excel"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- GridStack CSS & JS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/gridstack@10.1.1/dist/gridstack.min.css" />
<script src="https://cdn.jsdelivr.net/npm/gridstack@10.1.1/dist/gridstack-all.js"></script>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
    // Inicializar GridStack
    const grid = GridStack.init({
        cellHeight: 80,
        margin: 25,
        float: true,
        draggable: {
            handle: '.widget-drag-handle'
        }
    });

    // Configuración de colores suaves
    const colors = {
        purple: '#9F7AEA',
        blue: '#4299E1',
        green: '#48BB78',
        yellow: '#ECC94B',
        orange: '#ED8936',
        teal: '#38B2AC',
        pink: '#ED64A6',
        gray: '#A0AEC0',
    };

    // Configuración global de Chart.js
    Chart.defaults.font.family = '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif';
    Chart.defaults.color = '#718096';

    // Almacenar referencias a las gráficas
    const charts = {};

    // Gráfica de Proyectos por Estado
    charts.proyectos = new Chart(document.getElementById('proyectosEstadoChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($proyectosPorEstado->keys()->map(fn($key) => ucfirst($key))) !!},
            datasets: [{
                data: {!! json_encode($proyectosPorEstado->values()) !!},
                backgroundColor: [colors.blue, colors.yellow, colors.green, colors.gray],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        font: { size: 13 },
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                }
            }
        }
    });

    // Gráfica de Eventos por Estado
    charts.eventos = new Chart(document.getElementById('eventosEstadoChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($eventosPorEstado->keys()->map(fn($key) => ucfirst($key))) !!},
            datasets: [{
                label: 'Número de Eventos',
                data: {!! json_encode($eventosPorEstado->values()) !!},
                backgroundColor: colors.purple,
                borderRadius: 6,
                barThickness: 40,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, font: { size: 12 } },
                    grid: { display: false }
                },
                x: {
                    grid: { color: '#f1f3f5', drawBorder: false },
                    ticks: { font: { size: 12 } }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });

    // Gráfica de Participación por Evento
    charts.participacion = new Chart(document.getElementById('participacionChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($participacionPorEvento->pluck('nombre')) !!},
            datasets: [
                {
                    label: 'Participantes',
                    data: {!! json_encode($participacionPorEvento->pluck('participantes')) !!},
                    backgroundColor: colors.blue,
                    borderRadius: 6,
                    barThickness: 30,
                },
                {
                    label: 'Proyectos',
                    data: {!! json_encode($participacionPorEvento->pluck('proyectos')) !!},
                    backgroundColor: colors.teal,
                    borderRadius: 6,
                    barThickness: 30,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: { intersect: false, mode: 'index' },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, font: { size: 12 } },
                    grid: { color: '#f1f3f5', drawBorder: false }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 12 } }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    align: 'end',
                    labels: {
                        padding: 15,
                        font: { size: 12 },
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                }
            }
        }
    });

    // Gráfica de Participantes por Carrera
    @if($participantesPorCarrera->isNotEmpty())
    charts.carreras = new Chart(document.getElementById('carrerasChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($participantesPorCarrera->keys()) !!},
            datasets: [{
                label: 'Participantes',
                data: {!! json_encode($participantesPorCarrera->values()) !!},
                backgroundColor: colors.orange,
                borderRadius: 6,
                barThickness: 25,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, font: { size: 12 } },
                    grid: { color: '#f1f3f5', drawBorder: false }
                },
                y: {
                    grid: { display: false },
                    ticks: { font: { size: 12 } }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
    @endif

    // Event listeners para redibujar las gráficas cuando se mueva o redimensione un widget
    grid.on('dragstop resizestop', function(event, element) {
        // Pequeño delay para que el DOM se actualice
        setTimeout(() => {
            // Redibujar todas las gráficas
            Object.values(charts).forEach(chart => {
                if (chart) {
                    chart.resize();
                    chart.update('none');
                }
            });
        }, 100);
    });

    // Redibujar gráficas cuando la ventana cambie de tamaño
    window.addEventListener('resize', () => {
        Object.values(charts).forEach(chart => {
            if (chart) {
                chart.resize();
            }
        });
    });
</script>
@endsection
