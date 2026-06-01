<?php
// session_start();
// include("includes/auth.php");
$nombre_usuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Juan';
$rol_usuario    = 'Trabajador';
$partes         = explode(' ', trim($nombre_usuario));
$inicial        = strtoupper(substr($partes[0], 0, 1));
$nombre_corto   = $partes[0];

$productos = [
    ["id"=>1,"nombre"=>"Alcohol Isopropílico 1000ml",              "categoria"=>"Consumibles y Limpieza","precio"=>25, "stock"=>45],
    ["id"=>2,"nombre"=>"Pasta Térmica (jeringa 5g)",                "categoria"=>"Consumibles y Limpieza","precio"=>18, "stock"=>30],
    ["id"=>3,"nombre"=>"Kit Destornilladores 58 en 1",              "categoria"=>"Herramientas y Kits",   "precio"=>65, "stock"=>18],
    ["id"=>4,"nombre"=>"Kit Destornilladores de Precisión 128 en 1","categoria"=>"Herramientas y Kits",   "precio"=>95, "stock"=>12],
    ["id"=>5,"nombre"=>"SSD 1TB SATA",                              "categoria"=>"Almacenamiento",        "precio"=>195,"stock"=>10],
    ["id"=>6,"nombre"=>"SSD 512GB NVMe",                            "categoria"=>"Almacenamiento",        "precio"=>165,"stock"=>14],
    ["id"=>7,"nombre"=>"RAM DDR4 16GB 3200MHz",                     "categoria"=>"Memoria RAM",            "precio"=>145,"stock"=>20],
    ["id"=>8,"nombre"=>"RAM DDR5 16GB 4800MHz",                     "categoria"=>"Memoria RAM",            "precio"=>185,"stock"=>9],
];

