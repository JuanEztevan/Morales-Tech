<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Morales Tech — Soporte Técnico Inteligente</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
  <link rel="icon" type="image/png" href="img/isotipo-color.png" media="(prefers-color-scheme: light)">
  <link rel="icon" type="image/png" href="img/isotipo-blanco.png" media="(prefers-color-scheme: dark)">
</head>
<body>

<nav class="navbar" id="navbar">
  <div class="container">
    <div class="nav-inner">
      <div class="nav-left">
        <a href="#" class="nav-logo">
          <img src="img/logo-horizontal-blanco.png" alt="Morales Tech"
               onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
          <span class="nav-logo-fallback">Morales<span>Tech</span></span>
        </a>
      </div>
      <div class="nav-center">
        <ul class="nav-links">
          <li><a href="#inicio" class="active">Inicio</a></li>
          <li><a href="#servicios">Servicios</a></li>
          <li><a href="#como-funciona">Cómo funciona</a></li>
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
  <a href="#inicio"        onclick="closeMobileMenu()">Inicio</a>
  <a href="#servicios"     onclick="closeMobileMenu()">Servicios</a>
  <a href="#como-funciona" onclick="closeMobileMenu()">Cómo funciona</a>
  <a href="#contacto"      onclick="closeMobileMenu()">Soporte</a>
  <hr class="mobile-divider">
  <a href="consulta_tickets.php">Consultar mi ticket</a>
  <a href="login.php">Iniciar sesión</a>
  <a href="registro.php" class="mobile-cta">Crear cuenta →</a>
</div>

<section class="hero" id="inicio">
  <div class="hero-glow"></div>
  <div class="hero-glow-tr"></div>
  <div class="container">
    <div class="hero-inner">
      <div class="hero-tag reveal">
        <span class="tag">Sistema de soporte técnico digital</span>
      </div>
      <h1 class="hero-title reveal reveal-delay-1">
        Soporte técnico <em>inteligente</em> para tu PC o laptop
      </h1>
      <p class="hero-sub reveal reveal-delay-2">
        Cotiza, solicita y da seguimiento a tu servicio en tiempo real desde un solo portal. Sin complicaciones, sin incertidumbre.
      </p>
      <div class="hero-buttons reveal reveal-delay-3">
        <a href="consulta_tickets.php" class="btn btn--primary">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/>
          </svg>
          Consultar estado de mi ticket
        </a>
        <a href="login.php" class="btn btn--outline">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
            <polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>
          </svg>
          Ya tengo cuenta
        </a>
      </div>
      <div class="hero-checks reveal reveal-delay-4">
        <div class="hero-check">
          <div class="check-dot">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
          </div>
          Ingresa tu número de ticket
        </div>
        <div class="hero-check">
          <div class="check-dot">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
          </div>
          Sin necesidad de iniciar sesión
        </div>
        <div class="hero-check">
          <div class="check-dot">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
          </div>
          Seguimiento en tiempo real
        </div>
      </div>
    </div>
  </div>
</section>

<div class="clients">
  <div class="container">
    <p class="clients-label">Empresas que han confiado en nosotros</p>
    <div class="clients-logos">
      <img class="client-logo" src="img/clientes/logo-redondos.png"         alt="Redondos">
      <img class="client-logo" src="img/clientes/logo-cordillera.png"       alt="La Cordillera Latam">
      <img class="client-logo" src="img/clientes/logo-patty-farma.png"      alt="Patty Farma">
      <img class="client-logo" src="img/clientes/logo-notaria.png"          alt="Notaría Vásquez">
      <img class="client-logo" src="img/clientes/logo-clinica-palomino.png" alt="Clínica Palomino">
    </div>
  </div>
</div>

