<?php
// session_start();
// include("includes/auth_cliente.php");
$nombre_cliente = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Esteban Demo';
$partes         = explode(' ', trim($nombre_cliente));
$nombre_corto   = $partes[0];
$hora           = (int) date('H');
$saludo         = $hora < 12 ? 'Buenos días' : ($hora < 19 ? 'Buenas tardes' : 'Buenas noches');

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
  <link rel="stylesheet" href="styles.css">
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

  <!-- BIENVENIDA -->
  <section class="dash-hero">
    <div class="dash-container">
      <div class="dash-hero-inner">
        <div class="dash-tag">Portal de clientes</div>
        <div class="dash-greeting"><?= $saludo ?>,</div>
        <h1 class="dash-hero-name">
          Hola, <em><?= htmlspecialchars($nombre_corto) ?></em>
        </h1>
        <p class="dash-hero-sub">
          Bienvenido a tu portal. Aquí puedes revisar el estado de tus equipos y solicitar nuevos servicios técnicos.
        </p>
      </div>
    </div>
  </section>

  <!-- ACCESOS RÁPIDOS -->
  <section class="dash-section">
    <div class="dash-container">
      <div class="dash-section-label">Accesos rápidos</div>
      <div class="qa-grid">
        <a href="nuevo_ticket_cliente.php" class="qa-btn qa-btn--accent">
          <div class="qa-btn__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          </div>
          <div class="qa-btn__label">Nueva cotización</div>
        </a>
        <a href="tickets_cliente.php" class="qa-btn">
          <div class="qa-btn__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
          </div>
          <div class="qa-btn__label">Mis tickets</div>
        </a>
        <a href="tickets_cliente.php" class="qa-btn">
          <div class="qa-btn__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
          </div>
          <div class="qa-btn__label">Estado equipo</div>
        </a>
        <a href="https://wa.me/51903208170" target="_blank" rel="noopener" class="qa-btn">
          <div class="qa-btn__icon qa-btn__icon--wa">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
            </svg>
          </div>
          <div class="qa-btn__label">Contacta con Soporte</div>
        </a>
      </div>
    </div>
  </section>

  <!-- TICKETS RECIENTES -->
  <section class="dash-section dash-section--bottom">
    <div class="dash-container">
      <div class="dash-panel">
        <div class="dash-panel__head">
          <div class="dash-panel__title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
            Mis tickets recientes
          </div>
          <a href="tickets_cliente.php" class="dash-panel__link">Ver todos →</a>
        </div>
        <div class="dash-table-wrap">
          <?php if (empty($tickets)): ?>
          <div class="dash-empty">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
            <p>No tienes tickets aún</p>
          </div>
          <?php else: ?>
          <table class="dash-table">
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
              <td><span class="dash-t-id">#<?= htmlspecialchars($t['id']) ?></span></td>
              <td>
                <div class="dash-t-eq"><?= htmlspecialchars($t['equipo']) ?></div>
                <div class="dash-t-svc"><?= htmlspecialchars($t['servicio']) ?></div>
              </td>
              <td>
                <span class="dash-estado-badge" style="background:<?= $ec['bg'] ?>;color:<?= $ec['color'] ?>;">
                  <span class="dash-estado-dot" style="background:<?= $ec['dot'] ?>;"></span>
                  <?= htmlspecialchars($t['estado']) ?>
                </span>
              </td>
              <td><span class="dash-t-fecha"><?= $t['fecha'] ?></span></td>
              <td>
                <a href="detalle_ticket.php?id=<?= urlencode($t['id']) ?>" class="dash-t-det">
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

</div><!-- /dash-page -->

<!-- WhatsApp flotante -->
<a href="https://wa.me/51903208170" target="_blank" rel="noopener" class="float-wa" title="WhatsApp">
  <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
</a>

<script src="script.js" defer></script>
</body>
</html>
