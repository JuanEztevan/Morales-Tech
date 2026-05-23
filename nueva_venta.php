<?php
// session_start();
// include("includes/auth.php");
$nombre_usuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Juan';
$rol_usuario    = 'Trabajador';
$partes         = explode(' ', trim($nombre_usuario));
$inicial        = strtoupper(substr($partes[0], 0, 1));
$nombre_corto   = $partes[0];
// Tickets disponibles (completados con servicio confirmado)
$tickets_disponibles = [
    ["id"=>"MT-8842","cliente"=>"Valeria Ramírez",  "servicio"=>"Mantenimiento correctivo", "subtotal"=>90],
    ["id"=>"MT-8843","cliente"=>"Andrés Ochante",    "servicio"=>"Repotenciación (mano de obra)","subtotal"=>50],
    ["id"=>"MT-8844","cliente"=>"Diana Calderón",    "servicio"=>"Diagnóstico",               "subtotal"=>30],
    ["id"=>"MT-8845","cliente"=>"Brenda Benites",    "servicio"=>"Mantenimiento preventivo",  "subtotal"=>60],
];
// Inventario disponible
$productos = [
    ["id"=>1,"nombre"=>"Alcohol Isopropílico 1000ml",            "categoria"=>"Consumibles","precio"=>25],
    ["id"=>2,"nombre"=>"Pasta Térmica (jeringa 5g)",              "categoria"=>"Consumibles","precio"=>18],
    ["id"=>3,"nombre"=>"Kit Destornilladores 58 en 1",            "categoria"=>"Herramientas","precio"=>65],
    ["id"=>4,"nombre"=>"Kit Destornilladores de Precisión 128 en 1","categoria"=>"Herramientas","precio"=>95],
    ["id"=>5,"nombre"=>"SSD 1TB SATA",                            "categoria"=>"Almacenamiento","precio"=>195],
    ["id"=>6,"nombre"=>"SSD 512GB NVMe",                          "categoria"=>"Almacenamiento","precio"=>165],
    ["id"=>7,"nombre"=>"RAM DDR4 16GB 3200MHz",                   "categoria"=>"Memoria RAM","precio"=>145],
    ["id"=>8,"nombre"=>"RAM DDR5 16GB 4800MHz",                   "categoria"=>"Memoria RAM","precio"=>185],
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Venta — Morales Tech</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --azul-prof: #000019;
            --azul-tec:  #1746EA;
            --celeste:   #1883ED;
            --border:    #e6e9f0;
            --bg:        #fff;
            --green:     #1a7a4a;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Montserrat', sans-serif; background: #fff; color: var(--azul-prof); min-height: 100vh; }
        /* ══ LAYOUT ══ */
        .app-shell { display: flex; min-height: 100vh; }
        .main { flex: 1; display: flex; flex-direction: column; min-width: 0; width: 0; background: #fff; }
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
        .header__breadcrumb a { color: #a0a8bb; text-decoration: none; }
        .header__breadcrumb a:hover { color: var(--azul-tec); }
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
        .btn-back { display: inline-flex; align-items: center; gap: 8px; background: #fff; color: #7a8096; font-family: 'Montserrat',sans-serif; font-size: 13px; font-weight: 600; padding: 10px 18px; border-radius: 50px; border: 1.5px solid var(--border); cursor: pointer; text-decoration: none; white-space: nowrap; transition: all .15s; }
        .btn-back:hover { border-color: var(--azul-tec); color: var(--azul-tec); }
        .btn-back svg { width: 15px; height: 15px; }
        /* ══ CONTENT GRID ══ */
        .content-grid { display: grid; grid-template-columns: 1fr 340px; gap: 22px; align-items: start; }
        /* ══ CARDS ══ */
        .card { background: #fff; border: 1px solid var(--border); border-radius: 16px; padding: 24px; margin-bottom: 16px; box-shadow: 0 2px 10px rgba(0,0,25,.04); position: relative; overflow: hidden; }
        .card:last-child { margin-bottom: 0; }
        .card::before { content:''; position:absolute; left:0;top:0;bottom:0;width:4px; background:linear-gradient(180deg,var(--azul-tec),var(--celeste)); border-radius:16px 0 0 16px; }
        .card__header { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; }
        .card__icon { width: 34px; height: 34px; border-radius: 10px; background: linear-gradient(135deg,var(--azul-tec),var(--celeste)); display: grid; place-items: center; flex-shrink: 0; }
        .card__icon svg { width: 16px; height: 16px; color: #fff; }
        .card__title-text { font-size: 14px; font-weight: 800; color: var(--azul-prof); }
        .card__title-sub  { font-size: 11px; font-weight: 500; color: #a0a8bb; margin-top: 1px; }

        /* ══ VENTA TYPE PICKER ══
           Selected: degradé #000019 → #1746EA con texto/icono blancos para contraste máximo */
        .vtype-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .vtype-opt {
            border: 2px solid var(--border); border-radius: 14px; padding: 20px 16px;
            cursor: pointer; text-align: center; transition: all .22s; background: #fafbff; user-select: none;
        }
        .vtype-opt:hover { border-color: var(--celeste); background: #f0f6ff; }
        .vtype-opt.selected {
            border-color: transparent;
            background: linear-gradient(135deg, #1746EA 0%, #1883ED 100%);
            box-shadow: 0 6px 20px rgba(23,70,234,.30);
        }
        .vtype-opt__icon { margin-bottom: 10px; display: flex; justify-content: center; }
        .vtype-opt__icon svg { width: 36px; height: 36px; color: #c0c8dc; transition: color .2s; }
        .vtype-opt.selected .vtype-opt__icon svg { color: rgba(255,255,255,.90); }
        .vtype-opt__title { font-size: 13px; font-weight: 800; color: #7a8096; transition: color .2s; margin-bottom: 4px; }
        .vtype-opt.selected .vtype-opt__title { color: #fff; }
        .vtype-opt__desc { font-size: 11px; font-weight: 500; color: #b0b8cc; line-height: 1.4; transition: color .2s; }
        .vtype-opt.selected .vtype-opt__desc { color: rgba(255,255,255,.60); }

        /* ══ FORM ══ */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-group.full { grid-column: span 2; }
        .form-group label { font-size: 11px; font-weight: 700; color: #7a8096; letter-spacing: .04em; text-transform: uppercase; }
        .label-opt { font-size: 10px; font-weight: 500; color: #b8bfcc; text-transform: none; letter-spacing: 0; margin-left: 3px; }
        input[type="text"], select {
            font-family: 'Montserrat',sans-serif; width: 100%; padding: 11px 18px;
            border: 1.5px solid var(--border); border-radius: 50px; font-size: 13px;
            font-weight: 500; color: var(--azul-prof); background: #fff; outline: none;
            -webkit-appearance: none; appearance: none; transition: border-color .15s, box-shadow .15s;
        }
        select { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23a0a8bb' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 16px center; padding-right:40px; cursor:pointer; }
        input:focus, select:focus { border-color: var(--azul-tec); box-shadow: 0 0 0 3px rgba(23,70,234,.10); }
        input::placeholder { color: #c4c9d8; }
        input[readonly] { background: #f7f8fd; color: #8a94b0; cursor: not-allowed; }

        /* ══ TICKET INFO PILL ══
           Sin icono de usuario: solo texto del cliente y servicio */
        .ticket-info {
            display: none; align-items: center; gap: 10px; margin-top: 14px;
            background: linear-gradient(90deg,#edf1fd,#e8f0ff); border: 1.5px solid #c4d0f8;
            border-radius: 12px; padding: 12px 16px;
        }
        .ticket-info.visible { display: flex; }
        .ticket-info__text { flex: 1; min-width: 0; }
        .ticket-info__label { font-size: 10px; font-weight: 700; color: #a0a8bb; letter-spacing:.04em;text-transform:uppercase; }
        .ticket-info__value { font-size: 13px; font-weight: 700; color: var(--azul-prof); margin-top: 1px; }
        .ticket-info__price { margin-left: auto; font-size: 15px; font-weight: 800; color: var(--azul-tec); white-space: nowrap; }

        /* ══ PRODUCTS LIST ══ */
        .prod-list { display: flex; flex-direction: column; gap: 8px; }
        .prod-row {
            display: flex; align-items: center; gap: 10px;
            border: 1.5px solid var(--border); border-radius: 50px;
            padding: 9px 14px; background: #fafbff; transition: border-color .15s;
        }
        .prod-row:focus-within { border-color: var(--azul-tec); background: #f7f9ff; }
        .prod-row select {
            flex: 1; min-width: 0; border: none; background: transparent;
            box-shadow: none; padding: 0 8px 0 0; font-size: 13px; border-radius: 0;
        }
        .prod-row select:focus { box-shadow: none; border: none; }
        .qty-ctrl { display: flex; align-items: center; gap: 5px; flex-shrink: 0; }
        .qty-btn { width: 26px; height: 26px; border-radius: 50%; border: 1.5px solid var(--border); background: #fff; color: var(--azul-prof); font-size: 15px; font-weight: 700; display: grid; place-items: center; cursor: pointer; transition: all .15s; line-height: 1; }
        .qty-btn:hover { border-color: var(--azul-tec); color: var(--azul-tec); background: #edf1fd; }
        .qty-num { width: 26px; text-align: center; font-size: 13px; font-weight: 800; color: var(--azul-prof); }
        .prod-precio { font-size: 12px; font-weight: 700; color: var(--azul-tec); white-space: nowrap; min-width: 54px; text-align: right; }
        .prod-remove { background: none; border: none; cursor: pointer; color: #d0d4e0; display: grid; place-items: center; padding: 0; flex-shrink: 0; transition: color .15s; }
        .prod-remove:hover { color: #c94a00; }
        .prod-remove svg { width: 15px; height: 15px; }
        .btn-add-prod { display: inline-flex; align-items: center; gap: 6px; border: 1.5px dashed var(--border); border-radius: 50px; background: none; padding: 9px 18px; font-family:'Montserrat',sans-serif; font-size:12px; font-weight:700; color:#a0a8bb; cursor:pointer; transition:all .15s; margin-top:8px; }
        .btn-add-prod:hover { border-color:var(--azul-tec); color:var(--azul-tec); }
        .btn-add-prod svg { width:13px;height:13px; }

        /* ══ RESUMEN PANEL ══ */
        .sticky-right { position: sticky; top: 93px; }
        .quote-card { background: linear-gradient(145deg,var(--azul-tec) 0%,var(--celeste) 100%); border-radius: 20px; padding: 26px; color: #fff; box-shadow: 0 12px 40px rgba(23,70,234,.30); }
        .quote-card__title { font-size: 11px; font-weight: 800; letter-spacing:.10em; text-transform:uppercase; opacity:.75; margin-bottom:18px; display:flex; align-items:center; gap:8px; }
        .quote-card__title svg { width:14px;height:14px; }

        /* Client pill en resumen: solo nombre, sin avatar ni iniciales */
        .quote-client {
            display: flex; align-items: center;
            background: rgba(255,255,255,.15); border-radius: 50px;
            padding: 8px 16px; margin-bottom: 14px;
        }
        .quote-client__name { font-size: 12px; font-weight: 700; }

        .quote-empty { text-align:center;padding:16px 0 8px;opacity:.5;font-size:12px;font-weight:500;line-height:1.6; }
        .quote-empty svg { width:30px;height:30px;margin-bottom:8px;display:block;margin-inline:auto;opacity:.5; }
        .quote-items { display:flex;flex-direction:column;gap:9px; }
        .quote-item { display:flex;justify-content:space-between;align-items:flex-start;gap:8px;font-size:12px; }
        .quote-item__name { font-weight:500;opacity:.85;line-height:1.3; }
        .quote-item__price { font-weight:700;white-space:nowrap; }
        .quote-divider { border:none;border-top:1px solid rgba(255,255,255,.2);margin:14px 0; }
        .quote-subtotal,.quote-igv { display:flex;justify-content:space-between;font-size:12px;font-weight:600;opacity:.8;margin-bottom:6px; }
        .quote-total { display:flex;justify-content:space-between;align-items:center;margin-top:10px; }
        .quote-total__label { font-size:13px;font-weight:700;opacity:.85; }
        .quote-total__amount { font-size:30px;font-weight:800;letter-spacing:-.5px; }

        /* ══ MÉTODO DE PAGO
           Sin .metodo-opt__logo: 3 columnas, solo etiqueta de texto */
        .metodo-title { font-size:11px;font-weight:700;letter-spacing:.07em;text-transform:uppercase;opacity:.7;margin-bottom:10px;margin-top:16px; }
        .metodo-opts { display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px; }
        .metodo-opt {
            border: 2px solid rgba(255,255,255,.25); border-radius: 12px;
            padding: 11px 6px; text-align: center; cursor: pointer;
            transition: all .18s; user-select: none;
        }
        .metodo-opt:hover { border-color:rgba(255,255,255,.6);background:rgba(255,255,255,.10); }
        .metodo-opt.selected { border-color:#fff;background:rgba(255,255,255,.18); }
        .metodo-opt__label { font-size:11px;font-weight:700;opacity:.85; }

        .btn-create { display:block;width:100%;margin-top:18px;background:#fff;color:var(--azul-tec);font-family:'Montserrat',sans-serif;font-size:13px;font-weight:800;padding:14px;border-radius:50px;border:none;cursor:pointer;transition:background .15s,transform .1s;box-shadow:0 3px 12px rgba(0,0,0,.12); }
        .btn-create:hover { background:#edf1fd;transform:translateY(-1px); }
        .hidden { display: none !important; }

        /* ══ RESPONSIVE ══ */
        @media(max-width:960px){ .content-grid{grid-template-columns:1fr;} .sticky-right{position:static;} }
        @media(max-width:700px){ .sidebar{width:60px;min-width:60px;} .nav-link{width:46px;height:50px;font-size:0;} .sidebar__logout span{display:none;} .header__user-info{display:none;} .page{padding:18px 14px;} .header{padding:0 14px;} .form-grid{grid-template-columns:1fr;} .form-group.full{grid-column:span 1;} .vtype-grid{grid-template-columns:1fr 1fr;} }
        @media(max-width:480px){ .sidebar{display:none;} }
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
            <div class="header__breadcrumb">
                Panel / <a href="ventas.php">Ventas</a> / <span>Nueva Venta</span>
            </div>
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
                    <h1 class="page__title">Nueva Venta</h1>
                    <p class="page__subtitle">Registra una venta de servicio técnico o de productos</p>
                </div>
                <a href="ventas.php" class="btn-back">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                    Volver
                </a>
            </div>
            <div class="content-grid">
                <!-- ══ IZQUIERDA ══ -->
                <div>
                    <!-- 1. TIPO DE VENTA -->
                    <div class="card">
                        <div class="card__header">
                            <div class="card__icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                            </div>
                            <div>
                                <div class="card__title-text">Tipo de venta</div>
                                <div class="card__title-sub">¿Qué vas a registrar?</div>
                            </div>
                        </div>
                        <div class="vtype-grid">
                            <div class="vtype-opt selected" id="vopt-ticket" onclick="selTipo('ticket',this)">
                                <div class="vtype-opt__icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
                                </div>
                                <div class="vtype-opt__title">Venta por ticket</div>
                                <div class="vtype-opt__desc">Servicio técnico concretado, con opción de añadir productos</div>
                            </div>
                            <div class="vtype-opt" id="vopt-producto" onclick="selTipo('producto',this)">
                                <div class="vtype-opt__icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                                </div>
                                <div class="vtype-opt__title">Venta de producto</div>
                                <div class="vtype-opt__desc">Venta directa de productos del inventario a un cliente</div>
                            </div>
                        </div>
                    </div>
                    <!-- 2A. SECCIÓN TICKET (visible por defecto) -->
                    <div class="card" id="bloque-ticket">
                        <div class="card__header">
                            <div class="card__icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
                            </div>
                            <div>
                                <div class="card__title-text">Ticket asociado</div>
                                <div class="card__title-sub">Selecciona el ticket del servicio completado</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Número de ticket <span style="color:#c94a00">*</span></label>
                            <select id="sel-ticket" onchange="onTicketChange()">
                                <option value="">Seleccionar ticket…</option>
                                <?php foreach ($tickets_disponibles as $t): ?>
                                <option value="<?= $t['id'] ?>"
                                    data-cliente="<?= htmlspecialchars($t['cliente']) ?>"
                                    data-servicio="<?= htmlspecialchars($t['servicio']) ?>"
                                    data-subtotal="<?= $t['subtotal'] ?>">
                                    <?= htmlspecialchars($t['id']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <!-- Info pill: sin icono de usuario, solo texto -->
                        <div class="ticket-info" id="ticket-info">
                            <div class="ticket-info__text">
                                <div class="ticket-info__label">Cliente · Servicio</div>
                                <div class="ticket-info__value" id="ticket-info-text">—</div>
                            </div>
                            <div class="ticket-info__price" id="ticket-info-price">S/ 0</div>
                        </div>
                    </div>
                    <!-- 2B. SECCIÓN CLIENTE DIRECTO (oculto por defecto) -->
                    <div class="card hidden" id="bloque-cliente">
                        <div class="card__header">
                            <div class="card__icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </div>
                            <div>
                                <div class="card__title-text">Datos del cliente</div>
                                <div class="card__title-sub">Identifica al comprador</div>
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>DNI <span style="color:#c94a00">*</span></label>
                                <input type="text" id="cli-dni" placeholder="8 dígitos" maxlength="8" oninput="this.value=this.value.replace(/\D/g,'')">
                            </div>
                            <div class="form-group">
                                <label>RUC <span class="label-opt">* opcional</span></label>
                                <input type="text" id="cli-ruc" placeholder="11 dígitos" maxlength="11" oninput="this.value=this.value.replace(/\D/g,'')">
                            </div>
                            <div class="form-group full">
                                <label>Nombre completo <span style="color:#c94a00">*</span></label>
                                <input type="text" id="cli-nombre" placeholder="Nombre del cliente">
                            </div>
                        </div>
                    </div>
                    <!-- 3. PRODUCTOS -->
                    <div class="card" id="bloque-productos">
                        <div class="card__header">
                            <div class="card__icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                            </div>
                            <div>
                                <div class="card__title-text" id="prod-card-title">Productos adicionales</div>
                                <div class="card__title-sub" id="prod-card-sub">Añade repuestos o materiales usados en el servicio</div>
                            </div>
                        </div>
                        <div class="prod-list" id="prod-list"></div>
                        <button class="btn-add-prod" onclick="agregarProd()">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Añadir producto
                        </button>
                    </div>
                </div>
                <!-- ══ DERECHA: RESUMEN ══ -->
                <div class="sticky-right">
                    <div class="quote-card">
                        <div class="quote-card__title">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="16" x2="13" y2="16"/></svg>
                            Resumen de Venta
                        </div>
                        <!-- Client pill: solo nombre, sin avatar ni iniciales -->
                        <div class="quote-client hidden" id="q-client">
                            <span class="quote-client__name" id="q-client-name">—</span>
                        </div>
                        <!-- Empty state -->
                        <div id="q-empty" class="quote-empty">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg>
                            Selecciona un ticket o<br>añade productos para<br>ver el resumen
                        </div>
                        <!-- Items -->
                        <div id="q-details" class="hidden">
                            <div class="quote-items" id="q-items"></div>
                            <hr class="quote-divider">
                            <div class="quote-subtotal"><span>Subtotal</span><span>S/ <span id="q-subtotal">0.00</span></span></div>
                            <div class="quote-igv"><span>IGV (18%)</span><span>S/ <span id="q-igv">0.00</span></span></div>
                            <hr class="quote-divider">
                            <div class="quote-total">
                                <span class="quote-total__label">Total</span>
                                <span class="quote-total__amount">S/ <span id="q-total">0.00</span></span>
                            </div>
                        </div>
                        <!-- Método de pago: sin íconos, solo texto, 3 columnas -->
                        <div class="metodo-title">Método de pago</div>
                        <div class="metodo-opts">
                            <div class="metodo-opt selected" id="mopt-yape" onclick="selMetodo('Yape',this)">
                                <div class="metodo-opt__label">Yape</div>
                            </div>
                            <div class="metodo-opt" id="mopt-transf" onclick="selMetodo('Transferencia',this)">
                                <div class="metodo-opt__label">Transferencia</div>
                            </div>
                            <div class="metodo-opt" id="mopt-efectivo" onclick="selMetodo('Efectivo',this)">
                                <div class="metodo-opt__label">Efectivo</div>
                            </div>
                        </div>
                        <button class="btn-create" onclick="guardarVenta()">Registrar venta</button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<script>
/* ═══ DATA ═══ */
const TICKETS = <?= json_encode($tickets_disponibles) ?>;
const PRODUCTOS = <?= json_encode($productos) ?>;
let tipoVenta  = 'ticket';
let metodoVenta = 'Yape';
let prodCounter = 0;
let ticketSelData = null;

/* ═══ TIPO SELECTOR ═══ */
function selTipo(tipo, el) {
    tipoVenta = tipo;
    document.querySelectorAll('.vtype-opt').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');
    const bT  = document.getElementById('bloque-ticket');
    const bC  = document.getElementById('bloque-cliente');
    const pTitle = document.getElementById('prod-card-title');
    const pSub   = document.getElementById('prod-card-sub');
    if (tipo === 'ticket') {
        bT.classList.remove('hidden');
        bC.classList.add('hidden');
        pTitle.textContent = 'Productos adicionales';
        pSub.textContent   = 'Añade repuestos o materiales usados en el servicio';
    } else {
        bT.classList.add('hidden');
        bC.classList.remove('hidden');
        pTitle.textContent = 'Productos';
        pSub.textContent   = 'Selecciona los productos que desea el cliente';
        ticketSelData = null;
        document.getElementById('ticket-info').classList.remove('visible');
        document.getElementById('sel-ticket').selectedIndex = 0;
    }
    updateQuote();
}

/* ═══ TICKET SELECT ═══ */
function onTicketChange() {
    const sel = document.getElementById('sel-ticket');
    const opt = sel.options[sel.selectedIndex];
    const info = document.getElementById('ticket-info');
    if (!sel.value) {
        info.classList.remove('visible');
        ticketSelData = null;
        updateQuote();
        return;
    }
    ticketSelData = {
        cliente:  opt.dataset.cliente,
        servicio: opt.dataset.servicio,
        subtotal: parseFloat(opt.dataset.subtotal),
    };
    document.getElementById('ticket-info-text').textContent =
        ticketSelData.cliente + ' · ' + ticketSelData.servicio;
    document.getElementById('ticket-info-price').textContent =
        'S/ ' + ticketSelData.subtotal.toFixed(2);
    info.classList.add('visible');
    updateQuote();
}

/* ═══ PRODUCTOS ═══ */
function agregarProd() {
    const list = document.getElementById('prod-list');
    const id = ++prodCounter;
    const opts = PRODUCTOS.map(p =>
        `<option value="${p.id}" data-precio="${p.precio}">${p.nombre}</option>`
    ).join('');
    const row = document.createElement('div');
    row.className = 'prod-row';
    row.id = 'prow-' + id;
    row.innerHTML = `
        <select id="psel-${id}" onchange="onProdChange(${id})">
            <option value="">Seleccionar producto…</option>
            ${opts}
        </select>
        <div class="qty-ctrl">
            <button class="qty-btn" onclick="cambiarQty(${id},-1)">−</button>
            <span class="qty-num" id="pqty-${id}">1</span>
            <button class="qty-btn" onclick="cambiarQty(${id},1)">+</button>
        </div>
        <span class="prod-precio" id="pprecio-${id}">S/ —</span>
        <button class="prod-remove" onclick="eliminarProd(${id})">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>`;
    list.appendChild(row);
}
function onProdChange(id) {
    const sel = document.getElementById('psel-' + id);
    const precio = sel.options[sel.selectedIndex]?.dataset?.precio;
    const qty = parseInt(document.getElementById('pqty-' + id).textContent);
    const etiq = document.getElementById('pprecio-' + id);
    if (precio) {
        etiq.textContent = 'S/ ' + (parseFloat(precio) * qty).toFixed(2);
    } else {
        etiq.textContent = 'S/ —';
    }
    updateQuote();
}
function cambiarQty(id, delta) {
    const el  = document.getElementById('pqty-' + id);
    let v = Math.max(1, parseInt(el.textContent) + delta);
    el.textContent = v;
    onProdChange(id);
}
function eliminarProd(id) {
    document.getElementById('prow-' + id)?.remove();
    updateQuote();
}

/* ═══ MÉTODO ═══ */
function selMetodo(m, el) {
    metodoVenta = m;
    document.querySelectorAll('.metodo-opt').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');
}

/* ═══ QUOTE UPDATE ═══ */
function updateQuote() {
    const items = [];
    if (tipoVenta === 'ticket' && ticketSelData) {
        items.push({ name: ticketSelData.servicio, price: ticketSelData.subtotal });
    }
    document.querySelectorAll('[id^="psel-"]').forEach(sel => {
        if (!sel.value) return;
        const prod = PRODUCTOS.find(p => p.id == sel.value);
        if (!prod) return;
        const qty = parseInt(document.getElementById(sel.id.replace('psel-','pqty-'))?.textContent || 1);
        items.push({ name: prod.nombre + (qty > 1 ? ` ×${qty}` : ''), price: prod.precio * qty });
    });

    // Client pill — solo nombre, sin avatar
    const clientName = tipoVenta === 'ticket' && ticketSelData
        ? ticketSelData.cliente
        : (document.getElementById('cli-nombre')?.value?.trim() || '');
    const qClient = document.getElementById('q-client');
    const qClientName = document.getElementById('q-client-name');
    if (clientName) {
        qClientName.textContent = clientName;
        qClient.classList.remove('hidden');
    } else {
        qClient.classList.add('hidden');
    }

    const qEmpty   = document.getElementById('q-empty');
    const qDetails = document.getElementById('q-details');
    if (items.length === 0) {
        qEmpty.classList.remove('hidden');
        qDetails.classList.add('hidden');
        return;
    }
    qEmpty.classList.add('hidden');
    qDetails.classList.remove('hidden');
    document.getElementById('q-items').innerHTML = items.map(i => `
        <div class="quote-item">
            <span class="quote-item__name">${i.name}</span>
            <span class="quote-item__price">S/ ${i.price.toFixed(2)}</span>
        </div>`).join('');
    const subtotal = items.reduce((a, i) => a + i.price, 0);
    const igv      = subtotal * 0.18;
    const total    = subtotal + igv;
    document.getElementById('q-subtotal').textContent = subtotal.toFixed(2);
    document.getElementById('q-igv').textContent      = igv.toFixed(2);
    document.getElementById('q-total').textContent    = total.toFixed(2);
}

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('cli-nombre')?.addEventListener('input', updateQuote);
});

/* ═══ GUARDAR ═══ */
function guardarVenta() {
    if (tipoVenta === 'ticket') {
        if (!ticketSelData) { alert('Selecciona un ticket.'); return; }
    } else {
        const dni    = document.getElementById('cli-dni').value.trim();
        const nombre = document.getElementById('cli-nombre').value.trim();
        if (!dni || dni.length !== 8)  { alert('El DNI debe tener 8 dígitos.'); return; }
        if (!nombre)                   { alert('Ingresa el nombre del cliente.'); return; }
    }
    const total = parseFloat(document.getElementById('q-total').textContent);
    if (total <= 0 || isNaN(total)) { alert('Agrega al menos un producto o servicio.'); return; }
    alert('¡Venta registrada correctamente!\nTotal: S/ ' + total.toFixed(2) + ' — ' + metodoVenta);
    window.location.href = 'ventas.php';
}
</script>
</body>
</html>