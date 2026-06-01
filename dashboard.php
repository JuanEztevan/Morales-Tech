<?php
session_start();

if (!isset($_SESSION['idAdmin'])) {
    header("Location: login_staff.php");
    exit;
}
// session_start();
// include("includes/auth.php");
require_once 'conexion.php';

$idAdmin = $_SESSION['idAdmin'];

$sql = "SELECT nombres, apellidos, email
        FROM ADMIN
        WHERE idAdmin = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idAdmin);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $admin = $result->fetch_assoc();

    $nombre_usuario   = $admin['nombres'] . ' ' . $admin['apellidos'];
    $nombre_display   = $admin['nombres'];
    $apellido_display = $admin['apellidos'];

} else {
    session_destroy();
    header("Location: login_staff.php");
    exit;
}

$rol_usuario = 'Administrador';

$inicial = strtoupper(substr($nombre_display, 0, 1));
$hora   = (int) date('H');
$saludo = $hora < 12 ? 'Buenos días' : ($hora < 19 ? 'Buenas tardes' : 'Buenas noches');

$tickets_hoy         = 4;
$tickets_pendientes  = 7;
$tickets_completados = 12;
$ingresos_mes        = 3480;

$tickets_recientes = [
    ["id"=>"MT-8845","cliente"=>"Brenda Benites",  "servicio"=>"Limpieza preventiva",     "estado"=>"Completado",     "fecha"=>"Hoy, 10:30"],
    ["id"=>"MT-8844","cliente"=>"Diana Calderón",  "servicio"=>"Mantenimiento correctivo", "estado"=>"En reparación",  "fecha"=>"Hoy, 09:15"],
    ["id"=>"MT-8843","cliente"=>"Andrés Ochante",  "servicio"=>"Repotenciación",           "estado"=>"Recibido",       "fecha"=>"Ayer, 16:00"],
    ["id"=>"MT-8842","cliente"=>"Valeria Ramírez", "servicio"=>"Reparación",               "estado"=>"En diagnóstico", "fecha"=>"Ayer, 11:45"],
    ["id"=>"MT-8841","cliente"=>"Carlos Quispe",   "servicio"=>"Formateo e instalación",   "estado"=>"Completado",     "fecha"=>"22 may, 09:00"],
];

$stock_alerta = [
    ["nombre"=>"RAM DDR5 16GB 4800MHz", "stock"=>9,  "minimo"=>10, "clase"=>"dash-stock--min"],
    ["nombre"=>"SSD 1TB SATA",          "stock"=>10, "minimo"=>12, "clase"=>"dash-stock--low"],
    ["nombre"=>"Kit Dest. 128 en 1",    "stock"=>12, "minimo"=>15, "clase"=>"dash-stock--low"],
];

