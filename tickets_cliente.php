<?php
require_once 'conexion.php';
session_start();

if (!isset($_SESSION['idCliente'])) {
    header("Location: login.php");
    exit;
}

$nombre_cliente = $_SESSION['nombres'];
$partes         = explode(' ', trim($nombre_cliente));
$nombre_corto   = $partes[0];

$filtro_activo = isset($_GET['estado']) ? $_GET['estado'] : 'Todos';

function fecha_es($fechaSql) {
    $meses = ['ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic'];
    $ts = strtotime($fechaSql);
    return date('j', $ts) . ' ' . $meses[date('n', $ts) - 1] . ' ' . date('Y', $ts);
}

$todos_los_tickets = [];

$sql = "SELECT t.codigo, t.estado, t.fechaCreacion,
               e.tipoEquipo, e.marca, e.sistemaOperativo, e.observaciones,
               c.idCotizacion, c.subtotal, c.igv, c.total
        FROM TICKET t
        JOIN COTIZACION c ON c.idCotizacion = t.idCotizacion
        JOIN EQUIPO e ON e.idEquipo = c.idEquipo
        WHERE c.idCliente = ?
        ORDER BY t.fechaCreacion DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['idCliente']);
$stmt->execute();
$resultado = $stmt->get_result();

$sqlServicios = "SELECT s.nomServicio
                   FROM COTIZACION_SERVICIO cs
                   JOIN SERVICIO s ON s.idServicio = cs.idServicio
                  WHERE cs.idCotizacion = ?
                  ORDER BY (s.tipo = 'Principal') DESC, s.nomServicio ASC";
$stmtServicios = $conn->prepare($sqlServicios);

while ($fila = $resultado->fetch_assoc()) {
    $stmtServicios->bind_param("i", $fila['idCotizacion']);
    $stmtServicios->execute();
    $resServicios = $stmtServicios->get_result();

    $nombres_servicios = [];
    while ($s = $resServicios->fetch_assoc()) {
        $nombres_servicios[] = $s['nomServicio'];
    }
    $servicio_principal = array_shift($nombres_servicios) ?? 'Servicio técnico';

    $todos_los_tickets[] = [
        "id"          => $fila['codigo'],
        "tipo"        => $fila['tipoEquipo'],
        "marca"       => $fila['marca'],
        "so"          => $fila['sistemaOperativo'],
        "servicio"    => $servicio_principal,
        "adicionales" => $nombres_servicios,
        "estado"      => $fila['estado'],
        "fecha"       => fecha_es($fila['fechaCreacion']),
        "subtotal"    => 'S/ ' . number_format((float) $fila['subtotal'], 2),
        "igv"         => 'S/ ' . number_format((float) $fila['igv'], 2),
        "total"       => 'S/ ' . number_format((float) $fila['total'], 2),
        "obs"         => $fila['observaciones'] ?? '',
    ];
}
$stmtServicios->close();
$stmt->close();

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

