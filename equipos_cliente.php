<?php
// session_start();
// include("includes/auth_cliente.php");
$nombre_cliente = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Esteban Demo';
$partes         = explode(' ', trim($nombre_cliente));
$nombre_corto   = $partes[0];

/* ══════════════════════════════════════════
   DATOS DE EQUIPOS DEL CLIENTE
   En producción estos vendrían de la BD.
   ══════════════════════════════════════════ */
$equipos = [
    [
        "id"            => "EQ-001",
        "tipo"          => "Laptop",
        "marca"         => "HP",
        "modelo"        => "Pavilion 15-eg2xxx",
        "serie"         => "5CD2350KXY",
        "so"            => "Windows 11 Home",
        "fecha_reg"     => "10 ene 2024",
        "tickets"       => [
            ["id"=>"MT-8842","servicio"=>"Reparación","adicionales"=>["Limpieza profunda"],"estado"=>"En diagnóstico","fecha"=>"20 may 2025","total"=>"S/ 115.00","obs"=>"La laptop no enciende correctamente y presenta pantalla azul al iniciar."],
            ["id"=>"MT-8845","servicio"=>"Limpieza preventiva","adicionales"=>[],"estado"=>"Completado","fecha"=>"15 may 2025","total"=>"S/ 70.80","obs"=>""],
        ],
    ],
    [
        "id"            => "EQ-002",
        "tipo"          => "PC",
        "marca"         => "",
        "modelo"        => "",
        "serie"         => "",
        "so"            => "macOS Sonoma 14.4",
        "fecha_reg"     => "03 mar 2024",
        "tickets"       => [
            ["id"=>"MT-8843","servicio"=>"Repotenciación (mano de obra)","adicionales"=>[],"estado"=>"Recibido","fecha"=>"21 may 2025","total"=>"S/ 59.00","obs"=>""],
        ],
    ],
    [
        "id"            => "EQ-003",
        "tipo"          => "Laptop",
        "marca"         => "Apple",
        "modelo"        => "MacBook Pro 14\" M3",
        "serie"         => "C3QXWV1XJGH5",
        "so"            => "macOS Sonoma 14.5",
        "fecha_reg"     => "18 abr 2025",
        "tickets"       => [
            ["id"=>"MT-8844","servicio"=>"Mantenimiento correctivo","adicionales"=>["Optimización del sistema","Instalación de programas"],"estado"=>"En reparación","fecha"=>"18 may 2025","total"=>"S/ 141.30","obs"=>"El equipo va muy lento y se calienta mucho."],
        ],
    ],
    [
        "id"            => "EQ-004",
        "tipo"          => "Laptop",
        "marca"         => "ASUS",
        "modelo"        => "VivoBook 15 X1502",
        "serie"         => "N8N0CX03T456789",
        "so"            => "Windows 10 Pro",
        "fecha_reg"     => "22 feb 2023",
        "tickets"       => [],
    ],
];

/* ── helper: config de color por estado (igual que tickets_cliente) ── */
function estado_cfg($e) {
    $m = [
        'Recibido'       => ['bg'=>'rgba(245,166,35,.18)',  'color'=>'#f5c048', 'dot'=>'#f5a623'],
        'En diagnóstico' => ['bg'=>'rgba(23,70,234,.22)',   'color'=>'#8db4ff', 'dot'=>'#1746EA'],
        'En reparación'  => ['bg'=>'rgba(201,74,0,.20)',    'color'=>'#f5a07a', 'dot'=>'#e85d04'],
        'Completado'     => ['bg'=>'rgba(26,122,74,.22)',   'color'=>'#5fc98a', 'dot'=>'#1a7a4a'],
    ];
    return $m[$e] ?? ['bg'=>'rgba(100,100,120,.18)','color'=>'#a0a8bb','dot'=>'#7a8096'];
}

/* ── helper: icono SVG por tipo de equipo ── */
function icono_equipo($tipo) {
    if ($tipo === 'PC') {
        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>';
    }
    // Laptop por defecto
    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 16V7a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v9m16 0H4m16 0 1.28 2.55A1 1 0 0 1 20.37 20H3.63a1 1 0 0 1-.91-1.45L4 16"/></svg>';
}

/* Solo equipos con al menos un servicio registrado */
$equipos        = array_values(array_filter($equipos, fn($eq) => count($eq['tickets']) > 0));