<section class="section" id="beneficios">
  <div class="container">
    <div class="benefits-inner">
      <div>
        <p class="eyebrow reveal">Por qué Morales Tech</p>
        <h2 class="benefits-title reveal reveal-delay-1">Una nueva forma de gestionar tu soporte técnico</h2>
        <p class="benefits-sub reveal reveal-delay-2">Olvídate de llamar y esperar sin saber qué pasa. Con nuestro sistema tienes visibilidad total en cada etapa.</p>
        <div class="benefits-cards">
          <div class="benefit-card reveal reveal-delay-1">
            <div class="benefit-card-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </div>
            <div>
              <div class="benefit-card-title">Diagnóstico preciso</div>
              <div class="benefit-card-text">Identificamos el problema de tu equipo con análisis técnico detallado desde el primer momento.</div>
            </div>
          </div>
          <div class="benefit-card reveal reveal-delay-2">
            <div class="benefit-card-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <div>
              <div class="benefit-card-title">Cotización transparente</div>
              <div class="benefit-card-text">Conoce el costo exacto antes de confirmar el servicio, sin sorpresas ni cobros ocultos.</div>
            </div>
          </div>
          <div class="benefit-card reveal reveal-delay-3">
            <div class="benefit-card-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            </div>
            <div>
              <div class="benefit-card-title">Seguimiento en tiempo real</div>
              <div class="benefit-card-text">Consulta el estado de tu equipo en cada etapa: diagnóstico, reparación y entrega.</div>
            </div>
          </div>
        </div>
      </div>
      <div class="benefits-panel reveal reveal-delay-2">
        <div class="bp-eyebrow">Resultados</div>
        <div class="bp-title">Números que demuestran nuestra calidad</div>
        <div class="bp-stats">
          <div class="bp-stat">
            <div class="bp-stat-val">98%</div>
            <div class="bp-stat-txt">de clientes satisfechos con el servicio</div>
          </div>
          <hr class="bp-divider">
          <div class="bp-stat">
            <div class="bp-stat-val">24h</div>
            <div class="bp-stat-txt">tiempo promedio de resolución</div>
          </div>
          <hr class="bp-divider">
          <div class="bp-stat">
            <div class="bp-stat-val">500+</div>
            <div class="bp-stat-txt">equipos reparados en Ica</div>
          </div>
        </div>
        <hr class="bp-divider">
        <a href="registro.php" class="bp-cta">
          Solicitar servicio ahora
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
      </div>
    </div>
  </div>
</section>

<section class="section section--alt" id="como-funciona">
  <div class="container">
    <div class="how-header">
      <p class="eyebrow reveal">Proceso simple</p>
      <h2 class="reveal reveal-delay-1">Así de simple funciona Morales Tech</h2>
      <p class="reveal reveal-delay-2">En solo cuatro pasos tu equipo queda en manos de nuestros técnicos especializados.</p>
    </div>
    <div class="how-steps">
      <div class="how-step reveal reveal-delay-1">
        <div class="how-step-num-wrap"><div class="how-step-num">1</div></div>
        <div class="how-step-title">Crea tu cuenta</div>
        <div class="how-step-text">Regístrate en segundos y accede a tu panel personal desde cualquier dispositivo.</div>
      </div>
      <div class="how-step reveal reveal-delay-2">
        <div class="how-step-num-wrap"><div class="how-step-num">2</div></div>
        <div class="how-step-title">Solicita tu servicio</div>
        <div class="how-step-text">Selecciona el tipo de soporte y obtén una cotización inmediata y transparente.</div>
      </div>
      <div class="how-step reveal reveal-delay-3">
        <div class="how-step-num-wrap"><div class="how-step-num">3</div></div>
        <div class="how-step-title">Genera tu ticket</div>
        <div class="how-step-text">Confirma el servicio y tu solicitud quedará registrada automáticamente en el sistema.</div>
      </div>
      <div class="how-step reveal reveal-delay-4">
        <div class="how-step-num-wrap"><div class="how-step-num">4</div></div>
        <div class="how-step-title">Sigue tu equipo</div>
        <div class="how-step-text">Monitorea el estado de tu servicio en tiempo real desde el portal de clientes.</div>
      </div>
    </div>
  </div>
</section>

