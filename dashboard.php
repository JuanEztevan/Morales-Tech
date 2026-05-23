<?php
// session_start();
// include("includes/auth.php");

$nombre_usuario  = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Juan Carmona';
$rol_usuario     = 'Trabajador';
$partes          = explode(' ', trim($nombre_usuario));
$inicial         = strtoupper(substr($partes[0], 0, 1));
$nombre_corto    = $partes[0];
$nombre_display  = isset($partes[0]) ? $partes[0] : 'Juan';
$apellido_display= isset($partes[1]) ? $partes[1] : 'Carmona';

$hora   = (int) date('H');
$saludo = $hora < 12 ? 'Buenos días' : ($hora < 19 ? 'Buenas tardes' : 'Buenas noches');

$tickets_hoy         = 4;
$tickets_pendientes  = 7;
$tickets_completados = 12;
$ingresos_mes        = 3480;

$tickets_recientes = [
    ["id"=>"MT-8845","cliente"=>"Brenda Benites",  "servicio"=>"Limpieza preventiva",      "estado"=>"Completado",     "fecha"=>"Hoy, 10:30"],
    ["id"=>"MT-8844","cliente"=>"Diana Calderón",  "servicio"=>"Mantenimiento correctivo",  "estado"=>"En reparación",  "fecha"=>"Hoy, 09:15"],
    ["id"=>"MT-8843","cliente"=>"Andrés Ochante",  "servicio"=>"Repotenciación",            "estado"=>"Recibido",       "fecha"=>"Ayer, 16:00"],
    ["id"=>"MT-8842","cliente"=>"Valeria Ramírez", "servicio"=>"Reparación",                "estado"=>"En diagnóstico", "fecha"=>"Ayer, 11:45"],
    ["id"=>"MT-8841","cliente"=>"Carlos Quispe",   "servicio"=>"Formateo e instalación",    "estado"=>"Completado",     "fecha"=>"22 may, 09:00"],
];

$stock_alerta = [
    ["nombre"=>"RAM DDR5 16GB 4800MHz", "stock"=>9,  "minimo"=>10, "clase"=>"stock--min"],
    ["nombre"=>"SSD 1TB SATA",          "stock"=>10, "minimo"=>12, "clase"=>"stock--low"],
    ["nombre"=>"Kit Dest. 128 en 1",    "stock"=>12, "minimo"=>15, "clase"=>"stock--low"],
];

