<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['idAdmin'])) {
    header("Location: login_staff.php");
    exit;
}

// ── AJAX: actualizar estado de un ticket ──
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    $datos  = json_decode(file_get_contents('php://input'), true);
    $codigo = trim($datos['codigo'] ?? '');
    $estado = trim($datos['estado'] ?? '');
    $validos = ['Recibido','En diagnóstico','En reparación','Completado'];
    if ($codigo === '' || !in_array($estado, $validos)) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
        exit;
    }
    $stmt = $conn->prepare("UPDATE TICKET SET estado=? WHERE codigo=?");
    $stmt->bind_param("ss", $estado, $codigo);
    $ok = $stmt->execute();
    $stmt->close();
    echo json_encode(['success' => $ok]);
    exit;
}

// ── Datos del admin para el header ──
$stmt = $conn->prepare("SELECT nombres, apellidos FROM ADMIN WHERE idAdmin=? LIMIT 1");
$stmt->bind_param("i", $_SESSION['idAdmin']);
$stmt->execute();
$adm = $stmt->get_result()->fetch_assoc();
$stmt->close();

$nombre_usuario = $adm ? $adm['nombres'] . ' ' . $adm['apellidos'] : 'Admin';
$rol_usuario    = 'Administrador';
$partes         = explode(' ', trim($nombre_usuario));
$inicial        = strtoupper(substr($partes[0], 0, 1));
$nombre_corto   = $partes[0];

// ── Filtro activo ──
$estados_validos = ['Recibido','En diagnóstico','En reparación','Completado'];
$filtro_activo   = $_GET['estado'] ?? 'Todos';
if (!in_array($filtro_activo, array_merge(['Todos'], $estados_validos))) {
    $filtro_activo = 'Todos';
}

// ── Consulta tickets ──
$sql = "SELECT t.codigo, t.estado,
               cl.nombres, cl.apellidos,
               e.tipoEquipo, e.marca,
               (SELECT s.nomServicio FROM COTIZACION_SERVICIO cs
                JOIN SERVICIO s ON s.idServicio=cs.idServicio
                WHERE cs.idCotizacion=c.idCotizacion AND s.tipo='Principal' LIMIT 1) AS servicio
        FROM TICKET t
        JOIN COTIZACION c ON c.idCotizacion = t.idCotizacion
        JOIN CLIENTE   cl ON cl.idCliente   = c.idCliente
        JOIN EQUIPO    e  ON e.idEquipo      = c.idEquipo";

if ($filtro_activo !== 'Todos') {
    $stmt = $conn->prepare($sql . " WHERE t.estado=? ORDER BY t.fechaCreacion DESC");
    $stmt->bind_param("s", $filtro_activo);
    $stmt->execute();
    $res = $stmt->get_result();
    $stmt->close();
} else {
    $res = $conn->query($sql . " ORDER BY t.fechaCreacion DESC");
}

$tickets = [];
while ($f = $res->fetch_assoc()) {
    $tickets[] = [
        "id"       => $f['codigo'],
        "cliente"  => $f['nombres'] . ' ' . $f['apellidos'],
        "tipo"     => $f['tipoEquipo'] ?? '',
        "marca"    => $f['marca'] ?? '',
        "servicio" => $f['servicio'] ?? 'Servicio técnico',
        "estado"   => $f['estado'],
    ];
}

$estados = ['Todos','Recibido','En diagnóstico','En reparación','Completado'];

function clase_estado_ticket($estado) {
    $m = [
        'Recibido'       => 'dash-badge--recibido',
        'En diagnóstico' => 'dash-badge--diagnostico',
        'En reparación'  => 'dash-badge--reparacion',
        'Completado'     => 'dash-badge--completado',
    ];
    return $m[$estado] ?? 'dash-badge--default';
}

