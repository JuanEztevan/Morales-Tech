<?php

/* --- AJAX: CONSULTA PÚBLICA DE TICKET --- */
if (isset($_GET['ajax']) && $_GET['ajax'] === '1') {
    header('Content-Type: application/json; charset=utf-8');

    $codigo = isset($_GET['codigo']) ? trim($_GET['codigo']) : '';

    // valida el formato del código: MT- seguido de 4 a 10 caracteres
    if (!preg_match('/^MT-[A-F0-9]{4,10}$/i', $codigo)) {
        echo json_encode(['found' => false]);
        exit;
    }
    $codigo = strtoupper($codigo);

    require_once 'conexion.php';

    $sql = "
        SELECT
            t.codigo,
            t.estado,
            t.fechaCreacion,
            e.tipoEquipo,
            e.marca,
            e.modelo,
            GROUP_CONCAT(
                s.nomServicio
                ORDER BY s.tipo DESC
                SEPARATOR '||'
            ) AS servicios,
            GROUP_CONCAT(
                s.tipo
                ORDER BY s.tipo DESC
                SEPARATOR '||'
            ) AS tiposServicio
        FROM TICKET t
        JOIN COTIZACION          c  ON t.idCotizacion = c.idCotizacion
        JOIN EQUIPO              e  ON c.idEquipo      = e.idEquipo
        JOIN COTIZACION_SERVICIO cs ON c.idCotizacion  = cs.idCotizacion
        JOIN SERVICIO            s  ON cs.idServicio   = s.idServicio
        WHERE t.codigo = ?
        GROUP BY t.idTicket
        LIMIT 1
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log('consulta_tickets prepare error: ' . $conn->error);
        echo json_encode(['found' => false]);
        exit;
    }

    $stmt->bind_param('s', $codigo);
    $stmt->execute();
    $result = $stmt->get_result();
    $row    = $result->fetch_assoc();
    $stmt->close();

    if (!$row) {
        echo json_encode(['found' => false]);
        exit;
    }

    // separa el servicio principal de los servicios adicionales
    $nombresArr  = explode('||', $row['servicios']);
    $tiposArr    = explode('||', $row['tiposServicio']);
    $principal   = '';
    $adicionales = [];

    foreach ($nombresArr as $i => $nombre) {
        $tipo = isset($tiposArr[$i]) ? $tiposArr[$i] : '';
        if (strtolower($tipo) === 'principal' && $principal === '') {
            $principal = $nombre;
        } else {
            $adicionales[] = $nombre;
        }
    }

    // convierte el estado del ticket a número de paso para la barra de progreso
    $estadoMap = [
        'Recibido'           => 1,
        'En proceso'         => 2,
        'Listo para entrega' => 3,
        'Completado'         => 4,
    ];
    $estadoLabel = $row['estado'];
    $estadoNum   = $estadoMap[$estadoLabel] ?? 1;

    $badgeClasses = [
        1 => 'recibido',
        2 => 'proceso',
        3 => 'entrega',
        4 => 'completado',
    ];

    $updateTexts = [
        1 => 'Tu equipo acaba de ser recibido y registrado en nuestro sistema. En breve un técnico comenzará a trabajar en él.',
        2 => 'Tu equipo está siendo atendido por nuestro técnico. Estamos trabajando en el servicio solicitado para entregártelo en las mejores condiciones.',
        3 => 'El servicio ha sido completado con éxito. Tu equipo ya está listo y disponible para ser recogido en nuestras instalaciones.',
        4 => 'Servicio finalizado y equipo entregado. ¡Gracias por confiar en Morales Tech!',
    ];

    $etaTexts = [
        1 => 'Un técnico será asignado en breve',
        2 => 'En atención — tiempo estimado según el servicio contratado',
        3 => 'Listo para recoger — visítanos en horario de atención',
        4 => 'Servicio completado y entregado',
    ];

    // construye la descripción del equipo sin exponer datos del cliente
    $tipoLabel = $row['tipoEquipo'] ?? '—';
    $modeloStr = '';
    if (!empty($row['marca']) && !empty($row['modelo'])) {
        $modeloStr = $row['marca'] . ' ' . $row['modelo'];
    } elseif (!empty($row['marca'])) {
        $modeloStr = $row['marca'];
    }
    $deviceStr = $modeloStr ? "$tipoLabel $modeloStr" : $tipoLabel;

    echo json_encode([
        'found'       => true,
        'codigo'      => $row['codigo'],
        'device'      => $deviceStr,
        'service'     => $principal ?: '—',
        'adicionales' => $adicionales,
        'statusNum'   => $estadoNum,
        'statusLabel' => $estadoLabel,
        'statusClass' => $badgeClasses[$estadoNum] ?? 'recibido',
        'updateText'  => $updateTexts[$estadoNum],
        'eta'         => $etaTexts[$estadoNum],
        'fecha'       => date('d/m/Y', strtotime($row['fechaCreacion'])),
    ], JSON_UNESCAPED_UNICODE);

    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Consulta tu Ticket — Morales Tech</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
  <link rel="icon" type="image/png" href="img/isotipo-color.png" media="(prefers-color-scheme: light)">
  <link rel="icon" type="image/png" href="img/isotipo-blanco.png" media="(prefers-color-scheme: dark)">
