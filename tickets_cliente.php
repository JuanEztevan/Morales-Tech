<?php
// include("includes/auth.php");
$nombre_cliente = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Esteban Demo';
$partes         = explode(' ', trim($nombre_cliente));
$nombre_corto   = $partes[0];
$inicial        = strtoupper(substr($partes[0], 0, 1));

$filtro_activo = isset($_GET['estado']) ? $_GET['estado'] : 'Todos';

$todos_los_tickets = [
    [
        "id"         => "MT-8842",
        "tipo"       => "Laptop",
        "marca"      => "HP",
        "so"         => "Windows 11",
        "servicio"   => "Reparación",
        "adicionales"=> ["Limpieza profunda"],
        "estado"     => "En diagnóstico",
        "fecha"      => "20 may 2025",
        "total"      => "S/ 115.00",
        "obs"        => "La laptop no enciende correctamente y presenta pantalla azul al iniciar.",
    ],
    [
        "id"         => "MT-8843",
        "tipo"       => "PC",
        "marca"      => "Apple",
        "so"         => "macOS",
        "servicio"   => "Repotenciación (mano de obra)",
        "adicionales"=> [],
        "estado"     => "Recibido",
        "fecha"      => "21 may 2025",
        "total"      => "S/ 59.00",
        "obs"        => "",
    ],
    [
        "id"         => "MT-8844",
        "tipo"       => "Laptop",
        "marca"      => "Apple",
        "so"         => "macOS",
        "servicio"   => "Mantenimiento correctivo",
        "adicionales"=> ["Optimización del sistema", "Instalación de programas"],
        "estado"     => "En reparación",
        "fecha"      => "18 may 2025",
        "total"      => "S/ 141.30",
        "obs"        => "El equipo va muy lento y se calienta mucho.",
    ],
    [
        "id"         => "MT-8845",
        "tipo"       => "Laptop",
        "marca"      => "ASUS",
        "so"         => "Windows 10",
        "servicio"   => "Limpieza preventiva",
        "adicionales"=> [],
        "estado"     => "Completado",
        "fecha"      => "15 may 2025",
        "total"      => "S/ 70.80",
        "obs"        => "",
    ],
];

$mapa_estados = [
    'Recibidos'      => 'Recibido',
    'Completados'    => 'Completado',
    'En diagnóstico' => 'En diagnóstico',
    'En reparación'  => 'En reparación',
];
$estados = ['Todos', 'Recibidos', 'En diagnóstico', 'En reparación', 'Completados'];

$tickets = $todos_los_tickets;
if ($filtro_activo !== 'Todos') {
    $valor_real = $mapa_estados[$filtro_activo] ?? $filtro_activo;
    $tickets = array_values(array_filter($tickets, fn($t) => $t['estado'] === $valor_real));
}

function estado_cfg($e) {
    $m = [
        'Recibido'       => ['bg'=>'rgba(245,166,35,.18)',  'color'=>'#f5c048', 'dot'=>'#f5a623'],
        'En diagnóstico' => ['bg'=>'rgba(23,70,234,.22)',   'color'=>'#8db4ff', 'dot'=>'#1746EA'],
        'En reparación'  => ['bg'=>'rgba(201,74,0,.20)',    'color'=>'#f5a07a', 'dot'=>'#e85d04'],
        'Completado'     => ['bg'=>'rgba(26,122,74,.22)',   'color'=>'#5fc98a', 'dot'=>'#1a7a4a'],
    ];
    return $m[$e] ?? ['bg'=>'rgba(100,100,120,.18)','color'=>'#a0a8bb','dot'=>'#7a8096'];
}

