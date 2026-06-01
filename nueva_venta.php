<?php
// session_start();
// include("includes/auth.php");
$nombre_usuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Juan';
$rol_usuario    = 'Trabajador';
$partes         = explode(' ', trim($nombre_usuario));
$inicial        = strtoupper(substr($partes[0], 0, 1));
$nombre_corto   = $partes[0];

$tickets_disponibles = [
    ["id"=>"MT-8842","cliente"=>"Valeria Ramírez", "servicio"=>"Mantenimiento correctivo",     "subtotal"=>90],
    ["id"=>"MT-8843","cliente"=>"Andrés Ochante",  "servicio"=>"Repotenciación (mano de obra)","subtotal"=>50],
    ["id"=>"MT-8844","cliente"=>"Diana Calderón",  "servicio"=>"Diagnóstico",                  "subtotal"=>30],
    ["id"=>"MT-8845","cliente"=>"Brenda Benites",  "servicio"=>"Mantenimiento preventivo",     "subtotal"=>60],
];

$productos = [
    ["id"=>1,"nombre"=>"Alcohol Isopropílico 1000ml",              "categoria"=>"Consumibles",    "precio"=>25],
    ["id"=>2,"nombre"=>"Pasta Térmica (jeringa 5g)",                "categoria"=>"Consumibles",    "precio"=>18],
    ["id"=>3,"nombre"=>"Kit Destornilladores 58 en 1",              "categoria"=>"Herramientas",   "precio"=>65],
    ["id"=>4,"nombre"=>"Kit Destornilladores de Precisión 128 en 1","categoria"=>"Herramientas",   "precio"=>95],
    ["id"=>5,"nombre"=>"SSD 1TB SATA",                              "categoria"=>"Almacenamiento", "precio"=>195],
    ["id"=>6,"nombre"=>"SSD 512GB NVMe",                            "categoria"=>"Almacenamiento", "precio"=>165],
    ["id"=>7,"nombre"=>"RAM DDR4 16GB 3200MHz",                     "categoria"=>"Memoria RAM",    "precio"=>145],
    ["id"=>8,"nombre"=>"RAM DDR5 16GB 4800MHz",                     "categoria"=>"Memoria RAM",    "precio"=>185],
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nueva Venta — Morales Tech</title>
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
      <div class="dash-header__breadcrumb">
        Panel / <a href="ventas.php" class="dash-header__breadcrumb-link">Ventas</a> / <span>Nueva Venta</span>
      </div>
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
          <h1 class="tk-page-title">Nueva Venta</h1>
          <p class="tk-page-subtitle">Registra una venta de servicio técnico o de productos</p>
        </div>
        <a href="ventas.php" class="dash-btn-back">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
          Volver
        </a>
      </div>

      <div class="nv-content-grid">

        <!-- ══ IZQUIERDA ══ -->
        <div>

          <!-- 1. Tipo de venta -->
          <div class="nv-card">
            <div class="nv-card__header">
              <div class="ntk-step-card__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
              </div>
              <div>
                <div class="nv-card__title">Tipo de venta</div>
                <div class="nv-card__sub">¿Qué vas a registrar?</div>
              </div>
            </div>
            <div class="nv-vtype-grid">
              <div class="nv-vtype-opt selected" id="vopt-ticket" onclick="nvSelTipo('ticket',this)">
                <div class="nv-vtype-opt__icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
                </div>
                <div class="nv-vtype-opt__title">Venta por ticket</div>
                <div class="nv-vtype-opt__desc">Servicio técnico concretado, con opción de añadir productos</div>
              </div>
              <div class="nv-vtype-opt" id="vopt-producto" onclick="nvSelTipo('producto',this)">
                <div class="nv-vtype-opt__icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                </div>
                <div class="nv-vtype-opt__title">Venta de producto</div>
                <div class="nv-vtype-opt__desc">Venta directa de productos del inventario a un cliente</div>
              </div>
            </div>
          </div>

          <!-- 2A. Ticket asociado -->
          <div class="nv-card" id="nv-bloque-ticket">
            <div class="nv-card__header">
              <div class="ntk-step-card__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
              </div>
              <div>
                <div class="nv-card__title">Ticket asociado</div>
                <div class="nv-card__sub">Selecciona el ticket del servicio completado</div>
              </div>
            </div>
            <div class="ntk-form-group">
              <label>Número de ticket <span class="ntk-req">*</span></label>
              <select id="nv-sel-ticket" class="ntk-input ntk-select" onchange="nvOnTicketChange()">
                <option value="">Seleccionar ticket…</option>
                <?php foreach ($tickets_disponibles as $t): ?>
                <option value="<?= $t['id'] ?>"
                        data-cliente="<?= htmlspecialchars($t['cliente']) ?>"
                        data-servicio="<?= htmlspecialchars($t['servicio']) ?>"
                        data-subtotal="<?= $t['subtotal'] ?>">
                  <?= htmlspecialchars($t['id']) ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="nv-ticket-info" id="nv-ticket-info">
              <div class="nv-ticket-info__text">
                <div class="nv-ticket-info__label">Cliente · Servicio</div>
                <div class="nv-ticket-info__value" id="nv-ticket-info-text">—</div>
              </div>
              <div class="nv-ticket-info__price" id="nv-ticket-info-price">S/ 0</div>
            </div>
          </div>

          <!-- 2B. Cliente directo -->
          <div class="nv-card nv-hidden" id="nv-bloque-cliente">
            <div class="nv-card__header">
              <div class="ntk-step-card__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
              </div>
              <div>
                <div class="nv-card__title">Datos del cliente</div>
                <div class="nv-card__sub">Identifica al comprador</div>
              </div>
            </div>
            <div class="ntk-form-grid">
              <div class="ntk-form-group">
                <label>DNI <span class="ntk-req">*</span></label>
                <input type="text" id="nv-cli-dni" placeholder="8 dígitos" maxlength="8" class="ntk-input"
                       oninput="this.value=this.value.replace(/\D/g,'')">
              </div>
              <div class="ntk-form-group">
                <label>RUC <span class="ntk-label-opt">opcional</span></label>
                <input type="text" id="nv-cli-ruc" placeholder="11 dígitos" maxlength="11" class="ntk-input"
                       oninput="this.value=this.value.replace(/\D/g,'')">
              </div>
              <div class="ntk-form-group ntk-span-2">
                <label>Nombre completo <span class="ntk-req">*</span></label>
                <input type="text" id="nv-cli-nombre" placeholder="Nombre del cliente" class="ntk-input"
                       oninput="nvUpdateQuote()">
              </div>
            </div>
          </div>

          <!-- 3. Productos -->
          <div class="nv-card" id="nv-bloque-productos">
            <div class="nv-card__header">
              <div class="ntk-step-card__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
              </div>
              <div>
                <div class="nv-card__title" id="nv-prod-card-title">Productos adicionales</div>
                <div class="nv-card__sub" id="nv-prod-card-sub">Añade repuestos o materiales usados en el servicio</div>
              </div>
            </div>
            <div class="nv-prod-list" id="nv-prod-list"></div>
            <button class="nv-btn-add-prod" onclick="nvAgregarProd()">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              Añadir producto
            </button>
          </div>

        </div><!-- /izquierda -->

        <!-- ══ DERECHA: RESUMEN ══ -->
        <div class="nv-sticky-right">
          <div class="nv-quote-card">
            <div class="nv-quote-card__title">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="16" x2="13" y2="16"/></svg>
              Resumen de Venta
            </div>

            <div class="nv-quote-client nv-hidden" id="nv-q-client">
              <span class="nv-quote-client__name" id="nv-q-client-name">—</span>
            </div>

            <div id="nv-q-empty" class="nv-quote-empty">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg>
              Selecciona un ticket o<br>añade productos para<br>ver el resumen
            </div>

            <div id="nv-q-details" class="nv-hidden">
              <div class="nv-quote-items" id="nv-q-items"></div>
              <hr class="nv-quote-divider">
              <div class="nv-quote-subtotal"><span>Subtotal</span><span>S/ <span id="nv-q-subtotal">0.00</span></span></div>
              <div class="nv-quote-igv"><span>IGV (18%)</span><span>S/ <span id="nv-q-igv">0.00</span></span></div>
              <hr class="nv-quote-divider">
              <div class="nv-quote-total">
                <span class="nv-quote-total__label">Total</span>
                <span class="nv-quote-total__amount">S/ <span id="nv-q-total">0.00</span></span>
              </div>
            </div>

            <div class="nv-metodo-title">Método de pago</div>
            <div class="nv-metodo-opts">
              <div class="nv-metodo-opt selected" onclick="nvSelMetodo('Yape',this)">
                <div class="nv-metodo-opt__label">Yape</div>
              </div>
              <div class="nv-metodo-opt" onclick="nvSelMetodo('Transferencia',this)">
                <div class="nv-metodo-opt__label">Transferencia</div>
              </div>
              <div class="nv-metodo-opt" onclick="nvSelMetodo('Efectivo',this)">
                <div class="nv-metodo-opt__label">Efectivo</div>
              </div>
            </div>

            <button class="nv-btn-create" onclick="nvGuardarVenta()">Registrar venta</button>
          </div>
        </div>

      </div><!-- /nv-content-grid -->

    </main>
  </div><!-- /dash-main -->

</div><!-- /dash-shell -->

<!-- MODAL ÉXITO -->
<div class="nv-modal-overlay" id="nv-modal-success">
  <div class="ntk-modal-box" style="max-width:400px;">
    <div class="ntk-modal-icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
    </div>
    <div class="ntk-modal-title">¡Venta registrada!</div>
    <div class="ntk-modal-sub">La venta ha sido registrada correctamente en el sistema.</div>
    <div class="nv-modal-amount" id="nv-modal-amount">S/ 0.00</div>
    <div class="nv-modal-method" id="nv-modal-method">—</div>
    <button class="ntk-modal-btn" onclick="window.location.href='ventas.php'">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
      Ver ventas
    </button>
  </div>
</div>

<!-- Datos PHP → JS -->
<script>
  const NV_TICKETS  = <?= json_encode($tickets_disponibles) ?>;
  const NV_PRODUCTOS = <?= json_encode($productos) ?>;
</script>
<script src="script.js" defer></script>
</body>
</html>