</head>
<body>

<nav class="navbar" id="navbar">
  <div class="container">
    <div class="nav-inner">
      <div class="nav-left">
        <a href="index.php" class="nav-logo">
          <img src="img/logo-horizontal-blanco.png" alt="Morales Tech"
               onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
          <span class="nav-logo-fallback">Morales<span>Tech</span></span>
        </a>
      </div>
      <div class="nav-center">
        <ul class="nav-links">
          <li><a href="index.php">Inicio</a></li>
          <li><a href="index.php#servicios">Servicios</a></li>
          <li><a href="index.php#como-funciona">Cómo funciona</a></li>
          <li><a href="#contacto">Soporte</a></li>
        </ul>
      </div>
      <div class="nav-right">
        <a href="login.php" class="btn-nav-login">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
            <polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>
          </svg>
          Iniciar sesión
        </a>
        <a href="registro.php" class="btn-nav-registro">
          Crear cuenta
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
          </svg>
        </a>
        <button class="nav-hamburger" id="hamburger" aria-label="Menú">
          <span></span><span></span><span></span>
        </button>
      </div>
    </div>
  </div>
</nav>

<div class="mobile-menu" id="mobile-menu">
  <a href="index.php"               onclick="closeMobileMenu()">Inicio</a>
  <a href="index.php#servicios"     onclick="closeMobileMenu()">Servicios</a>
  <a href="index.php#como-funciona" onclick="closeMobileMenu()">Cómo funciona</a>
  <a href="#contacto"               onclick="closeMobileMenu()">Soporte</a>
  <hr class="mobile-divider">
  <a href="consulta_tickets.php" class="mobile-active">Consultar ticket</a>
  <a href="login.php">Iniciar sesión</a>
  <a href="registro.php" class="mobile-cta">Crear cuenta →</a>
</div>

<div class="page-header">
  <div class="container">
    <div class="page-header-tag reveal">Seguimiento de servicio</div>
    <h1 class="reveal reveal-delay-1">Consulta tu <em>Estado</em></h1>
    <p class="reveal reveal-delay-2">Ingresa tu código único de seguimiento y ve el progreso de tu solicitud en tiempo real. No necesitas iniciar sesión.</p>
  </div>
</div>

