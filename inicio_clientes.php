<?php
// session_start();
// include("includes/auth_cliente.php");
$nombre_cliente = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Esteban Demo';
$partes         = explode(' ', trim($nombre_cliente));
$nombre_corto   = $partes[0];
$inicial        = strtoupper(substr($partes[0], 0, 1));
$hora   = (int) date('H');
$saludo = $hora < 12 ? 'Buenos días' : ($hora < 19 ? 'Buenas tardes' : 'Buenas noches');

$tickets = [
    ["id"=>"MT-8842","equipo"=>"Laptop HP Pavilion 15","servicio"=>"Mantenimiento correctivo","estado"=>"En diagnóstico","fecha"=>"20 may 2025"],
    ["id"=>"MT-8843","equipo"=>"PC Escritorio",        "servicio"=>"Repotenciación",           "estado"=>"Recibido",       "fecha"=>"21 may 2025"],
    ["id"=>"MT-8844","equipo"=>"MacBook Pro 2021",     "servicio"=>"Diagnóstico técnico",       "estado"=>"Completado",     "fecha"=>"18 may 2025"],
];

function estado_cfg($e) {
    $m = [
        'Recibido'       => ['bg'=>'rgba(245,166,35,.18)',  'color'=>'#f5c048', 'dot'=>'#f5a623'],
        'En diagnóstico' => ['bg'=>'rgba(23,70,234,.22)',   'color'=>'#8db4ff', 'dot'=>'#1746EA'],
        'En reparación'  => ['bg'=>'rgba(201,74,0,.20)',    'color'=>'#f5a07a', 'dot'=>'#e85d04'],
        'Completado'     => ['bg'=>'rgba(26,122,74,.22)',   'color'=>'#5fc98a', 'dot'=>'#1a7a4a'],
    ];
    return $m[$e] ?? ['bg'=>'rgba(100,100,120,.18)','color'=>'#a0a8bb','dot'=>'#7a8096'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Portal — Morales Tech</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --azul:     #1746EA;
            --celeste:  #1883ED;
            --negro:    #000019;
            --s1:       #05061a;
            --s2:       #0b0d22;
            --s3:       #12142e;
            --s4:       #1a1d3d;
            --borde:    rgba(255,255,255,.07);
            --txt:      #e8ebff;
            --muted:    #6b74a8;
            --grad:     linear-gradient(135deg,#1746EA 0%,#1883ED 100%);
            --grad-soft:linear-gradient(135deg,rgba(23,70,234,.18) 0%,rgba(24,131,237,.12) 100%);
        }
        *,*::before,*::after { box-sizing:border-box; margin:0; padding:0; }
        html { scroll-behavior:smooth; }
        body { font-family:'Montserrat',sans-serif; background:var(--negro); color:var(--txt); min-height:100vh; overflow-x:hidden; }
        a { text-decoration:none; color:inherit; }
        .container { width:100%; max-width:1060px; margin-inline:auto; padding-inline:24px; }

        /* ══ NAVBAR ══ */
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
        .mob-menu { display:none; position:fixed; top:64px; left:0; right:0; bottom:0; background:rgba(0,0,20,.98); backdrop-filter:blur(24px); z-index:99; flex-direction:column; padding:20px; gap:8px; }
        .mob-menu.open { display:flex; }
        .mob-menu a { font-size:14px; font-weight:600; color:var(--txt); padding:12px 16px; border-radius:10px; border:1px solid var(--borde); display:flex; align-items:center; gap:10px; transition:all .15s; }
        .mob-menu a:hover { background:rgba(23,70,234,.10); color:#8db4ff; border-color:rgba(23,70,234,.3); }
        .mob-menu a svg { width:16px; height:16px; flex-shrink:0; }
        .mob-divider { border:none; border-top:1px solid var(--borde); margin:4px 0; }

        /* ══ PAGE WRAPPER ══ */
        .page { padding-top:64px; }

        /* ══ BIENVENIDA ══ */
        .welcome-section {
            background: linear-gradient(160deg, #060720 0%, #0d1035 55%, #060720 100%);
            border-bottom:1px solid var(--borde);
            padding:48px 0 40px;
            position:relative; overflow:hidden;
        }
        .welcome-section::before {
            content:''; position:absolute; left:-100px; top:-80px;
            width:400px; height:400px; border-radius:50%;
            background:radial-gradient(circle,rgba(23,70,234,.2) 0%,transparent 70%);
            pointer-events:none;
        }
        .welcome-section::after {
            content:''; position:absolute; right:-60px; bottom:-100px;
            width:320px; height:320px; border-radius:50%;
            background:radial-gradient(circle,rgba(24,131,237,.15) 0%,transparent 70%);
            pointer-events:none;
        }
        .welcome-inner { position:relative; z-index:1; }
        .welcome-tag {
            display:inline-flex; align-items:center; gap:7px;
            background:rgba(23,70,234,.16); border:1px solid rgba(23,70,234,.32);
            border-radius:50px; padding:4px 14px;
            font-size:10px; font-weight:800; letter-spacing:.09em;
            text-transform:uppercase; color:#8db4ff; margin-bottom:18px;
        }
        .welcome-tag::before { content:''; width:5px; height:5px; border-radius:50%; background:var(--grad); flex-shrink:0; }
        .welcome-greeting { font-size:13px; font-weight:500; color:var(--muted); margin-bottom:5px; }
        .welcome-name {
            font-size:clamp(1.8rem,3.2vw,2.4rem); font-weight:800;
            letter-spacing:-.03em; color:var(--txt); line-height:1.15;
            margin-bottom:10px;
        }
        .welcome-name em { font-style:normal; background:var(--grad); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
        .welcome-sub { font-size:13px; color:var(--muted); line-height:1.7; max-width:420px; }

        /* ══ ACCESOS RÁPIDOS ══ */
        .quick-section { padding:36px 0 0; }
        .section-label {
            font-size:10px; font-weight:800; letter-spacing:.10em; text-transform:uppercase;
            color:var(--muted); margin-bottom:16px;
        }
        .quick-grid { display:flex; gap:12px; flex-wrap:wrap; }
        .qa-btn {
            display:flex; flex-direction:column; align-items:center; justify-content:center;
            gap:10px; padding:20px 16px; border-radius:16px;
            border:1px solid var(--borde); background:var(--s2);
            cursor:pointer; transition:all .2s; min-width:100px;
            text-align:center;
        }
        .qa-btn:hover { border-color:rgba(23,70,234,.40); background:rgba(23,70,234,.10); transform:translateY(-2px); }
        .qa-btn--accent { background:var(--grad); border-color:transparent; box-shadow:0 4px 22px rgba(23,70,234,.40); }
        .qa-btn--accent:hover { opacity:.88; transform:translateY(-2px); }
        .qa-btn__icon { width:40px; height:40px; border-radius:12px; background:rgba(255,255,255,.10); display:grid; place-items:center; }
        .qa-btn--accent .qa-btn__icon { background:rgba(255,255,255,.18); }
        .qa-btn__icon svg { width:20px; height:20px; color:#fff; }
        .qa-btn__label { font-size:11px; font-weight:700; color:var(--txt); white-space:nowrap; }

        /* ══ CONTENIDO PRINCIPAL ══ */
        .main-section { padding:36px 0 56px; }

        /* ══ TABLA DE TICKETS ══ */
        .panel {
            background:var(--s2); border:1px solid var(--borde);
            border-radius:16px; overflow:hidden;
            box-shadow:0 4px 32px rgba(0,0,0,.40);
        }
        .panel__head {
            padding:15px 20px 13px; border-bottom:1px solid var(--borde);
            display:flex; align-items:center; justify-content:space-between;
        }
        .panel__title { font-size:13px; font-weight:800; color:var(--txt); display:flex; align-items:center; gap:8px; }
        .panel__title svg { width:14px; height:14px; color:#6fa3ff; }
        .panel__link { font-size:12px; font-weight:600; color:#6fa3ff; transition:color .15s; }
        .panel__link:hover { color:#c5d4ff; }

        /* Tabla */
        .ticket-table { width:100%; border-collapse:collapse; min-width:500px; }
        .tt-wrap { overflow-x:auto; -webkit-overflow-scrolling:touch; }
        .ticket-table th {
            padding:10px 18px; font-size:10px; font-weight:700;
            letter-spacing:.08em; text-transform:uppercase;
            color:var(--muted); text-align:left;
            border-bottom:1px solid var(--borde);
            background:rgba(255,255,255,.02);
        }
        .ticket-table td { padding:14px 18px; font-size:13px; vertical-align:middle; border-bottom:1px solid var(--borde); }
        .ticket-table tr:last-child td { border-bottom:none; }
        .ticket-table tr:hover td { background:rgba(255,255,255,.025); }

        .t-id    { font-size:12px; font-weight:800; color:#6fa3ff; }
        .t-eq    { font-size:13px; font-weight:700; color:var(--txt); }
        .t-svc   { font-size:11px; color:var(--muted); margin-top:2px; }
        .t-fecha { font-size:11px; font-weight:600; color:#3a4470; white-space:nowrap; }
        .estado-badge {
            display:inline-flex; align-items:center; gap:5px;
            font-size:10px; font-weight:700;
            padding:4px 11px; border-radius:50px; white-space:nowrap;
        }
        .estado-dot { width:5px; height:5px; border-radius:50%; flex-shrink:0; }
        .t-det {
            display:inline-flex; align-items:center; gap:5px;
            font-size:11px; font-weight:700; color:#6fa3ff;
            padding:5px 12px; border-radius:50px;
            border:1px solid rgba(23,70,234,.3);
            background:rgba(23,70,234,.10);
            transition:background .15s;
        }
        .t-det:hover { background:rgba(23,70,234,.22); }
        .t-det svg { width:11px; height:11px; }

        /* Empty */
        .empty-state { padding:44px 20px; text-align:center; }
        .empty-state svg { width:36px; height:36px; margin-inline:auto; margin-bottom:12px; opacity:.2; }
        .empty-state p { font-size:13px; color:var(--muted); font-weight:600; }

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
        @media(max-width:700px){
            .nav-center { display:none; }
            .nav-ham    { display:flex; }
            .welcome-section { padding:36px 0 32px; }
            .quick-grid { gap:10px; }
            .qa-btn     { padding:16px 12px; min-width:80px; }
        }
        @media(max-width:480px){
            .welcome-name { font-size:1.7rem; }
            .qa-btn__label { font-size:10px; }
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
                        <a href="inicio_clientes.php" class="active">
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
                        <a href="nuevo_ticket_cliente.php">
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

    <!-- 1. BIENVENIDA -->
    <section class="welcome-section">
        <div class="container">
            <div class="welcome-inner">
                <div class="welcome-tag">Portal de clientes</div>
                <div class="welcome-greeting"><?= $saludo ?>,</div>
                <h1 class="welcome-name">
                    Hola, <em><?= htmlspecialchars($nombre_corto) ?></em>
                </h1>
                <p class="welcome-sub">
                    Bienvenido a tu portal. Aquí puedes revisar el estado de tus equipos y solicitar nuevos servicios técnicos.
                </p>
            </div>
        </div>
    </section>

    <!-- 2. ACCESOS RÁPIDOS -->
    <section class="quick-section">
        <div class="container">
            <div class="section-label">Accesos rápidos</div>
            <div class="quick-grid">

                <!-- Nueva cotización (accent) -->
                <a href="nuevo_ticket_cliente.php" class="qa-btn qa-btn--accent">
                    <div class="qa-btn__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    </div>
                    <div class="qa-btn__label">Nueva cotización</div>
                </a>

                <!-- Mis tickets -->
                <a href="tickets_cliente.php" class="qa-btn">
                    <div class="qa-btn__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
                    </div>
                    <div class="qa-btn__label">Mis tickets</div>
                </a>

                <!-- Estado del equipo -->
                <a href="tickets_cliente.php" class="qa-btn">
                    <div class="qa-btn__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    </div>
                    <div class="qa-btn__label">Estado equipo</div>
                </a>

                <!-- WhatsApp -->
                <a href="https://wa.me/51903208170" target="_blank" rel="noopener" class="qa-btn">
                    <div class="qa-btn__icon" style="background:rgba(37,211,102,.15);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:#5fc98a;">
                            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                        </svg>
                    </div>
                    <div class="qa-btn__label">Contacta con Soporte</div>
                </a>

            </div>
        </div>
    </section>

    <!-- 3. TICKETS RECIENTES -->
    <section class="main-section">
        <div class="container">
            <div class="panel">
                <div class="panel__head">
                    <div class="panel__title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
                        Mis tickets recientes
                    </div>
                    <a href="tickets_cliente.php" class="panel__link">Ver todos →</a>
                </div>
                <div class="tt-wrap">
                    <?php if (empty($tickets)): ?>
                    <div class="empty-state">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
                        <p>No tienes tickets aún</p>
                    </div>
                    <?php else: ?>
                    <table class="ticket-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Equipo / Servicio</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($tickets as $t):
                            $ec = estado_cfg($t['estado']);
                        ?>
                        <tr>
                            <td><span class="t-id">#<?= htmlspecialchars($t['id']) ?></span></td>
                            <td>
                                <div class="t-eq"><?= htmlspecialchars($t['equipo']) ?></div>
                                <div class="t-svc"><?= htmlspecialchars($t['servicio']) ?></div>
                            </td>
                            <td>
                                <span class="estado-badge" style="background:<?= $ec['bg'] ?>;color:<?= $ec['color'] ?>;">
                                    <span class="estado-dot" style="background:<?= $ec['dot'] ?>;"></span>
                                    <?= htmlspecialchars($t['estado']) ?>
                                </span>
                            </td>
                            <td><span class="t-fecha"><?= $t['fecha'] ?></span></td>
                            <td>
                                <a href="detalle_ticket.php?id=<?= urlencode($t['id']) ?>" class="t-det">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    Ver
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

</div><!-- /page -->

<!-- WhatsApp flotante -->
<a href="https://wa.me/51903208170" target="_blank" rel="noopener" class="float-wa" title="WhatsApp">
    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
</a>

<script>
    /* Navbar scroll */
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => { navbar.classList.toggle('scrolled', window.scrollY > 20); });

    /* Hamburger */
    const ham    = document.getElementById('hamburger');
    const mobMenu = document.getElementById('mob-menu');
    let open = false;
    ham.addEventListener('click', () => {
        open = !open;
        mobMenu.classList.toggle('open', open);
        const s = ham.querySelectorAll('span');
        if (open) { s[0].style.transform='translateY(7px) rotate(45deg)'; s[1].style.opacity='0'; s[2].style.transform='translateY(-7px) rotate(-45deg)'; }
        else { s.forEach(x => { x.style.transform=''; x.style.opacity=''; }); }
    });
    function closeMenu() {
        open = false;
        mobMenu.classList.remove('open');
        ham.querySelectorAll('span').forEach(x => { x.style.transform=''; x.style.opacity=''; });
    }
</script>
</body>
</html>