<section class="section" id="servicios">
  <div class="container">
    <div class="services-header">
      <div>
        <p class="eyebrow reveal">Soluciones técnicas</p>
        <h2 class="reveal reveal-delay-1">Soluciones para tu equipo</h2>
      </div>
      <p class="reveal reveal-delay-2">Desde un simple mantenimiento hasta una reparación compleja, cubrimos todas las necesidades de tu PC o laptop.</p>
    </div>
    <div class="services-grid">
      <div class="service-card reveal reveal-delay-1">
        <div class="service-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></div>
        <div>
          <div class="service-card-title">Diagnóstico técnico</div>
          <div class="service-card-text">Detectamos fallas en hardware y software con precisión usando herramientas especializadas para identificar la causa raíz del problema.</div>
        </div>
      </div>
      <div class="service-card reveal reveal-delay-2">
        <div class="service-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg></div>
        <div>
          <div class="service-card-title">Mantenimiento y reparación</div>
          <div class="service-card-text">Prolonga la vida útil de tu equipo y corrige fallos críticos de hardware. Limpieza, reemplazo de piezas y más.</div>
        </div>
      </div>
      <div class="service-card reveal reveal-delay-3">
        <div class="service-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="13" rx="2"/><polyline points="1 21 23 21"/></svg></div>
        <div>
          <div class="service-card-title">Instalación y configuración</div>
          <div class="service-card-text">Formateo, instalación de sistema operativo, drivers y software. Tu equipo listo para trabajar desde el primer día.</div>
        </div>
      </div>
      <div class="service-card reveal reveal-delay-4">
        <div class="service-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div>
        <div>
          <div class="service-card-title">Optimización del sistema</div>
          <div class="service-card-text">Mejora el rendimiento y velocidad de tu equipo. Eliminamos procesos innecesarios, malware y optimizamos el arranque.</div>
        </div>
      </div>
    </div>
    <div class="services-cta reveal">
      <a href="registro.php" class="btn btn--primary">
        Cotizar ahora
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </a>
    </div>
  </div>
</section>