$total_equipos  = count($equipos);
$total_tickets  = array_sum(array_map(fn($eq) => count($eq['tickets']), $equipos));
$eq_activos     = count(array_filter($equipos, fn($eq) => count(array_filter($eq['tickets'], fn($t) => $t['estado'] !== 'Completado')) > 0));
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mis Equipos — Morales Tech</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
  <style>
    /* ════════════════════════════════════════
       EQUIPOS_CLIENTE.PHP — estilos propios
       Complementan los estilos de styles.css
       ════════════════════════════════════════ */

    /* ── KPI bar en la cabecera del panel ── */
    .eq-kpi-bar {
      display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
    }
    .eq-kpi-pill {
      display: inline-flex; align-items: center; gap: 5px;
      background: rgba(255,255,255,.05);
      border: 1px solid var(--borde);
      border-radius: 50px; padding: 4px 12px;
    }
    .eq-kpi-pill--activo {
      background: rgba(23,70,234,.14);
      border-color: rgba(23,70,234,.32);
    }
    .eq-kpi-pill__dot {
      width: 6px; height: 6px; border-radius: 50%;
      background: #1746ea; flex-shrink: 0;
      animation: kpi-pulse 2s ease-in-out infinite;
    }
    @keyframes kpi-pulse {
      0%,100% { opacity: 1; } 50% { opacity: .45; }
    }
    .eq-kpi-pill__val {
      font-family: 'Montserrat', sans-serif;
      font-size: 13px; font-weight: 800; color: var(--txt-main);
    }
    .eq-kpi-pill--activo .eq-kpi-pill__val { color: var(--txt-accent); }
    .eq-kpi-pill__lbl {
      font-family: 'Montserrat', sans-serif;
      font-size: 11px; font-weight: 600; color: var(--txt-muted);
    }
    .eq-kpi-sep {
      width: 1px; height: 14px; background: var(--borde); flex-shrink: 0;
    }

    /* ── Grid de tarjetas de equipos ── */
    .eq-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
      gap: 16px;
    }

    /* ── Tarjeta individual de equipo ── */
    .eq-card {
      background: var(--surface-2);
      border: 1px solid var(--borde);
      border-radius: 18px;
      overflow: hidden;
      box-shadow: 0 4px 24px rgba(0,0,0,.35);
      transition: border-color .2s, box-shadow .2s, transform .2s;
    }
    .eq-card:hover {
      border-color: rgba(23,70,234,.40);
      box-shadow: 0 8px 40px rgba(23,70,234,.18);
      transform: translateY(-2px);
    }

    /* Cabecera de la tarjeta */
    .eq-card__head {
      padding: 20px 22px 16px;
      display: flex; align-items: flex-start; gap: 14px;
      border-bottom: 1px solid var(--borde);
    }
    .eq-card__icon-wrap {
      width: 46px; height: 46px; border-radius: 14px;
      background: var(--grad-soft);
      border: 1px solid rgba(23,70,234,.22);
      display: grid; place-items: center; flex-shrink: 0;
    }
    .eq-card__icon-wrap svg {
      width: 22px; height: 22px; color: var(--txt-accent);
    }
    .eq-card__info { flex: 1; min-width: 0; }
    .eq-card__marca {
      font-family: 'Montserrat', sans-serif;
      font-size: 18px; font-weight: 800; color: var(--txt-main);
      letter-spacing: -.3px; line-height: 1.15; margin-bottom: 2px;
      white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .eq-card__modelo {
      font-size: 12px; color: var(--txt-muted); font-weight: 500;
      white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .eq-card__id-badge {
      font-family: 'Montserrat', sans-serif;
      font-size: 10px; font-weight: 800; letter-spacing: .08em;
      color: rgba(141,180,255,.60); flex-shrink: 0;
      align-self: flex-start; padding-top: 3px;
    }

    /* Cuerpo de la tarjeta: datos del equipo */
    .eq-card__body { padding: 14px 22px 16px; }
    .eq-card__fields {
      display: grid; grid-template-columns: 1fr 1fr; gap: 10px;
    }
    .eq-field {
      background: rgba(255,255,255,.03);
      border: 1px solid var(--borde);
      border-radius: 10px; padding: 10px 12px;
    }
    .eq-field__label {
      font-family: 'Montserrat', sans-serif;
      font-size: 9px; font-weight: 800;
      letter-spacing: .09em; text-transform: uppercase;
      color: var(--txt-muted); margin-bottom: 3px;
    }
    .eq-field__value {
      font-family: 'Montserrat', sans-serif;
      font-size: 12px; font-weight: 700; color: var(--txt-main);
      white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .eq-field--full { grid-column: span 2; }

    /* Pie de la tarjeta */
    .eq-card__footer {
      padding: 0 22px 18px;
      display: flex; align-items: center; justify-content: space-between; gap: 10px;
    }
    .eq-tickets-count {
      display: inline-flex; align-items: center; gap: 6px;
      font-family: 'Montserrat', sans-serif;
      font-size: 11px; font-weight: 600; color: var(--txt-muted);
    }
    .eq-tickets-count svg { width: 13px; height: 13px; color: #6fa3ff; }

    /* Botón "Ver Historial" */
    .btn-ver-historial {
      display: inline-flex; align-items: center; gap: 7px;
      background: rgba(23,70,234,.16);
      border: 1.5px solid rgba(23,70,234,.38);
      border-radius: 50px; padding: 8px 16px;
      font-family: 'Montserrat', sans-serif;
      font-size: 12px; font-weight: 700; color: var(--txt-accent);
      cursor: pointer; transition: all .18s; white-space: nowrap;
    }
    .btn-ver-historial:hover {
      background: rgba(23,70,234,.28);
      border-color: var(--azul-tecnologico);
      color: var(--blanco);
    }
    .btn-ver-historial svg { width: 13px; height: 13px; flex-shrink: 0; transition: transform .25s; }
    .btn-ver-historial.open svg.chevron { transform: rotate(180deg); }

    /* ── Accordion: historial de servicios ── */
    .eq-historial {
      max-height: 0; overflow: hidden;
      transition: max-height .38s cubic-bezier(.4,0,.2,1), opacity .28s;
      opacity: 0;
    }
    .eq-historial.open {
      max-height: 600px; /* suficientemente grande */
      opacity: 1;
    }
    .eq-historial__inner {
      border-top: 1px solid var(--borde);
      padding: 16px 22px 18px;
    }
    .eq-historial__title {
      font-family: 'Montserrat', sans-serif;
      font-size: 9px; font-weight: 800; letter-spacing: .10em;
      text-transform: uppercase; color: var(--txt-muted); margin-bottom: 12px;
    }

    /* Fila de ticket dentro del historial */
    .eq-ticket-row {
      display: flex; align-items: flex-start; gap: 12px;
      padding: 11px 14px; border-radius: 12px;
      background: rgba(255,255,255,.03);
      border: 1px solid var(--borde);
      margin-bottom: 8px; transition: border-color .15s, background .15s;
    }
    .eq-ticket-row:last-child { margin-bottom: 0; }
    .eq-ticket-row:hover {
      border-color: rgba(23,70,234,.30);
      background: rgba(23,70,234,.06);
    }
    .eq-ticket-row__dot {
      width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; margin-top: 4px;
    }
    .eq-ticket-row__info { flex: 1; min-width: 0; }
    .eq-ticket-row__svc {
      font-family: 'Montserrat', sans-serif;
      font-size: 12px; font-weight: 700; color: var(--txt-main);
      margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .eq-ticket-row__meta {
      display: flex; align-items: center; flex-wrap: wrap; gap: 8px;
    }
    .eq-ticket-row__id {
      font-family: 'Montserrat', sans-serif;
      font-size: 10px; font-weight: 800; color: rgba(141,180,255,.65);
    }
    .eq-ticket-row__fecha {
      font-size: 10px; color: var(--txt-muted); font-weight: 500;
    }
    .eq-ticket-row__adicionales {
      font-size: 10px; color: #3a4470; font-weight: 500;
      font-style: italic;
    }
    .eq-ticket-row__right {
      display: flex; flex-direction: column; align-items: flex-end;
      gap: 6px; flex-shrink: 0;
    }
    /* Reutiliza .dash-estado-badge y .dash-estado-dot de styles.css */
    .eq-ticket-row__total {
      font-family: 'Montserrat', sans-serif;
      font-size: 11px; font-weight: 800; color: var(--txt-accent);
    }
    .eq-ticket-row__obs {
      font-size: 10px; color: var(--txt-muted);
      font-style: italic; margin-top: 4px; line-height: 1.55;
    }

    /* Estado vacío dentro de historial */
    .eq-historial__empty {
      text-align: center; padding: 18px 0 10px;
    }
    .eq-historial__empty svg {
      width: 28px; height: 28px; display: block;
      margin-inline: auto; margin-bottom: 8px; opacity: .18;
    }
    .eq-historial__empty p {
      font-family: 'Montserrat', sans-serif;
      font-size: 12px; color: var(--txt-muted); font-weight: 600;
    }
    .eq-historial__empty small {
      font-size: 11px; color: #3a4470; display: block; margin-top: 3px;
    }

    /* Estado vacío (sin equipos) */
    .eq-empty-state {
      text-align: center; padding: 64px 24px;
    }
    .eq-empty-state svg {
      width: 48px; height: 48px; display: block;
      margin-inline: auto; margin-bottom: 16px; opacity: .18;
    }
    .eq-empty-state p { font-family: 'Montserrat', sans-serif; font-size: 14px; color: var(--txt-muted); font-weight: 600; }
    .eq-empty-state small { font-size: 12px; color: #3a4470; }

    /* ── Responsive ── */
    @media (max-width: 700px) {
      .eq-grid { grid-template-columns: 1fr; }
      .eq-kpi-bar { gap: 6px; }
    }
    @media (max-width: 480px) {
      .eq-card__fields { grid-template-columns: 1fr; }
      .eq-field--full  { grid-column: span 1; }
    }
  </style>
</head>
<body>

<!-- ══ NAVBAR DASHBOARD ══ -->
<nav class="navbar dash-navbar" id="navbar">
  <div class="dash-container">
    <div class="nav-inner">
      <a href="index.php" class="nav-logo">
        <img src="img/logo-horizontal-blanco.png" alt="Morales Tech"
             onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
        <div class="nav-logo-fallback">Morales<span>Tech</span></div>
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
            <a href="equipos_cliente.php" class="active">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
              Mis equipos
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
        <button class="nav-hamburger" id="hamburger" aria-label="Menú">
          <span></span><span></span><span></span>
        </button>
      </div>
    </div>
  </div>
</nav>

<!-- Mobile menu dashboard -->
<div class="dash-mobile-menu" id="mob-menu">
  <a href="inicio_clientes.php" onclick="closeDashMenu()">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
    Inicio
  </a>
  <a href="tickets_cliente.php" onclick="closeDashMenu()">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
    Mis tickets
  </a>
  <a href="equipos_cliente.php" onclick="closeDashMenu()">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
    Mis equipos
  </a>
  <a href="nuevo_ticket_cliente.php" onclick="closeDashMenu()">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
    Nueva cotización
  </a>
  <hr class="mobile-divider">
  <a href="login.php">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
    Cerrar sesión
  </a>
</div>

<!-- ══ PAGE ══ -->
<div class="dash-page">

  <!-- ── LISTADO DE EQUIPOS ── -->
  <section class="dash-section dash-section--bottom" style="padding-top:32px;">
    <div class="dash-container">

      <div class="dash-panel">

        <div class="dash-panel__head">
          <div class="dash-panel__title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
            Mis equipos
          </div>
          <!-- KPIs compactos -->
          <div class="eq-kpi-bar">
            <span class="eq-kpi-pill">
              <span class="eq-kpi-pill__val"><?= $total_equipos ?></span>
              <span class="eq-kpi-pill__lbl">equipos</span>
            </span>
            <span class="eq-kpi-sep"></span>
            <span class="eq-kpi-pill">
              <span class="eq-kpi-pill__val"><?= $total_tickets ?></span>
              <span class="eq-kpi-pill__lbl">servicios</span>
            </span>
            <?php if ($eq_activos > 0): ?>
            <span class="eq-kpi-sep"></span>
            <span class="eq-kpi-pill eq-kpi-pill--activo">
              <span class="eq-kpi-pill__dot"></span>
              <span class="eq-kpi-pill__val"><?= $eq_activos ?></span>
              <span class="eq-kpi-pill__lbl">activo<?= $eq_activos !== 1 ? 's' : '' ?></span>
            </span>
            <?php endif; ?>
          </div>
        </div>

        <div style="padding: 20px;">

          <?php if (empty($equipos)): ?>
          <div class="eq-empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
            <p>Aún no tienes equipos registrados</p>
            <small>Cuando traigas tu primer equipo aparecerá aquí</small>
          </div>

          <?php else: ?>

          <div class="eq-grid">
            <?php foreach ($equipos as $idx => $eq):
              $num_tickets  = count($eq['tickets']);
              $tickets_activos = array_filter($eq['tickets'], fn($t) => $t['estado'] !== 'Completado');
              $tiene_activo = count($tickets_activos) > 0;
            ?>
            <div class="eq-card" id="eq-card-<?= $idx ?>">

              <!-- Cabecera de la tarjeta -->
              <div class="eq-card__head">
                <div class="eq-card__icon-wrap">
                  <?= icono_equipo($eq['tipo']) ?>
                </div>
                <div class="eq-card__info">
                  <?php if ($eq['tipo'] === 'Laptop'): ?>
                    <div class="eq-card__marca"><?= htmlspecialchars($eq['marca'] . ' · Laptop') ?></div>
                    <div class="eq-card__modelo"><?= htmlspecialchars($eq['modelo']) ?></div>
                  <?php else: ?>
                    <div class="eq-card__marca">PC de escritorio</div>
                    <div class="eq-card__modelo"><?= htmlspecialchars($eq['so']) ?></div>
                  <?php endif; ?>
                </div>
                <div class="eq-card__id-badge"><?= htmlspecialchars($eq['id']) ?></div>
              </div>

              <!-- Datos del equipo -->
              <div class="eq-card__body">
                <div class="eq-card__fields">
                  <?php if ($eq['tipo'] === 'Laptop'): ?>
                    <div class="eq-field">
                      <div class="eq-field__label">Marca</div>
                      <div class="eq-field__value"><?= htmlspecialchars($eq['marca']) ?></div>
                    </div>
                    <div class="eq-field">
                      <div class="eq-field__label">Modelo</div>
                      <div class="eq-field__value"><?= htmlspecialchars($eq['modelo']) ?></div>
                    </div>
                    <div class="eq-field">
                      <div class="eq-field__label">Nº Serie</div>
                      <div class="eq-field__value"><?= htmlspecialchars($eq['serie']) ?></div>
                    </div>
                    <div class="eq-field">
                      <div class="eq-field__label">Fecha de registro</div>
                      <div class="eq-field__value"><?= htmlspecialchars($eq['fecha_reg']) ?></div>
                    </div>
                    <div class="eq-field eq-field--full">
                      <div class="eq-field__label">Sistema Operativo</div>
                      <div class="eq-field__value"><?= htmlspecialchars($eq['so']) ?></div>
                    </div>
                  <?php else: /* PC — solo SO y Fecha */ ?>
                    <div class="eq-field eq-field--full">
                      <div class="eq-field__label">Sistema Operativo</div>
                      <div class="eq-field__value"><?= htmlspecialchars($eq['so']) ?></div>
                    </div>
                    <div class="eq-field eq-field--full">
                      <div class="eq-field__label">Fecha de registro</div>
                      <div class="eq-field__value"><?= htmlspecialchars($eq['fecha_reg']) ?></div>
                    </div>
                  <?php endif; ?>
                </div>
              </div>

              <!-- Pie: contador + botón Ver Historial -->
              <div class="eq-card__footer">
                <span class="eq-tickets-count">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
                  <?= $num_tickets ?> servicio<?= $num_tickets !== 1 ? 's' : '' ?>
                  <?php if ($tiene_activo): ?>
                    &middot; <span style="color:#8db4ff;font-weight:700;"><?= count($tickets_activos) ?> activo<?= count($tickets_activos) !== 1 ? 's' : '' ?></span>
                  <?php endif; ?>
                </span>
                <?php if ($num_tickets > 0): ?>
                <button
                  class="btn-ver-historial"
                  id="btn-historial-<?= $idx ?>"
                  onclick="toggleHistorial(<?= $idx ?>)"
                  aria-expanded="false"
                  aria-controls="historial-<?= $idx ?>">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                  Ver historial
                  <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <?php else: ?>
                <span class="eq-tickets-count" style="color:#3a4470;font-size:11px;">Sin servicios</span>
                <?php endif; ?>
              </div>

              <!-- Accordion: Historial de servicios -->
              <?php if ($num_tickets > 0): ?>
              <div class="eq-historial" id="historial-<?= $idx ?>" role="region" aria-labelledby="btn-historial-<?= $idx ?>">
                <div class="eq-historial__inner">
                  <div class="eq-historial__title">Historial de servicios</div>

                  <?php foreach ($eq['tickets'] as $t):
                    $ec = estado_cfg($t['estado']);
                  ?>
                  <div class="eq-ticket-row">
                    <span class="eq-ticket-row__dot" style="background:<?= $ec['dot'] ?>;"></span>
                    <div class="eq-ticket-row__info">
                      <div class="eq-ticket-row__svc"><?= htmlspecialchars($t['servicio']) ?></div>
                      <div class="eq-ticket-row__meta">
                        <span class="eq-ticket-row__id">#<?= htmlspecialchars($t['id']) ?></span>
                        <span class="eq-ticket-row__fecha"><?= htmlspecialchars($t['fecha']) ?></span>
                        <?php if (!empty($t['adicionales'])): ?>
                        <span class="eq-ticket-row__adicionales">+ <?= implode(', ', array_map('htmlspecialchars', $t['adicionales'])) ?></span>
                        <?php endif; ?>
                      </div>
                      <?php if (!empty($t['obs'])): ?>
                      <div class="eq-ticket-row__obs"><?= htmlspecialchars($t['obs']) ?></div>
                      <?php endif; ?>
                    </div>
                    <div class="eq-ticket-row__right">
                      <span class="dash-estado-badge" style="background:<?= $ec['bg'] ?>;color:<?= $ec['color'] ?>;">
                        <span class="dash-estado-dot" style="background:<?= $ec['dot'] ?>;"></span>
                        <?= htmlspecialchars($t['estado']) ?>
                      </span>
                      <span class="eq-ticket-row__total"><?= htmlspecialchars($t['total']) ?></span>
                    </div>
                  </div>
                  <?php endforeach; ?>

                </div>
              </div>
              <?php endif; ?>

            </div><!-- /eq-card -->
            <?php endforeach; ?>
          </div><!-- /eq-grid -->

          <?php endif; ?>

        </div><!-- /padding wrapper -->

        <div class="dash-table-footer">
          <?= $total_equipos ?> equipo<?= $total_equipos !== 1 ? 's' : '' ?> registrado<?= $total_equipos !== 1 ? 's' : '' ?>
          <?php if ($total_tickets > 0): echo ' &middot; ' . $total_tickets . ' servicio' . ($total_tickets !== 1 ? 's' : ''); endif; ?>
        </div>

      </div><!-- /dash-panel -->

    </div>
  </section>

</div><!-- /dash-page -->

<!-- WhatsApp flotante -->
<a href="https://wa.me/51903208170" target="_blank" rel="noopener" class="float-wa" title="WhatsApp">
  <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
</a>

<script src="script.js" defer></script>
<script>
/* ══════════════════════════════════════════
   EQUIPOS_CLIENTE.PHP — Accordion / Toggle
   ══════════════════════════════════════════ */
(function initEquiposAccordion() {

  /* Cierra todos los accordions excepto el índice indicado (-1 = cierra todos) */
  function closeAll(exceptIdx) {
    document.querySelectorAll('.eq-historial').forEach(function(panel) {
      var idx = panel.id.replace('historial-', '');
      if (parseInt(idx) === exceptIdx) return;
      panel.classList.remove('open');
      var btn = document.getElementById('btn-historial-' + idx);
      if (btn) {
        btn.classList.remove('open');
        btn.setAttribute('aria-expanded', 'false');
      }
    });
  }

  /* Toggle público (llamado desde el onclick del PHP) */
  window.toggleHistorial = function(idx) {
    var panel = document.getElementById('historial-' + idx);
    var btn   = document.getElementById('btn-historial-' + idx);
    if (!panel || !btn) return;

    var isOpen = panel.classList.contains('open');

    if (isOpen) {
      /* Cerrar */
      panel.classList.remove('open');
      btn.classList.remove('open');
      btn.setAttribute('aria-expanded', 'false');
    } else {
      /* Cerrar los demás primero (accordion exclusivo) */
      closeAll(idx);
      /* Abrir éste */
      panel.classList.add('open');
      btn.classList.add('open');
      btn.setAttribute('aria-expanded', 'true');

      /* Scroll suave hacia la tarjeta si queda fuera de la vista */
      var card = document.getElementById('eq-card-' + idx);
      if (card) {
        var rect = card.getBoundingClientRect();
        if (rect.top < 80 || rect.bottom > window.innerHeight) {
          card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
      }
    }
  };

})();
</script>
</body>
</html>