function clase_estado_dash($e) {
    $m = ['Recibido'=>'badge--recibido','En diagnóstico'=>'badge--diagnostico','En reparación'=>'badge--reparacion','Completado'=>'badge--completado'];
    return $m[$e] ?? 'badge--default';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Morales Tech</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --azul-prof: #000019;
            --azul-tec:  #1746EA;
            --celeste:   #1883ED;
            --border:    #e6e9f0;
            --bg:        #ffffff;
            --surface2:  #f4f6fb;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Montserrat', sans-serif; background: var(--bg); color: var(--azul-prof); min-height: 100vh; }

        /* ══ LAYOUT ══ */
        .app-shell { display: flex; min-height: 100vh; }
        .main { flex: 1; display: flex; flex-direction: column; min-width: 0; width: 0; background: var(--bg); }

        /* ══ SIDEBAR ══ */
        .sidebar { width: 80px; min-width: 80px; background: linear-gradient(180deg,#fff 0%,#edf1fd 100%); border-right: 1px solid var(--border); display: flex; flex-direction: column; align-items: center; height: 100vh; position: sticky; top: 0; flex-shrink: 0; }
        .sidebar__logo { width: 100%; display: flex; justify-content: center; padding: 18px 0 14px; }
        .sidebar__isotipo { width: 38px; height: auto; }
        .sidebar__nav { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 2px; width: 100%; padding: 12px 6px; }
        .nav-link { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 4px; width: 62px; height: 62px; border-radius: 12px; text-decoration: none; color: #7a8096; font-size: 10px; font-weight: 600; transition: all .18s; text-align: center; }
        .nav-link svg { width: 20px; height: 20px; flex-shrink: 0; }
        .nav-link:hover { background: rgba(255,255,255,.8); color: var(--azul-prof); }
        .nav-link--active { background: #fff; color: var(--azul-tec); font-weight: 700; box-shadow: 0 3px 10px rgba(23,70,234,.10); }
        .sidebar__footer { width: 100%; display: flex; flex-direction: column; align-items: center; padding: 12px 6px 24px; }
        .sidebar__logout { display: flex; flex-direction: column; align-items: center; gap: 3px; text-decoration: none; color: #a0a8bb; font-size: 10px; font-weight: 600; padding: 6px 10px; border-radius: 10px; transition: background .15s,color .15s; width: 62px; text-align: center; }
        .sidebar__logout:hover { background: rgba(255,255,255,.8); color: #c94a00; }
        .sidebar__logout svg { width: 18px; height: 18px; }

        /* ══ HEADER ══ */
        .header { background: #fff; border-bottom: 1px solid var(--border); padding: 0 28px; height: 65px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 10; flex-shrink: 0; width: 100%; }
        .header__breadcrumb { font-size: 13px; font-weight: 500; color: #a0a8bb; }
        .header__breadcrumb span { color: var(--azul-prof); font-weight: 600; }
        .header__user { display: flex; align-items: center; gap: 10px; }
        .header__user-info { display: flex; flex-direction: column; align-items: flex-start; }
        .header__avatar { width: 36px; height: 36px; background: linear-gradient(135deg,var(--azul-tec),var(--celeste)); border-radius: 50%; display: grid; place-items: center; font-size: 13px; font-weight: 700; color: #fff; }
        .header__username { font-size: 13px; font-weight: 700; color: var(--azul-prof); line-height: 1.1; }
        .header__user-role { font-size: 11px; font-weight: 500; color: #a0a8bb; }

        /* ══ PAGE ══ */
        .page { padding: 28px; flex: 1; }

        /* ══ DASH GRID ══ */
        .dash-layout { display: grid; grid-template-columns: 1fr 300px; gap: 22px; align-items: start; }
        .dash-left  { display: flex; flex-direction: column; gap: 18px; }
        .dash-right { display: flex; flex-direction: column; gap: 18px; position: sticky; top: 88px; }

        /* ══ KPI CARDS — estilo ventas.php ══ */
        .kpi-row { display: grid; grid-template-columns: repeat(4,1fr); gap: 14px; }

        .kpi-card {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 20px 22px 18px;
            box-shadow: 0 1px 4px rgba(0,0,25,.06);
            transition: box-shadow .15s, transform .15s;
            position: relative;
            overflow: hidden;
        }
        .kpi-card:hover { box-shadow: 0 6px 20px rgba(0,0,25,.10); transform: translateY(-1px); }

        /* Highlight (primer card) en azul completo */
        .kpi-card--highlight {
            background: var(--azul-tec);
            border-color: var(--azul-tec);
        }

        .kpi-card__top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }

        .kpi-card__icon { width: 32px; height: 32px; border-radius: 8px; background: var(--surface2); display: grid; place-items: center; }
        .kpi-card--highlight .kpi-card__icon { background: rgba(255,255,255,.18); }
        .kpi-card__icon svg { width: 15px; height: 15px; color: var(--azul-prof); }
        .kpi-card--highlight .kpi-card__icon svg { color: #fff; }

        .kpi-badge { display: inline-flex; align-items: center; gap: 3px; font-size: 10px; font-weight: 700; padding: 3px 8px; border-radius: 50px; white-space: nowrap; }
        .kpi-badge--up { background: rgba(24,131,237,.12); color: var(--celeste); }
        .kpi-badge--neutral { background: var(--surface2); color: #7a8096; }
        .kpi-badge--warn { background: #fff8e6; color: #9a6c00; }
        .kpi-card--highlight .kpi-badge--up { background: rgba(255,255,255,.18); color: #fff; }
        .kpi-badge svg { width: 10px; height: 10px; }

        .kpi-card__label { font-size: 11px; font-weight: 600; color: #7a8096; margin-bottom: 5px; }
        .kpi-card--highlight .kpi-card__label { color: rgba(255,255,255,.65); }
        .kpi-card__value { font-size: 26px; font-weight: 800; color: var(--azul-prof); letter-spacing: -.5px; line-height: 1; }
        .kpi-card--highlight .kpi-card__value { color: #fff; }
        .kpi-card__sub { font-size: 11px; font-weight: 500; color: #a0a8bb; margin-top: 5px; }
        .kpi-card--highlight .kpi-card__sub { color: rgba(255,255,255,.45); }

        /* ══ PANEL BLOCKS (igual que ventas.php) ══ */
        .panel-block { background: var(--bg); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,25,.05); }
        .panel-block__head { padding: 15px 20px 13px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .panel-block__title { font-size: 13px; font-weight: 800; color: var(--azul-prof); display: flex; align-items: center; gap: 8px; }
        .panel-block__title svg { width: 14px; height: 14px; color: var(--azul-tec); }
        .panel-block__link { font-size: 12px; font-weight: 600; color: var(--azul-tec); text-decoration: none; }
        .panel-block__link:hover { text-decoration: underline; }

        /* ══ TABLA TICKETS ══ */
        .ticket-table { width: 100%; border-collapse: collapse; min-width: 520px; }
        .ticket-table th { padding: 10px 18px; font-size: 10px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: #a0a8bb; text-align: left; background: #fafbff; border-bottom: 1px solid var(--border); white-space: nowrap; }
        .ticket-table td { padding: 12px 18px; font-size: 13px; vertical-align: middle; border-bottom: 1px solid var(--border); }
        .ticket-table tr:last-child td { border-bottom: none; }
        .ticket-table tr:hover td { background: #f7f8fd; }
        .ticket-id      { font-weight: 800; color: var(--azul-prof); font-size: 12px; }
        .ticket-cliente { font-weight: 600; }
        .ticket-svc     { font-size: 12px; color: #7a8096; }
        .ticket-fecha   { font-size: 11px; color: #a0a8bb; white-space: nowrap; }

        .estado-badge { display: inline-block; font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 20px; border: 1.5px solid transparent; white-space: nowrap; }
        .badge--recibido    { background:#fff8e6; color:#9a6c00;  border-color:#f0dfa0; }
        .badge--diagnostico { background:#edf1fd; color:#1746EA;  border-color:#c4d0f8; }
        .badge--reparacion  { background:#fff0eb; color:#c94a00;  border-color:#f5c4a8; }
        .badge--completado  { background:#e6f8f0; color:#1a7a4a;  border-color:#a8dfc4; }
        .badge--default     { background:#f4f6fb; color:#7a8096;  border-color:#e6e9f0; }

        /* ══ BOTTOM ROW ══ */
        .bottom-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

        /* Stock */
        .stock-item { display: flex; align-items: center; justify-content: space-between; padding: 11px 18px; border-bottom: 1px solid var(--border); gap: 10px; }
        .stock-item:last-child { border-bottom: none; }
        .stock-item__name { font-size: 12px; font-weight: 700; color: var(--azul-prof); margin-bottom: 2px; line-height: 1.3; }
        .stock-item__min  { font-size: 10px; color: #a0a8bb; }
        .stock-val { font-size: 12px; font-weight: 800; padding: 4px 10px; border-radius: 20px; white-space: nowrap; }
        .stock--min { background: #fff0eb; color: #c94a00; }
        .stock--low { background: #fff8e6; color: #9a6c00; }

        /* Donut */
        .donut-wrap { padding: 18px 20px; display: flex; flex-direction: column; align-items: center; gap: 14px; }
        .donut-svg  { width: 130px; height: 130px; flex-shrink: 0; }
        .donut-legend { width: 100%; display: flex; flex-direction: column; gap: 7px; }
        .legend-item { display: flex; align-items: center; gap: 8px; font-size: 11px; font-weight: 600; color: var(--azul-prof); }
        .legend-dot  { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
        .legend-pct  { margin-left: auto; font-weight: 800; color: #7a8096; }

        /* ══ COLUMNA DERECHA ══ */

        /* Welcome card */
        .welcome-card {
            background: linear-gradient(160deg, var(--azul-tec) 0%, var(--celeste) 100%);
            border-radius: 16px; padding: 24px 22px; color: #fff;
            position: relative; overflow: hidden;
            box-shadow: 0 6px 24px rgba(23,70,234,.22);
        }
        .welcome-card::before { content:''; position:absolute; right:-40px; top:-40px; width:160px; height:160px; border-radius:50%; background:rgba(255,255,255,.07); }
        .welcome-card::after  { content:''; position:absolute; right:20px; bottom:-50px; width:110px; height:110px; border-radius:50%; background:rgba(255,255,255,.05); }

        .welcome-avatar { width: 50px; height: 50px; background: rgba(255,255,255,.2); border: 2px solid rgba(255,255,255,.3); border-radius: 50%; display: grid; place-items: center; font-size: 20px; font-weight: 800; margin-bottom: 14px; position: relative; z-index: 1; }
        .welcome-greeting { font-size: 11px; font-weight: 500; opacity: .75; margin-bottom: 2px; position: relative; z-index: 1; }
        .welcome-name { font-size: 19px; font-weight: 800; line-height: 1.15; margin-bottom: 8px; position: relative; z-index: 1; letter-spacing: -.3px; }
        .welcome-sub  { font-size: 11px; opacity: .70; line-height: 1.5; position: relative; z-index: 1; }
        .welcome-date { display: inline-flex; align-items: center; gap: 5px; margin-top: 14px; font-size: 11px; font-weight: 600; background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.22); border-radius: 50px; padding: 5px 12px; position: relative; z-index: 1; }
        .welcome-date svg { width: 12px; height: 12px; }
        .btn-nuevo { display: flex; align-items: center; justify-content: center; gap: 7px; width: 100%; margin-top: 14px; background: rgba(255,255,255,.17); border: 1.5px solid rgba(255,255,255,.30); color: #fff; font-family: 'Montserrat',sans-serif; font-size: 13px; font-weight: 700; padding: 10px; border-radius: 50px; text-decoration: none; cursor: pointer; transition: background .15s; position: relative; z-index: 1; }
        .btn-nuevo:hover { background: rgba(255,255,255,.26); }
        .btn-nuevo svg { width: 14px; height: 14px; }

        /* Resumen del día */
        .day-summary { padding: 14px 16px; display: flex; flex-direction: column; gap: 8px; }
        .day-item { display: flex; align-items: center; justify-content: space-between; padding: 11px 14px; background: var(--surface2); border-radius: 10px; border: 1px solid var(--border); }
        .day-item__label { font-size: 12px; font-weight: 600; color: #7a8096; display: flex; align-items: center; gap: 7px; }
        .day-item__label svg { width: 13px; height: 13px; color: var(--azul-tec); }
        .day-item__val { font-size: 15px; font-weight: 800; color: var(--azul-prof); }
        .day-item__val--green { color: #1a7a4a; }

        /* ══ RESPONSIVE ══ */
        @media(max-width:1150px){ .dash-layout{grid-template-columns:1fr;} .dash-right{position:static;} }
        @media(max-width:900px){ .kpi-row{grid-template-columns:1fr 1fr;} .bottom-row{grid-template-columns:1fr;} }
        @media(max-width:700px){ .sidebar{width:60px;min-width:60px;} .nav-link{width:46px;height:50px;font-size:0;} .sidebar__logout span{display:none;} .header__user-info{display:none;} .page{padding:18px 14px;} .header{padding:0 14px;} }
        @media(max-width:480px){ .sidebar{display:none;} .kpi-row{grid-template-columns:1fr 1fr;} }
    </style>
</head>
<body>
<div class="app-shell">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar__logo">
            <img src="img/isotipo-color.png" alt="Morales Tech" class="sidebar__isotipo">
        </div>
        <nav class="sidebar__nav">
            <a href="dashboard.php" class="nav-link nav-link--active">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                Dashboard
            </a>
            <a href="tickets.php" class="nav-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
                Tickets
            </a>
            <a href="inventario.php" class="nav-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                Inventario
            </a>
            <a href="ventas.php" class="nav-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                Ventas
            </a>
        </nav>
        <div class="sidebar__footer">
            <a href="logout.php" class="sidebar__logout">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                <span>Salir</span>
            </a>
        </div>
    </aside>

    <!-- MAIN -->
    <div class="main">
        <header class="header">
            <div class="header__breadcrumb">Panel / <span>Dashboard</span></div>
            <div class="header__user">
                <div class="header__avatar"><?= $inicial ?></div>
                <div class="header__user-info">
                    <span class="header__username"><?= htmlspecialchars($nombre_display) ?></span>
                    <span class="header__user-role"><?= htmlspecialchars($rol_usuario) ?></span>
                </div>
            </div>
        </header>

        <main class="page">
            <div class="dash-layout">

                <!-- ══ IZQUIERDA ══ -->
                <div class="dash-left">

                    <!-- KPIs -->
                    <div class="kpi-row">

                        <!-- Ingresos (highlight azul) -->
                        <div class="kpi-card kpi-card--highlight">
                            <div class="kpi-card__top">
                                <div class="kpi-card__icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                </div>
                                <span class="kpi-badge kpi-badge--up">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
                                    +18%
                                </span>
                            </div>
                            <div class="kpi-card__label">Ingresos del mes</div>
                            <div class="kpi-card__value">S/ <?= number_format($ingresos_mes,0) ?></div>
                            <div class="kpi-card__sub">Mayo 2025</div>
                        </div>

                        <!-- Tickets hoy -->
                        <div class="kpi-card">
                            <div class="kpi-card__top">
                                <div class="kpi-card__icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
                                </div>
                                <span class="kpi-badge kpi-badge--up">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
                                    +2 hoy
                                </span>
                            </div>
                            <div class="kpi-card__label">Tickets hoy</div>
                            <div class="kpi-card__value"><?= $tickets_hoy ?></div>
                            <div class="kpi-card__sub">vs ayer</div>
                        </div>

                        <!-- Pendientes -->
                        <div class="kpi-card">
                            <div class="kpi-card__top">
                                <div class="kpi-card__icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                </div>
                                <span class="kpi-badge kpi-badge--warn">3 en diag.</span>
                            </div>
                            <div class="kpi-card__label">Pendientes</div>
                            <div class="kpi-card__value"><?= $tickets_pendientes ?></div>
                            <div class="kpi-card__sub">requieren atención</div>
                        </div>

                        <!-- Completados -->
                        <div class="kpi-card">
                            <div class="kpi-card__top">
                                <div class="kpi-card__icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                </div>
                                <span class="kpi-badge kpi-badge--up">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
                                    6.2%
                                </span>
                            </div>
                            <div class="kpi-card__label">Completados</div>
                            <div class="kpi-card__value"><?= $tickets_completados ?></div>
                            <div class="kpi-card__sub">este mes</div>
                        </div>

                    </div><!-- /kpi-row -->

                    <!-- Tickets recientes -->
                    <div class="panel-block">
                        <div class="panel-block__head">
                            <div class="panel-block__title">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
                                Tickets recientes
                            </div>
                            <a href="tickets.php" class="panel-block__link">Ver todos →</a>
                        </div>
                        <div style="overflow-x:auto;">
                            <table class="ticket-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Cliente</th>
                                        <th>Servicio</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($tickets_recientes as $t): ?>
                                <tr>
                                    <td><span class="ticket-id">#<?= $t['id'] ?></span></td>
                                    <td><span class="ticket-cliente"><?= htmlspecialchars($t['cliente']) ?></span></td>
                                    <td><span class="ticket-svc"><?= htmlspecialchars($t['servicio']) ?></span></td>
                                    <td><span class="estado-badge <?= clase_estado_dash($t['estado']) ?>"><?= $t['estado'] ?></span></td>
                                    <td><span class="ticket-fecha"><?= $t['fecha'] ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Bottom row: stock + donut -->
                    <div class="bottom-row">

                        <!-- Stock bajo -->
                        <div class="panel-block">
                            <div class="panel-block__head">
                                <div class="panel-block__title">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                    Stock bajo
                                </div>
                                <a href="inventario.php" class="panel-block__link">Gestionar →</a>
                            </div>
                            <?php foreach ($stock_alerta as $s): ?>
                            <div class="stock-item">
                                <div>
                                    <div class="stock-item__name"><?= htmlspecialchars($s['nombre']) ?></div>
                                    <div class="stock-item__min">Mín. <?= $s['minimo'] ?> uds.</div>
                                </div>
                                <span class="stock-val <?= $s['clase'] ?>"><?= $s['stock'] ?> uds.</span>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Donut servicios -->
                        <div class="panel-block">
                            <div class="panel-block__head">
                                <div class="panel-block__title">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    Servicios solicitados
                                </div>
                            </div>
                            <div class="donut-wrap">
                                <svg class="donut-svg" viewBox="0 0 36 36">
                                    <circle cx="18" cy="18" r="15.9" fill="none" stroke="#f0f2f8" stroke-width="3.5"/>
                                    <circle cx="18" cy="18" r="15.9" fill="none" stroke="var(--azul-tec)" stroke-width="3.5" stroke-dasharray="35 65" stroke-dashoffset="25" stroke-linecap="round"/>
                                    <circle cx="18" cy="18" r="15.9" fill="none" stroke="var(--celeste)" stroke-width="3.5" stroke-dasharray="28 72" stroke-dashoffset="-10" stroke-linecap="round"/>
                                    <circle cx="18" cy="18" r="15.9" fill="none" stroke="#f5a623" stroke-width="3.5" stroke-dasharray="20 80" stroke-dashoffset="-38" stroke-linecap="round"/>
                                    <circle cx="18" cy="18" r="15.9" fill="none" stroke="#1a7a4a" stroke-width="3.5" stroke-dasharray="17 83" stroke-dashoffset="-58" stroke-linecap="round"/>
                                    <text x="18" y="19.5" text-anchor="middle" font-size="5" font-weight="800" fill="#000019" font-family="Montserrat,sans-serif">May 2025</text>
                                </svg>
                                <div class="donut-legend">
                                    <div class="legend-item"><span class="legend-dot" style="background:var(--azul-tec)"></span>Mantenimiento<span class="legend-pct">35%</span></div>
                                    <div class="legend-item"><span class="legend-dot" style="background:var(--celeste)"></span>Reparación<span class="legend-pct">28%</span></div>
                                    <div class="legend-item"><span class="legend-dot" style="background:#f5a623"></span>Formateo<span class="legend-pct">20%</span></div>
                                    <div class="legend-item"><span class="legend-dot" style="background:#1a7a4a"></span>Limpieza<span class="legend-pct">17%</span></div>
                                </div>
                            </div>
                        </div>

                    </div><!-- /bottom-row -->

                </div><!-- /dash-left -->

                <!-- ══ DERECHA ══ -->
                <div class="dash-right">

                    <!-- Welcome -->
                    <div class="welcome-card">
                        <div class="welcome-avatar"><?= $inicial ?></div>
                        <div class="welcome-greeting"><?= $saludo ?>,</div>
                        <div class="welcome-name"><?= htmlspecialchars($nombre_display . ' ' . $apellido_display) ?></div>
                        <div class="welcome-sub">Tienes <?= $tickets_pendientes ?> tickets pendientes. Revisa la tabla para ver el detalle.</div>
                        <div class="welcome-date">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            <?= date('d M Y') ?>
                        </div>
                        <a href="nuevo_ticket.php" class="btn-nuevo">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Nuevo ticket
                        </a>
                    </div>

                    <!-- Resumen del día -->
                    <div class="panel-block">
                        <div class="panel-block__head">
                            <div class="panel-block__title">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                Resumen del día
                            </div>
                        </div>
                        <div class="day-summary">
                            <div class="day-item">
                                <div class="day-item__label">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
                                    Tickets ingresados
                                </div>
                                <div class="day-item__val"><?= $tickets_hoy ?></div>
                            </div>
                            <div class="day-item">
                                <div class="day-item__label">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                    Completados hoy
                                </div>
                                <div class="day-item__val day-item__val--green">2</div>
                            </div>
                            <div class="day-item">
                                <div class="day-item__label">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                    Ingresos hoy
                                </div>
                                <div class="day-item__val day-item__val--green">S/ 320</div>
                            </div>
                            <div class="day-item">
                                <div class="day-item__label">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg>
                                    Alertas de stock
                                </div>
                                <div class="day-item__val" style="color:#c94a00;">3</div>
                            </div>
                        </div>
                    </div>

                </div><!-- /dash-right -->

            </div><!-- /dash-layout -->
        </main>
    </div>
</div>
</body>
</html>