function clase_estado_dash($e) {
    $m = [
        'Recibido'       => 'dash-badge--recibido',
        'En diagnóstico' => 'dash-badge--diagnostico',
        'En reparación'  => 'dash-badge--reparacion',
        'Completado'     => 'dash-badge--completado',
    ];
    return $m[$e] ?? 'dash-badge--default';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard — Morales Tech</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body class="admin-body admin-body--dashboard">

<div class="dash-shell">

  <!-- ══ SIDEBAR ══ -->
  <aside class="dash-sidebar">
    <div class="dash-sidebar__logo">
      <img src="img/isotipo-color.png" alt="Morales Tech" class="dash-sidebar__isotipo"
           onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
      <div class="dash-sidebar__isotipo-fallback">MT</div>
    </div>
    <nav class="dash-sidebar__nav">
      <a href="dashboard.php" class="dash-nav-link dash-nav-link--active">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
        <span>Dashboard</span>
      </a>
      <a href="tickets.php" class="dash-nav-link">
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

  <!-- ══ MAIN ══ -->
  <div class="dash-main">

    <!-- Header -->
    <header class="dash-header">
      <div class="dash-header__breadcrumb">Panel / <span>Dashboard</span></div>
      <div class="dash-header__user">
        <div class="dash-header__avatar"><?= $inicial ?></div>
        <div class="dash-header__user-info">
          <span class="dash-header__username"><?= htmlspecialchars($nombre_display) ?></span>
          <span class="dash-header__role"><?= htmlspecialchars($rol_usuario) ?></span>
        </div>
      </div>
    </header>

    <!-- Page content -->
    <main class="dash-page-content">
      <div class="dash-layout">

        <!-- ── COLUMNA IZQUIERDA ── -->
        <div class="dash-col-left">

          <!-- KPIs -->
          <div class="dash-kpi-row">
            <div class="dash-kpi-card dash-kpi-card--highlight">
              <div class="dash-kpi-card__top">
                <div class="dash-kpi-card__icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <span class="dash-kpi-badge dash-kpi-badge--up">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
                  +18%
                </span>
              </div>
              <div class="dash-kpi-card__label">Ingresos del mes</div>
              <div class="dash-kpi-card__value">S/ <?= number_format($ingresos_mes, 0) ?></div>
              <div class="dash-kpi-card__sub">Mayo 2025</div>
            </div>

            <div class="dash-kpi-card">
              <div class="dash-kpi-card__top">
                <div class="dash-kpi-card__icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
                </div>
                <span class="dash-kpi-badge dash-kpi-badge--up">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
                  +2 hoy
                </span>
              </div>
              <div class="dash-kpi-card__label">Tickets hoy</div>
              <div class="dash-kpi-card__value"><?= $tickets_hoy ?></div>
              <div class="dash-kpi-card__sub">vs ayer</div>
            </div>

            <div class="dash-kpi-card">
              <div class="dash-kpi-card__top">
                <div class="dash-kpi-card__icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </div>
                <span class="dash-kpi-badge dash-kpi-badge--warn">3 en diag.</span>
              </div>
              <div class="dash-kpi-card__label">Pendientes</div>
              <div class="dash-kpi-card__value"><?= $tickets_pendientes ?></div>
              <div class="dash-kpi-card__sub">requieren atención</div>
            </div>

            <div class="dash-kpi-card">
              <div class="dash-kpi-card__top">
                <div class="dash-kpi-card__icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <span class="dash-kpi-badge dash-kpi-badge--up">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
                  6.2%
                </span>
              </div>
              <div class="dash-kpi-card__label">Completados</div>
              <div class="dash-kpi-card__value"><?= $tickets_completados ?></div>
              <div class="dash-kpi-card__sub">este mes</div>
            </div>
          </div><!-- /kpi-row -->

          <!-- Tickets recientes -->
          <div class="dash-panel-block">
            <div class="dash-panel-block__head">
              <div class="dash-panel-block__title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
                Tickets recientes
              </div>
              <a href="tickets.php" class="dash-panel-block__link">Ver todos →</a>
            </div>
            <div style="overflow-x:auto;">
              <table class="dash-admin-table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Servicio</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                  </tr>
                </thead>
                <tbody>
                <?php foreach ($tickets_recientes as $t): ?>
                <tr>
                  <td><span class="dash-admin-t-id">#<?= htmlspecialchars($t['id']) ?></span></td>
                  <td><span class="dash-admin-t-cliente"><?= htmlspecialchars($t['cliente']) ?></span></td>
                  <td><span class="dash-admin-t-svc"><?= htmlspecialchars($t['servicio']) ?></span></td>
                  <td><span class="dash-admin-badge <?= clase_estado_dash($t['estado']) ?>"><?= htmlspecialchars($t['estado']) ?></span></td>
                  <td><span class="dash-admin-t-fecha"><?= htmlspecialchars($t['fecha']) ?></span></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Bottom row: stock + donut -->
          <div class="dash-bottom-row">
            <!-- Stock bajo -->
            <div class="dash-panel-block">
              <div class="dash-panel-block__head">
                <div class="dash-panel-block__title">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                  Stock bajo
                </div>
                <a href="inventario.php" class="dash-panel-block__link">Gestionar →</a>
              </div>
              <?php foreach ($stock_alerta as $s): ?>
              <div class="dash-stock-item">
                <div>
                  <div class="dash-stock-item__name"><?= htmlspecialchars($s['nombre']) ?></div>
                  <div class="dash-stock-item__min">Mín. <?= $s['minimo'] ?> uds.</div>
                </div>
                <span class="dash-stock-val <?= $s['clase'] ?>"><?= $s['stock'] ?> uds.</span>
              </div>
              <?php endforeach; ?>
            </div>

            <!-- Donut servicios -->
            <div class="dash-panel-block">
              <div class="dash-panel-block__head">
                <div class="dash-panel-block__title">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                  Servicios solicitados
                </div>
              </div>
              <div class="dash-donut-wrap">
                <svg class="dash-donut-svg" viewBox="0 0 36 36">
                  <circle cx="18" cy="18" r="15.9" fill="none" stroke="#f0f2f8" stroke-width="3.5"/>
                  <circle cx="18" cy="18" r="15.9" fill="none" stroke="#1746EA" stroke-width="3.5" stroke-dasharray="35 65" stroke-dashoffset="25" stroke-linecap="round"/>
                  <circle cx="18" cy="18" r="15.9" fill="none" stroke="#1883ED" stroke-width="3.5" stroke-dasharray="28 72" stroke-dashoffset="-10" stroke-linecap="round"/>
                  <circle cx="18" cy="18" r="15.9" fill="none" stroke="#f5a623" stroke-width="3.5" stroke-dasharray="20 80" stroke-dashoffset="-38" stroke-linecap="round"/>
                  <circle cx="18" cy="18" r="15.9" fill="none" stroke="#1a7a4a" stroke-width="3.5" stroke-dasharray="17 83" stroke-dashoffset="-58" stroke-linecap="round"/>
                  <text x="18" y="19.5" text-anchor="middle" font-size="5" font-weight="800" fill="#000019" font-family="Montserrat,sans-serif">May 2025</text>
                </svg>
                <div class="dash-donut-legend">
                  <div class="dash-legend-item"><span class="dash-legend-dot" style="background:#1746EA"></span>Mantenimiento<span class="dash-legend-pct">35%</span></div>
                  <div class="dash-legend-item"><span class="dash-legend-dot" style="background:#1883ED"></span>Reparación<span class="dash-legend-pct">28%</span></div>
                  <div class="dash-legend-item"><span class="dash-legend-dot" style="background:#f5a623"></span>Formateo<span class="dash-legend-pct">20%</span></div>
                  <div class="dash-legend-item"><span class="dash-legend-dot" style="background:#1a7a4a"></span>Limpieza<span class="dash-legend-pct">17%</span></div>
                </div>
              </div>
            </div>
          </div><!-- /bottom-row -->

        </div><!-- /col-left -->

        <!-- ── COLUMNA DERECHA ── -->
        <div class="dash-col-right">

          <!-- Welcome card -->
          <div class="dash-welcome-card">
            <div class="dash-welcome-card__avatar"><?= $inicial ?></div>
            <div class="dash-welcome-card__greeting"><?= $saludo ?>,</div>
            <div class="dash-welcome-card__name"><?= htmlspecialchars($nombre_display . ' ' . $apellido_display) ?></div>
            <div class="dash-welcome-card__sub">Tienes <?= $tickets_pendientes ?> tickets pendientes. Revisa la tabla para ver el detalle.</div>
            <div class="dash-welcome-card__date">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              <?= date('d M Y') ?>
            </div>
            <a href="nuevo_ticket.php" class="dash-welcome-card__btn">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              Nuevo ticket
            </a>
          </div>

          <!-- Resumen del día -->
          <div class="dash-panel-block">
            <div class="dash-panel-block__head">
              <div class="dash-panel-block__title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Resumen del día
              </div>
            </div>
            <div class="dash-day-summary">
              <div class="dash-day-item">
                <div class="dash-day-item__label">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
                  Tickets ingresados
                </div>
                <div class="dash-day-item__val"><?= $tickets_hoy ?></div>
              </div>
              <div class="dash-day-item">
                <div class="dash-day-item__label">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                  Completados hoy
                </div>
                <div class="dash-day-item__val dash-day-item__val--green">2</div>
              </div>
              <div class="dash-day-item">
                <div class="dash-day-item__label">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                  Ingresos hoy
                </div>
                <div class="dash-day-item__val dash-day-item__val--green">S/ 320</div>
              </div>
              <div class="dash-day-item">
                <div class="dash-day-item__label">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg>
                  Alertas de stock
                </div>
                <div class="dash-day-item__val dash-day-item__val--warn">3</div>
              </div>
            </div>
          </div>

        </div><!-- /col-right -->

      </div><!-- /dash-layout -->
    </main>
  </div><!-- /dash-main -->

</div><!-- /dash-shell -->

<script src="script.js" defer></script>
</body>
</html>