<section class="section section--alt" id="portal">
  <div class="container">
    <div class="portal-inner">
      <div>
        <p class="eyebrow reveal">Portal de clientes</p>
        <h2 class="portal-title reveal reveal-delay-1">Todo el control en un solo lugar</h2>
        <p class="portal-sub reveal reveal-delay-2">Gestiona tus servicios, revisa tu historial y mantente informado en cada etapa del proceso desde tu panel personalizado.</p>
        <div class="portal-features reveal reveal-delay-3">
          <div class="portal-feature-item">
            <div class="portal-feature-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/></svg></div>
            <div class="portal-feature-text">Gestión de tickets en tiempo real</div>
          </div>
          <div class="portal-feature-item">
            <div class="portal-feature-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg></div>
            <div class="portal-feature-text">Historial completo de servicios</div>
          </div>
          <div class="portal-feature-item">
            <div class="portal-feature-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div>
            <div class="portal-feature-text">Actualizaciones automáticas de estado</div>
          </div>
          <div class="portal-feature-item">
            <div class="portal-feature-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg></div>
            <div class="portal-feature-text">Contacto con técnicos vía WhatsApp</div>
          </div>
        </div>
        <a href="registro.php" class="btn btn--primary reveal reveal-delay-4">
          Crear cuenta gratis
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
      </div>
      <div class="reveal reveal-delay-2">
        <div class="portal-mockup">
          <div class="pm-bar">
            <div class="pm-dot" style="background:#ff5f57"></div>
            <div class="pm-dot" style="background:#febc2e"></div>
            <div class="pm-dot" style="background:#28c840"></div>
          </div>
          <div class="pm-content">
            <div class="pm-header">
              <div class="pm-title">Mis Tickets</div>
              <div class="pm-badge">3 activos</div>
            </div>
            <div class="pm-tickets">
              <div class="pm-ticket">
                <div class="pm-ticket-id">#MT-8842</div>
                <div class="pm-ticket-info">
                  <div class="pm-ticket-name">Laptop HP Pavilion</div>
                  <div class="pm-ticket-svc">Mantenimiento correctivo</div>
                </div>
                <div class="pm-status pm-status--diag">En diagnóstico</div>
              </div>
              <div class="pm-ticket">
                <div class="pm-ticket-id">#MT-8843</div>
                <div class="pm-ticket-info">
                  <div class="pm-ticket-name">PC Escritorio</div>
                  <div class="pm-ticket-svc">Repotenciación</div>
                </div>
                <div class="pm-status pm-status--rep">En reparación</div>
              </div>
              <div class="pm-ticket">
                <div class="pm-ticket-id">#MT-8844</div>
                <div class="pm-ticket-info">
                  <div class="pm-ticket-name">Laptop Apple MacBook</div>
                  <div class="pm-ticket-svc">Diagnóstico técnico</div>
                </div>
                <div class="pm-status pm-status--done">Completado ✓</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section" id="diferencial">
  <div class="container">
    <div class="diff-header">
      <p class="eyebrow reveal">Nuestra ventaja</p>
      <h2 class="reveal reveal-delay-1">¿Por qué elegir Morales Tech?</h2>
      <p class="reveal reveal-delay-2" style="max-width:480px;margin:14px auto 0;font-size:15px">Más que un servicio técnico, una experiencia de soporte pensada para el usuario.</p>
    </div>
    <div class="diff-grid">
      <div class="diff-card reveal reveal-delay-1">
        <div class="diff-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
        <div class="diff-title">Atención rápida</div>
        <div class="diff-text">Reducimos tiempos de espera con procesos optimizados y diagnóstico ágil desde el primer contacto.</div>
      </div>
      <div class="diff-card reveal reveal-delay-2">
        <div class="diff-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
        <div class="diff-title">Soporte transparente</div>
        <div class="diff-text">Información clara en cada etapa. Sabes exactamente qué se hace, cuánto cuesta y cuándo estará listo.</div>
      </div>
      <div class="diff-card reveal reveal-delay-3">
        <div class="diff-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
        <div class="diff-title">Centrado en el usuario</div>
        <div class="diff-text">Plataforma diseñada para ser simple e intuitiva. Sin tecnicismos innecesarios, solo resultados reales.</div>
      </div>
      <div class="diff-card reveal reveal-delay-4">
        <div class="diff-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div>
        <div class="diff-title">Seguimiento constante</div>
        <div class="diff-text">Nunca pierdes de vista el estado de tu equipo. Notificaciones y actualizaciones en cada cambio de estado.</div>
      </div>
    </div>
  </div>
</section>

<section class="cta-section">
  <div class="container">
    <div class="cta-inner">
      <div class="cta-tag reveal">
        <span class="tag">Ica, Perú · Atención inmediata</span>
      </div>
      <h2 class="cta-title reveal reveal-delay-1">¿Listo para solucionar tu <em>problema técnico?</em></h2>
      <p class="cta-sub reveal reveal-delay-2">Ingresa tu número de ticket y revisa el estado de tu equipo al instante, o crea una cuenta para cotizar y gestionar todos tus servicios.</p>
      <div class="cta-buttons reveal reveal-delay-3">
        <a href="consulta_tickets.php" class="btn--white">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a2 2 0 0 1 0-4V7a2 2 0 0 0-2-2H5z"/>
          </svg>
          Consultar mi ticket
        </a>
        <a href="https://wa.me/51903208170" target="_blank" rel="noopener" class="btn--whatsapp">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
          </svg>
          Escribir al técnico
        </a>
        <a href="registro.php" class="btn--ghost">Crear cuenta para cotizar →</a>
      </div>
    </div>
  </div>
</section>

<footer class="footer" id="contacto">
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
          <li><a href="#inicio">Inicio</a></li>
          <li><a href="#servicios">Servicios</a></li>
          <li><a href="#como-funciona">Cómo funciona</a></li>
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