$reciente = $todos_los_tickets[0] ?? null;
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
            <a href="equipos_cliente.php">
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

  <!-- HERO: ticket más reciente -->
  <?php if ($reciente): ?>
  <section class="dash-hero">
    <div class="dash-container">
      <div class="dash-tag">Mis tickets</div>
      <?php $ec = estado_cfg($reciente['estado']); ?>
      <div class="ticket-hero-card"
           onclick="abrirModal('<?= htmlspecialchars($reciente['id']) ?>','<?= htmlspecialchars($reciente['tipo'].' '.$reciente['marca']) ?>','<?= htmlspecialchars($reciente['so']) ?>','<?= htmlspecialchars($reciente['servicio']) ?>',<?= htmlspecialchars(json_encode($reciente['adicionales']), ENT_QUOTES) ?>,'<?= htmlspecialchars($reciente['estado']) ?>','<?= htmlspecialchars($reciente['fecha']) ?>','<?= htmlspecialchars($reciente['subtotal']) ?>','<?= htmlspecialchars($reciente['igv']) ?>','<?= htmlspecialchars($reciente['total']) ?>','<?= addslashes(htmlspecialchars($reciente['obs'])) ?>')">
        <div class="ticket-hero-card__label">Ticket más reciente</div>
        <div class="ticket-hero-card__inner">
          <div class="ticket-hero-card__left">
            <div class="ticket-hero-card__id">#<?= htmlspecialchars($reciente['id']) ?></div>
            <div class="ticket-hero-card__equipo"><?= htmlspecialchars($reciente['tipo'] . ' · ' . $reciente['marca']) ?></div>
            <div class="ticket-hero-card__svc"><?= htmlspecialchars($reciente['servicio']) ?></div>
            <div class="ticket-hero-badge">
              <span class="ticket-hero-badge__dot"></span>
              <?= htmlspecialchars($reciente['estado']) ?>
            </div>
          </div>
          <div class="ticket-hero-card__right">
            <div class="ticket-hero-card__fecha"><?= htmlspecialchars($reciente['fecha']) ?></div>
            <button class="btn-hero-detail" onclick="event.stopPropagation(); abrirModal('<?= htmlspecialchars($reciente['id']) ?>','<?= htmlspecialchars($reciente['tipo'].' '.$reciente['marca']) ?>','<?= htmlspecialchars($reciente['so']) ?>','<?= htmlspecialchars($reciente['servicio']) ?>',<?= htmlspecialchars(json_encode($reciente['adicionales']), ENT_QUOTES) ?>,'<?= htmlspecialchars($reciente['estado']) ?>','<?= htmlspecialchars($reciente['fecha']) ?>','<?= htmlspecialchars($reciente['subtotal']) ?>','<?= htmlspecialchars($reciente['igv']) ?>','<?= htmlspecialchars($reciente['total']) ?>','<?= addslashes(htmlspecialchars($reciente['obs'])) ?>')">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              Ver detalle
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- LISTADO DE TICKETS -->
  <section class="dash-section dash-section--bottom">
    <div class="dash-container">
      <div class="dash-section-label">Filtrar por estado</div>
      <div class="dash-filters">
        <?php foreach ($estados as $estado):
          $url_f     = ($estado === 'Todos') ? 'tickets_cliente.php' : 'tickets_cliente.php?estado='.urlencode($estado);
          $valor_cmp = $mapa_estados[$estado] ?? $estado;
          $es_activo = ($filtro_activo === 'Todos' && $estado === 'Todos')
                    || ($filtro_activo !== 'Todos' && ($valor_cmp === $filtro_activo || $estado === $filtro_activo));
        ?>
        <a href="<?= $url_f ?>" class="dash-filter-btn <?= $es_activo ? 'active' : '' ?>">
          <?= htmlspecialchars($estado) ?>
        </a>
        <?php endforeach; ?>
      </div>

      <div class="dash-panel">
        <div class="dash-panel__head">
          <div class="dash-panel__title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
            Mis tickets
          </div>
          <a href="nuevo_ticket_cliente.php" class="dash-panel__link dash-panel__link--new">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nueva cotización
          </a>
        </div>
        <div class="dash-table-wrap">
          <?php if (empty($tickets)): ?>
          <div class="dash-empty">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
            <p>No hay tickets con este estado</p>
            <small>Prueba otro filtro</small>
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
                <div class="dash-t-eq"><?= htmlspecialchars($t['tipo'] . ' ' . $t['marca']) ?></div>
                <div class="dash-t-svc"><?= htmlspecialchars($t['servicio']) ?></div>
              </td>
              <td>
                <span class="dash-estado-badge" style="background:<?= $ec['bg'] ?>;color:<?= $ec['color'] ?>;">
                  <span class="dash-estado-dot" style="background:<?= $ec['dot'] ?>;"></span>
                  <?= htmlspecialchars($t['estado']) ?>
                </span>
              </td>
              <td><span class="dash-t-fecha"><?= htmlspecialchars($t['fecha']) ?></span></td>
              <td>
                <button class="dash-t-det" onclick="abrirModal(
                  '<?= htmlspecialchars($t['id']) ?>',
                  '<?= htmlspecialchars($t['tipo'].' '.$t['marca']) ?>',
                  '<?= htmlspecialchars($t['so']) ?>',
                  '<?= htmlspecialchars($t['servicio']) ?>',
                  <?= htmlspecialchars(json_encode($t['adicionales']), ENT_QUOTES) ?>,
                  '<?= htmlspecialchars($t['estado']) ?>',
                  '<?= htmlspecialchars($t['fecha']) ?>',
                  '<?= htmlspecialchars($t['subtotal']) ?>',
                  '<?= htmlspecialchars($t['igv']) ?>',
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
        <div class="dash-table-footer">
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

</div><!-- /dash-page -->

<!-- ══ MODAL DETALLE ══ -->
<div class="dash-modal-overlay" id="modal-overlay" onclick="cerrarOverlay(event)">
  <div class="dash-modal-box">
    <div class="dash-modal-hero">
      <button class="dash-modal-close" onclick="cerrarModal()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
      <div class="dash-modal-hero__id"     id="m-id"></div>
      <div class="dash-modal-hero__equipo" id="m-equipo"></div>
      <div class="dash-modal-hero__badge">
        <span class="dash-modal-hero__dot"></span>
        <span id="m-estado"></span>
      </div>
    </div>
    <div class="dash-modal-body">
      <div class="dash-modal-section">
        <div class="dash-modal-section__title">Información del equipo</div>
        <div class="dash-modal-grid">
          <div class="dash-modal-field">
            <div class="dash-modal-field__label">Tipo / Marca</div>
            <div class="dash-modal-field__value" id="m-tipo"></div>
          </div>
          <div class="dash-modal-field">
            <div class="dash-modal-field__label">Sistema operativo</div>
            <div class="dash-modal-field__value" id="m-so"></div>
          </div>
          <div class="dash-modal-field">
            <div class="dash-modal-field__label">Fecha de ingreso</div>
            <div class="dash-modal-field__value" id="m-fecha"></div>
          </div>
          <div class="dash-modal-field" id="m-obs-block">
            <div class="dash-modal-field__label">Observaciones</div>
            <div class="dash-modal-field__value dash-modal-field__value--obs" id="m-obs"></div>
          </div>
        </div>
      </div>
      <div class="dash-modal-section">
        <div class="dash-modal-section__title">Servicios solicitados</div>
        <div class="dash-modal-svc-list" id="m-servicios"></div>
        <div class="dash-modal-total" style="background:transparent;border:none;padding:4px 4px 0;">
          <span class="dash-modal-total__label" style="font-weight:500;color:#a0a8bb;">Subtotal</span>
          <span class="dash-modal-total__val" style="font-size:14px;" id="m-subtotal"></span>
        </div>
        <div class="dash-modal-total" style="background:transparent;border:none;padding:0 4px 4px;">
          <span class="dash-modal-total__label" style="font-weight:500;color:#a0a8bb;">IGV (18%)</span>
          <span class="dash-modal-total__val" style="font-size:14px;" id="m-igv"></span>
        </div>
        <div class="dash-modal-total">
          <span class="dash-modal-total__label">Total estimado</span>
          <span class="dash-modal-total__val" id="m-total"></span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- WhatsApp flotante -->
<a href="https://wa.me/51903208170" target="_blank" rel="noopener" class="float-wa" title="WhatsApp">
  <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
</a>

<script src="script.js?v=<?= filemtime(__DIR__ . '/script.js') ?>" defer></script>
</body>
</html>