function inv_cat_class($cat) {
    return match($cat) {
        'Consumibles y Limpieza' => 'inv-cat--consumibles',
        'Herramientas y Kits'    => 'inv-cat--herramientas',
        'Almacenamiento'         => 'inv-cat--almacenamiento',
        'Memoria RAM'            => 'inv-cat--ram',
        default                  => ''
    };
}
function inv_stock_class($stock) {
    return $stock >= 20 ? 'inv-stock--ok' : ($stock >= 10 ? 'inv-stock--low' : 'inv-stock--min');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inventario — Morales Tech</title>
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
      <a href="inventario.php" class="dash-nav-link dash-nav-link--active">
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
      <div class="dash-header__breadcrumb">Panel / <span>Inventario</span></div>
      <div class="dash-header__user">
        <div class="dash-header__avatar"><?= $inicial ?></div>
        <div class="dash-header__user-info">
          <span class="dash-header__username"><?= htmlspecialchars($nombre_corto) ?></span>
          <span class="dash-header__role"><?= htmlspecialchars($rol_usuario) ?></span>
        </div>
      </div>
    </header>

    <main class="dash-page-content">
      <div class="tk-page-header">
        <div>
          <h1 class="tk-page-title">Inventario</h1>
          <p class="tk-page-subtitle">Gestiona el stock de productos y materiales</p>
        </div>
        <button class="dash-btn-new" onclick="invAbrirModal()">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Nuevo producto
        </button>
      </div>

      <!-- KPI cards -->
      <div class="dash-kpi-row" style="margin-bottom:24px;">
        <div class="dash-kpi-card dash-kpi-card--highlight">
          <div class="dash-kpi-card__top">
            <div class="dash-kpi-card__icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
            </div>
            <span class="dash-kpi-badge dash-kpi-badge--neutral">8 registros</span>
          </div>
          <div class="dash-kpi-card__label">Productos en catálogo</div>
          <div class="dash-kpi-card__value">8</div>
          <div class="dash-kpi-card__sub">4 categorías activas</div>
        </div>
        <div class="dash-kpi-card">
          <div class="dash-kpi-card__top">
            <div class="dash-kpi-card__icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            </div>
            <span class="dash-kpi-badge dash-kpi-badge--up">en stock</span>
          </div>
          <div class="dash-kpi-card__label">Unidades totales</div>
          <div class="dash-kpi-card__value">158</div>
          <div class="dash-kpi-card__sub">suma de todos los productos</div>
        </div>
        <div class="dash-kpi-card">
          <div class="dash-kpi-card__top">
            <div class="dash-kpi-card__icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            </div>
            <span class="dash-kpi-badge dash-kpi-badge--warn">requieren atención</span>
          </div>
          <div class="dash-kpi-card__label">Stock bajo</div>
          <div class="dash-kpi-card__value">3</div>
          <div class="dash-kpi-card__sub">menos de 15 unidades</div>
        </div>
        <div class="dash-kpi-card">
          <div class="dash-kpi-card__top">
            <div class="dash-kpi-card__icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <span class="dash-kpi-badge dash-kpi-badge--up">valorizado</span>
          </div>
          <div class="dash-kpi-card__label">Valor del stock</div>
          <div class="dash-kpi-card__value">S/ 9,935</div>
          <div class="dash-kpi-card__sub">a precio de venta</div>
        </div>
      </div>

      <!-- Filtros y buscador -->
      <div class="inv-filters-bar">
        <div class="inv-filter-tabs">
          <a href="#" class="inv-filter-tab active" onclick="invFiltrar(event,'Todos')">Todos</a>
          <a href="#" class="inv-filter-tab" onclick="invFiltrar(event,'Consumibles y Limpieza')">Consumibles</a>
          <a href="#" class="inv-filter-tab" onclick="invFiltrar(event,'Herramientas y Kits')">Herramientas</a>
          <a href="#" class="inv-filter-tab" onclick="invFiltrar(event,'Almacenamiento')">Almacenamiento</a>
          <a href="#" class="inv-filter-tab" onclick="invFiltrar(event,'Memoria RAM')">Memoria RAM</a>
        </div>
        <div class="inv-search-box">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input type="text" id="inv-search-input" placeholder="Buscar producto…" oninput="invBuscar(this.value)">
        </div>
      </div>

      <!-- Tabla -->
      <div class="dash-panel-block">
        <div class="dash-table-wrap">
          <table class="dash-admin-table" style="min-width:700px;">
            <thead>
              <tr>
                <th>Producto</th>
                <th>Categoría</th>
                <th>Precio unit.</th>
                <th>Stock</th>
                <th>Cantidad</th>
                <th></th>
              </tr>
            </thead>
            <tbody id="inv-tbody">
            <?php foreach ($productos as $p):
              $stock_class = inv_stock_class($p['stock']);
              $cat_class   = inv_cat_class($p['categoria']);
              $cat = $p['categoria'];
            ?>
            <tr data-cat="<?= htmlspecialchars($cat) ?>"
                data-nombre="<?= strtolower(htmlspecialchars($p['nombre'])) ?>">
              <td>
                <div class="inv-prod-cell">
                  <div class="inv-prod-icon">
                    <?php
                    if ($cat === 'Consumibles y Limpieza')
                      echo '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>';
                    elseif ($cat === 'Herramientas y Kits')
                      echo '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>';
                    elseif ($cat === 'Almacenamiento')
                      echo '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg>';
                    else
                      echo '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="2" y1="10" x2="22" y2="10"/></svg>';
                    ?>
                  </div>
                  <div>
                    <div class="inv-prod-name"><?= htmlspecialchars($p['nombre']) ?></div>
                    <div class="inv-prod-id">#<?= str_pad($p['id'],4,'0',STR_PAD_LEFT) ?></div>
                  </div>
                </div>
              </td>
              <td><span class="inv-cat-badge <?= $cat_class ?>"><?= htmlspecialchars($p['categoria']) ?></span></td>
              <td><span class="inv-precio">S/ <?= number_format($p['precio'],2) ?></span></td>
              <td><span class="inv-stock-badge <?= $stock_class ?>" id="inv-stock-label-<?= $p['id'] ?>"><?= $p['stock'] ?> uds.</span></td>
              <td>
                <div class="inv-qty-control">
                  <button class="inv-qty-btn" onclick="invCambiarQty(<?= $p['id'] ?>,-1)">−</button>
                  <span class="inv-qty-num" id="inv-qty-<?= $p['id'] ?>"><?= $p['stock'] ?></span>
                  <button class="inv-qty-btn" onclick="invCambiarQty(<?= $p['id'] ?>,1)">+</button>
                </div>
              </td>
              <td>
                <button class="inv-btn-update" onclick="invGuardarCambio(<?= $p['id'] ?>, event)">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                  Actualizar
                </button>
              </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <div class="dash-table-footer" style="justify-content:space-between;display:flex;align-items:center;">
          <span id="inv-footer-count">8 productos</span>
          <div class="inv-pagination">
            <button class="inv-pag-btn" id="inv-btn-prev" onclick="invCambiarPagina(-1)" disabled>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
              Anterior
            </button>
            <span class="inv-pag-info" id="inv-pag-info">Pág. 1 de 1</span>
            <button class="inv-pag-btn" id="inv-btn-next" onclick="invCambiarPagina(1)" disabled>
              Siguiente
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
          </div>
        </div>
      </div>

    </main>
  </div><!-- /dash-main -->

</div><!-- /dash-shell -->

<!-- MODAL NUEVO PRODUCTO -->
<div class="inv-modal-overlay" id="inv-modal-overlay" onclick="invCerrarOverlay(event)">
  <div class="inv-modal">
    <div class="inv-modal__header">
      <div class="inv-modal__icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
      </div>
      <div>
        <div class="inv-modal__title">Nuevo Producto</div>
        <div class="inv-modal__sub">Añadir al inventario</div>
      </div>
      <button class="inv-modal__close" onclick="invCerrarModal()">×</button>
    </div>
    <div class="inv-modal-form">
      <div class="inv-modal-field inv-modal-field--full">
        <label>Nombre del producto</label>
        <input type="text" id="inv-m-nombre" placeholder="Ej. Cable HDMI 2m" class="ntk-input">
      </div>
      <div class="inv-modal-field inv-modal-field--full">
        <label>Categoría</label>
        <select id="inv-m-categoria" class="ntk-input ntk-select">
          <option value="">Seleccionar…</option>
          <option>Consumibles y Limpieza</option>
          <option>Herramientas y Kits</option>
          <option>Almacenamiento</option>
          <option>Memoria RAM</option>
        </select>
      </div>
      <div class="inv-modal-field">
        <label>Precio unitario (S/)</label>
        <input type="text" id="inv-m-precio" placeholder="Ej. 45.00" class="ntk-input">
      </div>
      <div class="inv-modal-field">
        <label>Stock inicial</label>
        <input type="text" id="inv-m-stock" placeholder="Ej. 20" class="ntk-input">
      </div>
    </div>
    <div class="inv-modal__actions">
      <button class="inv-btn-cancel" onclick="invCerrarModal()">Cancelar</button>
      <button class="inv-btn-save" onclick="invGuardarNuevo()">Guardar producto</button>
    </div>
  </div>
</div>

<script src="script.js" defer></script>
</body>
</html>
