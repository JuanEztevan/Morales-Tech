<?php
require_once 'admin_protect.php';
require_once 'conexion.php';

// Admin header
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

// KPIs — mes actual
$kpi = $conn->query("
    SELECT COUNT(*) AS cnt,
           COALESCE(SUM(total),0) AS sum_total,
           COALESCE(AVG(total),0) AS avg_total,
           COUNT(DISTINCT COALESCE(nombreCliente,'')) AS clientes
    FROM VENTA
    WHERE YEAR(fechaVenta)=YEAR(NOW()) AND MONTH(fechaVenta)=MONTH(NOW())
")->fetch_assoc();
$ingresos_mes    = (float)$kpi['sum_total'];
$ventas_mes      = (int)$kpi['cnt'];
$ticket_promedio = (float)$kpi['avg_total'];
$clientes_mes    = (int)$kpi['clientes'];

// KPIs — mes anterior (para % cambio)
$kpi_prev = $conn->query("
    SELECT COALESCE(SUM(total),0) AS sum_total, COUNT(*) AS cnt
    FROM VENTA
    WHERE YEAR(fechaVenta)=YEAR(NOW() - INTERVAL 1 MONTH)
      AND MONTH(fechaVenta)=MONTH(NOW() - INTERVAL 1 MONTH)
")->fetch_assoc();
$ingresos_prev = (float)$kpi_prev['sum_total'];
$ventas_prev   = (int)$kpi_prev['cnt'];

function pct_change($curr, $prev) {
    if ($prev == 0) return $curr > 0 ? 100 : 0;
    return round(($curr - $prev) / $prev * 100, 1);
}
$pct_ingresos = pct_change($ingresos_mes, $ingresos_prev);
$pct_ventas   = pct_change($ventas_mes,   $ventas_prev);

// Métodos de pago — mes actual (para donut)
$res_metodos = $conn->query("
    SELECT metodoPago, COUNT(*) AS cnt, COALESCE(SUM(total),0) AS suma
    FROM VENTA
    WHERE YEAR(fechaVenta)=YEAR(NOW()) AND MONTH(fechaVenta)=MONTH(NOW())
    GROUP BY metodoPago ORDER BY suma DESC
");
$metodos = [];
while ($r = $res_metodos->fetch_assoc()) $metodos[] = $r;

// Ventas por mes — para gráfico de barras
$res_meses = $conn->query("
    SELECT YEAR(fechaVenta) AS yr, MONTH(fechaVenta) AS mo, SUM(total) AS val
    FROM VENTA GROUP BY yr, mo ORDER BY yr, mo
");
$ventas_por_mes = [];
while ($r = $res_meses->fetch_assoc())
    $ventas_por_mes[] = ['yr'=>(int)$r['yr'],'mo'=>(int)$r['mo'],'val'=>round((float)$r['val'],2)];

// Lista de ventas para la tabla
$res_ventas = $conn->query("
    SELECT v.idVenta, v.nombreCliente, v.metodoPago,
           v.total, v.fechaVenta,
           COALESCE(v.tipo,'ticket') AS tipo,
           t.codigo AS ticketCodigo,
           (SELECT GROUP_CONCAT(c.nombre ORDER BY c.nombre SEPARATOR ', ')
            FROM DETALLE_VENTA dv JOIN COMPONENTE c ON c.idComponente=dv.idComponente
            WHERE dv.idVenta=v.idVenta) AS productos,
           (SELECT s.nomServicio
            FROM COTIZACION_SERVICIO cs JOIN SERVICIO s ON s.idServicio=cs.idServicio
            WHERE cs.idCotizacion=cot.idCotizacion AND s.tipo='Principal'
            LIMIT 1) AS servicio
    FROM VENTA v
    JOIN TICKET t   ON t.idTicket     = v.idTicket
    JOIN COTIZACION cot ON cot.idCotizacion = t.idCotizacion
    ORDER BY v.fechaVenta DESC
");
$ventas = [];
while ($r = $res_ventas->fetch_assoc()) $ventas[] = $r;

// Donut — colores y cálculo de arcos SVG
$METODO_COLORES = [
    'Yape'          => '#000019',
    'Transferencia' => '#1883ED',
    'Efectivo'      => '#1746EA',
];
$COLOR_DEFAULT = '#6c757d';
$CIRC          = 276.46; // 2 * pi * 44
$total_metodos = (float)array_sum(array_column($metodos, 'suma'));

function donut_circles($metodos, $colores, $default_color, $circ, $total) {
    if ($total <= 0) return '';
    $out = ''; $offset = 0;
    foreach ($metodos as $m) {
        $color = $colores[$m['metodoPago']] ?? $default_color;
        $arc   = round(((float)$m['suma'] / $total) * $circ, 2);
        $gap   = round($circ - $arc, 2);
        $off   = round(-$offset, 2);
        $out  .= "<circle cx=\"55\" cy=\"55\" r=\"44\" fill=\"none\" stroke=\"{$color}\" stroke-width=\"12\"
                  stroke-dasharray=\"{$arc} {$gap}\" stroke-dashoffset=\"{$off}\" stroke-linecap=\"round\"/>\n";
        $offset += $arc;
    }
    return $out;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ventas — Morales Tech</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
  <link rel="icon" type="image/png" href="img/isotipo-color.png" media="(prefers-color-scheme: light)">
  <link rel="icon" type="image/png" href="img/isotipo-blanco.png" media="(prefers-color-scheme: dark)">
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
      <a href="tickets.php" class="dash-nav-link">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
        <span>Tickets</span>
      </a>
      <a href="inventario.php" class="dash-nav-link">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
        <span>Inventario</span>
      </a>
      <a href="ventas.php" class="dash-nav-link dash-nav-link--active">
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
      <div class="dash-header__breadcrumb">Panel / <span>Ventas</span></div>
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
          <h1 class="tk-page-title">Ventas</h1>
          <p class="tk-page-subtitle">Historial de ingresos por servicios y productos</p>
        </div>
      </div>

      <!-- KPI cards -->
      <div class="dash-kpi-row" style="margin-bottom:24px;">

        <!-- Ingresos del mes -->
        <div class="dash-kpi-card dash-kpi-card--highlight">
          <div class="dash-kpi-card__top">
            <div class="dash-kpi-card__icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <?php if ($pct_ingresos > 0): ?>
            <span class="dash-kpi-badge dash-kpi-badge--up">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
              <?= $pct_ingresos ?>% vs mes ant.
            </span>
            <?php elseif ($pct_ingresos < 0): ?>
            <span class="dash-kpi-badge dash-kpi-badge--down">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
              <?= abs($pct_ingresos) ?>% vs mes ant.
            </span>
            <?php else: ?>
            <span class="dash-kpi-badge dash-kpi-badge--neutral">sin cambio</span>
            <?php endif; ?>
          </div>
          <div class="dash-kpi-card__label">Ingresos del mes</div>
          <div class="dash-kpi-card__value">S/ <?= number_format($ingresos_mes, 0, '.', ',') ?></div>
          <div class="dash-kpi-card__sub"><?= date('F Y') ?></div>
        </div>

        <!-- Ventas este mes -->
        <div class="dash-kpi-card">
          <div class="dash-kpi-card__top">
            <div class="dash-kpi-card__icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            </div>
            <?php if ($pct_ventas > 0): ?>
            <span class="dash-kpi-badge dash-kpi-badge--up">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
              <?= $pct_ventas ?>%
            </span>
            <?php elseif ($pct_ventas < 0): ?>
            <span class="dash-kpi-badge dash-kpi-badge--down">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
              <?= abs($pct_ventas) ?>%
            </span>
            <?php else: ?>
            <span class="dash-kpi-badge dash-kpi-badge--neutral">sin cambio</span>
            <?php endif; ?>
          </div>
          <div class="dash-kpi-card__label">Ventas este mes</div>
          <div class="dash-kpi-card__value"><?= $ventas_mes ?></div>
          <div class="dash-kpi-card__sub">vs mes anterior</div>
        </div>

        <!-- Ticket promedio -->
        <div class="dash-kpi-card">
          <div class="dash-kpi-card__top">
            <div class="dash-kpi-card__icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
            </div>
            <span class="dash-kpi-badge dash-kpi-badge--neutral">este mes</span>
          </div>
          <div class="dash-kpi-card__label">Ticket promedio</div>
          <div class="dash-kpi-card__value">S/ <?= number_format($ticket_promedio, 0, '.', ',') ?></div>
          <div class="dash-kpi-card__sub">por venta</div>
        </div>

        <!-- Clientes atendidos -->
        <div class="dash-kpi-card">
          <div class="dash-kpi-card__top">
            <div class="dash-kpi-card__icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <span class="dash-kpi-badge dash-kpi-badge--neutral">este mes</span>
          </div>
          <div class="dash-kpi-card__label">Clientes atendidos</div>
          <div class="dash-kpi-card__value"><?= $clientes_mes ?></div>
          <div class="dash-kpi-card__sub">este mes</div>
        </div>

      </div>

      <!-- Gráfico + donut -->
      <div class="vt-charts-row">

        <!-- Gráfico de barras -->
        <div class="dash-panel-block" style="padding:22px 24px 24px;">
          <div class="vt-chart-header">
            <div>
              <div class="vt-chart-title">Resumen de ventas</div>
              <div class="vt-chart-sub" id="vt-chart-range-label"></div>
            </div>
            <div class="vt-period-selector">
              <button class="vt-period-btn active" onclick="vtSetPeriod('mes',this)">Mes</button>
              <button class="vt-period-btn" onclick="vtSetPeriod('quincena',this)">Quincena</button>
              <button class="vt-period-btn" onclick="vtSetPeriod('año',this)">Año</button>
            </div>
          </div>
          <div class="vt-chart-inner">
            <div class="vt-y-axis" id="vt-y-axis"></div>
            <div class="vt-bars-wrapper">
              <div class="vt-bars-nav-row">
                <button class="vt-chart-nav" id="vt-btn-prev" onclick="vtNavChart(-1)">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                </button>
                <div class="vt-bars-area" id="vt-bars-area"></div>
                <button class="vt-chart-nav" id="vt-btn-next" onclick="vtNavChart(1)">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Métodos de pago + botón -->
        <div class="dash-panel-block" style="padding:22px 24px 24px;">
          <div class="vt-chart-title" style="margin-bottom:4px;">Métodos de pago</div>
          <div class="vt-chart-sub" style="margin-bottom:18px;">Distribución del mes actual</div>
          <div class="vt-donut-wrap">
            <div class="vt-donut-svg-wrap">
              <svg viewBox="0 0 110 110">
                <circle cx="55" cy="55" r="44" fill="none" stroke="#e6e9f0" stroke-width="12"/>
                <?php if ($total_metodos > 0): ?>
                <?= donut_circles($metodos, $METODO_COLORES, $COLOR_DEFAULT, $CIRC, $total_metodos) ?>
                <?php endif; ?>
              </svg>
              <div class="vt-donut-center">
                <?php if ($total_metodos > 0): ?>
                <span class="vt-donut-center__val">S/ <?= number_format($total_metodos, 0, '.', ',') ?></span>
                <span class="vt-donut-center__lbl">total</span>
                <?php else: ?>
                <span class="vt-donut-center__lbl" style="font-size:10px;text-align:center;">Sin ventas<br>este mes</span>
                <?php endif; ?>
              </div>
            </div>
            <div class="vt-donut-legend">
              <?php if (empty($metodos)): ?>
              <div class="vt-leg-row" style="color:var(--color-text-muted);font-size:12px;">Sin datos este mes</div>
              <?php else: foreach ($metodos as $m):
                $color = $METODO_COLORES[$m['metodoPago']] ?? $COLOR_DEFAULT;
                $pct   = $total_metodos > 0 ? round(($m['suma'] / $total_metodos) * 100) : 0;
              ?>
              <div class="vt-leg-row">
                <div class="vt-leg-dot" style="background:<?= $color ?>"></div>
                <span class="vt-leg-name"><?= htmlspecialchars($m['metodoPago']) ?></span>
                <span class="vt-leg-val">S/ <?= number_format($m['suma'], 0, '.', ',') ?></span>
                <span class="vt-leg-pct"><?= $pct ?>%</span>
              </div>
              <?php endforeach; endif; ?>
            </div>
          </div>
          <a href="nueva_venta.php" class="dash-btn-new" style="width:100%;justify-content:center;margin-top:20px;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Registrar venta
          </a>
        </div>

      </div>

      <!-- Filtros y tabla -->
      <div class="inv-filters-bar">
        <div class="inv-filter-tabs">
          <button class="inv-filter-tab active" onclick="vtFiltrar('Todos',this)">Todas</button>
          <button class="inv-filter-tab" onclick="vtFiltrar('ticket',this)">Con ticket</button>
          <button class="inv-filter-tab" onclick="vtFiltrar('producto',this)">Solo producto</button>
        </div>
        <div class="inv-search-box">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input type="text" placeholder="Buscar cliente o ID…" oninput="vtBuscar(this.value)">
        </div>
      </div>

      <div class="dash-panel-block" style="padding:0;overflow:hidden;">
        <div class="dash-table-wrap">
          <table class="dash-admin-table" style="min-width:580px;">
            <thead>
              <tr>
                <th>ID Venta</th>
                <th>Cliente</th>
                <th>Servicio / Producto</th>
                <th>Método</th>
                <th>Total</th>
                <th>Fecha</th>
              </tr>
            </thead>
            <tbody id="vt-tbody">
            <?php if (empty($ventas)): ?>
            <tr><td colspan="6">
              <div class="dash-empty">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <p>No hay ventas registradas</p>
                <small>Registra la primera venta usando el botón de arriba</small>
              </div>
            </td></tr>
            <?php else: foreach ($ventas as $v):
              $vid   = 'VT-' . str_pad($v['idVenta'], 4, '0', STR_PAD_LEFT);
              $tipo  = $v['tipo'] ?? 'ticket';
              $svcs  = trim($v['servicio'] ?? '');
              $prods = trim($v['productos'] ?? '');
            ?>
            <tr data-tipo="<?= htmlspecialchars($tipo) ?>"
                data-search="<?= strtolower(htmlspecialchars(($v['nombreCliente'] ?? '') . ' ' . $vid)) ?>">
              <td><span class="dash-admin-t-id">#<?= htmlspecialchars($vid) ?></span></td>
              <td>
                <div class="vt-cliente-nombre"><?= htmlspecialchars($v['nombreCliente'] ?? '—') ?></div>
                <?php if ($tipo === 'ticket' && $v['ticketCodigo']): ?>
                <div class="vt-cliente-ticket">Ticket #<?= htmlspecialchars($v['ticketCodigo']) ?></div>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($svcs): ?>
                <div class="vt-detalle-svc"><?= htmlspecialchars($svcs) ?></div>
                <?php endif; ?>
                <?php if ($prods): ?>
                <div class="vt-detalle-prod"><?= htmlspecialchars($prods) ?></div>
                <?php endif; ?>
                <?php if (!$svcs && !$prods): ?><span style="color:var(--color-text-muted)">—</span><?php endif; ?>
              </td>
              <td><span class="vt-metodo"><?= htmlspecialchars($v['metodoPago'] ?? '—') ?></span></td>
              <td><span class="vt-total">S/ <?= number_format((float)$v['total'], 2) ?></span></td>
              <td><span class="dash-admin-t-fecha"><?= date('d/m/Y', strtotime($v['fechaVenta'])) ?></span></td>
            </tr>
            <?php endforeach; endif; ?>
            </tbody>
          </table>
        </div>
        <div class="dash-table-footer">
          <span id="vt-footer-count"><?= count($ventas) ?> venta<?= count($ventas) !== 1 ? 's' : '' ?></span>
        </div>
      </div>

    </main>
  </div><!-- /dash-main -->

</div><!-- /dash-shell -->

<script>
window._vtVentasPorMes = <?= json_encode($ventas_por_mes) ?>;
</script>
<script src="script.js" defer></script>
</body>
</html>
