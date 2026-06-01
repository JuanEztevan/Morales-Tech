<?php
// session_start();
// include("includes/auth_cliente.php");
$nombre_cliente = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Esteban Carmona';
$email_cliente  = isset($_SESSION['email'])  ? $_SESSION['email']  : 'esteban@email.com';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nueva Cotización — Morales Tech</title>
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
            <a href="tickets_cliente.php">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
              Mis tickets
            </a>
          </li>
          <li>
            <a href="nuevo_ticket_cliente.php" class="active">
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

  <!-- HERO -->
  <section class="dash-hero">
    <div class="dash-container">
      <div class="dash-hero-inner dash-hero-inner--row">
        <div>
          <div class="dash-tag">Solicitud de servicio</div>
          <h1 class="dash-hero-name">Nueva cotización</h1>
          <p class="dash-hero-sub">Solicita un presupuesto para tu equipo en tres pasos</p>
        </div>
        <a href="tickets_cliente.php" class="btn-back">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
          Volver a tickets
        </a>
      </div>
    </div>
  </section>

  <!-- WIZARD -->
  <section class="dash-section dash-section--bottom">
    <div class="dash-container">
      <div class="dash-section-label">Completa los pasos</div>
      <div class="wizard">

        <!-- Panel de pasos -->
        <div class="wizard-steps-panel">
          <div class="wizard-steps-panel__header">Progreso</div>
          <div class="wizard-step-item active" id="nav-1" onclick="goStep(1)">
            <div class="wizard-step-num" id="num-1">1</div>
            <div>
              <div class="wizard-step-label">Dispositivo</div>
              <div class="wizard-step-sub">Tipo y especificaciones</div>
            </div>
          </div>
          <div class="wizard-step-item" id="nav-2" onclick="goStep(2)">
            <div class="wizard-step-num" id="num-2">2</div>
            <div>
              <div class="wizard-step-label">Servicios</div>
              <div class="wizard-step-sub">Qué necesitas</div>
            </div>
          </div>
          <div class="wizard-step-item" id="nav-3" onclick="goStep(3)">
            <div class="wizard-step-num" id="num-3">3</div>
            <div>
              <div class="wizard-step-label">Resumen</div>
              <div class="wizard-step-sub">Confirmar solicitud</div>
            </div>
          </div>
          <div class="wizard-progress-bar">
            <div class="wizard-progress-fill" id="progress-fill" style="width:33%"></div>
          </div>
        </div>

        <!-- Contenido pasos -->
        <div>
          <!-- PASO 1: DISPOSITIVO -->
          <div class="wizard-step-content active" id="step-1">
            <div class="wizard-step-card">
              <div class="wizard-step-card__head">
                <div class="wizard-step-card__icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="13" rx="2"/><polyline points="1 21 23 21"/></svg>
                </div>
                <div>
                  <div class="wizard-step-card__title">Tu dispositivo</div>
                  <div class="wizard-step-card__sub">Selecciona el tipo de equipo a cotizar</div>
                </div>
              </div>
              <div class="wizard-step-card__body">
                <div class="wizard-section-sm">Tipo de equipo</div>
                <div class="device-grid">
                  <div class="device-opt" onclick="selectDevice(this,'Laptop')" id="opt-laptop">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="13" rx="2"/><polyline points="1 21 23 21"/></svg>
                    <div class="device-opt__label">Laptop</div>
                  </div>
                  <div class="device-opt" onclick="selectDevice(this,'PC')" id="opt-pc">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                    <div class="device-opt__label">PC de escritorio</div>
                  </div>
                </div>
                <input type="hidden" id="tipo_dispositivo">

                <!-- Campos laptop -->
                <div class="wizard-extra-fields form-grid-2col" id="extra-laptop">
                  <div class="wizard-form-group">
                    <label>Marca</label>
                    <input type="text" id="marca" placeholder="HP, Apple, ASUS…">
                  </div>
                  <div class="wizard-form-group">
                    <label>Modelo <span class="wizard-label-opt">Opcional</span></label>
                    <input type="text" id="modelo" placeholder="Ej. Pavilion 15">
                  </div>
                  <div class="wizard-form-group">
                    <label>N.° de serie <span class="wizard-label-opt">Opcional</span></label>
                    <input type="text" id="serie" placeholder="Ej. 5CD1234XYZ">
                  </div>
                  <div class="wizard-form-group">
                    <label>Sistema operativo</label>
                    <select id="so-laptop">
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
                <div class="wizard-extra-fields" id="extra-pc" style="margin-top:18px;">
                  <div class="wizard-form-group" style="max-width:260px;">
                    <label>Sistema operativo</label>
                    <select id="so-pc">
                      <option value="">Seleccionar…</option>
                      <option>Windows 11</option>
                      <option>Windows 10</option>
                      <option>Linux</option>
                      <option>Sin SO</option>
                    </select>
                  </div>
                </div>

                <hr class="wizard-divider">
                <div class="wizard-form-group">
                  <label>Describe el problema <span class="wizard-label-opt">Opcional</span></label>
                  <textarea id="observaciones" placeholder="Cuéntanos qué le pasa a tu equipo o qué servicio necesitas…"></textarea>
                </div>
              </div>
              <div class="wizard-nav">
                <span></span>
                <button class="btn-wizard-next" onclick="nextStep(1)">
                  Siguiente
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </button>
              </div>
            </div>
          </div>

          <!-- PASO 2: SERVICIOS -->
          <div class="wizard-step-content" id="step-2">
            <div class="wizard-step-card">
              <div class="wizard-step-card__head">
                <div class="wizard-step-card__icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                </div>
                <div>
                  <div class="wizard-step-card__title">Servicios</div>
                  <div class="wizard-step-card__sub">Selecciona lo que necesitas</div>
                </div>
              </div>
              <div class="wizard-step-card__body">
                <div class="wizard-section-sm">Servicio principal</div>
                <div class="wizard-service-list" id="servicios-base">
                  <label class="wizard-service-item">
                    <input type="radio" name="srv_base" value="30" data-nombre="Diagnóstico" onchange="updateQuote()">
                    <span class="wizard-service-item__name">Diagnóstico</span>
                    <span class="wizard-service-item__price">S/ 30.00</span>
                  </label>
                  <label class="wizard-service-item">
                    <input type="radio" name="srv_base" value="60" data-nombre="Mantenimiento preventivo" onchange="updateQuote()">
                    <span class="wizard-service-item__name">Mantenimiento preventivo</span>
                    <span class="wizard-service-item__price">S/ 60.00</span>
                  </label>
                  <label class="wizard-service-item">
                    <input type="radio" name="srv_base" value="90" data-nombre="Mantenimiento correctivo" onchange="updateQuote()">
                    <span class="wizard-service-item__name">Mantenimiento correctivo</span>
                    <span class="wizard-service-item__price">S/ 90.00</span>
                  </label>
                  <label class="wizard-service-item">
                    <input type="radio" name="srv_base" value="80" data-nombre="Instalación / Formateo" onchange="updateQuote()">
                    <span class="wizard-service-item__name">Instalación / Formateo</span>
                    <span class="wizard-service-item__price">S/ 80.00</span>
                  </label>
                </div>
                <hr class="wizard-divider">
                <div class="wizard-add-toggle" id="add-toggle" onclick="toggleAdd()">
                  <div class="wizard-add-toggle__left">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Servicios adicionales
                    <span class="wizard-add-badge hidden" id="add-badge">0</span>
                  </div>
                  <div class="wizard-add-chevron">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                  </div>
                </div>
                <div class="wizard-add-panel" id="add-panel">
                  <label class="wizard-service-item">
                    <input type="checkbox" value="25" data-nombre="Limpieza profunda" onchange="updateQuote()">
                    <span class="wizard-service-item__name">Limpieza profunda</span>
                    <span class="wizard-service-item__price">S/ 25.00</span>
                  </label>
                  <label class="wizard-service-item">
                    <input type="checkbox" value="20" data-nombre="Instalación de programas" onchange="updateQuote()">
                    <span class="wizard-service-item__name">Instalación de programas</span>
                    <span class="wizard-service-item__price">S/ 20.00</span>
                  </label>
                  <label class="wizard-service-item">
                    <input type="checkbox" value="30" data-nombre="Optimización del sistema" onchange="updateQuote()">
                    <span class="wizard-service-item__name">Optimización del sistema</span>
                    <span class="wizard-service-item__price">S/ 30.00</span>
                  </label>
                  <label class="wizard-service-item">
                    <input type="checkbox" value="50" data-nombre="Repotenciación (mano de obra)" onchange="updateQuote()">
                    <span class="wizard-service-item__name">Repotenciación (mano de obra)</span>
                    <span class="wizard-service-item__price">S/ 50.00</span>
                  </label>
                </div>
              </div>
              <div class="wizard-nav">
                <button class="btn-wizard-prev" onclick="prevStep(2)">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                  Anterior
                </button>
                <button class="btn-wizard-next" onclick="nextStep(2)">
                  Siguiente
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </button>
              </div>
            </div>
          </div>

          <!-- PASO 3: RESUMEN -->
          <div class="wizard-step-content" id="step-3">
            <div class="wizard-step-card">
              <div class="wizard-step-card__head">
                <div class="wizard-step-card__icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="16" x2="13" y2="16"/></svg>
                </div>
                <div>
                  <div class="wizard-step-card__title">Resumen de tu solicitud</div>
                  <div class="wizard-step-card__sub">Revisa antes de enviar</div>
                </div>
              </div>
              <div class="wizard-step-card__body">
                <div class="wizard-summary-layout">
                  <div class="wizard-summary-left" id="summary-left"></div>
                  <div class="wizard-quote-box" id="summary-quote"></div>
                </div>
              </div>
              <div class="wizard-nav">
                <button class="btn-wizard-prev" onclick="prevStep(3)">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                  Anterior
                </button>
                <button class="btn-wizard-send" onclick="enviarSolicitud()">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                  Enviar solicitud
                </button>
              </div>
            </div>
          </div>

        </div><!-- /wizard cols -->
      </div><!-- /wizard -->
    </div>
  </section>

</div><!-- /dash-page -->

<!-- MODAL ÉXITO -->
<div class="dash-modal-overlay" id="modal-success" style="pointer-events:none;opacity:0;">
  <div class="dash-modal-box dash-modal-box--success">
    <div class="dash-modal-success-icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
    </div>
    <div class="dash-modal-success-title">¡Solicitud enviada!</div>
    <div class="dash-modal-success-sub">Tu cotización ha sido enviada correctamente. Pronto nos pondremos en contacto contigo.</div>
    <button class="btn-wizard-next" onclick="window.location.href='tickets_cliente.php'" style="margin-inline:auto;">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg>
      Ver mis tickets
    </button>
  </div>
</div>

<!-- WhatsApp flotante -->
<a href="https://wa.me/51903208170" target="_blank" rel="noopener" class="float-wa" title="WhatsApp">
  <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
</a>

<!-- Datos del cliente disponibles para JS -->
<script>
  const CLIENTE_NOMBRE = '<?= htmlspecialchars($nombre_cliente) ?>';
  const CLIENTE_EMAIL  = '<?= htmlspecialchars($email_cliente) ?>';
</script>
<script src="script.js" defer></script>
</body>
</html>