<div class="main-content">
  <div class="container">
    <div class="ct-grid reveal reveal-delay-2">

      <div class="ct-input-panel">
        <div class="ct-panel-label">Código de Ticket</div>
        <div class="ct-panel-title">Busca tu ticket</div>
        <p class="ct-panel-sub">Ingresa el código que recibiste al registrar tu equipo.</p>

        <label class="field-label" for="ticketInput">Número de ticket</label>
        <div class="field-wrap">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/>
          </svg>
          <input id="ticketInput" class="ct-input" type="text"
                 placeholder="Ej: MT-83AE28" maxlength="15"
                 autocomplete="off" spellcheck="false">
        </div>

        <button class="btn-consultar" id="btnConsultar">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          Consultar estado
        </button>

        <div class="ct-help">
          <p>¿No recuerdas tu código de ticket? Escríbenos directamente y te ayudamos a recuperarlo. <a href="https://wa.me/51903208170" target="_blank" rel="noopener">Contactar soporte →</a></p>
        </div>
      </div>

      <div class="ct-result-panel">

        <div class="result-empty" id="resultEmpty">
          <div class="result-empty-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/>
            </svg>
          </div>
          <div class="result-empty-title">Resultado de búsqueda</div>
          <p class="result-empty-text">Ingresa tu código de ticket en el formulario de la izquierda y presiona <strong style="color:var(--txt-main)">"Consultar estado"</strong> para ver el progreso de tu equipo aquí.</p>
        </div>

        <div class="result-card" id="resultCard">
          <div class="rc-header">
            <div>
              <div class="rc-header-label">Resultado de búsqueda</div>
              <div class="rc-ticket-id" id="rcTicketId">—</div>
              <div class="rc-device"    id="rcDevice">—</div>
            </div>
            <div class="status-badge" id="rcStatusBadge">—</div>
          </div>

          <div class="rc-progress">
            <div class="rcp-bar-wrap">
              <div class="rcp-bar-fill" id="rcBarFill" style="width:0%"></div>
            </div>
            <div class="rcp-steps">

              <div class="rcp-step" id="step1">
                <div class="rcp-step-icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"/>
                  </svg>
                </div>
                <div class="rcp-step-name">Recibido</div>
              </div>

              <div class="rcp-step" id="step2">
                <div class="rcp-step-icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                  </svg>
                </div>
                <div class="rcp-step-name">En proceso</div>
              </div>

              <div class="rcp-step" id="step3">
                <div class="rcp-step-icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="1" y="3" width="15" height="13" rx="2"/>
                    <path d="M16 8h4l3 5v3h-7V8z"/>
                    <circle cx="5.5" cy="18.5" r="2.5"/>
                    <circle cx="18.5" cy="18.5" r="2.5"/>
                  </svg>
                </div>
                <div class="rcp-step-name">Listo para entrega</div>
              </div>

              <div class="rcp-step" id="step4">
                <div class="rcp-step-icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                  </svg>
                </div>
                <div class="rcp-step-name">Completado</div>
              </div>

            </div>
          </div>

          <div class="rc-update">
            <div class="rc-update-inner">
              <div class="rc-update-dot">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
              </div>
              <div>
                <div class="rc-update-title">Actualización del sistema</div>
                <div class="rc-update-text" id="rcUpdateText">—</div>
                <div class="rc-update-eta" id="rcEta">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:12px;height:12px"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                  —
                </div>
              </div>
            </div>
          </div>

          <div class="rc-details">
            <div class="rc-details-title">Información del servicio</div>
            <div class="rc-details-grid">
              <div class="rc-detail-item">
                <div class="rc-detail-key">Fecha de ingreso</div>
                <div class="rc-detail-val" id="rcdFecha">—</div>
              </div>
              <div class="rc-detail-item">
                <div class="rc-detail-key">Servicio principal</div>
                <div class="rc-detail-val" id="rcdServicio">—</div>
              </div>
              <div class="rc-detail-item">
                <div class="rc-detail-key">Servicios adicionales</div>
                <div class="rc-detail-val" id="rcdAdicionales">—</div>
              </div>
              <div class="rc-detail-item">
                <div class="rc-detail-key">Estado actual</div>
                <div class="rc-detail-val" id="rcdEstado">—</div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<div class="features-strip">
  <div class="container">
    <div class="features-strip-grid">
      <div class="features-strip-item reveal">
        <div class="features-strip-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div>
          <div class="features-strip-title">Seguimiento 24/7</div>
          <div class="features-strip-text">Consulta el estado de tu equipo en cualquier momento, desde cualquier dispositivo, sin necesidad de llamar.</div>
        </div>
      </div>
      <div class="features-strip-item reveal reveal-delay-1">
        <div class="features-strip-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <div>
          <div class="features-strip-title">Garantía certificada</div>
          <div class="features-strip-text">Todos nuestros servicios incluyen garantía sobre el trabajo realizado. Tu equipo en buenas manos.</div>
        </div>
      </div>
      <div class="features-strip-item reveal reveal-delay-2">
        <div class="features-strip-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
        </div>
        <div>
          <div class="features-strip-title">Actualizaciones en tiempo real</div>
          <div class="features-strip-text">Cada avance en tu equipo se registra al instante. Siempre sabrás en qué etapa se encuentra tu servicio.</div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="support-cta" id="contacto">
  <div class="container">
    <div class="support-cta-inner reveal">
      <div class="support-cta-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
        </svg>
      </div>
      <div class="support-cta-title">¿Necesitas más información?</div>
      <p class="support-cta-sub">Si tienes dudas sobre el estado de tu equipo o necesitas hablar con un técnico, escríbenos directamente.</p>
      <div class="support-cta-buttons">
        <a href="https://wa.me/51903208170" target="_blank" rel="noopener" class="btn-wa">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
          Chatear ahora
        </a>
        <a href="tel:+51903208170" class="btn-call">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.77 1h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 8.91a16 16 0 0 0 5.9 5.9l.92-.92a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          Llamar a soporte
        </a>
      </div>
    </div>
  </div>