$reciente = $todos_los_tickets[0];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Tickets — Morales Tech</title>
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

        /* ══ HERO SECTION — padding idéntico a welcome-section ══ */
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

        /* Etiqueta superior igual a welcome-tag */
        .hero-tag {
            display:inline-flex; align-items:center; gap:7px;
            background:rgba(23,70,234,.16); border:1px solid rgba(23,70,234,.32);
            border-radius:50px; padding:4px 14px;
            font-size:10px; font-weight:800; letter-spacing:.09em;
            text-transform:uppercase; color:#8db4ff; margin-bottom:18px;
            position:relative; z-index:1;
        }
        .hero-tag::before { content:''; width:5px; height:5px; border-radius:50%; background:var(--grad); flex-shrink:0; }

        /* Card ticket reciente */
        .hero-card {
            position:relative; z-index:1;
            background:linear-gradient(135deg,rgba(23,70,234,.30) 0%,rgba(24,131,237,.20) 100%);
            border:1px solid rgba(23,70,234,.35);
            border-radius:18px; padding:24px 28px;
            cursor:pointer; transition:border-color .2s,box-shadow .2s;
            overflow:hidden;
        }
        .hero-card:hover { border-color:rgba(23,70,234,.65); box-shadow:0 8px 40px rgba(23,70,234,.25); }
        .hero-card::before {
            content:''; position:absolute; right:-50px; top:-50px;
            width:200px; height:200px; border-radius:50%;
            background:rgba(255,255,255,.05); pointer-events:none;
        }
        .hero-card__label {
            font-size:10px; font-weight:800; letter-spacing:.12em;
            text-transform:uppercase; color:rgba(141,180,255,.70);
            margin-bottom:14px; position:relative; z-index:1;
        }
        .hero-card__inner { display:flex; align-items:flex-start; justify-content:space-between; gap:20px; flex-wrap:wrap; position:relative; z-index:1; }
        .hero-card__left  { flex:1; min-width:0; }
        .hero-card__id    { font-size:12px; font-weight:800; color:rgba(255,255,255,.55); margin-bottom:4px; }
        .hero-card__equipo { font-size:20px; font-weight:800; letter-spacing:-.3px; color:#fff; line-height:1.2; margin-bottom:5px; }
        .hero-card__svc   { font-size:13px; color:rgba(255,255,255,.65); margin-bottom:14px; }
        .hero-badge {
            display:inline-flex; align-items:center; gap:6px;
            background:rgba(255,255,255,.14); border:1px solid rgba(255,255,255,.22);
            border-radius:50px; padding:5px 13px;
            font-size:11px; font-weight:700; color:#fff;
        }
        .hero-badge__dot { width:6px; height:6px; border-radius:50%; background:#fff; flex-shrink:0; }
        .hero-card__right { display:flex; flex-direction:column; align-items:flex-end; gap:10px; flex-shrink:0; }
        .hero-card__fecha { font-size:11px; color:rgba(255,255,255,.45); }
        .btn-hero {
            display:inline-flex; align-items:center; gap:7px;
            background:rgba(255,255,255,.14); border:1.5px solid rgba(255,255,255,.28);
            border-radius:50px; padding:9px 18px; color:#fff;
            font-family:'Montserrat',sans-serif; font-size:12px; font-weight:700;
            cursor:pointer; transition:background .15s; white-space:nowrap;
            text-decoration:none; border:1.5px solid rgba(255,255,255,.28);
        }
        .btn-hero:hover { background:rgba(255,255,255,.24); }
        .btn-hero svg { width:13px; height:13px; }

        /* ══ SECCIÓN PRINCIPAL — mismo padding que quick+main de inicio ══ */
        .main-section { padding:36px 0 56px; }

        /* Label de sección igual a inicio_clientes */
        .section-label {
            font-size:10px; font-weight:800; letter-spacing:.10em; text-transform:uppercase;
            color:var(--muted); margin-bottom:16px;
        }

        /* ── Filtros ── */
        .filters { display:flex; flex-wrap:wrap; gap:8px; margin-bottom:20px; }
        .filter-btn {
            font-family:'Montserrat',sans-serif;
            font-size:12px; font-weight:600; padding:7px 16px;
            border-radius:50px; border:1.5px solid rgba(255,255,255,.10);
            background:rgba(255,255,255,.04); color:var(--muted);
            text-decoration:none; cursor:pointer; transition:all .15s;
        }
        .filter-btn:hover { border-color:rgba(23,70,234,.5); color:#8db4ff; background:rgba(23,70,234,.08); }
        .filter-btn.active {
            background:var(--azul); border-color:var(--azul);
            color:#fff; box-shadow:0 3px 12px rgba(23,70,234,.35);
        }

        /* ══ PANEL / TABLA — idéntico a inicio_clientes ══ */
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

        .tt-wrap { overflow-x:auto; -webkit-overflow-scrolling:touch; }
        .ticket-table { width:100%; border-collapse:collapse; min-width:500px; }
        .ticket-table th {
            padding:10px 18px; font-size:10px; font-weight:700;
            letter-spacing:.08em; text-transform:uppercase;
            color:var(--muted); text-align:left;
            border-bottom:1px solid var(--borde);
            background:rgba(255,255,255,.02); white-space:nowrap;
        }
        .ticket-table td {
            padding:14px 18px; font-size:13px; vertical-align:middle;
            border-bottom:1px solid var(--borde);
        }
        .ticket-table tr:last-child td { border-bottom:none; }
        .ticket-table tbody tr { transition:background .1s; }
        .ticket-table tbody tr:hover td { background:rgba(255,255,255,.025); }

        /* Celdas — mismos nombres de clase que inicio_clientes */
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

        /* Botón ver — clase .t-det igual a inicio_clientes */
        .t-det {
            display:inline-flex; align-items:center; gap:5px;
            font-size:11px; font-weight:700; color:#6fa3ff;
            padding:5px 12px; border-radius:50px;
            border:1px solid rgba(23,70,234,.3);
            background:rgba(23,70,234,.10);
            transition:background .15s; cursor:pointer;
            white-space:nowrap; font-family:'Montserrat',sans-serif;
        }
        .t-det:hover { background:rgba(23,70,234,.22); }
        .t-det svg { width:11px; height:11px; }

        /* Empty */
        .empty-state { padding:44px 20px; text-align:center; }
        .empty-state svg { width:36px; height:36px; margin-inline:auto; margin-bottom:12px; opacity:.2; }
        .empty-state p { font-size:13px; color:var(--muted); font-weight:600; }
        .empty-state small { font-size:12px; color:#3a4470; display:block; margin-top:4px; }

        /* Footer tabla */
        .table-footer {
            padding:11px 18px; border-top:1px solid var(--borde);
            font-size:12px; color:var(--muted); font-weight:600;
        }

        /* ══ MODAL ══ */
        .modal-overlay {
            position:fixed; inset:0;
            background:rgba(0,0,20,.70); backdrop-filter:blur(8px);
            display:flex; align-items:center; justify-content:center;
            z-index:1000; opacity:0; pointer-events:none; transition:opacity .25s;
        }
        .modal-overlay.show { opacity:1; pointer-events:all; }
        .modal-box {
            background:var(--s2); border:1px solid var(--borde);
            border-radius:22px; width:90%; max-width:520px;
            overflow:hidden; box-shadow:0 24px 60px rgba(0,0,0,.60);
            transform:translateY(24px) scale(.97); transition:transform .25s;
            max-height:92vh; overflow-y:auto;
        }
        .modal-overlay.show .modal-box { transform:translateY(0) scale(1); }
        .modal-hero {
            background:linear-gradient(135deg,#1746EA 0%,#1883ED 100%);
            padding:24px 26px 22px; color:#fff;
            position:relative; overflow:hidden;
        }
        .modal-hero::after {
            content:''; position:absolute; right:-40px; top:-40px;
            width:160px; height:160px; border-radius:50%;
            background:rgba(255,255,255,.08);
        }
        .modal-close {
            position:absolute; top:14px; right:14px; z-index:2;
            background:rgba(255,255,255,.18); border:none; border-radius:50%;
            width:30px; height:30px; display:grid; place-items:center;
            cursor:pointer; color:#fff; transition:background .15s;
        }
        .modal-close:hover { background:rgba(255,255,255,.32); }
        .modal-close svg { width:14px; height:14px; }
        .modal-hero__id     { font-size:11px; font-weight:700; opacity:.65; margin-bottom:4px; position:relative; z-index:1; }
        .modal-hero__equipo { font-size:20px; font-weight:800; letter-spacing:-.3px; position:relative; z-index:1; margin-bottom:10px; }
        .modal-hero__badge  {
            display:inline-flex; align-items:center; gap:6px;
            background:rgba(255,255,255,.18); border:1px solid rgba(255,255,255,.28);
            border-radius:50px; padding:4px 13px;
            font-size:11px; font-weight:700; position:relative; z-index:1;
        }
        .modal-hero__dot { width:5px; height:5px; border-radius:50%; background:#fff; }
        .modal-body { padding:22px 24px 26px; }
        .modal-section { margin-bottom:20px; }
        .modal-section:last-child { margin-bottom:0; }
        .modal-section__title {
            font-size:10px; font-weight:800; letter-spacing:.09em;
            text-transform:uppercase; color:var(--muted); margin-bottom:12px;
        }
        .modal-grid { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
        .modal-field {
            background:rgba(255,255,255,.04); border:1px solid var(--borde);
            border-radius:10px; padding:11px 14px;
        }
        .modal-field__label { font-size:10px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.06em; margin-bottom:4px; }
        .modal-field__value { font-size:13px; font-weight:700; color:var(--txt); }
        .modal-field--full { grid-column:span 2; }
        .modal-svc-list { display:flex; flex-direction:column; gap:8px; }
        .modal-svc-item {
            display:flex; align-items:center; gap:10px;
            padding:10px 14px; border-radius:10px;
            background:rgba(255,255,255,.04); border:1px solid var(--borde);
        }
        .modal-svc-item--main { background:rgba(23,70,234,.18); border-color:rgba(23,70,234,.35); }
        .modal-svc-dot  { width:7px; height:7px; border-radius:50%; flex-shrink:0; }
        .modal-svc-name { flex:1; font-size:13px; font-weight:600; color:var(--txt); }
        .modal-svc-price { font-size:12px; font-weight:800; color:#8db4ff; white-space:nowrap; }
        .modal-svc-item--main .modal-svc-price { color:#aac5ff; }
        .modal-total {
            display:flex; align-items:center; justify-content:space-between;
            background:linear-gradient(135deg,#1746EA,#1883ED);
            border-radius:12px; padding:14px 18px; color:#fff; margin-top:14px;
        }
        .modal-total__label { font-size:12px; font-weight:700; opacity:.8; }
        .modal-total__val   { font-size:22px; font-weight:800; letter-spacing:-.5px; }
        .modal-obs {
            background:rgba(255,255,255,.04); border:1px solid var(--borde);
            border-radius:10px; padding:12px 14px;
            font-size:12px; color:var(--muted); line-height:1.65; font-style:italic;
        }

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
            .hero-section { padding:36px 0 32px; }
        }
        @media(max-width:580px){
            .hero-card__equipo { font-size:17px; }
            .modal-grid { grid-template-columns:1fr; }
            .modal-field--full { grid-column:span 1; }
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
                        <a href="tickets_cliente.php" class="active">
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

    <!-- HERO: ticket más reciente -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-tag">Mis tickets</div>
            <?php $ec = estado_cfg($reciente['estado']); ?>
            <div class="hero-card"
                 onclick="abrirModal('<?= htmlspecialchars($reciente['id']) ?>','<?= htmlspecialchars($reciente['tipo'].' '.$reciente['marca']) ?>','<?= htmlspecialchars($reciente['so']) ?>','<?= htmlspecialchars($reciente['servicio']) ?>',<?= json_encode($reciente['adicionales']) ?>,'<?= htmlspecialchars($reciente['estado']) ?>','<?= htmlspecialchars($reciente['fecha']) ?>','<?= htmlspecialchars($reciente['total']) ?>','<?= addslashes(htmlspecialchars($reciente['obs'])) ?>')">
                <div class="hero-card__label">Ticket más reciente</div>
                <div class="hero-card__inner">
                    <div class="hero-card__left">
                        <div class="hero-card__id">#<?= htmlspecialchars($reciente['id']) ?></div>
                        <div class="hero-card__equipo"><?= htmlspecialchars($reciente['tipo'] . ' · ' . $reciente['marca']) ?></div>
                        <div class="hero-card__svc"><?= htmlspecialchars($reciente['servicio']) ?></div>
                        <div class="hero-badge">
                            <span class="hero-badge__dot"></span>
                            <?= htmlspecialchars($reciente['estado']) ?>
                        </div>
                    </div>
                    <div class="hero-card__right">
                        <div class="hero-card__fecha"><?= htmlspecialchars($reciente['fecha']) ?></div>
                        <button class="btn-hero" onclick="event.stopPropagation(); abrirModal('<?= htmlspecialchars($reciente['id']) ?>','<?= htmlspecialchars($reciente['tipo'].' '.$reciente['marca']) ?>','<?= htmlspecialchars($reciente['so']) ?>','<?= htmlspecialchars($reciente['servicio']) ?>',<?= json_encode($reciente['adicionales']) ?>,'<?= htmlspecialchars($reciente['estado']) ?>','<?= htmlspecialchars($reciente['fecha']) ?>','<?= htmlspecialchars($reciente['total']) ?>','<?= addslashes(htmlspecialchars($reciente['obs'])) ?>')">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            Ver detalle
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- LISTADO DE TICKETS -->
    <section class="main-section">
        <div class="container">

            <!-- Label + filtros -->
            <div class="section-label">Filtrar por estado</div>
            <div class="filters">
                <?php foreach ($estados as $estado):
                    $url_f     = ($estado === 'Todos') ? 'tickets_cliente.php' : 'tickets_cliente.php?estado='.urlencode($estado);
                    $valor_cmp = $mapa_estados[$estado] ?? $estado;
                    $es_activo = ($filtro_activo === 'Todos' && $estado === 'Todos')
                              || ($filtro_activo !== 'Todos' && ($valor_cmp === $filtro_activo || $estado === $filtro_activo));
                ?>
                <a href="<?= $url_f ?>" class="filter-btn <?= $es_activo ? 'active' : '' ?>">
                    <?= htmlspecialchars($estado) ?>
                </a>
                <?php endforeach; ?>
            </div>

            <!-- Panel tabla — misma estructura exacta que inicio_clientes -->
            <div class="panel">
                <div class="panel__head">
                    <div class="panel__title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
                        Mis tickets
                    </div>
                    <a href="nuevo_ticket_cliente.php" style="font-size:12px;font-weight:700;color:#6fa3ff;display:inline-flex;align-items:center;gap:5px;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:12px;height:12px;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Nueva cotización
                    </a>
                </div>
                <div class="tt-wrap">
                    <?php if (empty($tickets)): ?>
                    <div class="empty-state">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
                        <p>No hay tickets con este estado</p>
                        <small>Prueba otro filtro</small>
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
                                <div class="t-eq"><?= htmlspecialchars($t['tipo'] . ' ' . $t['marca']) ?></div>
                                <div class="t-svc"><?= htmlspecialchars($t['servicio']) ?></div>
                            </td>
                            <td>
                                <span class="estado-badge" style="background:<?= $ec['bg'] ?>;color:<?= $ec['color'] ?>;">
                                    <span class="estado-dot" style="background:<?= $ec['dot'] ?>;"></span>
                                    <?= htmlspecialchars($t['estado']) ?>
                                </span>
                            </td>
                            <td><span class="t-fecha"><?= htmlspecialchars($t['fecha']) ?></span></td>
                            <td>
                                <button class="t-det" onclick="abrirModal(
                                    '<?= htmlspecialchars($t['id']) ?>',
                                    '<?= htmlspecialchars($t['tipo'].' '.$t['marca']) ?>',
                                    '<?= htmlspecialchars($t['so']) ?>',
                                    '<?= htmlspecialchars($t['servicio']) ?>',
                                    <?= json_encode($t['adicionales']) ?>,
                                    '<?= htmlspecialchars($t['estado']) ?>',
                                    '<?= htmlspecialchars($t['fecha']) ?>',
                                    '<?= htmlspecialchars($t['total']) ?>',
                                    '<?= addslashes(htmlspecialchars($t['obs'])) ?>')">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    Ver
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
                </div>
                <div class="table-footer">
                    <?php
                        $total_t = count($tickets);
                        echo $total_t . ' ticket' . ($total_t !== 1 ? 's' : '');
                        if ($filtro_activo !== 'Todos')
                            echo ' &middot; ' . htmlspecialchars($filtro_activo);
                    ?>
                </div>
            </div>

        </div>
    </section>
</div><!-- /page -->

<!-- ══ MODAL DETALLE ══ -->
<div class="modal-overlay" id="modal-overlay" onclick="cerrarOverlay(event)">
    <div class="modal-box">
        <div class="modal-hero">
            <button class="modal-close" onclick="cerrarModal()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
            <div class="modal-hero__id"     id="m-id"></div>
            <div class="modal-hero__equipo" id="m-equipo"></div>
            <div class="modal-hero__badge">
                <span class="modal-hero__dot"></span>
                <span id="m-estado"></span>
            </div>
        </div>
        <div class="modal-body">
            <div class="modal-section">
                <div class="modal-section__title">Información del equipo</div>
                <div class="modal-grid">
                    <div class="modal-field">
                        <div class="modal-field__label">Tipo / Marca</div>
                        <div class="modal-field__value" id="m-tipo"></div>
                    </div>
                    <div class="modal-field">
                        <div class="modal-field__label">Sistema operativo</div>
                        <div class="modal-field__value" id="m-so"></div>
                    </div>
                    <div class="modal-field">
                        <div class="modal-field__label">Fecha de ingreso</div>
                        <div class="modal-field__value" id="m-fecha"></div>
                    </div>
                    <div class="modal-field" id="m-obs-block">
                        <div class="modal-field__label">Observaciones</div>
                        <div class="modal-field__value" id="m-obs" style="font-weight:500;font-size:12px;font-style:italic;color:#6b74a8;"></div>
                    </div>
                </div>
            </div>
            <div class="modal-section">
                <div class="modal-section__title">Servicios solicitados</div>
                <div class="modal-svc-list" id="m-servicios"></div>
                <div class="modal-total">
                    <span class="modal-total__label">Total estimado</span>
                    <span class="modal-total__val" id="m-total"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- WhatsApp flotante -->
<a href="https://wa.me/51903208170" target="_blank" rel="noopener" class="float-wa" title="WhatsApp">
    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
</a>

<script>
    /* Navbar scroll */
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => { navbar.classList.toggle('scrolled', window.scrollY > 20); });

    /* Hamburger */
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

    /* Modal */
    const PRECIOS = {
        'Diagnóstico':30,'Mantenimiento preventivo':60,'Mantenimiento correctivo':90,
        'Instalación / Formateo':80,'Reparación':90,'Repotenciación (mano de obra)':50,
        'Limpieza preventiva':60,'Limpieza profunda':25,'Instalación de programas':20,
        'Optimización del sistema':30,
    };
    function abrirModal(id, equipo, so, servicio, adicionales, estado, fecha, total, obs) {
        document.getElementById('m-id').textContent     = '#' + id;
        document.getElementById('m-equipo').textContent = equipo;
        document.getElementById('m-estado').textContent = estado;
        document.getElementById('m-tipo').textContent   = equipo;
        document.getElementById('m-so').textContent     = so || '—';
        document.getElementById('m-fecha').textContent  = fecha;
        document.getElementById('m-total').textContent  = total;
        const obsEl    = document.getElementById('m-obs');
        const obsBlock = document.getElementById('m-obs-block');
        if (obs && obs.trim()) {
            obsEl.textContent      = obs;
            obsBlock.style.display = '';
        } else {
            obsBlock.style.display = 'none';
        }
        let html = `<div class="modal-svc-item modal-svc-item--main">
            <span class="modal-svc-dot" style="background:#6fa3ff;"></span>
            <span class="modal-svc-name">${servicio}</span>
            <span class="modal-svc-price">S/ ${((PRECIOS[servicio]||0)).toFixed(2)}</span>
        </div>`;
        if (adicionales && adicionales.length) {
            adicionales.forEach(a => {
                html += `<div class="modal-svc-item">
                    <span class="modal-svc-dot" style="background:#3a4470;"></span>
                    <span class="modal-svc-name">${a}</span>
                    <span class="modal-svc-price" style="color:#6b74a8;">S/ ${((PRECIOS[a]||0)).toFixed(2)}</span>
                </div>`;
            });
        }
        document.getElementById('m-servicios').innerHTML = html;
        document.getElementById('modal-overlay').classList.add('show');
    }
    function cerrarModal() { document.getElementById('modal-overlay').classList.remove('show'); }
    function cerrarOverlay(e) { if (e.target === document.getElementById('modal-overlay')) cerrarModal(); }
</script>
</body>
</html>