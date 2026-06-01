<?php
// session_start();
// include("includes/auth.php");
$nombre_usuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Juan';
$rol_usuario    = 'Trabajador';
$partes         = explode(' ', trim($nombre_usuario));
$inicial        = strtoupper(substr($partes[0], 0, 1));
$nombre_corto   = $partes[0];

$ventas = [
    ["id"=>"VT-0041","fecha"=>"2025-05-20","cliente"=>"Valeria Ramírez", "ticket"=>"MT-8842","servicio"=>"Mantenimiento correctivo",     "productos"=>"SSD 512GB NVMe",              "total"=>355.00,"metodo"=>"Yape"],
    ["id"=>"VT-0042","fecha"=>"2025-05-21","cliente"=>"Andrés Ochante",  "ticket"=>"MT-8843","servicio"=>"Repotenciación (mano de obra)","productos"=>"—",                           "total"=>50.00, "metodo"=>"Transferencia"],
    ["id"=>"VT-0043","fecha"=>"2025-05-22","cliente"=>"Diana Calderón",  "ticket"=>"MT-8844","servicio"=>"Diagnóstico",                  "productos"=>"RAM DDR4 16GB",               "total"=>175.00,"metodo"=>"Yape"],
    ["id"=>"VT-0044","fecha"=>"2025-05-22","cliente"=>"Brenda Benites",  "ticket"=>null,     "servicio"=>"—",                           "productos"=>"Kit Destornilladores 128 en 1","total"=>95.00, "metodo"=>"Efectivo"],
];
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

      <!-- KPI cards — reutiliza .dash-kpi-row -->
      <div class="dash-kpi-row" style="margin-bottom:24px;">
        <div class="dash-kpi-card dash-kpi-card--highlight">
          <div class="dash-kpi-card__top">
            <div class="dash-kpi-card__icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <span class="dash-kpi-badge dash-kpi-badge--up">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
              2.4% vs mes ant.
            </span>
          </div>
          <div class="dash-kpi-card__label">Ingresos del mes</div>
          <div class="dash-kpi-card__value">S/ 675</div>
          <div class="dash-kpi-card__sub">Mayo 2025</div>
        </div>
        <div class="dash-kpi-card">
          <div class="dash-kpi-card__top">
            <div class="dash-kpi-card__icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            </div>
            <span class="dash-kpi-badge dash-kpi-badge--up">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
              6.2%
            </span>
          </div>
          <div class="dash-kpi-card__label">Ventas este mes</div>
          <div class="dash-kpi-card__value">4</div>
          <div class="dash-kpi-card__sub">vs mes anterior</div>
        </div>
        <div class="dash-kpi-card">
          <div class="dash-kpi-card__top">
            <div class="dash-kpi-card__icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
            </div>
            <span class="dash-kpi-badge dash-kpi-badge--neutral">sin cambio</span>
          </div>
          <div class="dash-kpi-card__label">Ticket promedio</div>
          <div class="dash-kpi-card__value">S/ 168</div>
          <div class="dash-kpi-card__sub">por venta</div>
        </div>
        <div class="dash-kpi-card">
          <div class="dash-kpi-card__top">
            <div class="dash-kpi-card__icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <span class="dash-kpi-badge dash-kpi-badge--up">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
              0.8%
            </span>
          </div>
          <div class="dash-kpi-card__label">Clientes atendidos</div>
          <div class="dash-kpi-card__value">4</div>
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
              <div class="vt-chart-sub" id="vt-chart-range-label">Ene 2025 — May 2025</div>
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
                <circle cx="55" cy="55" r="44" fill="none" stroke="#000019" stroke-width="12"
                  stroke-dasharray="138.23 276.46" stroke-dashoffset="0" stroke-linecap="round"/>
                <circle cx="55" cy="55" r="44" fill="none" stroke="#1883ED" stroke-width="12"
                  stroke-dasharray="69.11 276.46" stroke-dashoffset="-138.23" stroke-linecap="round"/>
                <circle cx="55" cy="55" r="44" fill="none" stroke="#1746EA" stroke-width="12"
                  stroke-dasharray="69.11 276.46" stroke-dashoffset="-207.34" stroke-linecap="round"/>
              </svg>
              <div class="vt-donut-center">
                <span class="vt-donut-center__val">S/ 675</span>
                <span class="vt-donut-center__lbl">total</span>
              </div>
            </div>
            <div class="vt-donut-legend">
              <div class="vt-leg-row"><div class="vt-leg-dot" style="background:#000019"></div><span class="vt-leg-name">Yape</span><span class="vt-leg-val">S/ 530</span><span class="vt-leg-pct">50%</span></div>
              <div class="vt-leg-row"><div class="vt-leg-dot" style="background:#1883ED"></div><span class="vt-leg-name">Transferencia</span><span class="vt-leg-val">S/ 50</span><span class="vt-leg-pct">25%</span></div>
              <div class="vt-leg-row"><div class="vt-leg-dot" style="background:#1746EA"></div><span class="vt-leg-name">Efectivo</span><span class="vt-leg-val">S/ 95</span><span class="vt-leg-pct">25%</span></div>
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
            <?php foreach ($ventas as $v):
              $tipo = $v['ticket'] ? 'ticket' : 'producto';
            ?>
            <tr data-tipo="<?= $tipo ?>"
                data-search="<?= strtolower(htmlspecialchars($v['cliente'].' '.$v['id'])) ?>">
              <td><span class="dash-admin-t-id">#<?= htmlspecialchars($v['id']) ?></span></td>
              <td>
                <div class="vt-cliente-nombre"><?= htmlspecialchars($v['cliente']) ?></div>
                <?php if ($v['ticket']): ?>
                <div class="vt-cliente-ticket">Ticket #<?= htmlspecialchars($v['ticket']) ?></div>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($v['servicio'] !== '—'): ?>
                <div class="vt-detalle-svc"><?= htmlspecialchars($v['servicio']) ?></div>
                <?php endif; ?>
                <?php if ($v['productos'] !== '—'): ?>
                <div class="vt-detalle-prod"><?= htmlspecialchars($v['productos']) ?></div>
                <?php endif; ?>
              </td>
              <td><span class="vt-metodo"><?= htmlspecialchars($v['metodo']) ?></span></td>
              <td><span class="vt-total">S/ <?= number_format($v['total'],2) ?></span></td>
              <td><span class="dash-admin-t-fecha"><?= date('d/m/Y', strtotime($v['fecha'])) ?></span></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <div class="dash-table-footer">
          <span id="vt-footer-count">4 ventas</span>
        </div>
      </div>

    </main>
  </div><!-- /dash-main -->

</div><!-- /dash-shell -->

<script src="script.js" defer></script>
</body>
</html>