</div>

<footer class="footer">
  <div class="container">
    <div class="footer-grid">
      <div class="footer-brand">
        <img src="img/naming-logo-blanco.png" alt="Morales Tech"
             onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
        <span class="nav-logo-fallback">Morales<span>Tech</span></span>
        <p>Soluciones de soporte técnico digital para PC y laptops en Ica, Perú.</p>
        <div class="footer-socials">
          <a href="https://instagram.com/moralestech.pe" target="_blank" class="social-btn" title="Instagram">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
          </a>
          <a href="https://facebook.com/moralestech.pe" target="_blank" class="social-btn" title="Facebook">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
          </a>
          <a href="https://wa.me/51903208170" target="_blank" class="social-btn" title="WhatsApp">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
          </a>
        </div>
      </div>
      <div>
        <div class="footer-col-title">Navegación</div>
        <ul class="footer-links">
          <li><a href="index.php">Inicio</a></li>
          <li><a href="index.php#servicios">Servicios</a></li>
          <li><a href="index.php#como-funciona">Cómo funciona</a></li>
          <li><a href="consulta_tickets.php">Consultar ticket</a></li>
          <li><a href="#contacto">Contacto</a></li>
        </ul>
      </div>
      <div>
        <div class="footer-col-title">Legal</div>
        <ul class="footer-links">
          <li><a href="#">Privacidad</a></li>
          <li><a href="#">Términos de uso</a></li>
        </ul>
        <div style="margin-top:24px">
          <div class="footer-col-title">Otros servicios</div>
          <ul class="footer-links">
            <li><a href="#">Hosting</a></li>
            <li><a href="#">Redes y cableado</a></li>
            <li><a href="#">Diseño web</a></li>
            <li><a href="#">Identidad de marca</a></li>
          </ul>
        </div>
      </div>
      <div>
        <div class="footer-col-title">Contacto</div>
        <div class="footer-contact">
          <div class="footer-contact-item">
            <div class="footer-contact-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg></div>
            <div class="footer-contact-text"><a href="https://wa.me/51903208170" target="_blank" style="color:inherit">+51 903 208 170 (WhatsApp)</a></div>
          </div>
          <div class="footer-contact-item">
            <div class="footer-contact-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></div>
            <div class="footer-contact-text">moralestechsolutionss@gmail.com</div>
          </div>
          <div class="footer-contact-item">
            <div class="footer-contact-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></div>
            <div class="footer-contact-text">Urb. Jardines de Casablanca F-06, Ica, Perú</div>
          </div>
          <div class="footer-contact-item">
            <div class="footer-contact-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg></div>
            <div class="footer-contact-text">@moralestech.pe</div>
          </div>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <p>© 2026 Morales Tech · RUC 20613424238 · Todos los derechos reservados.</p>
      <div class="footer-legal">
        <a href="#">Privacidad</a>
        <a href="#">Términos</a>
      </div>
    </div>
  </div>
</footer>

<script src="script.js" defer></script>
</body>
</html>
