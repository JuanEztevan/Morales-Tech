<?php
$nombre_cliente = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Esteban Carmona';
$email_cliente  = isset($_SESSION['email'])  ? $_SESSION['email']  : 'esteban@email.com';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Cotización — Morales Tech</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --azul:      #1746EA;
            --celeste:   #1883ED;
            --negro:     #000019;
            --s1:        #05061a;
            --s2:        #0b0d22;
            --s3:        #12142e;
            --s4:        #1a1d3d;
            --borde:     rgba(255,255,255,.07);
            --txt:       #e8ebff;
            --muted:     #6b74a8;
            --grad:      linear-gradient(135deg,#1746EA 0%,#1883ED 100%);
            --grad-soft: linear-gradient(135deg,rgba(23,70,234,.18) 0%,rgba(24,131,237,.12) 100%);
        }
        *,*::before,*::after { box-sizing:border-box; margin:0; padding:0; }
        html { scroll-behavior:smooth; }
        body {
            font-family:'Montserrat',sans-serif;
            background:var(--negro);
            color:var(--txt);
            min-height:100vh;
            overflow-x:hidden;
        }
        a { text-decoration:none; color:inherit; }

        /* ── container idéntico a inicio_clientes ── */
        .container { width:100%; max-width:1060px; margin-inline:auto; padding-inline:24px; }

        /* ══ NAVBAR — idéntica a inicio_clientes.php ══ */
        .navbar {
            position:fixed; top:0; left:0; right:0; z-index:100;
            background:rgba(0,0,20,.82); backdrop-filter:blur(24px);
            border-bottom:1px solid var(--borde); height:64px;
            transition:background .25s,box-shadow .25s;
        }
        .navbar.scrolled { background:rgba(0,0,20,.97); box-shadow:0 4px 40px rgba(0,0,0,.6); }
        .nav-inner { display:flex; align-items:center; height:64px; }
        .nav-logo { display:flex; align-items:center; flex-shrink:0; margin-right:24px; }
        .nav-logo img { height:26px; width:auto; }
        .nav-logo-txt { font-weight:900; font-size:16px; color:var(--txt); display:none; }
        .nav-logo-txt span { color:var(--azul); }
        .nav-center { flex:1; display:flex; justify-content:center; }
        .nav-links {
            display:flex; align-items:center;
            background:rgba(255,255,255,.04); border:1px solid var(--borde);
            border-radius:50px; padding:4px 5px; gap:2px; list-style:none;
        }
        .nav-links li a {
            display:flex; align-items:center; gap:6px;
            padding:6px 14px; border-radius:50px;
            font-size:13px; font-weight:600; color:var(--muted);
            white-space:nowrap; transition:background .15s,color .15s;
        }
        .nav-links li a svg { width:13px; height:13px; flex-shrink:0; }
        .nav-links li a:hover  { background:rgba(255,255,255,.06); color:#c5d4ff; }
        .nav-links li a.active { background:rgba(23,70,234,.26); color:#fff; font-weight:700; }
        .nav-right { display:flex; align-items:center; gap:8px; flex-shrink:0; }
        .btn-salir {
            display:flex; align-items:center; gap:6px;
            font-size:12px; font-weight:700;
            padding:7px 15px; border-radius:50px;
            border:1.5px solid rgba(255,255,255,.10);
            background:rgba(255,255,255,.04); color:var(--muted);
            transition:all .18s;
        }
        .btn-salir:hover { border-color:rgba(201,74,0,.45); background:rgba(201,74,0,.08); color:#f5a07a; }
        .btn-salir svg { width:13px; height:13px; }
        .nav-ham { display:none; flex-direction:column; gap:5px; cursor:pointer; padding:6px; }
        .nav-ham span { display:block; width:22px; height:2px; background:var(--txt); border-radius:2px; transition:all .25s; }
        .mob-menu {
            display:none; position:fixed; top:64px; left:0; right:0; bottom:0;
            background:rgba(0,0,20,.98); backdrop-filter:blur(24px);
            z-index:99; flex-direction:column; padding:20px; gap:8px;
        }
        .mob-menu.open { display:flex; }
        .mob-menu a {
            font-size:14px; font-weight:600; color:var(--txt);
            padding:12px 16px; border-radius:10px; border:1px solid var(--borde);
            display:flex; align-items:center; gap:10px; transition:all .15s;
        }
        .mob-menu a:hover { background:rgba(23,70,234,.10); color:#8db4ff; border-color:rgba(23,70,234,.3); }
        .mob-menu a svg { width:16px; height:16px; flex-shrink:0; }
        .mob-divider { border:none; border-top:1px solid var(--borde); margin:4px 0; }

        /* ══ PAGE ══ */
        .page { padding-top:64px; }

        /* ══ HERO SECTION — idéntico a inicio_clientes y tickets_cliente ══ */
        .hero-section {
            background:linear-gradient(160deg,#060720 0%,#0d1035 55%,#060720 100%);
            border-bottom:1px solid var(--borde);
            padding:48px 0 40px;
            position:relative; overflow:hidden;
        }
        .hero-section::before {
            content:''; position:absolute; left:-100px; top:-80px;
            width:400px; height:400px; border-radius:50%;
            background:radial-gradient(circle,rgba(23,70,234,.20) 0%,transparent 70%);
            pointer-events:none;
        }
        .hero-section::after {
            content:''; position:absolute; right:-60px; bottom:-100px;
            width:320px; height:320px; border-radius:50%;
            background:radial-gradient(circle,rgba(24,131,237,.15) 0%,transparent 70%);
            pointer-events:none;
        }
        .hero-inner { position:relative; z-index:1; display:flex; align-items:flex-end; justify-content:space-between; gap:16px; flex-wrap:wrap; }
        .hero-tag {
            display:inline-flex; align-items:center; gap:7px;
            background:rgba(23,70,234,.16); border:1px solid rgba(23,70,234,.32);
            border-radius:50px; padding:4px 14px;
            font-size:10px; font-weight:800; letter-spacing:.09em;
            text-transform:uppercase; color:#8db4ff; margin-bottom:18px;
        }
        .hero-tag::before { content:''; width:5px; height:5px; border-radius:50%; background:var(--grad); flex-shrink:0; }
        .hero-title {
            font-size:clamp(1.8rem,3.2vw,2.4rem); font-weight:800;
            letter-spacing:-.03em; color:var(--txt); line-height:1.15;
            margin-bottom:8px;
        }
        .hero-sub { font-size:13px; color:var(--muted); line-height:1.7; }
        .btn-back {
            display:inline-flex; align-items:center; gap:7px;
            font-family:'Montserrat',sans-serif; font-size:12px; font-weight:700;
            padding:9px 18px; border-radius:50px;
            border:1.5px solid rgba(255,255,255,.10);
            background:rgba(255,255,255,.04); color:var(--muted);
            transition:all .15s; white-space:nowrap; flex-shrink:0;
        }
        .btn-back:hover { border-color:rgba(23,70,234,.5); color:#8db4ff; background:rgba(23,70,234,.08); }
        .btn-back svg { width:14px; height:14px; }

        /* ══ CONTENIDO WIZARD ══ */
        .main-section { padding:36px 0 60px; }

        /* ── Label igual a section-label de inicio ── */
        .section-label {
            font-size:10px; font-weight:800; letter-spacing:.10em; text-transform:uppercase;
            color:var(--muted); margin-bottom:16px;
        }

        /* ══ WIZARD ══ */
        .wizard { display:grid; grid-template-columns:200px 1fr; gap:22px; align-items:start; }

        /* Panel de pasos */
        .steps-panel {
            background:var(--s2); border:1px solid var(--borde);
            border-radius:16px; overflow:hidden;
            box-shadow:0 4px 32px rgba(0,0,0,.40);
            position:sticky; top:82px;
        }
        .steps-panel__header {
            padding:13px 18px 11px; border-bottom:1px solid var(--borde);
            font-size:10px; font-weight:800; letter-spacing:.10em;
            text-transform:uppercase; color:var(--muted);
        }
        .step-item {
            display:flex; align-items:center; gap:12px;
            padding:13px 18px; cursor:pointer;
            transition:background .15s; border-left:3px solid transparent;
        }
        .step-item:hover  { background:rgba(255,255,255,.03); }
        .step-item.active { background:rgba(23,70,234,.14); border-left-color:var(--azul); }
        .step-num {
            width:26px; height:26px; border-radius:50%;
            border:2px solid rgba(255,255,255,.12);
            display:grid; place-items:center;
            font-size:11px; font-weight:800; color:var(--muted);
            flex-shrink:0; transition:all .2s; background:transparent;
        }
        .step-item.active .step-num { background:var(--azul); border-color:var(--azul); color:#fff; }
        .step-item.done   .step-num { background:rgba(26,122,74,.3); border-color:rgba(26,122,74,.5); color:#5fc98a; }
        .step-label { font-size:12px; font-weight:700; color:var(--txt); line-height:1.2; }
        .step-item.active .step-label { color:#8db4ff; }
        .step-sub { font-size:10px; color:var(--muted); margin-top:1px; }
        .progress-bar  { height:3px; background:rgba(255,255,255,.06); }
        .progress-fill { height:100%; background:var(--grad); border-radius:2px; transition:width .3s; }

        /* Tarjetas de paso */
        .step-content        { display:none; }
        .step-content.active { display:block; }
        .step-card {
            background:var(--s2); border:1px solid var(--borde);
            border-radius:16px; overflow:hidden;
            box-shadow:0 4px 32px rgba(0,0,0,.40);
        }
        .step-card__head {
            padding:18px 22px 16px; border-bottom:1px solid var(--borde);
            display:flex; align-items:center; gap:12px;
        }
        .step-card__icon {
            width:34px; height:34px; border-radius:10px;
            background:var(--grad); display:grid; place-items:center; flex-shrink:0;
        }
        .step-card__icon svg { width:16px; height:16px; color:#fff; }
        .step-card__title { font-size:14px; font-weight:800; color:var(--txt); }
        .step-card__sub   { font-size:11px; color:var(--muted); margin-top:2px; }
        .step-card__body  { padding:22px; }

        /* ══ FORM OSCURO ══ */
        .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
        .span-2    { grid-column:span 2; }
        .form-group { display:flex; flex-direction:column; gap:6px; }
        .form-group label {
            font-size:11px; font-weight:700; color:var(--muted);
            letter-spacing:.04em; text-transform:uppercase;
        }
        .label-opt { font-size:10px; font-weight:500; color:#3a4470; text-transform:none; letter-spacing:0; margin-left:3px; }
        input[type="text"], select, textarea {
            font-family:'Montserrat',sans-serif;
            width:100%; padding:11px 16px;
            border:1.5px solid rgba(255,255,255,.09);
            border-radius:50px; font-size:13px; font-weight:500;
            color:var(--txt); background:rgba(255,255,255,.05);
            transition:border-color .15s,box-shadow .15s;
            outline:none; -webkit-appearance:none; appearance:none;
        }
        select {
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b74a8' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
            background-repeat:no-repeat; background-position:right 14px center;
            padding-right:38px; cursor:pointer;
            background-color:rgba(255,255,255,.05);
        }
        select option { background:#0b0d22; color:var(--txt); }
        textarea { border-radius:14px; resize:vertical; min-height:88px; }
        input:focus, select:focus, textarea:focus {
            border-color:var(--azul); box-shadow:0 0 0 3px rgba(23,70,234,.18);
        }
        input::placeholder, textarea::placeholder { color:#3a4470; }

        /* ══ SELECTOR DISPOSITIVO ══ */
        .section-sm {
            font-size:10px; font-weight:800; color:var(--muted);
            letter-spacing:.08em; text-transform:uppercase; margin-bottom:12px;
        }
        .device-grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
        .device-opt {
            border:2px solid rgba(255,255,255,.09);
            border-radius:14px; padding:22px 14px 18px;
            cursor:pointer; text-align:center;
            transition:all .22s; background:rgba(255,255,255,.03);
            user-select:none;
        }
        .device-opt:hover { border-color:rgba(24,131,237,.45); background:rgba(24,131,237,.07); }
        .device-opt.selected {
            border-color:transparent;
            background:linear-gradient(135deg,#1746EA 0%,#1883ED 100%);
            box-shadow:0 6px 22px rgba(23,70,234,.35);
        }
        .device-opt svg {
            width:40px; height:40px; display:block; margin-inline:auto;
            margin-bottom:12px; color:rgba(255,255,255,.25); transition:color .2s;
        }
        .device-opt:hover svg { color:rgba(255,255,255,.55); }
        .device-opt.selected svg { color:rgba(255,255,255,.95); }
        .device-opt__label { font-size:12px; font-weight:700; color:var(--muted); transition:color .2s; }
        .device-opt:hover .device-opt__label { color:#8db4ff; }
        .device-opt.selected .device-opt__label { color:#fff; font-weight:800; }
        .extra-fields { margin-top:20px; display:none; }
        .extra-fields.visible { display:grid; }

        /* ══ SERVICIOS ══ */
        .service-list { display:flex; flex-direction:column; gap:8px; }
        .service-item {
            display:flex; align-items:center; gap:12px;
            padding:12px 18px; border:1.5px solid rgba(255,255,255,.08);
            border-radius:50px; cursor:pointer; transition:all .15s;
            background:rgba(255,255,255,.03); user-select:none;
        }
        .service-item:hover { border-color:rgba(24,131,237,.4); background:rgba(24,131,237,.06); }
        .service-item.selected { border-color:var(--azul); background:rgba(23,70,234,.16); }
        .service-item.sel-add  { border-color:var(--celeste); background:rgba(24,131,237,.12); }
        .service-item input[type="radio"],
        .service-item input[type="checkbox"] {
            width:16px; height:16px; min-width:16px;
            accent-color:var(--azul); cursor:pointer;
            padding:0; border:none; background:none; box-shadow:none;
        }
        .service-item__name  { flex:1; font-size:13px; font-weight:600; color:var(--txt); }
        .service-item__price { font-size:12px; font-weight:700; color:var(--muted); white-space:nowrap; }
        .service-item.selected .service-item__price { color:#8db4ff; }
        .service-item.sel-add  .service-item__price { color:#8db4ff; }
        .divider { border:none; border-top:1px solid var(--borde); margin:18px 0; }

        /* Accordion adicionales */
        .add-toggle {
            display:flex; align-items:center; justify-content:space-between;
            padding:12px 18px; border:1.5px solid rgba(255,255,255,.08);
            border-radius:50px; cursor:pointer; background:rgba(255,255,255,.03);
            transition:all .15s; user-select:none; margin-top:4px;
        }
        .add-toggle:hover { border-color:rgba(24,131,237,.4); background:rgba(24,131,237,.06); }
        .add-toggle.open  { border-color:var(--azul); background:rgba(23,70,234,.12); border-radius:16px 16px 0 0; border-bottom:none; }
        .add-toggle__left { display:flex; align-items:center; gap:10px; font-size:13px; font-weight:700; color:var(--txt); }
        .add-toggle__left svg { width:15px; height:15px; color:#8db4ff; }
        .add-badge { font-size:10px; font-weight:800; background:var(--azul); color:#fff; border-radius:50px; padding:2px 8px; }
        .add-badge.hidden { display:none; }
        .add-chevron { transition:transform .25s; color:var(--muted); }
        .add-chevron svg { width:15px; height:15px; }
        .add-toggle.open .add-chevron { transform:rotate(180deg); }
        .add-panel {
            display:none; flex-direction:column; gap:0;
            border:1.5px solid var(--azul); border-top:none;
            border-radius:0 0 16px 16px;
            background:rgba(23,70,234,.08); overflow:hidden;
        }
        .add-panel.open { display:flex; }
        .add-panel .service-item {
            border-radius:0; border:none;
            border-bottom:1px solid rgba(23,70,234,.14);
            background:transparent;
        }
        .add-panel .service-item:last-child { border-bottom:none; }
        .add-panel .service-item:hover { background:rgba(23,70,234,.08); }

        /* ══ RESUMEN PASO 3 ══ */
        .summary-layout { display:grid; grid-template-columns:1fr 1.1fr; gap:16px; align-items:start; }
        .summary-left   { display:flex; flex-direction:column; gap:12px; }
        .summary-block {
            background:rgba(255,255,255,.04); border:1px solid var(--borde);
            border-radius:12px; padding:16px 18px;
        }
        .summary-block__label {
            font-size:10px; font-weight:800; color:var(--muted);
            letter-spacing:.08em; text-transform:uppercase; margin-bottom:10px;
        }
        .summary-block__value { font-size:13px; font-weight:500; color:var(--txt); line-height:1.7; }
        .summary-block__value strong { font-weight:800; }
        .quote-box {
            background:linear-gradient(145deg,#1746EA 0%,#1883ED 100%);
            border-radius:16px; padding:22px 24px; color:#fff;
            box-shadow:0 8px 30px rgba(23,70,234,.30);
        }
        .quote-box__title { font-size:10px; font-weight:800; letter-spacing:.10em; text-transform:uppercase; opacity:.75; margin-bottom:16px; }
        .quote-item { display:flex; justify-content:space-between; font-size:13px; margin-bottom:8px; }
        .quote-item__name  { font-weight:500; opacity:.9; }
        .quote-item__price { font-weight:700; white-space:nowrap; }
        .q-divider { border:none; border-top:1px solid rgba(255,255,255,.2); margin:14px 0; }
        .q-row { display:flex; justify-content:space-between; font-size:12px; font-weight:600; opacity:.8; margin-bottom:6px; }
        .q-total { display:flex; justify-content:space-between; align-items:center; margin-top:12px; }
        .q-total__label  { font-size:14px; font-weight:700; opacity:.9; }
        .q-total__amount { font-size:30px; font-weight:800; letter-spacing:-.5px; }
        .quote-note { margin-top:12px; font-size:10px; opacity:.55; font-style:italic; text-align:center; line-height:1.5; }

        /* ══ BOTONES DE NAVEGACIÓN ══ */
        .step-nav {
            display:flex; justify-content:space-between; align-items:center;
            padding:16px 22px; border-top:1px solid var(--borde);
            background:rgba(255,255,255,.02);
        }
        .btn-prev, .btn-next, .btn-send {
            display:inline-flex; align-items:center; gap:8px;
            font-family:'Montserrat',sans-serif;
            font-size:13px; font-weight:700;
            padding:10px 22px; border-radius:50px; border:none; cursor:pointer;
            transition:all .15s;
        }
        .btn-prev { background:rgba(255,255,255,.06); color:var(--muted); border:1.5px solid rgba(255,255,255,.10); }
        .btn-prev:hover { border-color:rgba(23,70,234,.5); color:#8db4ff; background:rgba(23,70,234,.08); }
        .btn-next { background:var(--azul); color:#fff; box-shadow:0 4px 14px rgba(23,70,234,.30); }
        .btn-next:hover  { background:#1238c2; transform:translateY(-1px); }
        .btn-send { background:rgba(26,122,74,.85); color:#fff; box-shadow:0 4px 14px rgba(26,122,74,.25); border:1px solid rgba(26,122,74,.5); }
        .btn-send:hover  { background:#145c38; transform:translateY(-1px); }
        .btn-prev svg, .btn-next svg, .btn-send svg { width:14px; height:14px; }

        /* ══ MODAL ÉXITO ══ */
        .modal-overlay {
            position:fixed; inset:0;
            background:rgba(0,0,20,.75); backdrop-filter:blur(8px);
            display:flex; align-items:center; justify-content:center;
            z-index:1000; opacity:0; pointer-events:none; transition:opacity .25s;
        }
        .modal-overlay.show { opacity:1; pointer-events:all; }
        .modal-box {
            background:var(--s2); border:1px solid var(--borde);
            border-radius:24px; padding:48px 40px 40px;
            max-width:380px; width:90%; text-align:center;
            box-shadow:0 24px 60px rgba(0,0,0,.60);
            transform:translateY(24px) scale(.97); transition:transform .25s;
        }
        .modal-overlay.show .modal-box { transform:translateY(0) scale(1); }
        .modal-icon {
            width:72px; height:72px;
            background:rgba(26,122,74,.22); border:1px solid rgba(26,122,74,.35);
            border-radius:50%; display:grid; place-items:center;
            margin:0 auto 20px;
        }
        .modal-icon svg { width:36px; height:36px; color:#5fc98a; }
        .modal-title { font-size:20px; font-weight:800; color:var(--txt); margin-bottom:8px; }
        .modal-sub   { font-size:13px; color:var(--muted); line-height:1.6; margin-bottom:28px; }
        .modal-btn {
            display:inline-flex; align-items:center; gap:8px;
            background:var(--azul); color:#fff;
            font-family:'Montserrat',sans-serif;
            font-size:14px; font-weight:700;
            padding:12px 28px; border-radius:50px; border:none;
            cursor:pointer; transition:background .15s,transform .1s;
            box-shadow:0 4px 14px rgba(23,70,234,.30);
        }
        .modal-btn:hover { background:#1238c2; transform:translateY(-1px); }

        /* ══ WA FLOAT ══ */
        .float-wa {
            position:fixed; bottom:26px; right:26px; z-index:200;
            width:54px; height:54px; border-radius:50%; background:#25D366;
            display:grid; place-items:center;
            box-shadow:0 6px 28px rgba(37,211,102,.45);
            transition:transform .2s,box-shadow .2s;
            animation:wa-pulse 3s ease-in-out infinite;
        }
        .float-wa:hover { transform:scale(1.10); animation:none; }
        .float-wa svg { width:25px; height:25px; color:#fff; }
        @keyframes wa-pulse {
            0%,100% { box-shadow:0 6px 28px rgba(37,211,102,.45),0 0 0 0 rgba(37,211,102,.3); }
            50%      { box-shadow:0 6px 28px rgba(37,211,102,.45),0 0 0 10px rgba(37,211,102,.0); }
        }

        /* ══ RESPONSIVE ══ */
        @media(max-width:800px) { .wizard{grid-template-columns:1fr;} .steps-panel{position:static;} }
        @media(max-width:700px) { .nav-center{display:none;} .nav-ham{display:flex;} .hero-section{padding:36px 0 32px;} }
        @media(max-width:580px) {
            .form-grid{grid-template-columns:1fr;} .span-2{grid-column:span 1;}
            .summary-layout{grid-template-columns:1fr;}
        }
    </style>
</head>
<body>

<!-- ══ NAVBAR ══ -->
<nav class="navbar" id="navbar">
    <div class="container">
        <div class="nav-inner">
            <a href="index.php" class="nav-logo">
                <img src="img/logo-horizontal-blanco.png" alt="Morales Tech"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                <div class="nav-logo-txt" style="display:none;">Morales<span>Tech</span></div>
            </a>
            <div class="nav-center">
                <ul class="nav-links">
                    <li>
                        <a href="inicio_clientes.php">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                            Inicio
                        </a>
                    </li>
                    <li>
                        <a href="tickets_cliente.php">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
                            Mis tickets
                        </a>
                    </li>
                    <li>
                        <a href="nuevo_ticket_cliente.php" class="active">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
                            Nueva cotización
                        </a>
                    </li>
                </ul>
            </div>
            <div class="nav-right">
                <a href="login.php" class="btn-salir">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Salir
                </a>
                <div class="nav-ham" id="hamburger" aria-label="Menú">
                    <span></span><span></span><span></span>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile menu -->
<div class="mob-menu" id="mob-menu">
    <a href="inicio_clientes.php" onclick="closeMenu()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
        Inicio
    </a>
    <a href="tickets_cliente.php" onclick="closeMenu()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
        Mis tickets
    </a>
    <a href="nuevo_ticket_cliente.php" onclick="closeMenu()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
        Nueva cotización
    </a>
    <hr class="mob-divider">
    <a href="login.php">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        Cerrar sesión
    </a>
</div>

<!-- ══ PAGE ══ -->
<div class="page">

    <!-- ══ HERO SECTION — mismo patrón que inicio y tickets ══ -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-inner">
                <div>
                    <div class="hero-tag">Solicitud de servicio</div>
                    <h1 class="hero-title">Nueva cotización</h1>
                    <p class="hero-sub">Solicita un presupuesto para tu equipo en tres pasos</p>
                </div>
                <a href="tickets_cliente.php" class="btn-back">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                    Volver a tickets
                </a>
            </div>
        </div>
    </section>

    <!-- ══ WIZARD ══ -->
    <section class="main-section">
        <div class="container">
            <div class="section-label">Completa los pasos</div>
            <div class="wizard">

                <!-- Panel de pasos -->
                <div class="steps-panel">
                    <div class="steps-panel__header">Progreso</div>
                    <div class="step-item active" id="nav-1" onclick="goStep(1)">
                        <div class="step-num" id="num-1">1</div>
                        <div>
                            <div class="step-label">Dispositivo</div>
                            <div class="step-sub">Tipo y especificaciones</div>
                        </div>
                    </div>
                    <div class="step-item" id="nav-2" onclick="goStep(2)">
                        <div class="step-num" id="num-2">2</div>
                        <div>
                            <div class="step-label">Servicios</div>
                            <div class="step-sub">Qué necesitas</div>
                        </div>
                    </div>
                    <div class="step-item" id="nav-3" onclick="goStep(3)">
                        <div class="step-num" id="num-3">3</div>
                        <div>
                            <div class="step-label">Resumen</div>
                            <div class="step-sub">Confirmar solicitud</div>
                        </div>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" id="progress-fill" style="width:33%"></div>
                    </div>
                </div>

                <!-- Contenido pasos -->
                <div>
                    <!-- PASO 1: DISPOSITIVO -->
                    <div class="step-content active" id="step-1">
                        <div class="step-card">
                            <div class="step-card__head">
                                <div class="step-card__icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="13" rx="2"/><polyline points="1 21 23 21"/></svg>
                                </div>
                                <div>
                                    <div class="step-card__title">Tu dispositivo</div>
                                    <div class="step-card__sub">Selecciona el tipo de equipo a cotizar</div>
                                </div>
                            </div>
                            <div class="step-card__body">
                                <div class="section-sm">Tipo de equipo</div>
                                <div class="device-grid">
                                    <div class="device-opt" onclick="selectDevice(this,'Laptop')" id="opt-laptop">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="13" rx="2"/><polyline points="1 21 23 21"/></svg>
                                        <div class="device-opt__label">Laptop</div>
                                    </div>
                                    <div class="device-opt" onclick="selectDevice(this,'PC')" id="opt-pc">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                                        <div class="device-opt__label">PC de escritorio</div>
                                    </div>
                                </div>
                                <input type="hidden" id="tipo_dispositivo">
                                <!-- Campos laptop -->
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
                                    <label>Describe el problema <span class="label-opt">Opcional</span></label>
                                    <textarea id="observaciones" placeholder="Cuéntanos qué le pasa a tu equipo o qué servicio necesitas…"></textarea>
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

                    <!-- PASO 2: SERVICIOS -->
                    <div class="step-content" id="step-2">
                        <div class="step-card">
                            <div class="step-card__head">
                                <div class="step-card__icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                                </div>
                                <div>
                                    <div class="step-card__title">Servicios</div>
                                    <div class="step-card__sub">Selecciona lo que necesitas</div>
                                </div>
                            </div>
                            <div class="step-card__body">
                                <div class="section-sm">Servicio principal</div>
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

                    <!-- PASO 3: RESUMEN -->
                    <div class="step-content" id="step-3">
                        <div class="step-card">
                            <div class="step-card__head">
                                <div class="step-card__icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="16" x2="13" y2="16"/></svg>
                                </div>
                                <div>
                                    <div class="step-card__title">Resumen de tu solicitud</div>
                                    <div class="step-card__sub">Revisa antes de enviar</div>
                                </div>
                            </div>
                            <div class="step-card__body">
                                <div class="summary-layout">
                                    <div class="summary-left" id="summary-left"></div>
                                    <div class="quote-box" id="summary-quote"></div>
                                </div>
                            </div>
                            <div class="step-nav">
                                <button class="btn-prev" onclick="prevStep(3)">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                                    Anterior
                                </button>
                                <button class="btn-send" onclick="enviarSolicitud()">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                    Enviar solicitud
                                </button>
                            </div>
                        </div>
                    </div>
                </div><!-- /wizard cols -->
            </div><!-- /wizard -->
        </div>
    </section>
</div><!-- /page -->

<!-- ══ MODAL ÉXITO ══ -->
<div class="modal-overlay" id="modal-success">
    <div class="modal-box">
        <div class="modal-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div class="modal-title">¡Solicitud enviada!</div>
        <div class="modal-sub">Tu cotización ha sido enviada correctamente. Pronto nos pondremos en contacto contigo.</div>
        <button class="modal-btn" onclick="window.location.href='tickets_cliente.php'">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
            Ver mis tickets
        </button>
    </div>
</div>

<!-- WhatsApp flotante -->
<a href="https://wa.me/51903208170" target="_blank" rel="noopener" class="float-wa" title="WhatsApp">
    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
</a>

<script>
    /* ══ NAVBAR SCROLL ══ */
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => { navbar.classList.toggle('scrolled', window.scrollY > 20); });

    /* ══ HAMBURGER ══ */
    const ham     = document.getElementById('hamburger');
    const mobMenu = document.getElementById('mob-menu');
    let menuOpen  = false;
    ham.addEventListener('click', () => {
        menuOpen = !menuOpen;
        mobMenu.classList.toggle('open', menuOpen);
        const s = ham.querySelectorAll('span');
        if (menuOpen) {
            s[0].style.transform = 'translateY(7px) rotate(45deg)';
            s[1].style.opacity   = '0';
            s[2].style.transform = 'translateY(-7px) rotate(-45deg)';
        } else {
            s.forEach(x => { x.style.transform = ''; x.style.opacity = ''; });
        }
    });
    function closeMenu() {
        menuOpen = false;
        mobMenu.classList.remove('open');
        ham.querySelectorAll('span').forEach(x => { x.style.transform = ''; x.style.opacity = ''; });
    }

    /* ══ WIZARD ══ */
    let currentStep = 1;
    const TOTAL     = 3;
    const progress  = [33, 66, 100];
    function goStep(n) {
        if (n > currentStep + 1 || n < 1) return;
        document.getElementById('step-' + currentStep).classList.remove('active');
        document.getElementById('nav-'  + currentStep).classList.remove('active');
        if (n > currentStep) document.getElementById('nav-' + currentStep).classList.add('done');
        currentStep = n;
        document.getElementById('step-' + currentStep).classList.add('active');
        document.getElementById('nav-'  + currentStep).classList.remove('done');
        document.getElementById('nav-'  + currentStep).classList.add('active');
        document.getElementById('progress-fill').style.width = progress[currentStep - 1] + '%';
        if (currentStep === 3) buildSummary();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    function nextStep(from) { if (!validateStep(from)) return; if (from < TOTAL) goStep(from + 1); }
    function prevStep(from) {
        if (from <= 1) return;
        document.getElementById('step-' + from).classList.remove('active');
        document.getElementById('nav-'  + from).classList.remove('active','done');
        currentStep = from - 1;
        document.getElementById('step-' + currentStep).classList.add('active');
        document.getElementById('nav-'  + currentStep).classList.add('active');
        document.getElementById('nav-'  + currentStep).classList.remove('done');
        document.getElementById('progress-fill').style.width = progress[currentStep - 1] + '%';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    function validateStep(n) {
        if (n === 1 && !document.getElementById('tipo_dispositivo').value) {
            alert('Por favor selecciona el tipo de dispositivo.');
            return false;
        }
        if (n === 2 && !document.querySelector('input[name="srv_base"]:checked')) {
            alert('Por favor selecciona al menos un servicio principal.');
            return false;
        }
        return true;
    }

    /* ══ DISPOSITIVO ══ */
    function selectDevice(el, type) {
        document.querySelectorAll('.device-opt').forEach(o => o.classList.remove('selected'));
        el.classList.add('selected');
        document.getElementById('tipo_dispositivo').value = type;
        document.getElementById('extra-laptop').classList.toggle('visible', type === 'Laptop');
        document.getElementById('extra-pc').classList.toggle('visible',     type === 'PC');
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
        const n = document.querySelectorAll('#add-panel input[type="checkbox"]:checked').length;
        const badge = document.getElementById('add-badge');
        badge.textContent = n;
        badge.classList.toggle('hidden', n === 0);
    }

    /* ══ RESUMEN ══ */
    function buildSummary() {
        const tipo  = document.getElementById('tipo_dispositivo').value || '—';
        const marca = document.getElementById('marca')?.value || '';
        const obs   = document.getElementById('observaciones').value || '';
        const nombreCliente = '<?= htmlspecialchars($nombre_cliente) ?>';
        const emailCliente  = '<?= htmlspecialchars($email_cliente) ?>';
        document.getElementById('summary-left').innerHTML = `
            <div class="summary-block">
                <div class="summary-block__label">Dispositivo</div>
                <div class="summary-block__value">
                    <strong>${tipo}</strong>${marca ? ' · ' + marca : ''}
                    ${obs ? '<br><span style="font-size:12px;color:#6b74a8;font-style:italic">' + obs + '</span>' : ''}
                </div>
            </div>
            <div class="summary-block">
                <div class="summary-block__label">Solicitante</div>
                <div class="summary-block__value">
                    <strong>${nombreCliente}</strong><br>
                    <span style="color:#6b74a8">${emailCliente}</span>
                </div>
            </div>`;
        const items = getItems();
        if (!items.length) {
            document.getElementById('summary-quote').innerHTML =
                `<div class="quote-box__title">Cotización estimada</div>
                 <p style="opacity:.65;font-size:13px;text-align:center;padding:20px 0">Sin servicios seleccionados.</p>`;
            return;
        }
        const subtotal = items.reduce((a, i) => a + i.price, 0);
        const igv      = subtotal * 0.18;
        const total    = subtotal + igv;
        document.getElementById('summary-quote').innerHTML = `
            <div class="quote-box__title">Cotización estimada</div>
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
            </div>
            <p class="quote-note">* Cotización referencial. El precio final puede variar según diagnóstico.</p>`;
    }

    /* ══ ENVIAR ══ */
    function enviarSolicitud() {
        document.getElementById('modal-success').classList.add('show');
    }
</script>
</body>
</html>