function clase_filtro_ticket($estado) {
    $m = [
        'Recibido'       => 'tk-filter--recibido',
        'En diagnóstico' => 'tk-filter--diagnostico',
        'En reparación'  => 'tk-filter--reparacion',
        'Completado'     => 'tk-filter--completado',
        'Todos'          => 'tk-filter--todos',
    ];
    return $m[$estado] ?? '';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tickets — Morales Tech</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body class="admin-body admin-body--dashboard">

<div class="dash-shell">

  <!-- SIDEBAR -->
  <aside class="dash-sidebar">
    <div class="dash-sidebar__logo">
      <img src="img/isotipo-color.png" alt="Morales Tech" class="dash-sidebar__isotipo"
           onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
      <div class="dash-sidebar__isotipo-fallback">MT</div>
    </div>
    <nav class="dash-sidebar__nav">
      <a href="dashboard.php" class="dash-nav-link">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
        <span>Dashboard</span>
      </a>
      <a href="tickets.php" class="dash-nav-link dash-nav-link--active">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
        <span>Tickets</span>
      </a>
      <a href="inventario.php" class="dash-nav-link">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
        <span>Inventario</span>
      </a>
      <a href="ventas.php" class="dash-nav-link">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        <span>Ventas</span>
      </a>
    </nav>
    <div class="dash-sidebar__footer">
      <a href="logout.php" class="dash-sidebar__logout">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        <span>Salir</span>
      </a>
    </div>
  </aside>

  <!-- MAIN -->
  <div class="dash-main">
    <header class="dash-header">
      <div class="dash-header__breadcrumb">Panel / <span>Tickets</span></div>
      <div class="dash-header__user">
        <div class="dash-header__avatar"><?= $inicial ?></div>
        <div class="dash-header__user-info">
          <span class="dash-header__username"><?= htmlspecialchars($nombre_corto) ?></span>
          <span class="dash-header__role"><?= htmlspecialchars($rol_usuario) ?></span>
        </div>
      </div>
    </header>

    <main class="dash-page-content">
      <!-- Page header -->
      <div class="tk-page-header">
        <div>
          <h1 class="tk-page-title">Tickets</h1>
          <p class="tk-page-subtitle">Gestiona los tickets de soporte técnico</p>
        </div>
        <a href="nuevo_ticket.php" class="dash-btn-new">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Registrar ticket
        </a>
      </div>

      <!-- Filtros -->
      <div class="tk-filters">
        <?php foreach ($estados as $estado):
          $url_f    = ($estado === 'Todos') ? 'tickets.php' : 'tickets.php?estado='.urlencode($estado);
          $es_activo = ($filtro_activo === $estado);
          $clase_f  = clase_filtro_ticket($estado);
        ?>
        <a href="<?= $url_f ?>"
           class="tk-filter-btn <?= $clase_f ?> <?= $es_activo ? 'tk-filter-btn--active' : '' ?>">
          <?= htmlspecialchars($estado) ?>
        </a>
        <?php endforeach; ?>
      </div>

      <!-- Tabla -->
      <div class="dash-panel-block">
        <div class="dash-table-wrap">
          <table class="dash-admin-table">
            <thead>
              <tr>
                <th>ID del ticket</th>
                <th>Nombre del cliente</th>
                <th>Marca</th>
                <th>Servicio solicitado</th>
                <th>Estado del ticket</th>
              </tr>
            </thead>
            <tbody>
            <?php if (empty($tickets)): ?>
            <tr><td colspan="5">
              <div class="dash-empty">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <p>No hay tickets con este estado</p>
                <small>Prueba otro filtro o registra un nuevo ticket</small>
              </div>
            </td></tr>
            <?php else: ?>
            <?php foreach ($tickets as $t):
              $p = explode(' ', $t['cliente']);
              $initials = strtoupper(substr($p[0],0,1)) . (isset($p[1]) ? strtoupper(substr($p[1],0,1)) : '');
            ?>
            <tr>
              <td><span class="dash-admin-t-id">#<?= htmlspecialchars($t['id']) ?></span></td>
              <td>
                <div class="tk-cliente-cell">
                  <div class="tk-cliente-avatar"><?= $initials ?></div>
                  <span class="tk-cliente-nombre"><?= htmlspecialchars($t['cliente']) ?></span>
                </div>
              </td>
              <td>
                <span class="tk-marca-tipo"><?= htmlspecialchars($t['tipo']) ?> · </span>
                <span class="tk-marca-nombre"><?= htmlspecialchars($t['marca']) ?></span>
              </td>
              <td><?= htmlspecialchars($t['servicio']) ?></td>
              <td>
                <select class="tk-estado-select <?= clase_estado_ticket($t['estado']) ?>"
                        data-id="<?= htmlspecialchars($t['id']) ?>"
                        onchange="tkActualizarEstado(this)">
                  <option <?= $t['estado']==='Recibido'       ? 'selected':'' ?>>Recibido</option>
                  <option <?= $t['estado']==='En diagnóstico' ? 'selected':'' ?>>En diagnóstico</option>
                  <option <?= $t['estado']==='En reparación'  ? 'selected':'' ?>>En reparación</option>
                  <option <?= $t['estado']==='Completado'     ? 'selected':'' ?>>Completado</option>
                </select>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
          </table>
        </div>
        <div class="dash-table-footer">
          <?php
            $total = count($tickets);
            echo $total . ' ticket' . ($total !== 1 ? 's' : '');
            if ($filtro_activo !== 'Todos')
              echo ' &middot; filtro: <strong>' . htmlspecialchars($filtro_activo) . '</strong>';
          ?>
        </div>
      </div>

    </main>
  </div><!-- /dash-main -->

</div><!-- /dash-shell -->

<script src="script.js" defer></script>
</body>
</html>
