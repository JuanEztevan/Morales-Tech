<?php
$nombre_usuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Juan';
$rol_usuario    = 'Trabajador';
$partes         = explode(' ', trim($nombre_usuario));
$inicial        = strtoupper(substr($partes[0], 0, 1));
$nombre_corto   = $partes[0];

$productos = [
    ["id"=>1, "nombre"=>"Alcohol Isopropílico 1000ml",              "categoria"=>"Consumibles y Limpieza", "precio"=>25,  "stock"=>45],
    ["id"=>2, "nombre"=>"Pasta Térmica (jeringa 5g)",                "categoria"=>"Consumibles y Limpieza", "precio"=>18,  "stock"=>30],
    ["id"=>3, "nombre"=>"Kit Destornilladores 58 en 1",              "categoria"=>"Herramientas y Kits",    "precio"=>65,  "stock"=>18],
    ["id"=>4, "nombre"=>"Kit Destornilladores de Precisión 128 en 1","categoria"=>"Herramientas y Kits",    "precio"=>95,  "stock"=>12],
    ["id"=>5, "nombre"=>"SSD 1TB SATA",                              "categoria"=>"Almacenamiento",         "precio"=>195, "stock"=>10],
    ["id"=>6, "nombre"=>"SSD 512GB NVMe",                            "categoria"=>"Almacenamiento",         "precio"=>165, "stock"=>14],
    ["id"=>7, "nombre"=>"RAM DDR4 16GB 3200MHz",                     "categoria"=>"Memoria RAM",             "precio"=>145, "stock"=>20],
    ["id"=>8, "nombre"=>"RAM DDR5 16GB 4800MHz",                     "categoria"=>"Memoria RAM",             "precio"=>185, "stock"=>9],
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario — Morales Tech</title>
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
        .header__username  { font-size: 13px; font-weight: 700; color: var(--azul-prof); line-height: 1.1; }
        .header__user-role { font-size: 11px; font-weight: 500; color: #a0a8bb; }

        /* ══ PAGE ══ */
        .page { padding: 28px; flex: 1; background: var(--bg); }
        .page__header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; gap: 16px; flex-wrap: wrap; }
        .page__title    { font-size: 28px; font-weight: 800; letter-spacing: -.5px; color: var(--azul-prof); margin-bottom: 4px; }
        .page__subtitle { font-size: 13px; color: #a0a8bb; }

        /* ══ BTN NEW ══ */
        .btn-new { display: inline-flex; align-items: center; gap: 7px; background: var(--azul-prof); color: #fff; font-family: 'Montserrat',sans-serif; font-size: 12px; font-weight: 700; padding: 10px 20px; border-radius: 50px; border: none; cursor: pointer; text-decoration: none; white-space: nowrap; box-shadow: 0 3px 10px rgba(0,0,25,.22); transition: background .15s,transform .1s; }
        .btn-new:hover { background: var(--azul-tec); transform: translateY(-1px); }
        .btn-new svg { width: 14px; height: 14px; }

        /* ══ KPI CARDS — mismo estilo ventas.php ══ */
        .stats-row { display: grid; grid-template-columns: repeat(4,1fr); gap: 14px; margin-bottom: 24px; }
        .stat-card { background: var(--surface); border: 1px solid var(--border); border-radius: 16px; padding: 20px 22px 18px; box-shadow: 0 1px 4px rgba(0,0,25,.06); transition: box-shadow .15s,transform .15s; }
        .stat-card:hover { box-shadow: 0 6px 20px rgba(0,0,25,.10); transform: translateY(-1px); }
        .stat-card--highlight { background: var(--azul-tec); border-color: var(--azul-tec); }
        .stat-card__top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
        .stat-card__icon { width: 32px; height: 32px; border-radius: 8px; background: var(--surface2); display: grid; place-items: center; }
        .stat-card--highlight .stat-card__icon { background: rgba(255,255,255,.18); }
        .stat-card__icon svg { width: 15px; height: 15px; color: var(--azul-prof); }
        .stat-card--highlight .stat-card__icon svg { color: #fff; }
        .stat-badge { display: inline-flex; align-items: center; gap: 3px; font-size: 10px; font-weight: 700; padding: 3px 8px; border-radius: 50px; white-space: nowrap; }
        .stat-badge--ok      { background: rgba(26,122,74,.10); color: #1a7a4a; }
        .stat-badge--warn    { background: rgba(201,74,0,.10);  color: #c94a00; }
        .stat-badge--neutral { background: var(--surface2); color: #7a8096; }
        .stat-card__label { font-size: 11px; font-weight: 600; color: #7a8096; margin-bottom: 5px; }
        .stat-card--highlight .stat-card__label { color: rgba(255,255,255,.60); }
        .stat-card__value { font-size: 26px; font-weight: 800; color: var(--azul-prof); letter-spacing: -.5px; line-height: 1; }
        .stat-card--highlight .stat-card__value { color: #fff; }
        .stat-card__sub { font-size: 11px; font-weight: 500; color: #a0a8bb; margin-top: 5px; }
        .stat-card--highlight .stat-card__sub { color: rgba(255,255,255,.45); }

        /* ══ FILTERS ══ */
        .filters-bar { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 16px; flex-wrap: wrap; }
        .filter-tabs { display: flex; gap: 6px; flex-wrap: wrap; }
        .filter-tab { font-family: 'Montserrat',sans-serif; font-size: 12px; font-weight: 600; padding: 7px 16px; border-radius: 50px; border: 1.5px solid var(--border); background: #fff; color: #7a8096; cursor: pointer; transition: all .15s; text-decoration: none; }
        .filter-tab:hover  { border-color: var(--azul-tec); color: var(--azul-tec); }
        .filter-tab.active { background: var(--azul-tec); border-color: var(--azul-tec); color: #fff; }
        .search-box { position: relative; }
        .search-box input { font-family: 'Montserrat',sans-serif; font-size: 13px; font-weight: 500; padding: 9px 16px 9px 38px; border: 1.5px solid var(--border); border-radius: 50px; background: #fff; color: var(--azul-prof); outline: none; width: 220px; transition: border-color .15s; }
        .search-box input:focus { border-color: var(--azul-tec); box-shadow: 0 0 0 3px rgba(23,70,234,.08); }
        .search-box input::placeholder { color: #c4c9d8; }
        .search-box svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: #a0a8bb; pointer-events: none; }

        /* ══ TABLE CARD ══ */
        .table-card { background: #fff; border: 1px solid var(--border); border-radius: 14px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,25,.05); }
        .table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        table { width: 100%; border-collapse: collapse; min-width: 700px; }
        thead th { padding: 12px 18px; font-size: 10px; font-weight: 700; letter-spacing: .09em; text-transform: uppercase; color: #a0a8bb; text-align: left; border-bottom: 1px solid var(--border); white-space: nowrap; background: #fafbff; }
        tbody tr { border-bottom: 1px solid var(--border); transition: background .1s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: #f7f8fd; }
        tbody td { padding: 13px 18px; font-size: 13px; vertical-align: middle; }

        /* Producto */
        .prod-cell { display: flex; align-items: center; gap: 12px; }
        .prod-icon { width: 36px; height: 36px; border-radius: 10px; flex-shrink: 0; background: linear-gradient(135deg,#edf1fd,#e4eeff); display: grid; place-items: center; }
        .prod-icon svg { width: 17px; height: 17px; color: var(--azul-tec); }
        .prod-name { font-size: 13px; font-weight: 700; color: var(--azul-prof); line-height: 1.2; }
        .prod-id   { font-size: 10px; font-weight: 600; color: #b0b8cc; margin-top: 1px; }

        /* Categoría */
        .cat-badge { display: inline-flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 700; padding: 4px 12px; border-radius: 50px; white-space: nowrap; }
        .cat--consumibles    { background: #fff8e6; color: #9a6c00; }
        .cat--herramientas   { background: #f0f6ff; color: #1746EA; }
        .cat--almacenamiento { background: #e6f8f0; color: #1a7a4a; }
        .cat--ram            { background: #fff0eb; color: #c94a00; }

        /* Precio */
        .precio-cell { font-size: 14px; font-weight: 800; color: var(--azul-prof); }

        /* Stock */
        .stock-badge { display: inline-flex; align-items: center; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 50px; }
        .stock--ok  { background: #e6f8f0; color: #1a7a4a; }
        .stock--low { background: #fff8e6; color: #9a6c00; }
        .stock--min { background: #fff0eb; color: #c94a00; }

        /* Cantidad */
        .qty-control { display: flex; align-items: center; gap: 6px; }
        .qty-btn { width: 28px; height: 28px; border-radius: 50%; border: 1.5px solid var(--border); background: #fff; color: var(--azul-prof); font-size: 16px; font-weight: 700; display: grid; place-items: center; cursor: pointer; transition: all .15s; line-height: 1; flex-shrink: 0; }
        .qty-btn:hover { border-color: var(--azul-tec); color: var(--azul-tec); background: #edf1fd; }
        .qty-btn:active { transform: scale(.92); }
        .qty-num { width: 36px; text-align: center; font-size: 14px; font-weight: 800; color: var(--azul-prof); user-select: none; }

        /* Actualizar */
        .btn-add-row { display: inline-flex; align-items: center; gap: 6px; background: linear-gradient(135deg,var(--azul-tec),var(--celeste)); color: #fff; font-family: 'Montserrat',sans-serif; font-size: 12px; font-weight: 700; padding: 7px 16px; border-radius: 50px; border: none; cursor: pointer; white-space: nowrap; transition: opacity .15s,transform .1s; box-shadow: 0 3px 10px rgba(23,70,234,.22); }
        .btn-add-row:hover { opacity: .88; transform: translateY(-1px); }
        .btn-add-row svg { width: 13px; height: 13px; }

        /* ══ TABLE FOOTER CON PAGINACIÓN ══ */
        .table-footer { padding: 12px 18px; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; font-size: 12px; color: #a0a8bb; }
        .pagination { display: flex; align-items: center; gap: 6px; }
        .pag-btn { display: inline-flex; align-items: center; gap: 5px; font-family: 'Montserrat',sans-serif; font-size: 12px; font-weight: 700; padding: 6px 14px; border-radius: 50px; border: 1.5px solid var(--border); background: #fff; color: #7a8096; cursor: pointer; transition: all .15s; }
        .pag-btn:hover:not(:disabled) { border-color: var(--azul-tec); color: var(--azul-tec); }
        .pag-btn:disabled { opacity: .35; cursor: not-allowed; }
        .pag-btn svg { width: 12px; height: 12px; }
        .pag-info { font-size: 12px; font-weight: 600; color: #a0a8bb; padding: 0 4px; }

        /* ══ MODAL ══ */
        .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,25,.35); backdrop-filter:blur(4px); z-index:100; align-items:center; justify-content:center; }
        .modal-overlay.open { display:flex; }
        .modal { background:#fff; border-radius:20px; padding:28px; width:100%; max-width:460px; box-shadow:0 24px 60px rgba(0,0,25,.18); animation:slideUp .22s ease; }
        @keyframes slideUp { from{transform:translateY(20px);opacity:0} to{transform:translateY(0);opacity:1} }
        .modal__header { display:flex; align-items:center; gap:12px; margin-bottom:22px; }
        .modal__icon { width:40px; height:40px; border-radius:12px; background:linear-gradient(135deg,var(--azul-tec),var(--celeste)); display:grid; place-items:center; flex-shrink:0; }
        .modal__icon svg { width:18px; height:18px; color:#fff; }
        .modal__title { font-size:16px; font-weight:800; color:var(--azul-prof); }
        .modal__sub   { font-size:11px; font-weight:500; color:#a0a8bb; margin-top:1px; }
        .modal__close { margin-left:auto; background:none; border:none; cursor:pointer; color:#a0a8bb; font-size:20px; line-height:1; padding:4px; }
        .modal__close:hover { color:var(--azul-prof); }
        .modal-form { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
        .modal-form .full { grid-column:span 2; }
        .modal-form label { font-size:11px; font-weight:700; color:#7a8096; letter-spacing:.04em; text-transform:uppercase; display:block; margin-bottom:6px; }
        .modal-form input, .modal-form select { font-family:'Montserrat',sans-serif; width:100%; padding:10px 16px; border:1.5px solid var(--border); border-radius:50px; font-size:13px; font-weight:500; color:var(--azul-prof); background:#fff; outline:none; -webkit-appearance:none; appearance:none; transition:border-color .15s; }
        .modal-form select { background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23a0a8bb' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 14px center; padding-right:36px; cursor:pointer; }
        .modal-form input:focus, .modal-form select:focus { border-color:var(--azul-tec); box-shadow:0 0 0 3px rgba(23,70,234,.10); }
        .modal-form input::placeholder { color:#c4c9d8; }
        .modal__actions { display:flex; gap:10px; margin-top:22px; }
        .btn-modal-cancel { flex:1; padding:12px; border-radius:50px; border:1.5px solid var(--border); background:#fff; font-family:'Montserrat',sans-serif; font-size:13px; font-weight:700; color:#7a8096; cursor:pointer; transition:all .15s; }
        .btn-modal-cancel:hover { border-color:var(--azul-tec); color:var(--azul-tec); }
        .btn-modal-save { flex:2; padding:12px; border-radius:50px; border:none; background:linear-gradient(135deg,var(--azul-tec),var(--celeste)); font-family:'Montserrat',sans-serif; font-size:13px; font-weight:800; color:#fff; cursor:pointer; transition:opacity .15s,transform .1s; box-shadow:0 4px 14px rgba(23,70,234,.25); }
        .btn-modal-save:hover { opacity:.88; transform:translateY(-1px); }

        /* ══ RESPONSIVE ══ */
        @media(max-width:900px)  { .stats-row { grid-template-columns:1fr 1fr; } }
        @media(max-width:700px)  { .sidebar{width:60px;min-width:60px;} .nav-link{width:46px;height:50px;font-size:0;} .sidebar__logout span{display:none;} .header__user-info{display:none;} .page{padding:18px 14px;} .header{padding:0 14px;} }
        @media(max-width:480px)  { .sidebar{display:none;} .stats-row{grid-template-columns:1fr;} }
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
            <a href="inventario.php" class="nav-link nav-link--active">
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
            <div class="header__breadcrumb">Panel / <span>Inventario</span></div>
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
                    <h1 class="page__title">Inventario</h1>
                    <p class="page__subtitle">Gestiona el stock de productos y materiales</p>
                </div>
                <button class="btn-new" onclick="abrirModal()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Nuevo producto
                </button>
            </div>

            <!-- ══ KPI CARDS — estilo ventas.php ══ -->
            <div class="stats-row">
                <!-- Productos totales — highlight -->
                <div class="stat-card stat-card--highlight">
                    <div class="stat-card__top">
                        <div class="stat-card__icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                        </div>
                        <span class="stat-badge stat-badge--neutral">8 registros</span>
                    </div>
                    <div class="stat-card__label">Productos en catálogo</div>
                    <div class="stat-card__value">8</div>
                    <div class="stat-card__sub">4 categorías activas</div>
                </div>
                <!-- Unidades -->
                <div class="stat-card">
                    <div class="stat-card__top">
                        <div class="stat-card__icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                        </div>
                        <span class="stat-badge stat-badge--ok">en stock</span>
                    </div>
                    <div class="stat-card__label">Unidades totales</div>
                    <div class="stat-card__value">158</div>
                    <div class="stat-card__sub">suma de todos los productos</div>
                </div>
                <!-- Stock bajo -->
                <div class="stat-card">
                    <div class="stat-card__top">
                        <div class="stat-card__icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        </div>
                        <span class="stat-badge stat-badge--warn">requieren atención</span>
                    </div>
                    <div class="stat-card__label">Productos con stock bajo</div>
                    <div class="stat-card__value">3</div>
                    <div class="stat-card__sub">menos de 15 unidades</div>
                </div>
                <!-- Valor del stock -->
                <div class="stat-card">
                    <div class="stat-card__top">
                        <div class="stat-card__icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        </div>
                        <span class="stat-badge stat-badge--ok">valorizado</span>
                    </div>
                    <div class="stat-card__label">Valor del stock</div>
                    <div class="stat-card__value">S/ 9,935</div>
                    <div class="stat-card__sub">a precio de venta</div>
                </div>
            </div>

            <!-- FILTERS + SEARCH -->
            <div class="filters-bar">
                <div class="filter-tabs">
                    <a href="#" class="filter-tab active" onclick="filtrar(event,'Todos')">Todos</a>
                    <a href="#" class="filter-tab" onclick="filtrar(event,'Consumibles y Limpieza')">Consumibles</a>
                    <a href="#" class="filter-tab" onclick="filtrar(event,'Herramientas y Kits')">Herramientas</a>
                    <a href="#" class="filter-tab" onclick="filtrar(event,'Almacenamiento')">Almacenamiento</a>
                    <a href="#" class="filter-tab" onclick="filtrar(event,'Memoria RAM')">Memoria RAM</a>
                </div>
                <div class="search-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" id="search-input" placeholder="Buscar producto…" oninput="buscar(this.value)">
                </div>
            </div>

            <!-- TABLE con paginación de 8 por página -->
            <div class="table-card">
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th>Precio unit.</th>
                                <th>Stock</th>
                                <th>Cantidad</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="inv-tbody">
                        <?php foreach ($productos as $p):
                            $stock       = $p['stock'];
                            $stock_class = $stock >= 20 ? 'stock--ok' : ($stock >= 10 ? 'stock--low' : 'stock--min');
                            $cat_class   = match($p['categoria']) {
                                'Consumibles y Limpieza' => 'cat--consumibles',
                                'Herramientas y Kits'    => 'cat--herramientas',
                                'Almacenamiento'         => 'cat--almacenamiento',
                                'Memoria RAM'            => 'cat--ram',
                                default                  => ''
                            };
                        ?>
                        <tr data-cat="<?= htmlspecialchars($p['categoria']) ?>" data-nombre="<?= strtolower(htmlspecialchars($p['nombre'])) ?>">
                            <td>
                                <div class="prod-cell">
                                    <div class="prod-icon">
                                        <?php
                                        $cat = $p['categoria'];
                                        if ($cat === 'Consumibles y Limpieza')
                                            echo '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>';
                                        elseif ($cat === 'Herramientas y Kits')
                                            echo '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>';
                                        elseif ($cat === 'Almacenamiento')
                                            echo '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg>';
                                        else
                                            echo '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="2" y1="10" x2="22" y2="10"/></svg>';
                                        ?>
                                    </div>
                                    <div>
                                        <div class="prod-name"><?= htmlspecialchars($p['nombre']) ?></div>
                                        <div class="prod-id">#<?= str_pad($p['id'],4,'0',STR_PAD_LEFT) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="cat-badge <?= $cat_class ?>"><?= htmlspecialchars($p['categoria']) ?></span></td>
                            <td><span class="precio-cell">S/ <?= number_format($p['precio'],2) ?></span></td>
                            <td>
                                <span class="stock-badge <?= $stock_class ?>" id="stock-label-<?= $p['id'] ?>">
                                    <?= $stock ?> uds.
                                </span>
                            </td>
                            <td>
                                <div class="qty-control">
                                    <button class="qty-btn" onclick="cambiarQty(<?= $p['id'] ?>,-1)">−</button>
                                    <span class="qty-num" id="qty-<?= $p['id'] ?>"><?= $stock ?></span>
                                    <button class="qty-btn" onclick="cambiarQty(<?= $p['id'] ?>,1)">+</button>
                                </div>
                            </td>
                            <td>
                                <button class="btn-add-row" onclick="guardarCambio(<?= $p['id'] ?>)">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                    Actualizar
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <!-- Footer: solo contador + paginación -->
                <div class="table-footer">
                    <span id="footer-count">8 productos</span>
                    <div class="pagination">
                        <button class="pag-btn" id="btn-prev-pag" onclick="cambiarPagina(-1)" disabled>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                            Anterior
                        </button>
                        <span class="pag-info" id="pag-info">Pág. 1 de 1</span>
                        <button class="pag-btn" id="btn-next-pag" onclick="cambiarPagina(1)" disabled>
                            Siguiente
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                        </button>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<!-- ══ MODAL ══ -->
<div class="modal-overlay" id="modal-overlay" onclick="cerrarModalOverlay(event)">
    <div class="modal">
        <div class="modal__header">
            <div class="modal__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
            </div>
            <div>
                <div class="modal__title">Nuevo Producto</div>
                <div class="modal__sub">Añadir al inventario</div>
            </div>
            <button class="modal__close" onclick="cerrarModal()">×</button>
        </div>
        <div class="modal-form">
            <div class="full">
                <label>Nombre del producto *</label>
                <input type="text" id="m-nombre" placeholder="Ej. Cable HDMI 2m">
            </div>
            <div>
                <label>Categoría *</label>
                <select id="m-categoria">
                    <option value="">Seleccionar…</option>
                    <option>Consumibles y Limpieza</option>
                    <option>Herramientas y Kits</option>
                    <option>Almacenamiento</option>
                    <option>Memoria RAM</option>
                </select>
            </div>
            <div>
                <label>Precio unitario (S/) *</label>
                <input type="text" id="m-precio" placeholder="Ej. 45.00">
            </div>
            <div>
                <label>Stock inicial *</label>
                <input type="text" id="m-stock" placeholder="Ej. 20">
            </div>
            <div>
                <label>Código / SKU <span style="font-size:10px;font-weight:500;color:#b8bfcc;text-transform:none;">opcional</span></label>
                <input type="text" id="m-sku" placeholder="Ej. SKU-001">
            </div>
        </div>
        <div class="modal__actions">
            <button class="btn-modal-cancel" onclick="cerrarModal()">Cancelar</button>
            <button class="btn-modal-save" onclick="guardarNuevo()">Guardar producto</button>
        </div>
    </div>
</div>

<script>
/* ══ ESTADO ══ */
const POR_PAGINA  = 8;
let paginaActual  = 1;
let filtroActivo  = 'Todos';
let busquedaActual = '';

const stockBase = {};
document.querySelectorAll('[id^="qty-"]').forEach(el => {
    const id = el.id.replace('qty-','');
    stockBase[id] = parseInt(el.textContent);
});

/* ══ CANTIDAD ══ */
function cambiarQty(id, delta) {
    const el = document.getElementById('qty-' + id);
    let v = parseInt(el.textContent) + delta;
    if (v < 0) v = 0;
    el.textContent = v;
}
function guardarCambio(id) {
    const qty   = parseInt(document.getElementById('qty-' + id).textContent);
    const label = document.getElementById('stock-label-' + id);
    label.textContent = qty + ' uds.';
    label.className   = 'stock-badge ' + (qty >= 20 ? 'stock--ok' : qty >= 10 ? 'stock--low' : 'stock--min');
    stockBase[id] = qty;
    const btn  = event.target.closest('button');
    const orig = btn.innerHTML;
    btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:13px;height:13px"><polyline points="20 6 9 17 4 12"/></svg> Guardado';
    btn.style.background = 'linear-gradient(135deg,#1a7a4a,#2ecc71)';
    setTimeout(() => { btn.innerHTML = orig; btn.style.background = ''; }, 1800);
}

/* ══ FILTROS ══ */
function filtrar(e, cat) {
    e.preventDefault();
    filtroActivo = cat;
    paginaActual = 1;
    document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
    e.target.classList.add('active');
    aplicarFiltros();
}
function buscar(q) { busquedaActual = q.toLowerCase().trim(); paginaActual = 1; aplicarFiltros(); }

/* ══ PAGINACIÓN ══ */
function cambiarPagina(dir) { paginaActual += dir; aplicarFiltros(); }

function aplicarFiltros() {
    const todasLasFilas = Array.from(document.querySelectorAll('#inv-tbody tr'));

    // 1. Filtrar las visibles según categoría y búsqueda
    const visibles = todasLasFilas.filter(row => {
        const matchCat = filtroActivo === 'Todos' || row.dataset.cat === filtroActivo;
        const matchQ   = !busquedaActual || row.dataset.nombre.includes(busquedaActual);
        return matchCat && matchQ;
    });

    const total   = visibles.length;
    const paginas = Math.max(1, Math.ceil(total / POR_PAGINA));
    paginaActual  = Math.min(paginaActual, paginas);

    const inicio = (paginaActual - 1) * POR_PAGINA;
    const fin    = inicio + POR_PAGINA;

    // 2. Ocultar todas, luego mostrar solo la página actual
    todasLasFilas.forEach(row => row.style.display = 'none');
    visibles.forEach((row, i) => {
        row.style.display = (i >= inicio && i < fin) ? '' : 'none';
    });

    // 3. Actualizar footer
    const mostrando = visibles.slice(inicio, fin).length;
    document.getElementById('footer-count').textContent =
        total + ' producto' + (total !== 1 ? 's' : '') +
        (total > POR_PAGINA ? ` — mostrando ${mostrando}` : '');

    document.getElementById('pag-info').textContent = `Pág. ${paginaActual} de ${paginas}`;
    document.getElementById('btn-prev-pag').disabled = paginaActual <= 1;
    document.getElementById('btn-next-pag').disabled = paginaActual >= paginas;
}

aplicarFiltros(); // inicio

/* ══ MODAL ══ */
function abrirModal() { document.getElementById('modal-overlay').classList.add('open'); }
function cerrarModal() { document.getElementById('modal-overlay').classList.remove('open'); limpiarModal(); }
function cerrarModalOverlay(e) { if (e.target === document.getElementById('modal-overlay')) cerrarModal(); }
function limpiarModal() {
    ['m-nombre','m-categoria','m-precio','m-stock','m-sku'].forEach(id => {
        const el = document.getElementById(id);
        if (el.tagName === 'SELECT') el.selectedIndex = 0;
        else el.value = '';
    });
}
function guardarNuevo() {
    const nombre = document.getElementById('m-nombre').value.trim();
    const cat    = document.getElementById('m-categoria').value;
    const precio = parseFloat(document.getElementById('m-precio').value);
    const stock  = parseInt(document.getElementById('m-stock').value);
    if (!nombre)              { alert('Ingresa el nombre del producto.'); return; }
    if (!cat)                 { alert('Selecciona una categoría.'); return; }
    if (isNaN(precio)||precio<=0) { alert('Ingresa un precio válido.'); return; }
    if (isNaN(stock)||stock<0)    { alert('Ingresa un stock válido.'); return; }

    const tbody    = document.getElementById('inv-tbody');
    const nuevoId  = 'new-' + Date.now();
    const catClass = {'Consumibles y Limpieza':'cat--consumibles','Herramientas y Kits':'cat--herramientas','Almacenamiento':'cat--almacenamiento','Memoria RAM':'cat--ram'}[cat] || '';
    const stClass  = stock >= 20 ? 'stock--ok' : stock >= 10 ? 'stock--low' : 'stock--min';
    const iconos   = {
        'Consumibles y Limpieza':'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
        'Herramientas y Kits'   :'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>',
        'Almacenamiento'        :'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg>',
        'Memoria RAM'           :'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="2" y1="10" x2="22" y2="10"/></svg>'
    };
    const tr = document.createElement('tr');
    tr.dataset.cat    = cat;
    tr.dataset.nombre = nombre.toLowerCase();
    tr.innerHTML = `
        <td><div class="prod-cell"><div class="prod-icon">${iconos[cat]||''}</div><div><div class="prod-name">${nombre}</div><div class="prod-id">Nuevo</div></div></div></td>
        <td><span class="cat-badge ${catClass}">${cat}</span></td>
        <td><span class="precio-cell">S/ ${precio.toFixed(2)}</span></td>
        <td><span class="stock-badge ${stClass}" id="stock-label-${nuevoId}">${stock} uds.</span></td>
        <td><div class="qty-control">
            <button class="qty-btn" onclick="cambiarQty('${nuevoId}',-1)">−</button>
            <span class="qty-num" id="qty-${nuevoId}">${stock}</span>
            <button class="qty-btn" onclick="cambiarQty('${nuevoId}',1)">+</button>
        </div></td>
        <td><button class="btn-add-row" onclick="guardarCambio('${nuevoId}')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            Actualizar
        </button></td>`;
    tbody.appendChild(tr);
    stockBase[nuevoId] = stock;
    cerrarModal();
    paginaActual = 1;
    aplicarFiltros();
}
</script>
</body>
</html>