<?php
// session_start();
// include("includes/auth.php");
$nombre_usuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Juan';
$rol_usuario    = 'Trabajador';
$partes         = explode(' ', trim($nombre_usuario));
$inicial        = strtoupper(substr($partes[0], 0, 1));
$nombre_corto   = $partes[0];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Ticket — Morales Tech</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* ══════════════════════════════════════════════
           VARIABLES — idénticas a ventas.php
        ══════════════════════════════════════════════ */
        :root {
            --azul-prof: #000019;
            --azul-tec:  #1746EA;
            --celeste:   #1883ED;
            --border:    #e6e9f0;
            --bg:        #ffffff;
            --surface:   #ffffff;
            --surface2:  #f4f6fb;
            --muted:     #a0a8bb;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Montserrat', sans-serif;
            background: var(--bg);          /* ← blanco #ffffff igual que ventas */
            color: var(--azul-prof);
            min-height: 100vh;
        }

        /* ══ LAYOUT ══ */
        .app-shell { display: flex; min-height: 100vh; }
        .main { flex: 1; display: flex; flex-direction: column; min-width: 0; width: 0; background: var(--bg); }

        /* ══════════════════════════════════════════════
           SIDEBAR — copiado exacto de ventas.php
        ══════════════════════════════════════════════ */
        .sidebar {
            width: 80px; min-width: 80px;
            background: linear-gradient(180deg, #fff 0%, #edf1fd 100%);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column; align-items: center;
            height: 100vh; position: sticky; top: 0; flex-shrink: 0;
        }
        .sidebar__logo {
            width: 100%; display: flex; justify-content: center;
            padding: 18px 0 14px;
        }
        .sidebar__isotipo { width: 38px; height: auto; }
        .sidebar__nav {
            flex: 1; display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            gap: 2px; width: 100%; padding: 12px 6px;
        }
        .nav-link {
            display: flex; flex-direction: column; align-items: center;
            justify-content: center; gap: 4px;
            width: 62px; height: 62px; border-radius: 12px;
            text-decoration: none; color: #7a8096;
            font-size: 10px; font-weight: 600;
            transition: all .18s; text-align: center;
        }
        .nav-link svg { width: 20px; height: 20px; flex-shrink: 0; }
        .nav-link:hover { background: rgba(255,255,255,.8); color: var(--azul-prof); }
        .nav-link--active {
            background: #fff; color: var(--azul-tec); font-weight: 700;
            box-shadow: 0 3px 10px rgba(23,70,234,.10);
        }
        .sidebar__footer {
            width: 100%; display: flex; flex-direction: column;
            align-items: center; padding: 12px 6px 24px;
        }
        .sidebar__logout {
            display: flex; flex-direction: column; align-items: center;
            gap: 3px; text-decoration: none; color: #a0a8bb;
            font-size: 10px; font-weight: 600;
            padding: 6px 10px; border-radius: 10px;
            transition: background .15s, color .15s;
            width: 62px; text-align: center;
        }
        .sidebar__logout:hover { background: rgba(255,255,255,.8); color: #c94a00; }
        .sidebar__logout svg { width: 18px; height: 18px; }

        /* ══════════════════════════════════════════════
           HEADER — copiado exacto de ventas.php
        ══════════════════════════════════════════════ */
        .header {
            background: #fff; border-bottom: 1px solid var(--border);
            padding: 0 28px; height: 65px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 10; flex-shrink: 0; width: 100%;
        }
        .header__breadcrumb { font-size: 13px; font-weight: 500; color: #a0a8bb; }
        .header__breadcrumb span { color: var(--azul-prof); font-weight: 600; }
        .header__breadcrumb a { color: #a0a8bb; text-decoration: none; }
        .header__breadcrumb a:hover { color: var(--azul-tec); }
        .header__user { display: flex; align-items: center; gap: 10px; }
        .header__user-info { display: flex; flex-direction: column; align-items: flex-start; }
        .header__avatar {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--azul-tec), var(--celeste));
            border-radius: 50%; display: grid; place-items: center;
            font-size: 13px; font-weight: 700; color: #fff;
        }
        .header__username { font-size: 13px; font-weight: 700; color: var(--azul-prof); line-height: 1.1; }
        .header__user-role { font-size: 11px; font-weight: 500; color: #a0a8bb; }

        /* ══ PAGE ══ */
        .page { padding: 28px; flex: 1; background: var(--bg); }
        .page__top {
            display: flex; align-items: flex-start; justify-content: space-between;
            margin-bottom: 28px; flex-wrap: wrap; gap: 12px;
        }
        .page__title { font-size: 28px; font-weight: 800; letter-spacing: -.5px; color: var(--azul-prof); margin-bottom: 4px; }
        .page__subtitle { font-size: 13px; color: #a0a8bb; }
        .btn-back {
            display: inline-flex; align-items: center; gap: 7px;
            background: #fff; color: #7a8096;
            font-family: 'Montserrat', sans-serif; font-size: 12px; font-weight: 600;
            padding: 10px 20px; border-radius: 50px; border: 1.5px solid var(--border);
            cursor: pointer; text-decoration: none; transition: all .15s;
            white-space: nowrap;
        }
        .btn-back:hover { border-color: var(--azul-tec); color: var(--azul-tec); }
        .btn-back svg { width: 14px; height: 14px; }

        /* ══ WIZARD LAYOUT ══ */
        .wizard { display: grid; grid-template-columns: 220px 1fr; gap: 24px; align-items: start; }

        /* ══ PANEL DE PASOS ══ */
        .steps-panel {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px; overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,25,.06);
            position: sticky; top: 88px;
        }
        .steps-panel__header {
            padding: 16px 20px 14px; border-bottom: 1px solid var(--border);
            font-size: 10px; font-weight: 800;
            letter-spacing: .1em; text-transform: uppercase; color: var(--muted);
        }
        .step-item {
            display: flex; align-items: center; gap: 14px;
            padding: 14px 20px; cursor: pointer;
            transition: background .15s;
            border-left: 3px solid transparent;
        }
        .step-item:hover { background: #f7f8fd; }
        .step-item.active { background: #edf1fd; border-left-color: var(--azul-tec); }
        .step-num {
            width: 28px; height: 28px; border-radius: 50%;
            border: 2px solid var(--border);
            display: grid; place-items: center;
            font-size: 11px; font-weight: 800; color: var(--muted);
            flex-shrink: 0; transition: all .2s; background: #fff;
        }
        .step-item.active .step-num { background: var(--azul-tec); border-color: var(--azul-tec); color: #fff; }
        .step-item.done .step-num { background: #e6f8f0; border-color: #a8dfc4; color: #1a7a4a; }
        .step-item.done .step-num::after { content: '✓'; font-size: 12px; }
        .step-info { flex: 1; }
        .step-label { font-size: 11px; font-weight: 700; color: var(--azul-prof); line-height: 1.2; }
        .step-item.active .step-label { color: var(--azul-tec); }
        .step-sub { font-size: 10px; color: var(--muted); margin-top: 2px; }
        .progress-bar { height: 3px; background: var(--border); }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--azul-tec), var(--celeste));
            border-radius: 2px; transition: width .3s ease;
        }

        /* ══ CONTENIDO DEL PASO ══ */
        .step-content { display: none; }
        .step-content.active { display: block; }
        .step-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 16px; overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,25,.06);
        }
        .step-card__head {
            padding: 20px 24px 18px; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 12px;
        }
        .step-card__icon {
            width: 36px; height: 36px; border-radius: 10px;
            background: linear-gradient(135deg, var(--azul-tec), var(--celeste));
            display: grid; place-items: center; flex-shrink: 0;
        }
        .step-card__icon svg { width: 17px; height: 17px; color: #fff; }
        .step-card__title { font-size: 15px; font-weight: 800; color: var(--azul-prof); }
        .step-card__sub { font-size: 11px; color: var(--muted); margin-top: 2px; }
        .step-card__body { padding: 24px; }

        /* ══ FORMULARIO ══ */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .span-2 { grid-column: span 2; }
        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-group label {
            font-size: 11px; font-weight: 700; color: #7a8096;
            letter-spacing: .04em; text-transform: uppercase;
        }
        .label-opt { font-size: 10px; font-weight: 500; color: #c0c8d8; text-transform: none; letter-spacing: 0; margin-left: 3px; }
        .req { color: #c94a00; }
        input[type="text"], select, textarea {
            font-family: 'Montserrat', sans-serif;
            width: 100%; padding: 11px 16px;
            border: 1.5px solid var(--border);
            border-radius: 50px;
            font-size: 13px; font-weight: 500;
            color: var(--azul-prof); background: #fff;
            transition: border-color .15s, box-shadow .15s;
            outline: none; -webkit-appearance: none; appearance: none;
        }
        select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23a0a8bb' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 14px center;
            padding-right: 38px; cursor: pointer;
        }
        textarea { border-radius: 14px; resize: vertical; min-height: 90px; }
        input:focus, select:focus, textarea:focus {
            border-color: var(--azul-tec);
            box-shadow: 0 0 0 3px rgba(23,70,234,.10);
        }
        input::placeholder, textarea::placeholder { color: #c4c9d8; }

        /* ══ DEVICE SELECTOR ══ */
        .section-label-sm {
            font-size: 10px; font-weight: 800; color: var(--muted);
            letter-spacing: .08em; text-transform: uppercase; margin-bottom: 12px;
        }
        .device-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 4px; }
        .device-opt {
            border: none;                        /* ← sin bordes */
            border-radius: 14px;
            padding: 22px 12px 18px;
            cursor: pointer; text-align: center;
            transition: all .22s;
            background: var(--surface2);
            user-select: none;
        }
        .device-opt:hover {
            background: linear-gradient(135deg, rgba(23,70,234,.12), rgba(24,131,237,.10));
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(23,70,234,.12);
        }
        /* Seleccionado: degradado sólido #1746EA → #1883ED, sin borde */
        .device-opt.selected {
            background: linear-gradient(135deg, #1746EA, #1883ED);
            box-shadow: 0 6px 20px rgba(23,70,234,.28);
            transform: translateY(-1px);
        }
        .device-opt svg {
            width: 40px; height: 40px; color: #b0b8cc;
            margin-bottom: 10px; display: block; margin-inline: auto;
            transition: color .2s;
        }
        .device-opt:hover svg { color: var(--azul-tec); }
        .device-opt.selected svg { color: #fff; }
        .device-opt__label {
            font-size: 12px; font-weight: 700; color: #8a94b0;
            transition: color .2s;
        }
        .device-opt:hover .device-opt__label { color: var(--azul-tec); }
        .device-opt.selected .device-opt__label { color: #fff; }

        .extra-fields { margin-top: 18px; display: none; }
        .extra-fields.visible { display: grid; }

        /* ══ SERVICIOS ══ */
        .service-list { display: flex; flex-direction: column; gap: 8px; }
        .service-item {
            display: flex; align-items: center; gap: 12px;
            padding: 12px 18px; border: 1.5px solid var(--border);
            border-radius: 50px; cursor: pointer; transition: all .15s;
            background: var(--surface2); user-select: none;
        }
        .service-item:hover { border-color: var(--celeste); background: #f0f6ff; }
        .service-item.selected  { border-color: var(--azul-tec); background: linear-gradient(90deg, #edf1fd, #e6efff); }
        .service-item.sel-add   { border-color: var(--celeste);  background: linear-gradient(90deg, #e8f3ff, #edf1fd); }
        .service-item input[type="radio"],
        .service-item input[type="checkbox"] {
            width: 16px; height: 16px; min-width: 16px;
            accent-color: var(--azul-tec); cursor: pointer;
            padding: 0; border: none; background: none; box-shadow: none;
        }
        .service-item__name  { flex: 1; font-size: 13px; font-weight: 600; color: var(--azul-prof); }
        .service-item__price { font-size: 12px; font-weight: 700; color: var(--muted); white-space: nowrap; }
        .divider { border: none; border-top: 1px solid var(--border); margin: 18px 0; }

        /* Accordion adicionales */
        .add-toggle {
            display: flex; align-items: center; justify-content: space-between;
            padding: 12px 18px; border: 1.5px solid var(--border);
            border-radius: 50px; cursor: pointer; background: var(--surface2);
            transition: all .15s; user-select: none; margin-top: 4px;
        }
        .add-toggle:hover { border-color: var(--celeste); background: #f0f6ff; }
        .add-toggle.open { border-color: var(--azul-tec); background: linear-gradient(90deg,#edf1fd,#e6efff); border-radius: 16px 16px 0 0; border-bottom: none; }
        .add-toggle__left { display: flex; align-items: center; gap: 10px; font-size: 13px; font-weight: 700; color: var(--azul-prof); }
        .add-toggle__left svg { width: 15px; height: 15px; color: var(--azul-tec); }
        .add-badge { font-size: 10px; font-weight: 800; background: var(--azul-tec); color: #fff; border-radius: 50px; padding: 2px 8px; }
        .add-badge.hidden { display: none; }
        .add-chevron { transition: transform .25s; color: var(--muted); }
        .add-chevron svg { width: 15px; height: 15px; }
        .add-toggle.open .add-chevron { transform: rotate(180deg); }
        .add-panel { display: none; flex-direction: column; gap: 0; border: 1.5px solid var(--azul-tec); border-top: none; border-radius: 0 0 16px 16px; background: linear-gradient(180deg,#edf1fd,#f0f5ff); overflow: hidden; }
        .add-panel.open { display: flex; }
        .add-panel .service-item { border-radius: 0; border: none; border-bottom: 1px solid rgba(23,70,234,.10); background: transparent; }
        .add-panel .service-item:last-child { border-bottom: none; }
        .add-panel .service-item:hover { background: rgba(23,70,234,.05); }

        /* ══ RESUMEN (paso 4) ══ */
        .summary-layout { display: grid; grid-template-columns: 1fr 1.1fr; gap: 16px; align-items: start; }
        .summary-left { display: flex; flex-direction: column; gap: 12px; }
        .summary-block {
            background: var(--surface2); border: 1px solid var(--border);
            border-radius: 12px; padding: 16px 18px;
        }
        .summary-block__label {
            font-size: 10px; font-weight: 800; color: var(--muted);
            letter-spacing: .08em; text-transform: uppercase; margin-bottom: 10px;
        }
        .summary-block__value { font-size: 13px; font-weight: 500; color: var(--azul-prof); line-height: 1.7; }
        .summary-block__value strong { font-weight: 800; }
        .quote-box {
            background: linear-gradient(145deg, var(--azul-tec) 0%, var(--celeste) 100%);
            border-radius: 16px; padding: 22px 24px; color: #fff;
            box-shadow: 0 8px 30px rgba(23,70,234,.28);
        }
        .quote-box__title { font-size: 10px; font-weight: 800; letter-spacing: .10em; text-transform: uppercase; opacity: .75; margin-bottom: 16px; }
        .quote-item { display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 8px; }
        .quote-item__name  { font-weight: 500; opacity: .9; }
        .quote-item__price { font-weight: 700; white-space: nowrap; }
        .q-divider { border: none; border-top: 1px solid rgba(255,255,255,.2); margin: 14px 0; }
        .q-row { display: flex; justify-content: space-between; font-size: 12px; font-weight: 600; opacity: .8; margin-bottom: 6px; }
        .q-total { display: flex; justify-content: space-between; align-items: center; margin-top: 12px; }
        .q-total__label  { font-size: 14px; font-weight: 700; opacity: .9; }
        .q-total__amount { font-size: 30px; font-weight: 800; letter-spacing: -.5px; }

        /* ══ MODAL ÉXITO ══ */
        .modal-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,25,.45); backdrop-filter: blur(6px);
            display: flex; align-items: center; justify-content: center;
            z-index: 1000; opacity: 0; pointer-events: none; transition: opacity .25s;
        }
        .modal-overlay.show { opacity: 1; pointer-events: all; }
        .modal-box {
            background: #fff; border-radius: 24px; padding: 48px 40px 40px;
            max-width: 380px; width: 90%; text-align: center;
            box-shadow: 0 24px 60px rgba(0,0,25,.18);
            transform: translateY(24px) scale(.97); transition: transform .25s;
        }
        .modal-overlay.show .modal-box { transform: translateY(0) scale(1); }
        .modal-icon { width: 72px; height: 72px; background: #e6f8f0; border-radius: 50%; display: grid; place-items: center; margin: 0 auto 20px; }
        .modal-icon svg { width: 36px; height: 36px; color: #1a7a4a; }
        .modal-title { font-size: 20px; font-weight: 800; color: var(--azul-prof); margin-bottom: 8px; }
        .modal-sub { font-size: 13px; color: var(--muted); line-height: 1.6; margin-bottom: 28px; }
        .modal-btn {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--azul-tec); color: #fff;
            font-family: 'Montserrat', sans-serif; font-size: 14px; font-weight: 700;
            padding: 12px 28px; border-radius: 50px; border: none;
            cursor: pointer; transition: background .15s, transform .1s;
            box-shadow: 0 4px 14px rgba(23,70,234,.28);
        }
        .modal-btn:hover { background: #1238c2; transform: translateY(-1px); }

        /* ══ NAV BOTONES PASOS ══ */
        .step-nav {
            display: flex; justify-content: space-between; align-items: center;
            padding: 18px 24px; border-top: 1px solid var(--border);
            background: var(--surface2);
        }
        .btn-prev, .btn-next, .btn-finish {
            display: inline-flex; align-items: center; gap: 8px;
            font-family: 'Montserrat', sans-serif;
            font-size: 13px; font-weight: 700;
            padding: 10px 22px; border-radius: 50px; border: none; cursor: pointer;
            transition: all .15s;
        }
        .btn-prev { background: #fff; color: #7a8096; border: 1.5px solid var(--border); }
        .btn-prev:hover { border-color: var(--azul-tec); color: var(--azul-tec); }
        .btn-next { background: var(--azul-tec); color: #fff; box-shadow: 0 4px 14px rgba(23,70,234,.25); }
        .btn-next:hover { background: #1238c2; transform: translateY(-1px); }
        .btn-finish { background: #1a7a4a; color: #fff; box-shadow: 0 4px 14px rgba(26,122,74,.25); }
        .btn-finish:hover { background: #145c38; transform: translateY(-1px); }
        .btn-prev svg, .btn-next svg, .btn-finish svg { width: 14px; height: 14px; }

        /* ══ RESPONSIVE ══ */
        @media (max-width: 1100px) { .summary-layout { grid-template-columns: 1fr; } }
        @media (max-width: 900px)  { .wizard { grid-template-columns: 1fr; } .steps-panel { position: static; } }
        @media (max-width: 700px)  {
            .sidebar { width: 60px; min-width: 60px; }
            .nav-link { width: 46px; height: 50px; font-size: 0; }
            .sidebar__logout span { display: none; }
            .header__user-info { display: none; }
            .page { padding: 18px 14px; }
            .header { padding: 0 14px; }
            .form-grid { grid-template-columns: 1fr; }
            .span-2 { grid-column: span 1; }
        }
        @media (max-width: 480px) { .sidebar { display: none; } }
    </style>
</head>
<body>
<div class="app-shell">

    <!-- ══ SIDEBAR — igual a ventas.php ══ -->
    <aside class="sidebar">
        <div class="sidebar__logo">
            <img src="img/isotipo-color.png" alt="Morales Tech" class="sidebar__isotipo">
        </div>
        <nav class="sidebar__nav">
            <a href="dashboard.php" class="nav-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                Dashboard
            </a>
            <a href="tickets.php" class="nav-link nav-link--active">
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

    <!-- ══ MAIN ══ -->
    <div class="main">

        <!-- HEADER — igual a ventas.php -->
        <header class="header">
            <div class="header__breadcrumb">
                Panel / <a href="tickets.php">Tickets</a> / <span>Nuevo Ticket</span>
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
            <div class="page__top">
                <div>
                    <h1 class="page__title">Nuevo Ticket</h1>
                    <p class="page__subtitle">Registra un nuevo servicio técnico paso a paso</p>
                </div>
                <a href="tickets.php" class="btn-back">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                    Volver
                </a>
            </div>

            <div class="wizard">
                <!-- ══ PANEL DE PASOS ══ -->
                <div class="steps-panel">
                    <div class="steps-panel__header">Progreso</div>
                    <div class="step-item active" id="nav-1" onclick="goStep(1)">
                        <div class="step-num" id="num-1">1</div>
                        <div class="step-info">
                            <div class="step-label">Cliente</div>
                            <div class="step-sub">Datos del cliente</div>
                        </div>
                    </div>
                    <div class="step-item" id="nav-2" onclick="goStep(2)">
                        <div class="step-num" id="num-2">2</div>
                        <div class="step-info">
                            <div class="step-label">Dispositivo</div>
                            <div class="step-sub">Tipo y especificaciones</div>
                        </div>
                    </div>
                    <div class="step-item" id="nav-3" onclick="goStep(3)">
                        <div class="step-num" id="num-3">3</div>
                        <div class="step-info">
                            <div class="step-label">Servicios</div>
                            <div class="step-sub">Servicio y cotización</div>
                        </div>
                    </div>
                    <div class="step-item" id="nav-4" onclick="goStep(4)">
                        <div class="step-num" id="num-4">4</div>
                        <div class="step-info">
                            <div class="step-label">Resumen</div>
                            <div class="step-sub">Confirmar y crear</div>
                        </div>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" id="progress-fill" style="width:25%"></div>
                    </div>
                </div>

                <!-- ══ CONTENIDO POR PASO ══ -->
                <div>

                    <!-- PASO 1: CLIENTE -->
                    <div class="step-content active" id="step-1">
                        <div class="step-card">
                            <div class="step-card__head">
                                <div class="step-card__icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                </div>
                                <div>
                                    <div class="step-card__title">Datos del Cliente</div>
                                    <div class="step-card__sub">Ingresa la información de identificación</div>
                                </div>
                            </div>
                            <div class="step-card__body">
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label>DNI <span class="req">*</span></label>
                                        <input type="text" id="dni" placeholder="8 dígitos" maxlength="8">
                                    </div>
                                    <div class="form-group">
                                        <label>RUC <span class="label-opt">Opcional</span></label>
                                        <input type="text" id="ruc" placeholder="11 dígitos" maxlength="11">
                                    </div>
                                    <div class="form-group span-2">
                                        <label>Nombre completo <span class="req">*</span></label>
                                        <input type="text" id="nombre_cliente" placeholder="Nombre y apellidos">
                                    </div>
                                    <div class="form-group">
                                        <label>Teléfono <span class="req">*</span></label>
                                        <input type="text" id="telefono" placeholder="9 dígitos" maxlength="9">
                                    </div>
                                    <div class="form-group">
                                        <label>Correo <span class="label-opt">Opcional</span></label>
                                        <input type="text" id="correo" placeholder="correo@email.com">
                                    </div>
                                </div>
                            </div>
                            <div class="step-nav">
                                <span></span>
                                <button class="btn-next" onclick="nextStep(1)">
                                    Siguiente
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- PASO 2: DISPOSITIVO -->
                    <div class="step-content" id="step-2">
                        <div class="step-card">
                            <div class="step-card__head">
                                <div class="step-card__icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="13" rx="2"/><polyline points="1 21 23 21"/></svg>
                                </div>
                                <div>
                                    <div class="step-card__title">Dispositivo</div>
                                    <div class="step-card__sub">Selecciona el tipo de equipo y sus características</div>
                                </div>
                            </div>
                            <div class="step-card__body">
                                <div class="section-label-sm">Tipo de equipo</div>
                                <div class="device-grid">
                                    <div class="device-opt" onclick="selectDevice(this,'Laptop')" id="opt-laptop">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="2" y="3" width="20" height="13" rx="2"/>
                                            <polyline points="1 21 23 21"/>
                                        </svg>
                                        <div class="device-opt__label">Laptop</div>
                                    </div>
                                    <div class="device-opt" onclick="selectDevice(this,'PC')" id="opt-pc">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="2" y="3" width="20" height="14" rx="2"/>
                                            <line x1="8" y1="21" x2="16" y2="21"/>
                                            <line x1="12" y1="17" x2="12" y2="21"/>
                                        </svg>
                                        <div class="device-opt__label">PC de escritorio</div>
                                    </div>
                                </div>
                                <input type="hidden" id="tipo_dispositivo">

                                <!-- Campos Laptop -->
                                <div class="extra-fields form-grid" id="extra-laptop">
                                    <div class="form-group">
                                        <label>Marca</label>
                                        <input type="text" id="marca" placeholder="HP, Apple, ASUS…">
                                    </div>
                                    <div class="form-group">
                                        <label>Modelo <span class="label-opt">Opcional</span></label>
                                        <input type="text" id="modelo" placeholder="Ej. Pavilion 15">
                                    </div>
                                    <div class="form-group">
                                        <label>N.° de serie <span class="label-opt">Opcional</span></label>
                                        <input type="text" id="serie" placeholder="Ej. 5CD1234XYZ">
                                    </div>
                                    <div class="form-group">
                                        <label>Sistema operativo</label>
                                        <select id="so-laptop">
                                            <option value="">Seleccionar…</option>
                                            <option>Windows 11</option>
                                            <option>Windows 10</option>
                                            <option>macOS</option>
                                            <option>Linux</option>
                                            <option>Sin SO</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Campos PC -->
                                <div class="extra-fields" id="extra-pc" style="margin-top:18px;">
                                    <div class="form-group" style="max-width:260px;">
                                        <label>Sistema operativo</label>
                                        <select id="so-pc">
                                            <option value="">Seleccionar…</option>
                                            <option>Windows 11</option>
                                            <option>Windows 10</option>
                                            <option>Linux</option>
                                            <option>Sin SO</option>
                                        </select>
                                    </div>
                                </div>

                                <hr class="divider">
                                <div class="form-group">
                                    <label>Observaciones del equipo <span class="label-opt">Opcional</span></label>
                                    <textarea id="observaciones" placeholder="Describe el problema o síntomas que reporta el cliente…"></textarea>
                                </div>
                            </div>
                            <div class="step-nav">
                                <button class="btn-prev" onclick="prevStep(2)">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                                    Anterior
                                </button>
                                <button class="btn-next" onclick="nextStep(2)">
                                    Siguiente
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- PASO 3: SERVICIOS -->
                    <div class="step-content" id="step-3">
                        <div class="step-card">
                            <div class="step-card__head">
                                <div class="step-card__icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                                </div>
                                <div>
                                    <div class="step-card__title">Servicios y Cotización</div>
                                    <div class="step-card__sub">Elige los servicios a realizar</div>
                                </div>
                            </div>
                            <div class="step-card__body">
                                <div class="section-label-sm">Servicio base</div>
                                <div class="service-list" id="servicios-base">
                                    <label class="service-item">
                                        <input type="radio" name="srv_base" value="30" data-nombre="Diagnóstico" onchange="updateQuote()">
                                        <span class="service-item__name">Diagnóstico</span>
                                        <span class="service-item__price">S/ 30.00</span>
                                    </label>
                                    <label class="service-item">
                                        <input type="radio" name="srv_base" value="60" data-nombre="Mantenimiento preventivo" onchange="updateQuote()">
                                        <span class="service-item__name">Mantenimiento preventivo</span>
                                        <span class="service-item__price">S/ 60.00</span>
                                    </label>
                                    <label class="service-item">
                                        <input type="radio" name="srv_base" value="90" data-nombre="Mantenimiento correctivo" onchange="updateQuote()">
                                        <span class="service-item__name">Mantenimiento correctivo</span>
                                        <span class="service-item__price">S/ 90.00</span>
                                    </label>
                                    <label class="service-item">
                                        <input type="radio" name="srv_base" value="80" data-nombre="Instalación / Formateo" onchange="updateQuote()">
                                        <span class="service-item__name">Instalación / Formateo</span>
                                        <span class="service-item__price">S/ 80.00</span>
                                    </label>
                                </div>
                                <hr class="divider">
                                <div class="add-toggle" id="add-toggle" onclick="toggleAdd()">
                                    <div class="add-toggle__left">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                        Servicios adicionales
                                        <span class="add-badge hidden" id="add-badge">0</span>
                                    </div>
                                    <div class="add-chevron">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                                    </div>
                                </div>
                                <div class="add-panel" id="add-panel">
                                    <label class="service-item">
                                        <input type="checkbox" value="25" data-nombre="Limpieza profunda" onchange="updateQuote()">
                                        <span class="service-item__name">Limpieza profunda</span>
                                        <span class="service-item__price">S/ 25.00</span>
                                    </label>
                                    <label class="service-item">
                                        <input type="checkbox" value="20" data-nombre="Instalación de programas" onchange="updateQuote()">
                                        <span class="service-item__name">Instalación de programas</span>
                                        <span class="service-item__price">S/ 20.00</span>
                                    </label>
                                    <label class="service-item">
                                        <input type="checkbox" value="30" data-nombre="Optimización del sistema" onchange="updateQuote()">
                                        <span class="service-item__name">Optimización del sistema</span>
                                        <span class="service-item__price">S/ 30.00</span>
                                    </label>
                                    <label class="service-item">
                                        <input type="checkbox" value="50" data-nombre="Repotenciación (mano de obra)" onchange="updateQuote()">
                                        <span class="service-item__name">Repotenciación (mano de obra)</span>
                                        <span class="service-item__price">S/ 50.00</span>
                                    </label>
                                </div>
                            </div>
                            <div class="step-nav">
                                <button class="btn-prev" onclick="prevStep(3)">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                                    Anterior
                                </button>
                                <button class="btn-next" onclick="nextStep(3)">
                                    Siguiente
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- PASO 4: RESUMEN -->
                    <div class="step-content" id="step-4">
                        <div class="step-card">
                            <div class="step-card__head">
                                <div class="step-card__icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="16" x2="13" y2="16"/></svg>
                                </div>
                                <div>
                                    <div class="step-card__title">Resumen del Ticket</div>
                                    <div class="step-card__sub">Verifica la información antes de crear</div>
                                </div>
                            </div>
                            <div class="step-card__body">
                                <div class="summary-layout">
                                    <div class="summary-left" id="summary-left"></div>
                                    <div class="quote-box" id="summary-quote"></div>
                                </div>
                            </div>
                            <div class="step-nav">
                                <button class="btn-prev" onclick="prevStep(4)">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                                    Anterior
                                </button>
                                <button class="btn-finish" onclick="crearTicket()">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                    Crear Ticket
                                </button>
                            </div>
                        </div>
                    </div>

                </div><!-- /contenido pasos -->
            </div><!-- /wizard -->
        </main>
    </div>
</div>

<!-- ══ MODAL ÉXITO ══ -->
<div class="modal-overlay" id="modal-success">
    <div class="modal-box">
        <div class="modal-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div class="modal-title">¡Ticket creado!</div>
        <div class="modal-sub">El ticket ha sido registrado correctamente en el sistema.</div>
        <button class="modal-btn" onclick="window.location.href='tickets.php'">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:15px;height:15px"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
            Ver tickets
        </button>
    </div>
</div>

<script>
let currentStep = 1;
const TOTAL = 4;
const progress = [25, 50, 75, 100];

/* ══ NAVEGACIÓN ══ */
function goStep(n) {
    if (n > currentStep + 1 || n < 1) return;
    document.getElementById('step-' + currentStep).classList.remove('active');
    document.getElementById('nav-' + currentStep).classList.remove('active');
    if (n > currentStep) {
        document.getElementById('nav-' + currentStep).classList.add('done');
        document.getElementById('num-' + currentStep).textContent = '';
    }
    currentStep = n;
    document.getElementById('step-' + currentStep).classList.add('active');
    document.getElementById('nav-' + currentStep).classList.remove('done');
    document.getElementById('nav-' + currentStep).classList.add('active');
    document.getElementById('num-' + currentStep).textContent = currentStep;
    document.getElementById('progress-fill').style.width = progress[currentStep - 1] + '%';
    if (currentStep === 4) buildSummary();
}

function nextStep(from) {
    if (!validateStep(from)) return;
    if (from < TOTAL) goStep(from + 1);
}

function prevStep(from) {
    if (from > 1) {
        document.getElementById('step-' + from).classList.remove('active');
        document.getElementById('nav-' + from).classList.remove('active');
        document.getElementById('nav-' + from).classList.remove('done');
        document.getElementById('num-' + from).textContent = from;
        currentStep = from - 1;
        document.getElementById('step-' + currentStep).classList.add('active');
        document.getElementById('nav-' + currentStep).classList.add('active');
        document.getElementById('nav-' + currentStep).classList.remove('done');
        document.getElementById('num-' + currentStep).textContent = currentStep;
        document.getElementById('progress-fill').style.width = progress[currentStep - 1] + '%';
    }
}

/* ══ VALIDACIONES ══ */
function validateStep(n) {
    if (n === 1) {
        const dni = document.getElementById('dni').value.trim();
        const nom = document.getElementById('nombre_cliente').value.trim();
        const tel = document.getElementById('telefono').value.trim();
        if (!dni || dni.length !== 8) { alert('El DNI debe tener 8 dígitos.'); return false; }
        if (!nom)                      { alert('Ingresa el nombre del cliente.'); return false; }
        if (!tel || tel.length !== 9) { alert('El teléfono debe tener 9 dígitos.'); return false; }
    }
    if (n === 2) {
        if (!document.getElementById('tipo_dispositivo').value) { alert('Selecciona el tipo de dispositivo.'); return false; }
    }
    if (n === 3) {
        if (!document.querySelector('input[name="srv_base"]:checked')) { alert('Selecciona al menos un servicio base.'); return false; }
    }
    return true;
}

/* ══ DISPOSITIVO ══ */
function selectDevice(el, type) {
    document.querySelectorAll('.device-opt').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('tipo_dispositivo').value = type;
    document.getElementById('extra-laptop').classList.toggle('visible', type === 'Laptop');
    document.getElementById('extra-pc').classList.toggle('visible', type === 'PC');
}

/* ══ ACCORDION ══ */
function toggleAdd() {
    document.getElementById('add-toggle').classList.toggle('open');
    document.getElementById('add-panel').classList.toggle('open');
}

/* ══ COTIZACIÓN ══ */
function getItems() {
    const items = [];
    const base = document.querySelector('input[name="srv_base"]:checked');
    if (base) items.push({ name: base.dataset.nombre, price: parseFloat(base.value) });
    document.querySelectorAll('#add-panel input[type="checkbox"]:checked').forEach(cb => {
        items.push({ name: cb.dataset.nombre, price: parseFloat(cb.value) });
    });
    return items;
}

function updateQuote() {
    document.querySelectorAll('#servicios-base .service-item').forEach(row => {
        const r = row.querySelector('input[type="radio"]');
        row.classList.toggle('selected', r && r.checked);
    });
    document.querySelectorAll('#add-panel .service-item').forEach(row => {
        const c = row.querySelector('input[type="checkbox"]');
        row.classList.toggle('sel-add', c && c.checked);
    });
    const addCount = document.querySelectorAll('#add-panel input[type="checkbox"]:checked').length;
    const badge = document.getElementById('add-badge');
    badge.textContent = addCount;
    badge.classList.toggle('hidden', addCount === 0);
}

/* ══ RESUMEN ══ */
function buildSummary() {
    const nombre = document.getElementById('nombre_cliente').value || '—';
    const dni    = document.getElementById('dni').value || '—';
    const tel    = document.getElementById('telefono').value || '—';
    const correo = document.getElementById('correo').value || '';
    const tipo   = document.getElementById('tipo_dispositivo').value || '—';
    const marca  = document.getElementById('marca') ? (document.getElementById('marca').value || '') : '';
    const obs    = document.getElementById('observaciones').value || '';

    document.getElementById('summary-left').innerHTML = `
        <div class="summary-block">
            <div class="summary-block__label">Cliente</div>
            <div class="summary-block__value">
                <strong>${nombre}</strong><br>
                DNI: ${dni}<br>
                Tel: ${tel}
                ${correo ? '<br>Correo: ' + correo : ''}
            </div>
        </div>
        <div class="summary-block">
            <div class="summary-block__label">Dispositivo</div>
            <div class="summary-block__value">
                <strong>${tipo}</strong>${marca ? ' · ' + marca : ''}
                ${obs ? '<br><span style="font-size:12px;color:#a0a8bb;font-style:italic">' + obs + '</span>' : ''}
            </div>
        </div>`;

    const items = getItems();
    if (items.length === 0) {
        document.getElementById('summary-quote').innerHTML =
            `<div class="quote-box__title">Resumen de Cotización</div>
             <p style="opacity:.7;font-size:13px;text-align:center;padding:20px 0">Sin servicios seleccionados.</p>`;
        return;
    }
    const subtotal = items.reduce((a, i) => a + i.price, 0);
    const igv      = subtotal * 0.18;
    const total    = subtotal + igv;
    document.getElementById('summary-quote').innerHTML = `
        <div class="quote-box__title">Resumen de Cotización</div>
        ${items.map(i => `
        <div class="quote-item">
            <span class="quote-item__name">${i.name}</span>
            <span class="quote-item__price">S/ ${i.price.toFixed(2)}</span>
        </div>`).join('')}
        <hr class="q-divider">
        <div class="q-row"><span>Subtotal</span><span>S/ ${subtotal.toFixed(2)}</span></div>
        <div class="q-row"><span>IGV (18%)</span><span>S/ ${igv.toFixed(2)}</span></div>
        <hr class="q-divider">
        <div class="q-total">
            <span class="q-total__label">Total</span>
            <span class="q-total__amount">S/ ${total.toFixed(2)}</span>
        </div>`;
}

/* ══ CREAR TICKET ══ */
function crearTicket() {
    document.getElementById('modal-success').classList.add('show');
}

/* ══ SOLO NÚMEROS ══ */
['dni','ruc','telefono'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('input', function(){ this.value = this.value.replace(/\D/g,''); });
});
</script>
</body>
</html>