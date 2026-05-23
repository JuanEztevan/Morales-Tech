<?php
$nombre_usuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Juan';
$rol_usuario    = 'Trabajador';
$partes         = explode(' ', trim($nombre_usuario));
$inicial        = strtoupper(substr($partes[0], 0, 1));
$nombre_corto   = $partes[0];
$ventas = [
    ["id"=>"VT-0041","fecha"=>"2025-05-20","cliente"=>"Valeria Ramírez","ticket"=>"MT-8842","servicio"=>"Mantenimiento correctivo","productos"=>"SSD 512GB NVMe","total"=>355.00,"metodo"=>"Yape"],
    ["id"=>"VT-0042","fecha"=>"2025-05-21","cliente"=>"Andrés Ochante","ticket"=>"MT-8843","servicio"=>"Repotenciación (mano de obra)","productos"=>"—","total"=>50.00,"metodo"=>"Transferencia"],
    ["id"=>"VT-0043","fecha"=>"2025-05-22","cliente"=>"Diana Calderón","ticket"=>"MT-8844","servicio"=>"Diagnóstico","productos"=>"RAM DDR4 16GB","total"=>175.00,"metodo"=>"Yape"],
    ["id"=>"VT-0044","fecha"=>"2025-05-22","cliente"=>"Brenda Benites","ticket"=>null,"servicio"=>"—","productos"=>"Kit Destornilladores 128 en 1","total"=>95.00,"metodo"=>"Efectivo"],
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas — Morales Tech</title>
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
            --surface:   #ffffff;
            --surface2:  #f4f6fb;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Montserrat', sans-serif; background: var(--bg); color: var(--azul-prof); min-height: 100vh; }
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
        .page { padding: 28px; flex: 1; background: var(--bg); }
        .page__header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; gap: 16px; flex-wrap: wrap; }
        .page__title { font-size: 28px; font-weight: 800; letter-spacing: -.5px; color: var(--azul-prof); margin-bottom: 4px; }
        .page__subtitle { font-size: 13px; color: #a0a8bb; }

        /* ══ KPI CARDS ══ */
        .stats-row { display: grid; grid-template-columns: repeat(4,1fr); gap: 14px; margin-bottom: 24px; }
        .stat-card { background: var(--surface); border: 1px solid var(--border); border-radius: 16px; padding: 20px 22px 18px; box-shadow: 0 1px 4px rgba(0,0,25,.06); transition: box-shadow .15s, transform .15s; }
        .stat-card:hover { box-shadow: 0 6px 20px rgba(0,0,25,.10); transform: translateY(-1px); }
        .stat-card--highlight { background: var(--azul-tec); border-color: var(--azul-tec); }
        .stat-card__top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
        .stat-card__icon { width: 32px; height: 32px; border-radius: 8px; background: var(--surface2); display: grid; place-items: center; }
        .stat-card--highlight .stat-card__icon { background: rgba(255,255,255,.18); }
        .stat-card__icon svg { width: 15px; height: 15px; color: var(--azul-prof); }
        .stat-card--highlight .stat-card__icon svg { color: #fff; }
        .stat-badge { display: inline-flex; align-items: center; gap: 3px; font-size: 10px; font-weight: 700; padding: 3px 8px; border-radius: 50px; white-space: nowrap; }
        .stat-badge--up { background: rgba(24,131,237,.12); color: var(--celeste); }
        .stat-badge--neutral { background: var(--surface2); color: #7a8096; }
        .stat-card--highlight .stat-badge--up { background: rgba(255,255,255,.18); color: #fff; }
        .stat-badge svg { width: 10px; height: 10px; }
        .stat-card__label { font-size: 11px; font-weight: 600; color: #7a8096; margin-bottom: 5px; }
        .stat-card--highlight .stat-card__label { color: rgba(255,255,255,.60); }
        .stat-card__value { font-size: 26px; font-weight: 800; color: var(--azul-prof); letter-spacing: -.5px; line-height: 1; }
        .stat-card--highlight .stat-card__value { color: #fff; }
        .stat-card__sub { font-size: 11px; font-weight: 500; color: #a0a8bb; margin-top: 5px; }
        .stat-card--highlight .stat-card__sub { color: rgba(255,255,255,.45); }

        /* ══ DOS BLOQUES SEPARADOS lado a lado ══ */
        .charts-row { display: grid; grid-template-columns: 1fr 300px; gap: 16px; margin-bottom: 24px; align-items: start; }

        /* Bloque compartido base */
        .panel-block { background: var(--surface); border: 1px solid var(--border); border-radius: 16px; padding: 22px 24px 24px; box-shadow: 0 1px 4px rgba(0,0,25,.06); }
        .panel-block__header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 20px; gap: 12px; flex-wrap: wrap; }
        .panel-block__title { font-size: 14px; font-weight: 800; color: var(--azul-prof); }
        .panel-block__sub { font-size: 11px; color: #a0a8bb; font-weight: 500; margin-top: 2px; }

        /* ── Period selector ── */
        .period-selector { display: flex; background: var(--surface2); border-radius: 50px; padding: 3px; gap: 2px; }
        .period-btn { font-family: 'Montserrat',sans-serif; font-size: 11px; font-weight: 700; padding: 6px 13px; border-radius: 50px; border: none; background: transparent; color: #7a8096; cursor: pointer; transition: all .18s; white-space: nowrap; }
        .period-btn.active { background: var(--azul-tec); color: #fff; box-shadow: 0 2px 8px rgba(23,70,234,.25); }
        .period-btn:not(.active):hover { background: rgba(23,70,234,.07); color: var(--azul-tec); }

        /* ── Botón nueva venta ── */
        .btn-new { display: inline-flex; align-items: center; gap: 7px; background: var(--azul-prof); color: #fff; font-family: 'Montserrat',sans-serif; font-size: 12px; font-weight: 700; padding: 10px 20px; border-radius: 50px; border: none; cursor: pointer; text-decoration: none; white-space: nowrap; box-shadow: 0 3px 10px rgba(0,0,25,.22); transition: background .15s,transform .1s; width: 100%; justify-content: center; margin-top: 20px; }
        .btn-new:hover { background: var(--azul-tec); transform: translateY(-1px); }
        .btn-new svg { width: 14px; height: 14px; }

        /* ── Gráfico de barras ── */
        .chart-inner { display: flex; gap: 0; align-items: stretch; }
        .y-axis { display: flex; flex-direction: column; justify-content: space-between; align-items: flex-end; padding-bottom: 26px; padding-right: 8px; min-width: 40px; flex-shrink: 0; }
        .y-tick { font-size: 9px; font-weight: 700; color: #b0b8cc; white-space: nowrap; }
        .bars-wrapper { flex: 1; display: flex; flex-direction: column; }
        .bars-nav-row { display: flex; align-items: flex-end; gap: 4px; }
        .chart-nav { width: 24px; height: 24px; border-radius: 50%; border: 1.5px solid var(--border); background: #fff; display: grid; place-items: center; cursor: pointer; flex-shrink: 0; margin-bottom: 26px; transition: all .15s; }
        .chart-nav:hover { border-color: var(--azul-tec); color: var(--azul-tec); }
        .chart-nav svg { width: 12px; height: 12px; }
        .bars-area { flex: 1; display: flex; align-items: flex-end; gap: 5px; }

        /* Columna de barra individual */
        .bar-col { flex: 1; display: flex; flex-direction: column; align-items: center; min-width: 0; cursor: pointer; position: relative; }
        .bar-track { width: 100%; background: var(--surface2); border-radius: 5px 5px 0 0; position: relative; overflow: visible; }
        .bar-fill {
            width: 100%; position: absolute; bottom: 0; left: 0;
            border-radius: 5px 5px 0 0;
            transition: height .55s cubic-bezier(.34,1.4,.64,1), background .2s;
        }
        /* Estado base */
        .bar-fill--normal  { background: var(--azul-prof); }
        .bar-fill--current { background: var(--celeste); }
        /* Hover: la barra resaltada cambia a azul-tec */
        .bar-col:hover .bar-fill--normal  { background: var(--azul-tec); }
        .bar-col:hover .bar-fill--current { background: var(--azul-tec); }

        .bar-label { font-size: 9px; font-weight: 700; color: #a0a8bb; margin-top: 6px; white-space: nowrap; }
        .bar-label--current { color: var(--celeste); }

        /* Burbuja tooltip */
        .bar-bubble {
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%) translateY(-4px);
            background: var(--azul-prof);
            color: #fff;
            border-radius: 9px;
            padding: 6px 10px;
            font-size: 10px; font-weight: 700;
            white-space: nowrap;
            pointer-events: none;
            opacity: 0;
            transition: opacity .18s, transform .18s;
            z-index: 10;
            text-align: center;
            line-height: 1.5;
            box-shadow: 0 4px 14px rgba(0,0,25,.28);
        }
        .bar-bubble::after {
            content: '';
            position: absolute;
            top: 100%; left: 50%; transform: translateX(-50%);
            border: 5px solid transparent;
            border-top-color: var(--azul-prof);
        }
        .bar-col:hover .bar-bubble,
        .bar-col.bar-active .bar-bubble {
            opacity: 1;
            transform: translateX(-50%) translateY(-8px);
        }

        /* ── Donut métodos de pago ── */
        .donut-wrap { display: flex; flex-direction: column; align-items: center; gap: 16px; }
        .donut-svg-wrap { position: relative; width: 120px; height: 120px; }
        .donut-svg-wrap svg { width: 120px; height: 120px; transform: rotate(-90deg); }
        .donut-center { position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; }
        .donut-center__val { font-size: 14px; font-weight: 800; color: var(--azul-prof); line-height: 1; }
        .donut-center__lbl { font-size: 9px; font-weight: 600; color: #a0a8bb; margin-top: 2px; }
        .donut-legend { width: 100%; display: flex; flex-direction: column; gap: 10px; }
        .leg-row { display: flex; align-items: center; gap: 8px; }
        .leg-dot { width: 10px; height: 10px; border-radius: 3px; flex-shrink: 0; }
        .leg-name { font-size: 12px; font-weight: 600; color: var(--azul-prof); flex: 1; }
        .leg-val  { font-size: 12px; font-weight: 800; color: var(--azul-prof); }
        .leg-pct  { font-size: 10px; font-weight: 600; color: #a0a8bb; margin-left: 3px; }

        /* ══ FILTERS ══ */
        .filters-bar { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 16px; flex-wrap: wrap; }
        .filter-tabs { display: flex; gap: 6px; flex-wrap: wrap; }
        /* Un solo color de selección: azul-tec */
        .filter-tab {
            font-family: 'Montserrat',sans-serif; font-size: 12px; font-weight: 600;
            padding: 7px 16px; border-radius: 50px;
            border: 1.5px solid var(--border); background: #fff;
            color: #7a8096; cursor: pointer; transition: all .15s;
        }
        .filter-tab:hover { border-color: var(--azul-tec); color: var(--azul-tec); }
        .filter-tab.active {
            background: var(--azul-tec);
            border-color: var(--azul-tec);
            color: #fff;
        }
        .search-box { position: relative; }
        .search-box input { font-family: 'Montserrat',sans-serif; font-size: 13px; font-weight: 500; padding: 9px 16px 9px 38px; border: 1.5px solid var(--border); border-radius: 50px; background: #fff; color: var(--azul-prof); outline: none; width: 220px; transition: border-color .15s; }
        .search-box input:focus { border-color: var(--azul-tec); box-shadow: 0 0 0 3px rgba(23,70,234,.08); }
        .search-box input::placeholder { color: #c4c9d8; }
        .search-box svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: #a0a8bb; pointer-events: none; }

        /* ══ TABLE ══ */
        .table-card { background: #fff; border: 1px solid var(--border); border-radius: 14px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,25,.05); }
        .table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        table { width: 100%; border-collapse: collapse; min-width: 580px; }
        thead th { padding: 12px 18px; font-size: 10px; font-weight: 700; letter-spacing: .09em; text-transform: uppercase; color: #a0a8bb; text-align: left; border-bottom: 1px solid var(--border); white-space: nowrap; background: #fafbff; }
        tbody tr { border-bottom: 1px solid var(--border); transition: background .1s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: #f7f8fd; }
        tbody td { padding: 13px 18px; font-size: 13px; vertical-align: middle; }
        .venta-id { font-size: 13px; font-weight: 800; color: var(--azul-prof); }
        .cliente-nombre { font-weight: 600; font-size: 13px; line-height: 1.2; }
        .cliente-ticket { font-size: 10px; color: #a0a8bb; font-weight: 600; }
        .detalle-svc { font-weight: 700; color: var(--azul-prof); font-size: 12px; }
        .detalle-prod { color: #7a8096; font-size: 12px; font-weight: 500; margin-top: 1px; }
        .total-cell { font-size: 14px; font-weight: 800; color: var(--azul-prof); white-space: nowrap; }
        /* Método de pago: sin badge, solo texto plano */
        .metodo-plain { font-size: 13px; font-weight: 600; color: var(--azul-prof); }
        .fecha-cell { font-size: 12px; font-weight: 600; color: #a0a8bb; white-space: nowrap; }
        .table-footer { padding: 12px 18px; border-top: 1px solid var(--border); font-size: 12px; color: #a0a8bb; }

        /* RESPONSIVE */
        @media(max-width:1100px){ .charts-row{grid-template-columns:1fr;} }
        @media(max-width:900px){ .stats-row{grid-template-columns:1fr 1fr;} }
        @media(max-width:700px){ .sidebar{width:60px;min-width:60px;} .nav-link{width:46px;height:50px;font-size:0;} .sidebar__logout span{display:none;} .header__user-info{display:none;} .page{padding:18px 14px;} .header{padding:0 14px;} }
        @media(max-width:480px){ .sidebar{display:none;} .stats-row{grid-template-columns:1fr;} }
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
            <a href="dashboard.php" class="nav-link">
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
            <a href="ventas.php" class="nav-link nav-link--active">
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
            <div class="header__breadcrumb">Panel / <span>Ventas</span></div>
            <div class="header__user">
                <div class="header__avatar"><?= $inicial ?></div>
                <div class="header__user-info">
                    <span class="header__username"><?= htmlspecialchars($nombre_corto) ?></span>
                    <span class="header__user-role"><?= htmlspecialchars($rol_usuario) ?></span>
                </div>
            </div>
        </header>

        <main class="page">
            <div class="page__header">
                <div>
                    <h1 class="page__title">Ventas</h1>
                    <p class="page__subtitle">Historial de ingresos por servicios y productos</p>
                </div>
            </div>

            <!-- ══ KPI CARDS ══ -->
            <div class="stats-row">
                <div class="stat-card stat-card--highlight">
                    <div class="stat-card__top">
                        <div class="stat-card__icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        </div>
                        <span class="stat-badge stat-badge--up">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
                            2.4% vs mes ant.
                        </span>
                    </div>
                    <div class="stat-card__label">Ingresos del mes</div>
                    <div class="stat-card__value">S/ 675</div>
                    <div class="stat-card__sub">Mayo 2025</div>
                </div>
                <div class="stat-card">
                    <div class="stat-card__top">
                        <div class="stat-card__icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                        </div>
                        <span class="stat-badge stat-badge--up">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
                            6.2%
                        </span>
                    </div>
                    <div class="stat-card__label">Ventas este mes</div>
                    <div class="stat-card__value">4</div>
                    <div class="stat-card__sub">vs mes anterior</div>
                </div>
                <div class="stat-card">
                    <div class="stat-card__top">
                        <div class="stat-card__icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
                        </div>
                        <span class="stat-badge stat-badge--neutral">sin cambio</span>
                    </div>
                    <div class="stat-card__label">Ticket promedio</div>
                    <div class="stat-card__value">S/ 168</div>
                    <div class="stat-card__sub">por venta</div>
                </div>
                <div class="stat-card">
                    <div class="stat-card__top">
                        <div class="stat-card__icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </div>
                        <span class="stat-badge stat-badge--up">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
                            0.8%
                        </span>
                    </div>
                    <div class="stat-card__label">Clientes atendidos</div>
                    <div class="stat-card__value">4</div>
                    <div class="stat-card__sub">este mes</div>
                </div>
            </div>

            <!-- ══ DOS BLOQUES LADO A LADO ══ -->
            <div class="charts-row">

                <!-- BLOQUE 1: Gráfico de barras -->
                <div class="panel-block">
                    <div class="panel-block__header">
                        <div>
                            <div class="panel-block__title">Resumen de ventas</div>
                            <div class="panel-block__sub" id="chart-range-label">Ene 2025 — May 2025</div>
                        </div>
                        <div class="period-selector">
                            <button class="period-btn active" onclick="setPeriod('mes',this)">Mes</button>
                            <button class="period-btn" onclick="setPeriod('quincena',this)">Quincena</button>
                            <button class="period-btn" onclick="setPeriod('año',this)">Año</button>
                        </div>
                    </div>
                    <div class="chart-inner">
                        <div class="y-axis" id="y-axis"></div>
                        <div class="bars-wrapper">
                            <div class="bars-nav-row">
                                <button class="chart-nav" id="btn-prev" onclick="navChart(-1)">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                                </button>
                                <div class="bars-area" id="bars-area"></div>
                                <button class="chart-nav" id="btn-next" onclick="navChart(1)">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BLOQUE 2: Métodos de pago + botón registrar -->
                <div class="panel-block">
                    <div class="panel-block__header">
                        <div>
                            <div class="panel-block__title">Métodos de pago</div>
                            <div class="panel-block__sub">Distribución del mes actual</div>
                        </div>
                    </div>
                    <div class="donut-wrap">
                        <div class="donut-svg-wrap">
                            <!-- circunferencia r=44 → 2π×44 ≈ 276.46 -->
                            <svg viewBox="0 0 110 110">
                                <circle cx="55" cy="55" r="44" fill="none" stroke="#e6e9f0" stroke-width="12"/>
                                <!-- Yape: 50% → 138.23 -->
                                <circle cx="55" cy="55" r="44" fill="none" stroke="var(--azul-prof)" stroke-width="12"
                                    stroke-dasharray="138.23 276.46" stroke-dashoffset="0" stroke-linecap="round"/>
                                <!-- Transferencia: 25% → 69.11, offset -138.23 -->
                                <circle cx="55" cy="55" r="44" fill="none" stroke="var(--celeste)" stroke-width="12"
                                    stroke-dasharray="69.11 276.46" stroke-dashoffset="-138.23" stroke-linecap="round"/>
                                <!-- Efectivo: 25% → 69.11, offset -207.34 -->
                                <circle cx="55" cy="55" r="44" fill="none" stroke="var(--azul-tec)" stroke-width="12"
                                    stroke-dasharray="69.11 276.46" stroke-dashoffset="-207.34" stroke-linecap="round"/>
                            </svg>
                            <div class="donut-center">
                                <span class="donut-center__val">S/ 675</span>
                                <span class="donut-center__lbl">total</span>
                            </div>
                        </div>
                        <div class="donut-legend">
                            <div class="leg-row">
                                <div class="leg-dot" style="background:var(--azul-prof)"></div>
                                <span class="leg-name">Yape</span>
                                <span class="leg-val">S/ 530</span>
                                <span class="leg-pct">50%</span>
                            </div>
                            <div class="leg-row">
                                <div class="leg-dot" style="background:var(--celeste)"></div>
                                <span class="leg-name">Transferencia</span>
                                <span class="leg-val">S/ 50</span>
                                <span class="leg-pct">25%</span>
                            </div>
                            <div class="leg-row">
                                <div class="leg-dot" style="background:var(--azul-tec)"></div>
                                <span class="leg-name">Efectivo</span>
                                <span class="leg-val">S/ 95</span>
                                <span class="leg-pct">25%</span>
                            </div>
                        </div>
                    </div>
                    <a href="nueva_venta.php" class="btn-new">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Registrar venta
                    </a>
                </div>
            </div>

            <!-- ══ FILTERS + TABLE ══ -->
            <div class="filters-bar">
                <div class="filter-tabs">
                    <button class="filter-tab active" onclick="filtrar('Todos',this)">Todas</button>
                    <button class="filter-tab" onclick="filtrar('ticket',this)">Con ticket</button>
                    <button class="filter-tab" onclick="filtrar('producto',this)">Solo producto</button>
                </div>
                <div class="search-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" placeholder="Buscar cliente o ID…" oninput="buscar(this.value)">
                </div>
            </div>

            <div class="table-card">
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>ID Venta</th>
                                <th>Cliente</th>
                                <th>Servicio / Producto</th>
                                <th>Método</th>
                                <th>Total</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody id="ventas-tbody">
                        <?php foreach ($ventas as $v):
                            $tipo = $v['ticket'] ? 'ticket' : 'producto';
                        ?>
                        <tr data-tipo="<?= $tipo ?>" data-search="<?= strtolower(htmlspecialchars($v['cliente'].' '.$v['id'])) ?>">
                            <td><span class="venta-id">#<?= htmlspecialchars($v['id']) ?></span></td>
                            <td>
                                <div class="cliente-nombre"><?= htmlspecialchars($v['cliente']) ?></div>
                                <?php if ($v['ticket']): ?>
                                <div class="cliente-ticket">Ticket #<?= htmlspecialchars($v['ticket']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($v['servicio'] !== '—'): ?>
                                <div class="detalle-svc"><?= htmlspecialchars($v['servicio']) ?></div>
                                <?php endif; ?>
                                <?php if ($v['productos'] !== '—'): ?>
                                <div class="detalle-prod"><?= htmlspecialchars($v['productos']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td><span class="metodo-plain"><?= htmlspecialchars($v['metodo']) ?></span></td>
                            <td><span class="total-cell">S/ <?= number_format($v['total'],2) ?></span></td>
                            <td><span class="fecha-cell"><?= date('d/m/Y', strtotime($v['fecha'])) ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="table-footer">
                    <span id="footer-count">4 ventas</span>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
/* ═══ CHART DATA ═══ */
const MONTHS_ES = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
const DATA_MES = [
    { label:'Ene', year:2025, val:18400 },
    { label:'Feb', year:2025, val:22100 },
    { label:'Mar', year:2025, val:15800 },
    { label:'Abr', year:2025, val:30200 },
    { label:'May', year:2025, val:27500 },
    { label:'Jun', year:2025, val:19300 },
    { label:'Jul', year:2025, val:24100 },
    { label:'Ago', year:2025, val:31000 },
    { label:'Sep', year:2025, val:20700 },
    { label:'Oct', year:2025, val:28400 },
    { label:'Nov', year:2025, val:23900 },
    { label:'Dic', year:2025, val:35200 },
];
const DATA_QUINCENA = DATA_MES.flatMap((m,i) => [
    { label: MONTHS_ES[i]+' 1ª', year: m.year, val: Math.round(m.val * 0.45) },
    { label: MONTHS_ES[i]+' 2ª', year: m.year, val: Math.round(m.val * 0.55) },
]);
const DATA_ANIO = [
    { label:'2022', year:2022, val:185000 },
    { label:'2023', year:2023, val:243000 },
    { label:'2024', year:2024, val:310000 },
    { label:'2025', year:2025, val:98000 },
];

const TODAY_IDX_MES = 4; // Mayo
const VISIBLE  = 8;
const CHART_H  = 200;

let period     = 'mes';
let offset     = 0;
let dataset    = DATA_MES;
let currentIdx = TODAY_IDX_MES;

/* Formatea valores en miles con "mil" en español */
function fmtVal(v) {
    if (v >= 1000000) return 'S/ ' + (v / 1000000).toFixed(1).replace('.0','') + ' millón';
    if (v >= 1000)    return 'S/ ' + (v / 1000).toFixed(v % 1000 === 0 ? 0 : 1) + ' mil';
    return 'S/ ' + v;
}
function fmtYTick(v) {
    if (v >= 1000000) return (v/1000000).toFixed(0) + 'M';
    if (v >= 1000)    return (v/1000).toFixed(0) + ' mil';
    return v === 0 ? '0' : String(v);
}

function setPeriod(p, el) {
    period = p;
    document.querySelectorAll('.period-btn').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
    if (p === 'mes')           { dataset = DATA_MES;       currentIdx = TODAY_IDX_MES; }
    else if (p === 'quincena') { dataset = DATA_QUINCENA;  currentIdx = TODAY_IDX_MES * 2 + 1; }
    else                       { dataset = DATA_ANIO;      currentIdx = DATA_ANIO.length - 1; }
    offset = Math.max(0, currentIdx - VISIBLE + 1);
    renderChart();
}

function navChart(dir) {
    offset = Math.max(0, Math.min(dataset.length - VISIBLE, offset + dir * VISIBLE));
    renderChart();
}

function renderChart() {
    const barsEl  = document.getElementById('bars-area');
    const yAxisEl = document.getElementById('y-axis');
    const slice   = dataset.slice(offset, offset + VISIBLE);
    const maxVal  = Math.max(...dataset.map(d => d.val), 1);

    document.getElementById('btn-prev').style.opacity = offset > 0 ? '1' : '0.25';
    document.getElementById('btn-next').style.opacity = (offset + VISIBLE < dataset.length) ? '1' : '0.25';

    const first = slice[0], last = slice[slice.length - 1];
    document.getElementById('chart-range-label').textContent = period === 'año'
        ? `${first.label} — ${last.label}`
        : `${first.label} ${first.year} — ${last.label} ${last.year}`;

    /* Y-axis: 5 ticks redondeados */
    const rawStep = maxVal / 4;
    const mag  = Math.pow(10, Math.floor(Math.log10(rawStep)));
    const step = Math.ceil(rawStep / mag) * mag;
    const yMax = step * 4;
    const ticks = [yMax, yMax * 0.75, yMax * 0.5, yMax * 0.25, 0];

    yAxisEl.style.height = (CHART_H + 26) + 'px';
    yAxisEl.innerHTML = ticks.map(t =>
        `<span class="y-tick">${fmtYTick(t)}</span>`
    ).join('');

    /* Barras */
    barsEl.style.height = CHART_H + 'px';
    barsEl.innerHTML = slice.map((d, i) => {
        const gIdx    = offset + i;
        const isCur   = gIdx === currentIdx;
        const fillH   = Math.max(4, Math.round((d.val / yMax) * CHART_H));
        const fillCls = isCur ? 'bar-fill--current' : 'bar-fill--normal';
        const lblCls  = isCur ? 'bar-label--current' : '';
        const t1      = period === 'año' ? d.label : `${d.label} ${d.year}`;
        const t2      = fmtVal(d.val);
        return `
        <div class="bar-col${isCur ? ' bar-active' : ''}" data-val="${d.val}">
            <div class="bar-bubble">${t1}<br><strong>${t2}</strong></div>
            <div class="bar-track" style="height:${CHART_H}px;">
                <div class="bar-fill ${fillCls}" style="height:0" data-h="${fillH}px"></div>
            </div>
            <span class="bar-label ${lblCls}">${d.label}</span>
        </div>`;
    }).join('');

    requestAnimationFrame(() => {
        barsEl.querySelectorAll('.bar-fill').forEach(b => { b.style.height = b.dataset.h; });
    });
}

offset = Math.max(0, currentIdx - VISIBLE + 1);
renderChart();

/* ═══ TABLE FILTERS ═══ */
let filtroTipo = 'Todos';
let busqueda   = '';

function filtrar(val, el) {
    filtroTipo = val;
    document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
    aplicarFiltros();
}
function buscar(q) { busqueda = q.toLowerCase(); aplicarFiltros(); }
function aplicarFiltros() {
    let visible = 0;
    document.querySelectorAll('#ventas-tbody tr').forEach(row => {
        const tipo   = row.dataset.tipo  || '';
        const search = row.dataset.search || '';
        const show   = (filtroTipo === 'Todos' || tipo === filtroTipo) &&
                       (!busqueda || search.includes(busqueda));
        row.style.display = show ? '' : 'none';
        if (show) visible++;
    });
    document.getElementById('footer-count').textContent = visible + ' venta' + (visible !== 1 ? 's' : '');
}
</script>
</body>
</html>