<?php
// session_start();
// include("includes/auth.php");
$nombre_usuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Juan';
$rol_usuario    = 'Trabajador';
$partes         = explode(' ', trim($nombre_usuario));
$inicial        = strtoupper(substr($partes[0], 0, 1));
$nombre_corto   = $partes[0];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nuevo Ticket — Morales Tech</title>
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
      <div class="dash-header__breadcrumb">
        Panel / <a href="tickets.php" class="dash-header__breadcrumb-link">Tickets</a> / <span>Nuevo Ticket</span>
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
          <h1 class="tk-page-title">Nuevo Ticket</h1>
          <p class="tk-page-subtitle">Registra un nuevo servicio técnico paso a paso</p>
        </div>
        <a href="tickets.php" class="dash-btn-back">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
          Volver
        </a>
      </div>

      <!-- WIZARD -->
      <div class="ntk-wizard">

        <!-- Panel de pasos -->
        <div class="ntk-steps-panel">
          <div class="ntk-steps-panel__header">Progreso</div>
          <div class="ntk-step-item active" id="nav-1" onclick="ntkGoStep(1)">
            <div class="ntk-step-num" id="num-1">1</div>
            <div>
              <div class="ntk-step-label">Cliente</div>
              <div class="ntk-step-sub">Datos del cliente</div>
            </div>
          </div>
          <div class="ntk-step-item" id="nav-2" onclick="ntkGoStep(2)">
            <div class="ntk-step-num" id="num-2">2</div>
            <div>
              <div class="ntk-step-label">Dispositivo</div>
              <div class="ntk-step-sub">Tipo y especificaciones</div>
            </div>
          </div>
          <div class="ntk-step-item" id="nav-3" onclick="ntkGoStep(3)">
            <div class="ntk-step-num" id="num-3">3</div>
            <div>
              <div class="ntk-step-label">Servicios</div>
              <div class="ntk-step-sub">Servicio y cotización</div>
            </div>
          </div>
          <div class="ntk-step-item" id="nav-4" onclick="ntkGoStep(4)">
            <div class="ntk-step-num" id="num-4">4</div>
            <div>
              <div class="ntk-step-label">Resumen</div>
              <div class="ntk-step-sub">Confirmar y crear</div>
            </div>
          </div>
          <div class="ntk-progress-bar">
            <div class="ntk-progress-fill" id="ntk-progress-fill" style="width:25%"></div>
          </div>
        </div>

        <!-- Contenido pasos -->
        <div>

          <!-- PASO 1: CLIENTE -->
          <div class="ntk-step-content active" id="step-1">
            <div class="ntk-step-card">
              <div class="ntk-step-card__head">
                <div class="ntk-step-card__icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <div>
                  <div class="ntk-step-card__title">Datos del Cliente</div>
                  <div class="ntk-step-card__sub">Ingresa la información de identificación</div>
                </div>
              </div>
              <div class="ntk-step-card__body">
                <div class="ntk-form-grid">
                  <div class="ntk-form-group">
                    <label>DNI <span class="ntk-req">*</span></label>
                    <input type="text" id="ntk-dni" placeholder="8 dígitos" maxlength="8" class="ntk-input">
                  </div>
                  <div class="ntk-form-group">
                    <label>RUC <span class="ntk-label-opt">Opcional</span></label>
                    <input type="text" id="ntk-ruc" placeholder="11 dígitos" maxlength="11" class="ntk-input">
                  </div>
                  <div class="ntk-form-group ntk-span-2">
                    <label>Nombre completo <span class="ntk-req">*</span></label>
                    <input type="text" id="ntk-nombre-cliente" placeholder="Nombre y apellidos" class="ntk-input">
                  </div>
                  <div class="ntk-form-group">
                    <label>Teléfono <span class="ntk-req">*</span></label>
                    <input type="text" id="ntk-telefono" placeholder="9 dígitos" maxlength="9" class="ntk-input">
                  </div>
                  <div class="ntk-form-group">
                    <label>Correo <span class="ntk-label-opt">Opcional</span></label>
                    <input type="text" id="ntk-correo" placeholder="correo@email.com" class="ntk-input">
                  </div>
                </div>
              </div>
              <div class="ntk-step-nav">
                <span></span>
                <button class="ntk-btn-next" onclick="ntkNextStep(1)">
                  Siguiente
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </button>
              </div>
            </div>
          </div>

          <!-- PASO 2: DISPOSITIVO -->
          <div class="ntk-step-content" id="step-2">
            <div class="ntk-step-card">
              <div class="ntk-step-card__head">
                <div class="ntk-step-card__icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="13" rx="2"/><polyline points="1 21 23 21"/></svg>
                </div>
                <div>
                  <div class="ntk-step-card__title">Dispositivo</div>
                  <div class="ntk-step-card__sub">Selecciona el tipo de equipo y sus características</div>
                </div>
              </div>
              <div class="ntk-step-card__body">
                <div class="ntk-section-sm">Tipo de equipo</div>
                <div class="ntk-device-grid">
                  <div class="ntk-device-opt" onclick="ntkSelectDevice(this,'Laptop')" id="opt-laptop">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="13" rx="2"/><polyline points="1 21 23 21"/></svg>
                    <div class="ntk-device-opt__label">Laptop</div>
                  </div>
                  <div class="ntk-device-opt" onclick="ntkSelectDevice(this,'PC')" id="opt-pc">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                    <div class="ntk-device-opt__label">PC de escritorio</div>
                  </div>
                </div>
                <input type="hidden" id="ntk-tipo-dispositivo">

                <!-- Campos Laptop -->
                <div class="ntk-extra-fields ntk-form-grid" id="extra-laptop">
                  <div class="ntk-form-group">
                    <label>Marca</label>
                    <input type="text" id="ntk-marca" placeholder="HP, Apple, ASUS…" class="ntk-input">
                  </div>
                  <div class="ntk-form-group">
                    <label>Modelo <span class="ntk-label-opt">Opcional</span></label>
                    <input type="text" id="ntk-modelo" placeholder="Ej. Pavilion 15" class="ntk-input">
                  </div>
                  <div class="ntk-form-group">
                    <label>N.° de serie <span class="ntk-label-opt">Opcional</span></label>
                    <input type="text" id="ntk-serie" placeholder="Ej. 5CD1234XYZ" class="ntk-input">
                  </div>
                  <div class="ntk-form-group">
                    <label>Sistema operativo</label>
                    <select id="ntk-so-laptop" class="ntk-input ntk-select">
                      <option value="">Seleccionar…</option>
                      <option>Windows 11</option>
                      <option>Windows 10</option>
                      <option>macOS</option>
                      <option>Linux</option>
                      <option>Sin SO</option>
                    </select>
                  </div>
                </div>

                <!-- Campos PC -->
                <div class="ntk-extra-fields" id="extra-pc" style="margin-top:18px;">
                  <div class="ntk-form-group" style="max-width:260px;">
                    <label>Sistema operativo</label>
                    <select id="ntk-so-pc" class="ntk-input ntk-select">
                      <option value="">Seleccionar…</option>
                      <option>Windows 11</option>
                      <option>Windows 10</option>
                      <option>Linux</option>
                      <option>Sin SO</option>
                    </select>
                  </div>
                </div>

                <hr class="ntk-divider">
                <div class="ntk-form-group">
                  <label>Observaciones del equipo <span class="ntk-label-opt">Opcional</span></label>
                  <textarea id="ntk-observaciones" placeholder="Describe el problema o síntomas que reporta el cliente…" class="ntk-textarea"></textarea>
                </div>
              </div>
              <div class="ntk-step-nav">
                <button class="ntk-btn-prev" onclick="ntkPrevStep(2)">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                  Anterior
                </button>
                <button class="ntk-btn-next" onclick="ntkNextStep(2)">
                  Siguiente
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </button>
              </div>
            </div>
          </div>

          <!-- PASO 3: SERVICIOS -->
          <div class="ntk-step-content" id="step-3">
            <div class="ntk-step-card">
              <div class="ntk-step-card__head">
                <div class="ntk-step-card__icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                </div>
                <div>
                  <div class="ntk-step-card__title">Servicios y Cotización</div>
                  <div class="ntk-step-card__sub">Elige los servicios a realizar</div>
                </div>
              </div>
              <div class="ntk-step-card__body">
                <div class="ntk-section-sm">Servicio base</div>
                <div class="ntk-service-list" id="ntk-servicios-base">
                  <label class="ntk-service-item">
                    <input type="radio" name="ntk_srv_base" value="30" data-nombre="Diagnóstico" onchange="ntkUpdateQuote()">
                    <span class="ntk-service-item__name">Diagnóstico</span>
                    <span class="ntk-service-item__price">S/ 30.00</span>
                  </label>
                  <label class="ntk-service-item">
                    <input type="radio" name="ntk_srv_base" value="60" data-nombre="Mantenimiento preventivo" onchange="ntkUpdateQuote()">
                    <span class="ntk-service-item__name">Mantenimiento preventivo</span>
                    <span class="ntk-service-item__price">S/ 60.00</span>
                  </label>
                  <label class="ntk-service-item">
                    <input type="radio" name="ntk_srv_base" value="90" data-nombre="Mantenimiento correctivo" onchange="ntkUpdateQuote()">
                    <span class="ntk-service-item__name">Mantenimiento correctivo</span>
                    <span class="ntk-service-item__price">S/ 90.00</span>
                  </label>
                  <label class="ntk-service-item">
                    <input type="radio" name="ntk_srv_base" value="80" data-nombre="Instalación / Formateo" onchange="ntkUpdateQuote()">
                    <span class="ntk-service-item__name">Instalación / Formateo</span>
                    <span class="ntk-service-item__price">S/ 80.00</span>
                  </label>
                </div>
                <hr class="ntk-divider">
                <div class="ntk-add-toggle" id="ntk-add-toggle" onclick="ntkToggleAdd()">
                  <div class="ntk-add-toggle__left">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Servicios adicionales
                    <span class="ntk-add-badge hidden" id="ntk-add-badge">0</span>
                  </div>
                  <div class="ntk-add-chevron">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                  </div>
                </div>
                <div class="ntk-add-panel" id="ntk-add-panel">
                  <label class="ntk-service-item">
                    <input type="checkbox" value="25" data-nombre="Limpieza profunda" onchange="ntkUpdateQuote()">
                    <span class="ntk-service-item__name">Limpieza profunda</span>
                    <span class="ntk-service-item__price">S/ 25.00</span>
                  </label>
                  <label class="ntk-service-item">
                    <input type="checkbox" value="20" data-nombre="Instalación de programas" onchange="ntkUpdateQuote()">
                    <span class="ntk-service-item__name">Instalación de programas</span>
                    <span class="ntk-service-item__price">S/ 20.00</span>
                  </label>
                  <label class="ntk-service-item">
                    <input type="checkbox" value="30" data-nombre="Optimización del sistema" onchange="ntkUpdateQuote()">
                    <span class="ntk-service-item__name">Optimización del sistema</span>
                    <span class="ntk-service-item__price">S/ 30.00</span>
                  </label>
                  <label class="ntk-service-item">
                    <input type="checkbox" value="50" data-nombre="Repotenciación (mano de obra)" onchange="ntkUpdateQuote()">
                    <span class="ntk-service-item__name">Repotenciación (mano de obra)</span>
                    <span class="ntk-service-item__price">S/ 50.00</span>
                  </label>
                </div>
              </div>
              <div class="ntk-step-nav">
                <button class="ntk-btn-prev" onclick="ntkPrevStep(3)">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                  Anterior
                </button>
                <button class="ntk-btn-next" onclick="ntkNextStep(3)">
                  Siguiente
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </button>
              </div>
            </div>
          </div>

          <!-- PASO 4: RESUMEN -->
          <div class="ntk-step-content" id="step-4">
            <div class="ntk-step-card">
              <div class="ntk-step-card__head">
                <div class="ntk-step-card__icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="16" x2="13" y2="16"/></svg>
                </div>
                <div>
                  <div class="ntk-step-card__title">Resumen del Ticket</div>
                  <div class="ntk-step-card__sub">Verifica la información antes de crear</div>
                </div>
              </div>
              <div class="ntk-step-card__body">
                <div class="ntk-summary-layout">
                  <div class="ntk-summary-left" id="ntk-summary-left"></div>
                  <div class="ntk-quote-box" id="ntk-summary-quote"></div>
                </div>
              </div>
              <div class="ntk-step-nav">
                <button class="ntk-btn-prev" onclick="ntkPrevStep(4)">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                  Anterior
                </button>
                <button class="ntk-btn-finish" onclick="ntkCrearTicket()">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                  Crear Ticket
                </button>
              </div>
            </div>
          </div>

        </div><!-- /wizard cols -->
      </div><!-- /ntk-wizard -->

    </main>
  </div><!-- /dash-main -->

</div><!-- /dash-shell -->

<!-- MODAL ÉXITO -->
<div class="ntk-modal-overlay" id="ntk-modal-success">
  <div class="ntk-modal-box">
    <div class="ntk-modal-icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
    </div>
    <div class="ntk-modal-title">¡Ticket creado!</div>
    <div class="ntk-modal-sub">El ticket ha sido registrado correctamente en el sistema.</div>
    <button class="ntk-modal-btn" onclick="window.location.href='tickets.php'">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
      Ver tickets
    </button>
  </div>
</div>

<script src="script.js" defer></script>
</body>
</html>
