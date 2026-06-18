/* ── Navbar scroll ── */
(function initNavbar() {
  const navbar = document.getElementById('navbar');
  if (!navbar) return;
  window.addEventListener('scroll', () => {
    navbar.classList.toggle('scrolled', window.scrollY > 20);
  });
})();

/* ── Hamburger / Mobile Menu ── */
(function initMobileMenu() {
  const hamburger  = document.getElementById('hamburger');
  const mobileMenu = document.getElementById('mobile-menu');
  if (!hamburger || !mobileMenu) return;

  let menuOpen = false;

  hamburger.addEventListener('click', () => {
    menuOpen = !menuOpen;
    mobileMenu.classList.toggle('open', menuOpen);
    const spans = hamburger.querySelectorAll('span');
    if (menuOpen) {
      spans[0].style.transform = 'translateY(7px) rotate(45deg)';
      spans[1].style.opacity   = '0';
      spans[2].style.transform = 'translateY(-7px) rotate(-45deg)';
    } else {
      spans.forEach(s => { s.style.transform = ''; s.style.opacity = ''; });
    }
  });

  // Expuesto globalmente para los onclick en el HTML
  window.closeMobileMenu = function() {
    menuOpen = false;
    mobileMenu.classList.remove('open');
    hamburger.querySelectorAll('span').forEach(s => { s.style.transform = ''; s.style.opacity = ''; });
  };
})();

/* ── Reveal on scroll ── */
(function initReveal() {
  const reveals = document.querySelectorAll('.reveal');
  if (!reveals.length) return;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        e.target.classList.add('visible');
        observer.unobserve(e.target);
      }
    });
  }, { threshold: 0.10 });

  reveals.forEach(el => observer.observe(el));
})();

/* ── Active nav link on scroll (solo index.php) ── */
(function initActiveNavScroll() {
  const navLinks = document.querySelectorAll('.nav-links a[href^="#"]');
  if (!navLinks.length) return;

  window.addEventListener('scroll', () => {
    let current = 'inicio';
    document.querySelectorAll('section[id]').forEach(sec => {
      if (window.scrollY >= sec.offsetTop - 120) current = sec.getAttribute('id');
    });
    navLinks.forEach(a => {
      a.classList.toggle('active', a.getAttribute('href') === '#' + current);
    });
  });
})();

/* ── Toggle visibilidad de contraseña ── */
function setupPasswordToggle(btnId, inputId, showIconId, hideIconId) {
  const btn   = document.getElementById(btnId);
  const input = document.getElementById(inputId);
  const show  = document.getElementById(showIconId);
  const hide  = document.getElementById(hideIconId);
  if (!btn || !input) return;

  btn.addEventListener('click', () => {
    const visible   = input.type === 'password';
    input.type      = visible ? 'text'  : 'password';
    if (show) show.style.display = visible ? 'none'  : 'block';
    if (hide) hide.style.display = visible ? 'block' : 'none';
  });
}

/* ── LOGIN: toggle password ── */
(function initLoginPage() {
  setupPasswordToggle('pw-toggle', 'password', 'eye-show', 'eye-hide');
})();

/* ── REGISTRO: solo dígitos, strength, match, toggles ── */
(function initRegistroPage() {
  // Solo números
  ['dni', 'telefono', 'ruc'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('input', function() {
      this.value = this.value.replace(/\D/g, '');
    });
  });

  // Toggles contraseña
  setupPasswordToggle('pw-toggle-1', 'password', 'eye1-show', 'eye1-hide');
  setupPasswordToggle('pw-toggle-2', 'confirm',  'eye2-show', 'eye2-hide');

  // Fuerza de contraseña
  const bars   = ['bar1','bar2','bar3','bar4'].map(id => document.getElementById(id));
  const pwInput = document.getElementById('password');
  if (!pwInput || !bars[0]) return;

  const strengthLabels = ['', 'Débil', 'Regular', 'Buena', 'Fuerte'];
  const strengthColors = ['', '#ff5f57', '#f5a623', '#28c840', '#28c840'];

  window.checkStrength = function(val) {
    let score = 0;
    if (val.length >= 6)                                    score++;
    if (val.length >= 10)                                   score++;
    if (/[A-Z]/.test(val) && /[a-z]/.test(val))           score++;
    if (/[0-9]/.test(val) || /[^A-Za-z0-9]/.test(val))   score++;

    bars.forEach((b, i) => {
      if (!b) return;
      b.className = 'pw-strength-bar';
      if (val.length > 0 && i < score) b.classList.add('level-' + score);
    });

    const lbl = document.getElementById('pw-label');
    if (lbl) {
      lbl.textContent  = val.length > 0 ? strengthLabels[score] : '';
      lbl.style.color  = strengthColors[score];
    }
    window.checkMatch();
  };

  window.checkMatch = function() {
    const pw  = document.getElementById('password');
    const cf  = document.getElementById('confirm');
    const lbl = document.getElementById('match-label');
    if (!pw || !cf || !lbl) return;
    if (!cf.value) { lbl.textContent = ''; return; }
    if (pw.value === cf.value) {
      lbl.textContent = '✓ Contraseñas coinciden';
      lbl.style.color = '#28c840';
    } else {
      lbl.textContent = '✗ No coinciden';
      lbl.style.color = '#ff5f57';
    }
  };
})();


/* ── CONSULTA TICKETS ── */
(function initConsultaTickets() {
  const btnConsultar = document.getElementById('btnConsultar');
  const ticketInput  = document.getElementById('ticketInput');
  if (!btnConsultar || !ticketInput) return;

  const PROGRESS = { 1: 12, 2: 40, 3: 72, 4: 100 };

  function normalize(raw) {
    return raw.trim().toUpperCase().replace(/^MT-?(\d)/i, 'MT-$1');
  }

  function setBtnLoading(loading) {
    btnConsultar.disabled      = loading;
    btnConsultar.style.opacity = loading ? '.6' : '';
    btnConsultar.innerHTML     = loading
      ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="animation:ct-spin .8s linear infinite"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-.18-7.3"/></svg> Buscando\u2026'
      : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg> Consultar estado';
  }

  function renderTicket(data) {
    document.getElementById('rcTicketId').textContent = 'Ticket #' + data.codigo;
    document.getElementById('rcDevice').textContent   = data.device + ' \u00b7 ' + data.service;

    const badge = document.getElementById('rcStatusBadge');
    badge.textContent = data.statusLabel;
    badge.className   = 'status-badge ' + data.statusClass;

    setTimeout(function() {
      var fill = document.getElementById('rcBarFill');
      if (fill) fill.style.width = (PROGRESS[data.statusNum] || 12) + '%';
    }, 100);

    for (var i = 1; i <= 4; i++) {
      var el = document.getElementById('step' + i);
      if (!el) continue;
      el.className = 'rcp-step';
      if (i < data.statusNum)  el.classList.add('done');
      if (i === data.statusNum) el.classList.add('active');
    }

    document.getElementById('rcUpdateText').textContent = data.updateText;

    var etaEl = document.getElementById('rcEta');
    if (etaEl) {
      etaEl.innerHTML =
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:12px;height:12px"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>' + data.eta;
    }

    document.getElementById('rcdFecha').textContent    = data.fecha;
    document.getElementById('rcdServicio').textContent = data.service;
    document.getElementById('rcdEstado').textContent   = data.statusLabel;

    var adicEl = document.getElementById('rcdAdicionales');
    if (adicEl) {
      adicEl.textContent = (data.adicionales && data.adicionales.length)
        ? data.adicionales.join(', ')
        : 'Ninguno';
    }
  }

  function showError(rawInput) {
    var empty = document.getElementById('resultEmpty');
    var card  = document.getElementById('resultCard');
    card.classList.remove('visible');
    empty.style.display = '';
    var title = empty.querySelector('.result-empty-title');
    var text  = empty.querySelector('.result-empty-text');
    if (title) title.textContent = 'Ticket no encontrado';
    if (text)  text.innerHTML =
      'No encontramos un ticket con el c\u00f3digo <strong style="color:var(--txt-main)">' + (rawInput || '\u2014') + '</strong>. Verifica el c\u00f3digo e int\u00e9ntalo de nuevo, o <a href="https://wa.me/51903208170" target="_blank" style="color:#8db4ff;font-weight:600">cont\u00e1ctanos por WhatsApp</a>.';
  }

  function doSearch() {
    var raw  = ticketInput.value.trim();
    var code = normalize(raw);
    var fill = document.getElementById('rcBarFill');
    if (fill) fill.style.width = '0%';
    if (!raw) return;

    setBtnLoading(true);

    fetch('consulta_tickets.php?ajax=1&codigo=' + encodeURIComponent(code))
      .then(function(r) { return r.json(); })
      .then(function(data) {
        setBtnLoading(false);
        var empty = document.getElementById('resultEmpty');
        var card  = document.getElementById('resultCard');
        if (data.found) {
          empty.style.display = 'none';
          card.classList.add('visible');
          renderTicket(data);
          card.scrollIntoView({ behavior: 'smooth', block: 'start' });
        } else {
          showError(raw);
        }
      })
      .catch(function() {
        setBtnLoading(false);
        showError(raw);
      });
  }

  btnConsultar.addEventListener('click', doSearch);
  ticketInput.addEventListener('keydown', function(e) { if (e.key === 'Enter') doSearch(); });
})();


/* ══════════════════════════════════════════
   DASHBOARD — inicio_clientes, tickets_cliente, nuevo_ticket_cliente
   ══════════════════════════════════════════ */

/* ── Hamburger del dashboard (usa mob-menu en lugar de mobile-menu) ── */
(function initDashMobileMenu() {
  const hamburger = document.getElementById('hamburger');
  const mobMenu   = document.getElementById('mob-menu');
  if (!hamburger || !mobMenu) return;

  let menuOpen = false;

  hamburger.addEventListener('click', () => {
    menuOpen = !menuOpen;
    mobMenu.classList.toggle('open', menuOpen);
    const spans = hamburger.querySelectorAll('span');
    if (menuOpen) {
      spans[0].style.transform = 'translateY(7px) rotate(45deg)';
      spans[1].style.opacity   = '0';
      spans[2].style.transform = 'translateY(-7px) rotate(-45deg)';
    } else {
      spans.forEach(s => { s.style.transform = ''; s.style.opacity = ''; });
    }
  });

  window.closeDashMenu = function() {
    menuOpen = false;
    mobMenu.classList.remove('open');
    hamburger.querySelectorAll('span').forEach(s => { s.style.transform = ''; s.style.opacity = ''; });
  };
})();

/* ── Modal de tickets (tickets_cliente) ── */
(function initTicketsModal() {
  const overlay = document.getElementById('modal-overlay');
  if (!overlay) return;

  const PRECIOS = {
    'Diagnóstico': 30, 'Mantenimiento preventivo': 60, 'Mantenimiento correctivo': 90,
    'Instalación / Formateo': 80, 'Reparación': 90, 'Repotenciación (mano de obra)': 50,
    'Limpieza preventiva': 60, 'Limpieza profunda': 25,
    'Instalación de programas': 20, 'Optimización del sistema': 30,
  };

  const ESTADO_COLORES = {
    'Recibido':           { bg: 'rgba(245,166,35,.18)',  color: '#f5c048', dot: '#f5a623' },
    'En proceso':         { bg: 'rgba(23,70,234,.22)',   color: '#8db4ff', dot: '#1746EA' },
    'Listo para entrega': { bg: 'rgba(201,74,0,.20)',    color: '#f5a07a', dot: '#e85d04' },
    'Completado':         { bg: 'rgba(26,122,74,.22)',   color: '#5fc98a', dot: '#1a7a4a' },
  };

  window.abrirModal = function(id, equipo, so, servicio, adicionales, estado, fecha, subtotal, igv, total, obs) {
    document.getElementById('m-id').textContent     = '#' + id;
    document.getElementById('m-equipo').textContent = equipo;
    document.getElementById('m-estado').textContent = estado;

    const ec = ESTADO_COLORES[estado] || { bg: 'rgba(100,100,120,.18)', color: '#a0a8bb', dot: '#7a8096' };
    const badge = document.getElementById('m-estado-badge');
    const dot   = document.getElementById('m-estado-dot');
    if (badge) { badge.style.background = ec.bg; badge.style.color = ec.color; }
    if (dot)   { dot.style.background   = ec.dot; }
    document.getElementById('m-tipo').textContent   = equipo;
    document.getElementById('m-so').textContent     = so || '—';
    document.getElementById('m-fecha').textContent  = fecha;
    document.getElementById('m-subtotal').textContent = subtotal;
    document.getElementById('m-igv').textContent      = igv;
    document.getElementById('m-total').textContent  = total;

    const obsEl    = document.getElementById('m-obs');
    const obsBlock = document.getElementById('m-obs-block');
    if (obsEl && obsBlock) {
      if (obs && obs.trim()) {
        obsEl.textContent      = obs;
        obsBlock.style.display = '';
      } else {
        obsBlock.style.display = 'none';
      }
    }

    let html = `<div class="dash-modal-svc-item dash-modal-svc-item--main">
      <span class="dash-modal-svc-dot" style="background:#6fa3ff;"></span>
      <span class="dash-modal-svc-name">${servicio}</span>
      <span class="dash-modal-svc-price">S/ ${((PRECIOS[servicio] || 0)).toFixed(2)}</span>
    </div>`;
    if (adicionales && adicionales.length) {
      adicionales.forEach(a => {
        html += `<div class="dash-modal-svc-item">
          <span class="dash-modal-svc-dot" style="background:#3a4470;"></span>
          <span class="dash-modal-svc-name">${a}</span>
          <span class="dash-modal-svc-price" style="color:#6b74a8;">S/ ${((PRECIOS[a] || 0)).toFixed(2)}</span>
        </div>`;
      });
    }

    const svcList = document.getElementById('m-servicios');
    if (svcList) svcList.innerHTML = html;
    overlay.classList.add('show');
  };

  window.cerrarModal = function() { overlay.classList.remove('show'); };
  window.cerrarOverlay = function(e) { if (e.target === overlay) cerrarModal(); };
})();

/* ── Modal de detalle de ticket (inicio_clientes) ── */
(function initDetalleTicketModal() {
  const overlay = document.getElementById('modal-detalle-ticket');
  if (!overlay) return;

  window.verDetalleTicket = function(codigo) {
    const t = (typeof TICKETS_DETALLE !== 'undefined') ? TICKETS_DETALLE[codigo] : null;
    if (!t) return;

    document.getElementById('dt-codigo').textContent = '#' + t.codigo;
    document.getElementById('dt-fecha').textContent  = t.fecha;

    const ec2 = ESTADO_COLORES[t.estado] || { bg: 'rgba(100,100,120,.18)', color: '#a0a8bb', dot: '#7a8096' };
    const dtEstado = document.getElementById('dt-estado');
    if (dtEstado) {
      dtEstado.textContent = t.estado;
      dtEstado.style.cssText = `background:${ec2.bg};color:${ec2.color};padding:2px 10px;border-radius:50px;font-weight:600;font-size:11px;`;
    }

    const left = document.getElementById('dt-summary-left');
    if (left) {
      left.innerHTML = `
        <div class="wizard-summary-block">
          <div class="wizard-summary-block__label">Dispositivo</div>
          <div class="wizard-summary-block__value">
            <strong>${t.equipo}</strong>${t.so ? ' · ' + t.so : ''}
            ${t.observaciones ? '<br><span style="font-size:12px;color:#6b74a8;font-style:italic">' + t.observaciones + '</span>' : ''}
          </div>
        </div>
        <div class="wizard-summary-block">
          <div class="wizard-summary-block__label">Solicitante</div>
          <div class="wizard-summary-block__value">
            <strong>${DT_CLIENTE_NOMBRE}</strong><br>
            <span style="color:#6b74a8">${DT_CLIENTE_EMAIL}</span>
          </div>
        </div>`;
    }

    const quoteBox = document.getElementById('dt-summary-quote');
    if (quoteBox) {
      quoteBox.innerHTML = `
        <div class="wizard-quote-box__title">Cotización</div>
        ${t.servicios.map(s => `
          <div class="wizard-quote-item">
            <span class="wizard-quote-item__name">${s.nombre}</span>
            <span class="wizard-quote-item__price">S/ ${s.precio.toFixed(2)}</span>
          </div>`).join('')}
        <hr class="wizard-q-divider">
        <div class="wizard-q-row"><span>Subtotal</span><span>S/ ${t.subtotal.toFixed(2)}</span></div>
        <div class="wizard-q-row"><span>IGV (18%)</span><span>S/ ${t.igv.toFixed(2)}</span></div>
        <hr class="wizard-q-divider">
        <div class="wizard-q-total">
          <span class="wizard-q-total__label">Total</span>
          <span class="wizard-q-total__amount">S/ ${t.total.toFixed(2)}</span>
        </div>
        <p class="wizard-quote-note">* Cotización referencial. El precio final puede variar según diagnóstico.</p>`;
    }

    overlay.classList.add('show');
  };

  window.cerrarDetalleTicket  = function() { overlay.classList.remove('show'); };
  window.cerrarDetalleOverlay = function(e) { if (e.target === overlay) cerrarDetalleTicket(); };
})();

/* ── Wizard (nuevo_ticket_cliente) ── */
(function initWizard() {
  if (!document.getElementById('step-1')) return;

  let currentStep = 1;
  const TOTAL     = 3;
  const progress  = [33, 66, 100];

  window.goStep = function(n) {
    if (n > currentStep + 1 || n < 1) return;
    if (n > currentStep && !validateStep(currentStep)) return;
    document.getElementById('step-' + currentStep).classList.remove('active');
    document.getElementById('nav-'  + currentStep).classList.remove('active');
    if (n > currentStep) document.getElementById('nav-' + currentStep).classList.add('done');
    currentStep = n;
    document.getElementById('step-' + currentStep).classList.add('active');
    document.getElementById('nav-'  + currentStep).classList.remove('done');
    document.getElementById('nav-'  + currentStep).classList.add('active');
    document.getElementById('progress-fill').style.width = progress[currentStep - 1] + '%';
    if (currentStep === 3) buildSummary();
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  window.nextStep = function(from) {
    if (!validateStep(from)) return;
    if (from < TOTAL) goStep(from + 1);
  };

  window.prevStep = function(from) {
    if (from <= 1) return;
    document.getElementById('step-' + from).classList.remove('active');
    document.getElementById('nav-'  + from).classList.remove('active', 'done');
    currentStep = from - 1;
    document.getElementById('step-' + currentStep).classList.add('active');
    document.getElementById('nav-'  + currentStep).classList.add('active');
    document.getElementById('nav-'  + currentStep).classList.remove('done');
    document.getElementById('progress-fill').style.width = progress[currentStep - 1] + '%';
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  function showToast(msg) {
    var t = document.getElementById('wiz-toast');
    if (!t) {
      t = document.createElement('div');
      t.id = 'wiz-toast';
      t.style.cssText = [
        'position:fixed;bottom:28px;left:50%;transform:translateX(-50%) translateY(16px)',
        'background:#1a1f35;border:1.5px solid rgba(248,113,113,.35)',
        'color:#f87171;padding:13px 22px;border-radius:12px',
        'font-size:14px;font-family:inherit;z-index:9999',
        'opacity:0;transition:opacity .22s,transform .22s',
        'pointer-events:none;white-space:nowrap',
        'box-shadow:0 6px 28px rgba(0,0,0,.5)',
        'display:flex;align-items:center;gap:10px'
      ].join(';');
      t.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg><span></span>';
      document.body.appendChild(t);
    }
    t.querySelector('span').textContent = msg;
    t.style.opacity = '1';
    t.style.transform = 'translateX(-50%) translateY(0)';
    clearTimeout(t._hide);
    t._hide = setTimeout(function() {
      t.style.opacity = '0';
      t.style.transform = 'translateX(-50%) translateY(16px)';
    }, 3000);
  }

  function validateStep(n) {
    if (n === 1 && !document.getElementById('tipo_dispositivo').value) {
      showToast('Selecciona el tipo de dispositivo para continuar.');
      return false;
    }
    if (n === 2 && !document.querySelector('input[name="srv_base"]:checked')) {
      showToast('Selecciona al menos un servicio principal para continuar.');
      return false;
    }
    return true;
  }

  /* Selector de dispositivo */
  window.selectDevice = function(el, type) {
    document.querySelectorAll('.device-opt').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('tipo_dispositivo').value = type;
    document.getElementById('extra-laptop').classList.toggle('visible', type === 'Laptop');
    document.getElementById('extra-pc').classList.toggle('visible',     type === 'PC');
  };

  /* Accordion adicionales */
  window.toggleAdd = function() {
    document.getElementById('add-toggle').classList.toggle('open');
    document.getElementById('add-panel').classList.toggle('open');
  };

  /* Cotización en vivo */
  window.updateQuote = function() {
    document.querySelectorAll('#servicios-base .wizard-service-item').forEach(row => {
      const r = row.querySelector('input[type="radio"]');
      row.classList.toggle('selected', r && r.checked);
    });
    document.querySelectorAll('#add-panel .wizard-service-item').forEach(row => {
      const c = row.querySelector('input[type="checkbox"]');
      row.classList.toggle('sel-add', c && c.checked);
    });
    const n     = document.querySelectorAll('#add-panel input[type="checkbox"]:checked').length;
    const badge = document.getElementById('add-badge');
    if (badge) {
      badge.textContent = n;
      badge.classList.toggle('hidden', n === 0);
    }
  };

  function getItems() {
    const items = [];
    const base  = document.querySelector('input[name="srv_base"]:checked');
    if (base) items.push({ name: base.dataset.nombre, price: parseFloat(base.value) });
    document.querySelectorAll('#add-panel input[type="checkbox"]:checked').forEach(cb => {
      items.push({ name: cb.dataset.nombre, price: parseFloat(cb.value) });
    });
    return items;
  }

  function buildSummary() {
    const tipo  = document.getElementById('tipo_dispositivo').value || '—';
    const marca = document.getElementById('marca')?.value || '';
    const obs   = document.getElementById('observaciones')?.value || '';
    const nombreCliente = typeof CLIENTE_NOMBRE !== 'undefined' ? CLIENTE_NOMBRE : '';
    const emailCliente  = typeof CLIENTE_EMAIL  !== 'undefined' ? CLIENTE_EMAIL  : '';

    const summaryLeft = document.getElementById('summary-left');
    if (summaryLeft) {
      summaryLeft.innerHTML = `
        <div class="wizard-summary-block">
          <div class="wizard-summary-block__label">Dispositivo</div>
          <div class="wizard-summary-block__value">
            <strong>${tipo}</strong>${marca ? ' · ' + marca : ''}
            ${obs ? '<br><span style="font-size:12px;color:#6b74a8;font-style:italic">' + obs + '</span>' : ''}
          </div>
        </div>
        <div class="wizard-summary-block">
          <div class="wizard-summary-block__label">Solicitante</div>
          <div class="wizard-summary-block__value">
            <strong>${nombreCliente}</strong><br>
            <span style="color:#6b74a8">${emailCliente}</span>
          </div>
        </div>`;
    }

    const items     = getItems();
    const quoteBox  = document.getElementById('summary-quote');
    if (!quoteBox) return;

    if (!items.length) {
      quoteBox.innerHTML = `<div class="wizard-quote-box__title">Cotización estimada</div>
        <p style="opacity:.65;font-size:13px;text-align:center;padding:20px 0">Sin servicios seleccionados.</p>`;
      return;
    }

    const subtotal = items.reduce((a, i) => a + i.price, 0);
    const igv      = subtotal * 0.18;
    const total    = subtotal + igv;

    quoteBox.innerHTML = `
      <div class="wizard-quote-box__title">Cotización estimada</div>
      ${items.map(i => `
        <div class="wizard-quote-item">
          <span class="wizard-quote-item__name">${i.name}</span>
          <span class="wizard-quote-item__price">S/ ${i.price.toFixed(2)}</span>
        </div>`).join('')}
      <hr class="wizard-q-divider">
      <div class="wizard-q-row"><span>Subtotal</span><span>S/ ${subtotal.toFixed(2)}</span></div>
      <div class="wizard-q-row"><span>IGV (18%)</span><span>S/ ${igv.toFixed(2)}</span></div>
      <hr class="wizard-q-divider">
      <div class="wizard-q-total">
        <span class="wizard-q-total__label">Total</span>
        <span class="wizard-q-total__amount">S/ ${total.toFixed(2)}</span>
      </div>
      <p class="wizard-quote-note">* Cotización referencial. El precio final puede variar según diagnóstico.</p>`;
  }

  /* Enviar solicitud: guarda equipo + cotización + servicios + ticket en la BD */
  window.enviarSolicitud = function() {
    const tipo     = document.getElementById('tipo_dispositivo').value;
    const esLaptop = tipo === 'Laptop';
    const marca  = (esLaptop ? document.getElementById('marca')?.value  : '') || '';
    const modelo = (esLaptop ? document.getElementById('modelo')?.value : '') || '';
    const serie  = (esLaptop ? document.getElementById('serie')?.value  : '') || '';
    const so     = (esLaptop
      ? document.getElementById('so-laptop')?.value
      : document.getElementById('so-pc')?.value) || '';
    const observaciones = document.getElementById('observaciones')?.value || '';
    const servicios = getItems().map(i => ({ nombre: i.name, precio: i.price }));

    const btn = document.querySelector('.btn-wizard-send');
    const textoOriginal = btn ? btn.innerHTML : '';
    if (btn) { btn.disabled = true; btn.style.opacity = '.6'; btn.textContent = 'Enviando…'; }

    function restaurarBoton() {
      if (btn) { btn.disabled = false; btn.style.opacity = ''; btn.innerHTML = textoOriginal; }
    }

    const idEquipoGuardado = parseInt(document.getElementById('id_equipo_guardado')?.value || '0') || 0;
    // Siempre enviar idEquipo: 0 = nuevo, N = reusar existente
    const payload = { tipo, marca, modelo, serie, so, observaciones, servicios, idEquipo: idEquipoGuardado };

    fetch('nuevo_ticket_cliente.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          const modal = document.getElementById('modal-success');
          if (modal) {
            modal.style.opacity      = '1';
            modal.style.pointerEvents = 'all';
            modal.classList.add('show');
          }
        } else {
          alert(data.message || 'No se pudo enviar la solicitud. Inténtalo de nuevo.');
          restaurarBoton();
        }
      })
      .catch(() => {
        alert('Ocurrió un error de conexión. Inténtalo de nuevo.');
        restaurarBoton();
      });
  };
})();

/* ══════════════════════════════════════════
   EQUIPOS GUARDADOS — nuevo_ticket_cliente.php
   Preselección manejada server-side via ?idEquipo=N
   ══════════════════════════════════════════ */

/* ══════════════════════════════════════════
   ADMIN PORTAL — login_staff & registro_staff
   ══════════════════════════════════════════ */

(function initAdminAuth() {
  const ALLOWED_DOMAIN = 'moralestechs.com';

  /* ── Validación de dominio en tiempo real ── */
  window.adminCheckDomain = function(input) {
    const hint = document.getElementById('domain-hint');
    if (!hint) return;
    const val = input.value;
    if (!val.includes('@')) {
      hint.classList.remove('visible');
      input.classList.remove('input-error');
      return;
    }
    const domain = val.split('@')[1] || '';
    const ok = domain === '' || domain === ALLOWED_DOMAIN || ALLOWED_DOMAIN.startsWith(domain);
    hint.classList.toggle('visible', !ok && domain !== '');
    input.classList.toggle('input-error', !ok && domain !== '');
  };

  /* Blur: valida dominio completo */
  const emailEl = document.getElementById('email');
  if (emailEl) {
    emailEl.addEventListener('blur', function() {
      if (!this.value.includes('@')) return;
      const domain = this.value.split('@')[1] || '';
      const hint   = document.getElementById('domain-hint');
      if (domain && domain !== ALLOWED_DOMAIN && hint) {
        hint.classList.add('visible');
        this.classList.add('input-error');
      }
    });
  }

  /* ── Toggle contraseña — login_staff ── */
  setupAdminToggle('pw-toggle-staff', 'password', 'eye-show-staff', 'eye-hide-staff');

  /* ── Toggles contraseña — registro_staff ── */
  setupAdminToggle('pw-toggle-reg1', 'password',  'eye-show-reg1', 'eye-hide-reg1');
  setupAdminToggle('pw-toggle-reg2', 'password2', 'eye-show-reg2', 'eye-hide-reg2');

  function setupAdminToggle(btnId, inputId, showId, hideId) {
    const btn   = document.getElementById(btnId);
    const input = document.getElementById(inputId);
    const show  = document.getElementById(showId);
    const hide  = document.getElementById(hideId);
    if (!btn || !input) return;
    btn.addEventListener('click', () => {
      const visible  = input.type === 'password';
      input.type     = visible ? 'text'  : 'password';
      if (show) show.style.display = visible ? 'none'  : 'block';
      if (hide) hide.style.display = visible ? 'block' : 'none';
    });
  }

  /* ── Indicador de fuerza — registro_staff ── */
  const strengthLevels = ['', 'Débil', 'Regular', 'Buena', 'Segura'];
  const strengthColors = ['', '#e05040', '#f59e0b', '#3b82f6', '#22c55e'];

  window.adminUpdateStrength = function(val) {
    const wrap  = document.getElementById('admin-pw-strength');
    const label = document.getElementById('admin-pw-strength-label');
    if (!wrap) return;
    if (!val) { wrap.style.display = 'none'; return; }
    wrap.style.display = 'flex';

    let score = 0;
    if (val.length >= 8)           score++;
    if (/[A-Z]/.test(val))         score++;
    if (/[0-9]/.test(val))         score++;
    if (/[^A-Za-z0-9]/.test(val))  score++;

    wrap.className = 'admin-pw-strength admin-strength-' + score;
    if (label) {
      label.textContent  = strengthLevels[score] || 'Débil';
      label.style.color  = strengthColors[score]  || '#9aa2bf';
    }
    adminCheckMatch();
  };

  /* ── Coincidencia contraseñas — registro_staff ── */
  window.adminCheckMatch = function() {
    const p1 = document.getElementById('password');
    const p2 = document.getElementById('password2');
    if (!p1 || !p2 || p2.value.length === 0) {
      if (p2) p2.classList.remove('input-error');
      return;
    }
    p2.classList.toggle('input-error', p1.value !== p2.value);
  };
})();

/* ══════════════════════════════════════════
   TICKETS.PHP — actualización de estado con select
   ══════════════════════════════════════════ */

(function initTicketsAdmin() {
  const BADGE_MAP = {
    'Recibido':       'dash-badge--recibido',
    'En diagnóstico': 'dash-badge--diagnostico',
    'En reparación':  'dash-badge--reparacion',
    'Completado':     'dash-badge--completado',
  };
  const ALL_BADGES = Object.values(BADGE_MAP).concat(['dash-badge--default']);

  window.tkActualizarEstado = function(select) {
    ALL_BADGES.forEach(cls => select.classList.remove(cls));
    select.classList.add(BADGE_MAP[select.value] || 'dash-badge--default');

    const codigo = select.dataset.id;
    if (!codigo) return;
    select.disabled = true;
    fetch('tickets.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ codigo, estado: select.value })
    })
      .then(r => r.json())
      .then(data => { if (!data.success) alert('No se pudo actualizar el estado.'); })
      .catch(() => alert('Error de conexión al actualizar el estado.'))
      .finally(() => { select.disabled = false; });
  };
})();


/* ════════════════════════════════════════════
   NUEVO_TICKET.PHP — wizard staff (4 pasos)
   ══════════════════════════════════════════ */
 
(function initNuevoTicket() {
  if (!document.getElementById('step-1')) return;
 
  let currentStep = 1;
  const TOTAL    = 4;
  const PROGRESS = [25, 50, 75, 100];

  /* ── Estado del equipo del cliente (Paso 2) ── */
  let ntkEquiposGuardados = [];   // equipos del cliente actual, traídos por AJAX
  let ntkIdEquipoSel      = 0;    // 0 = equipo nuevo / sin cliente con equipos
 
  /* ── Navegación ── */
  window.ntkGoStep = function(n) {
    if (n > currentStep + 1 || n < 1) return;
    document.getElementById('step-'  + currentStep).classList.remove('active');
    document.getElementById('nav-'   + currentStep).classList.remove('active');
    if (n > currentStep)
      document.getElementById('nav-' + currentStep).classList.add('done');
    currentStep = n;
    document.getElementById('step-'  + currentStep).classList.add('active');
    document.getElementById('nav-'   + currentStep).classList.remove('done');
    document.getElementById('nav-'   + currentStep).classList.add('active');
    document.getElementById('ntk-progress-fill').style.width = PROGRESS[currentStep - 1] + '%';
    if (currentStep === 4) ntkBuildSummary();
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };
 
  window.ntkNextStep = function(from) {
    if (!ntkValidateStep(from)) return;
    if (from < TOTAL) ntkGoStep(from + 1);
  };
 
  window.ntkPrevStep = function(from) {
    if (from <= 1) return;
    document.getElementById('step-' + from).classList.remove('active');
    document.getElementById('nav-'  + from).classList.remove('active', 'done');
    currentStep = from - 1;
    document.getElementById('step-' + currentStep).classList.add('active');
    document.getElementById('nav-'  + currentStep).classList.add('active');
    document.getElementById('nav-'  + currentStep).classList.remove('done');
    document.getElementById('ntk-progress-fill').style.width = PROGRESS[currentStep - 1] + '%';
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };
 
  /* ── Validaciones ── */
  function ntkValidateStep(n) {
    if (n === 1) {
      const dni = document.getElementById('ntk-dni')?.value.trim()       || '';
      const nom = document.getElementById('ntk-nombres')?.value.trim()   || '';
      const ape = document.getElementById('ntk-apellidos')?.value.trim() || '';
      const tel = document.getElementById('ntk-telefono')?.value.trim()  || '';
      if (!dni || dni.length !== 8)  { alert('El DNI debe tener 8 dígitos.');        return false; }
      if (!nom)                      { alert('Ingresa los nombres del cliente.');     return false; }
      if (!ape)                      { alert('Ingresa los apellidos del cliente.');   return false; }
      if (!tel || tel.length !== 9)  { alert('El teléfono debe tener 9 dígitos.');   return false; }
    }
    if (n === 2 && ntkIdEquipoSel === 0 && !document.getElementById('ntk-tipo-dispositivo')?.value) {
      alert('Selecciona un equipo guardado o registra uno nuevo.'); return false;
    }
    if (n === 3 && !document.querySelector('input[name="ntk_srv_base"]:checked')) {
      alert('Selecciona al menos un servicio base.'); return false;
    }
    return true;
  }
 
  /* ── Selector de dispositivo ── */
  window.ntkSelectDevice = function(el, type) {
    document.querySelectorAll('.ntk-device-opt').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('ntk-tipo-dispositivo').value = type;
    document.getElementById('extra-laptop').classList.toggle('visible', type === 'Laptop');
    document.getElementById('extra-pc').classList.toggle('visible',     type === 'PC');
  };

  /* ══════════════════════════════════════════
     EQUIPOS DEL CLIENTE (Paso 2)
     ══════════════════════════════════════════ */

  /* Pinta las tarjetas de equipos guardados + tarjeta "Registrar Nuevo Equipo" */
  function ntkPintarEquiposGuardados(equipos) {
    ntkEquiposGuardados = Array.isArray(equipos) ? equipos : [];
    const wrap  = document.getElementById('ntk-eq-saved-wrap');
    const cards = document.getElementById('ntk-eq-cards');
    if (!wrap || !cards) return;

    const nuevoCardHtml = `
      <button type="button" class="ntk-eq-saved-card ntk-eq-saved-card--new" id="ntk-btn-nuevo-equipo" onclick="ntkMostrarFormNuevoEquipo()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;flex-shrink:0;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Registrar Nuevo Equipo
      </button>`;

    if (!ntkEquiposGuardados.length) {
      wrap.classList.add('nv-hidden');
      cards.innerHTML = nuevoCardHtml;
      ntkMostrarFormNuevoEquipo();
      return;
    }

    const guardadosHtml = ntkEquiposGuardados.map(eq => {
      const esLaptop = (eq.tipoEquipo || '') === 'Laptop';
      let label = [eq.marca, eq.modelo].filter(Boolean).join(' ').trim();
      if (!label) label = esLaptop ? 'Laptop' : 'PC de escritorio';
      const sub = eq.sistemaOperativo || (esLaptop ? 'Laptop' : 'PC de escritorio');
      return `
        <button type="button" class="ntk-eq-saved-card" data-id-equipo="${eq.idEquipo}" onclick="ntkSeleccionarEquipoGuardado(${eq.idEquipo})">
          <span style="font-weight:600;font-size:13px;">${ntkEscapeHtml(label)}</span>
          <span style="font-size:11px;opacity:.65;margin-top:2px;">${ntkEscapeHtml(sub)}</span>
        </button>`;
    }).join('');

    cards.innerHTML = guardadosHtml + nuevoCardHtml;

    wrap.classList.remove('nv-hidden');
    // Por defecto, al cargar equipos guardados se pide elegir uno o registrar nuevo:
    // ocultamos el formulario hasta que el usuario decida.
    ntkOcultarFormEquipo();
  }

  function ntkEscapeHtml(str) {
    const d = document.createElement('div');
    d.textContent = str == null ? '' : String(str);
    return d.innerHTML;
  }

  /* Oculta el formulario de equipo (cuando aún no se eligió tarjeta ni "nuevo") */
  function ntkOcultarFormEquipo() {
    document.getElementById('ntk-equipo-form')?.classList.add('nv-hidden');
    document.getElementById('ntk-equipo-preview')?.classList.add('nv-hidden');
  }

  /* Click en "+ Registrar Nuevo Equipo": limpia selección y muestra el formulario normal */
  window.ntkMostrarFormNuevoEquipo = function() {
    ntkIdEquipoSel = 0;
    document.getElementById('ntk-id-equipo').value = '0';

    document.querySelectorAll('.ntk-eq-saved-card').forEach(c => c.classList.remove('ntk-eq-saved-card--sel'));
    document.getElementById('ntk-btn-nuevo-equipo')?.classList.add('ntk-eq-saved-card--sel');

    document.getElementById('ntk-equipo-preview')?.classList.add('nv-hidden');
    document.getElementById('ntk-equipo-form')?.classList.remove('nv-hidden');

    // Limpia el formulario para que no queden datos de un equipo guardado
    document.querySelectorAll('.ntk-device-opt').forEach(o => o.classList.remove('selected'));
    document.getElementById('ntk-tipo-dispositivo').value = '';
    document.getElementById('extra-laptop')?.classList.remove('visible');
    document.getElementById('extra-pc')?.classList.remove('visible');
    ['ntk-marca', 'ntk-modelo', 'ntk-serie'].forEach(id => { const el = document.getElementById(id); if (el) el.value = ''; });
    const soL = document.getElementById('ntk-so-laptop'); if (soL) soL.value = '';
    const soP = document.getElementById('ntk-so-pc');     if (soP) soP.value = '';
  };

  /* Click en una tarjeta de equipo guardado: oculta el formulario y muestra el resumen */
  window.ntkSeleccionarEquipoGuardado = function(idEquipo) {
    const eq = ntkEquiposGuardados.find(e => Number(e.idEquipo) === Number(idEquipo));
    if (!eq) return;

    ntkIdEquipoSel = Number(idEquipo);
    document.getElementById('ntk-id-equipo').value = String(ntkIdEquipoSel);

    document.querySelectorAll('.ntk-eq-saved-card').forEach(c => {
      c.classList.toggle('ntk-eq-saved-card--sel', Number(c.dataset.idEquipo) === ntkIdEquipoSel);
    });
    document.getElementById('ntk-btn-nuevo-equipo')?.classList.remove('ntk-eq-saved-card--sel');

    const esLaptop = (eq.tipoEquipo || '') === 'Laptop';
    let titulo = [eq.marca, eq.modelo].filter(Boolean).join(' ').trim();
    if (!titulo) titulo = esLaptop ? 'Laptop' : 'PC de escritorio';
    const sub = [eq.tipoEquipo, eq.sistemaOperativo, eq.numSerie ? ('S/N ' + eq.numSerie) : '']
      .filter(Boolean).join(' · ');

    const titleEl = document.getElementById('ntk-equipo-preview__title');
    const subEl   = document.getElementById('ntk-equipo-preview__sub');
    if (titleEl) titleEl.textContent = titulo;
    if (subEl)   subEl.textContent   = sub || '—';

    document.getElementById('ntk-equipo-form')?.classList.add('nv-hidden');
    document.getElementById('ntk-equipo-preview')?.classList.remove('nv-hidden');
  };

  /* Reinicia toda la sección de equipos (p. ej. cuando cambia el DNI) */
  function ntkResetSeccionEquipos() {
    ntkEquiposGuardados = [];
    ntkIdEquipoSel = 0;
    document.getElementById('ntk-id-equipo').value = '0';
    document.getElementById('ntk-eq-saved-wrap')?.classList.add('nv-hidden');
    document.getElementById('ntk-eq-cards').innerHTML = '';
    document.getElementById('ntk-equipo-preview')?.classList.add('nv-hidden');
    document.getElementById('ntk-equipo-form')?.classList.remove('nv-hidden');
    document.querySelectorAll('.ntk-device-opt').forEach(o => o.classList.remove('selected'));
    document.getElementById('ntk-tipo-dispositivo').value = '';
    document.getElementById('extra-laptop')?.classList.remove('visible');
    document.getElementById('extra-pc')?.classList.remove('visible');
  }

  async function ntkCargarEquiposCliente(idCliente) {
    if (!idCliente) { ntkResetSeccionEquipos(); return; }
    try {
      const res  = await fetch(`nuevo_ticket.php?action=listar_equipos&idCliente=${encodeURIComponent(idCliente)}`);
      const data = await res.json();
      ntkPintarEquiposGuardados(data.equipos || []);
    } catch {
      ntkResetSeccionEquipos();
    }
  }


  /* ── Accordion adicionales ── */
  window.ntkToggleAdd = function() {
    document.getElementById('ntk-add-toggle').classList.toggle('open');
    document.getElementById('ntk-add-panel').classList.toggle('open');
  };
 
  /* ── Cotización en vivo ── */
  window.ntkUpdateQuote = function() {
    document.querySelectorAll('#ntk-servicios-base .ntk-service-item').forEach(row => {
      const r = row.querySelector('input[type="radio"]');
      row.classList.toggle('selected', r && r.checked);
    });
    document.querySelectorAll('#ntk-add-panel .ntk-service-item').forEach(row => {
      const c = row.querySelector('input[type="checkbox"]');
      row.classList.toggle('sel-add', c && c.checked);
    });
    const n     = document.querySelectorAll('#ntk-add-panel input[type="checkbox"]:checked').length;
    const badge = document.getElementById('ntk-add-badge');
    if (badge) { badge.textContent = n; badge.classList.toggle('hidden', n === 0); }
  };
 
  function ntkGetItems() {
    const items = [];
    const base  = document.querySelector('input[name="ntk_srv_base"]:checked');
    if (base) items.push({ name: base.dataset.nombre, price: parseFloat(base.value) });
    document.querySelectorAll('#ntk-add-panel input[type="checkbox"]:checked').forEach(cb => {
      items.push({ name: cb.dataset.nombre, price: parseFloat(cb.value) });
    });
    return items;
  }

  /* Datos del dispositivo: del equipo guardado seleccionado o del formulario nuevo */
  function ntkGetDeviceData() {
    if (ntkIdEquipoSel > 0) {
      const eq = ntkEquiposGuardados.find(e => Number(e.idEquipo) === ntkIdEquipoSel);
      if (eq) {
        return {
          idEquipo: ntkIdEquipoSel,
          tipo:     eq.tipoEquipo || '',
          marca:    eq.marca || '',
          modelo:   eq.modelo || '',
          serie:    eq.numSerie || '',
          so:       eq.sistemaOperativo || '',
        };
      }
    }
    const tipo     = document.getElementById('ntk-tipo-dispositivo')?.value || '';
    const esLaptop = tipo === 'Laptop';
    return {
      idEquipo: 0,
      tipo,
      marca:  (esLaptop ? document.getElementById('ntk-marca')?.value  : '') || '',
      modelo: (esLaptop ? document.getElementById('ntk-modelo')?.value : '') || '',
      serie:  (esLaptop ? document.getElementById('ntk-serie')?.value  : '') || '',
      so:     (esLaptop
        ? document.getElementById('ntk-so-laptop')?.value
        : document.getElementById('ntk-so-pc')?.value) || '',
    };
  }
 
  /* ── Resumen paso 4 ── */
  function ntkBuildSummary() {
    const nombres   = document.getElementById('ntk-nombres')?.value    || '';
    const apellidos = document.getElementById('ntk-apellidos')?.value  || '';
    const nombre    = [nombres, apellidos].filter(Boolean).join(' ')   || '—';
    const dni       = document.getElementById('ntk-dni')?.value        || '—';
    const tel       = document.getElementById('ntk-telefono')?.value   || '—';
    const correo    = document.getElementById('ntk-correo')?.value     || '';
    const dev       = ntkGetDeviceData();
    const tipo      = dev.tipo  || '—';
    const marca     = dev.marca || '';
    const obs       = document.getElementById('ntk-observaciones')?.value || '';
 
    const summaryLeft = document.getElementById('ntk-summary-left');
    if (summaryLeft) {
      summaryLeft.innerHTML = `
        <div class="ntk-summary-block">
          <div class="ntk-summary-block__label">Cliente</div>
          <div class="ntk-summary-block__value">
            <strong>${nombre}</strong><br>
            DNI: ${dni}<br>Tel: ${tel}
            ${correo ? '<br>Correo: ' + correo : ''}
          </div>
        </div>
        <div class="ntk-summary-block">
          <div class="ntk-summary-block__label">Dispositivo</div>
          <div class="ntk-summary-block__value">
            <strong>${tipo}</strong>${marca ? ' · ' + marca : ''}
            ${obs ? '<br><span style="font-size:12px;color:var(--admin-gray-400);font-style:italic">' + obs + '</span>' : ''}
          </div>
        </div>`;
    }
 
    const items    = ntkGetItems();
    const quoteBox = document.getElementById('ntk-summary-quote');
    if (!quoteBox) return;
 
    if (!items.length) {
      quoteBox.innerHTML = `<div class="ntk-quote-box__title">Resumen de Cotización</div>
        <p style="opacity:.7;font-size:13px;text-align:center;padding:20px 0">Sin servicios seleccionados.</p>`;
      return;
    }
 
    const subtotal = items.reduce((a, i) => a + i.price, 0);
    const igv      = subtotal * 0.18;
    const total    = subtotal + igv;
 
    quoteBox.innerHTML = `
      <div class="ntk-quote-box__title">Resumen de Cotización</div>
      ${items.map(i => `
        <div class="ntk-quote-item">
          <span class="ntk-quote-item__name">${i.name}</span>
          <span class="ntk-quote-item__price">S/ ${i.price.toFixed(2)}</span>
        </div>`).join('')}
      <hr class="ntk-q-divider">
      <div class="ntk-q-row"><span>Subtotal</span><span>S/ ${subtotal.toFixed(2)}</span></div>
      <div class="ntk-q-row"><span>IGV (18%)</span><span>S/ ${igv.toFixed(2)}</span></div>
      <hr class="ntk-q-divider">
      <div class="ntk-q-total">
        <span class="ntk-q-total__label">Total</span>
        <span class="ntk-q-total__amount">S/ ${total.toFixed(2)}</span>
      </div>`;
  }
 
  /* ── Crear ticket ── */
  window.ntkCrearTicket = function() {
    const dev = ntkGetDeviceData();
    const { idEquipo, tipo, marca, modelo, serie, so } = dev;
    const observaciones = document.getElementById('ntk-observaciones')?.value || '';
 
    const nombres   = document.getElementById('ntk-nombres')?.value   || '';
    const apellidos = document.getElementById('ntk-apellidos')?.value || '';
    const dni       = document.getElementById('ntk-dni')?.value       || '';
    const ruc       = document.getElementById('ntk-ruc')?.value       || '';
    const telefono  = document.getElementById('ntk-telefono')?.value  || '';
    const correo    = document.getElementById('ntk-correo')?.value    || '';
 
    // nombre completo para PDF
    const nombreCompleto = [nombres, apellidos].filter(Boolean).join(' ');
 
    const serviciosLocal = [];
    const base = document.querySelector('input[name="ntk_srv_base"]:checked');
    if (base) serviciosLocal.push({ nombre: base.dataset.nombre, precio: parseFloat(base.value) });
    document.querySelectorAll('#ntk-add-panel input[type="checkbox"]:checked').forEach(cb => {
      serviciosLocal.push({ nombre: cb.dataset.nombre, precio: parseFloat(cb.value) });
    });
 
    const hoy = new Date();
    const fecha = hoy.toLocaleDateString('es-PE', { year: 'numeric', month: 'long', day: 'numeric' });
    const vencimiento = new Date(hoy);
    vencimiento.setDate(vencimiento.getDate() + 7);
    const fechaVencimiento = vencimiento.toLocaleDateString('es-PE', { year: 'numeric', month: 'long', day: 'numeric' });
 
    window._ntkPdfData = {
      tipo, marca, modelo, serie, so, observaciones,
      servicios: serviciosLocal,
      cliente: nombreCompleto,
      email: correo,
      dni,
      tel: telefono,
      ruc,
      fecha,
      fechaVencimiento,
      codigo: null, subtotal: null, igv: null, total: null,
    };
 
    const btn = document.querySelector('.ntk-btn-finish');
    const textoOriginal = btn ? btn.innerHTML : '';
    if (btn) { btn.disabled = true; btn.style.opacity = '.6'; btn.textContent = 'Guardando…'; }
 
    function restaurarBoton() {
      if (btn) { btn.disabled = false; btn.style.opacity = ''; btn.innerHTML = textoOriginal; }
    }
 
    fetch('nuevo_ticket.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        nombres, apellidos, dni, ruc, telefono, correo,
        idEquipo, tipo, marca, modelo, serie, so, observaciones,
        servicios: serviciosLocal.map(s => ({ nombre: s.nombre, precio: s.precio }))
      })
    })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          if (window._ntkPdfData) {
            window._ntkPdfData.codigo   = data.codigo;
            window._ntkPdfData.subtotal = data.subtotal;
            window._ntkPdfData.igv      = data.igv;
            window._ntkPdfData.total    = data.total;
          }
          const codigoEl = document.getElementById('ntk-modal-codigo');
          if (codigoEl) codigoEl.textContent = '#' + data.codigo;
          const modal = document.getElementById('ntk-modal-success');
          if (modal) { modal.style.opacity = '1'; modal.style.pointerEvents = 'all'; modal.classList.add('show'); }
        } else {
          alert(data.message || 'No se pudo registrar el ticket. Inténtalo de nuevo.');
          restaurarBoton();
        }
      })
      .catch(() => {
        alert('Error de conexión. Inténtalo de nuevo.');
        restaurarBoton();
      });
  };
 
  /* ── Solo números en campos del wizard ── */
  ['ntk-dni', 'ntk-ruc', 'ntk-telefono'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('input', function() { this.value = this.value.replace(/\D/g, ''); });
  });
 
  /* ══════════════════════════════════════════
     AUTOCOMPLETADO POR DNI
     ══════════════════════════════════════════ */
  (function ntkInitDniAutocomplete() {
    const dniInput = document.getElementById('ntk-dni');
    const statusEl = document.getElementById('ntk-dni-status');
    const hintEl   = document.getElementById('ntk-dni-hint');
    if (!dniInput) return;
 
    let debounceTimer = null;
 
    /* Helpers de UI */
    function setStatus(state) {
      // state: '' | 'loading' | 'found' | 'notfound'
      if (!statusEl) return;
      statusEl.className = 'ntk-dni-status';
      if (state) statusEl.classList.add('ntk-dni-status--' + state);
      statusEl.innerHTML = state === 'loading'
        ? '<span class="ntk-dni-spinner"></span>'
        : state === 'found'
          ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>'
          : state === 'notfound'
            ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>'
            : '';
    }
 
    function setHint(msg, type) {
      // type: '' | 'ok' | 'new'
      if (!hintEl) return;
      hintEl.textContent = msg;
      hintEl.className   = 'ntk-hint' + (type ? ' ntk-hint--' + type : '');
    }
 
    function fillFields(data) {
      const set = (id, val) => {
        const el = document.getElementById(id);
        if (el) el.value = val || '';
      };
      set('ntk-nombres',   data.nombres);
      set('ntk-apellidos', data.apellidos);
      set('ntk-correo',    data.correo);
      set('ntk-telefono',  data.telefono);
    }
 
    function clearFields() {
      ['ntk-nombres', 'ntk-apellidos', 'ntk-correo', 'ntk-telefono'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = '';
      });
    }
 
    async function buscarPorDni(dni) {
      setStatus('loading');
      setHint('Buscando cliente…', '');
      try {
        const res  = await fetch(`nuevo_ticket.php?action=buscar_dni&dni=${encodeURIComponent(dni)}`);
        const data = await res.json();
        if (data.found) {
          fillFields(data);
          setStatus('found');
          setHint('Cliente encontrado. Campos completados automáticamente.', 'ok');
          ntkCargarEquiposCliente(data.idCliente);
        } else {
          clearFields();
          setStatus('notfound');
          setHint('DNI no registrado. Completa los datos manualmente.', 'new');
          ntkResetSeccionEquipos();
        }
      } catch {
        setStatus('');
        setHint('Error al consultar. Completa los datos manualmente.', '');
        ntkResetSeccionEquipos();
      }
    }
 
    dniInput.addEventListener('input', function() {
      // El filtro de solo-dígitos ya lo maneja el listener de arriba;
      // aquí solo disparamos la búsqueda con debounce.
      const dni = this.value.trim();
 
      clearTimeout(debounceTimer);
      setStatus('');
      setHint('', '');
 
      if (dni.length === 8) {
        debounceTimer = setTimeout(() => buscarPorDni(dni), 400);
      } else {
        clearFields();
        ntkResetSeccionEquipos();
      }
    });
 
    /* Avisa si el usuario sale del campo con un DNI incompleto */
    dniInput.addEventListener('blur', function() {
      const dni = this.value.trim();
      if (dni.length > 0 && dni.length < 8) {
        setStatus('');
        setHint('El DNI debe tener exactamente 8 dígitos.', '');
      }
    });
  })();
 
})();
 
/* ── Admin: PDF de cotización para nuevo_ticket.php ── */
window._ntkPdfData = null;
window.ntkGenerarPDF = function() {
  window._pdfData = window._ntkPdfData;
  if (typeof generarPDF === 'function') generarPDF();
};


/* ══════════════════════════════════════════
   INVENTARIO.PHP — filtros, stock y modal
   ══════════════════════════════════════════ */

(function initInventario() {
  if (!document.getElementById('inv-tbody')) return;

  const POR_PAGINA = 8;
  let paginaActual   = 1;
  let filtroActual   = 'Todos';
  let busquedaActual = '';

  /* ── Control de cantidad ── */
  window.invCambiarQty = function(id, delta) {
    const el = document.getElementById('inv-qty-' + id);
    if (!el) return;
    let v = parseInt(el.textContent) + delta;
    if (v < 0) v = 0;
    el.textContent = v;
  };

  window.invGuardarCambio = function(id, evt) {
    const qty   = parseInt(document.getElementById('inv-qty-' + id).textContent);
    const label = document.getElementById('inv-stock-label-' + id);
    if (label) {
      label.textContent = qty + ' uds.';
      label.className   = 'inv-stock-badge ' + (qty >= 20 ? 'inv-stock--ok' : qty >= 10 ? 'inv-stock--low' : 'inv-stock--min');
    }
    const btn  = evt.target.closest('button');
    const orig = btn.innerHTML;
    btn.disabled = true;

    const idNum = parseInt(id);
    if (!isNaN(idNum)) {
      fetch('inventario.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ accion: 'actualizar_stock', id: idNum, stock: qty })
      })
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:13px;height:13px"><polyline points="20 6 9 17 4 12"/></svg> Guardado';
            btn.style.background = 'linear-gradient(135deg,#1a7a4a,#2ecc71)';
            invActualizarKPIs();
          } else {
            btn.innerHTML = 'Error';
            btn.style.background = '#c0392b';
          }
        })
        .catch(() => { btn.innerHTML = 'Error'; btn.style.background = '#c0392b'; })
        .finally(() => { setTimeout(() => { btn.innerHTML = orig; btn.style.background = ''; btn.disabled = false; }, 1800); });
    } else {
      btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:13px;height:13px"><polyline points="20 6 9 17 4 12"/></svg> Guardado';
      btn.style.background = 'linear-gradient(135deg,#1a7a4a,#2ecc71)';
      setTimeout(() => { btn.innerHTML = orig; btn.style.background = ''; btn.disabled = false; }, 1800);
    }
  };

  /* ── Filtros y búsqueda ── */
  window.invFiltrar = function(e, cat) {
    e.preventDefault();
    filtroActual = cat;
    paginaActual = 1;
    document.querySelectorAll('.inv-filter-tab').forEach(t => t.classList.remove('active'));
    e.target.classList.add('active');
    invAplicarFiltros();
  };

  window.invBuscar = function(q) {
    busquedaActual = q.toLowerCase().trim();
    paginaActual   = 1;
    invAplicarFiltros();
  };

  window.invCambiarPagina = function(dir) {
    paginaActual += dir;
    invAplicarFiltros();
  };

  function invAplicarFiltros() {
    const filas    = Array.from(document.querySelectorAll('#inv-tbody tr'));
    const visibles = filas.filter(row => {
      const matchCat = filtroActual === 'Todos' || row.dataset.cat === filtroActual;
      const matchQ   = !busquedaActual || (row.dataset.nombre || '').includes(busquedaActual);
      return matchCat && matchQ;
    });

    const total   = visibles.length;
    const paginas = Math.max(1, Math.ceil(total / POR_PAGINA));
    paginaActual  = Math.min(paginaActual, paginas);
    const inicio  = (paginaActual - 1) * POR_PAGINA;
    const fin     = inicio + POR_PAGINA;

    filas.forEach(row => row.style.display = 'none');
    visibles.forEach((row, i) => { row.style.display = (i >= inicio && i < fin) ? '' : 'none'; });

    const mostrando = visibles.slice(inicio, fin).length;
    const countEl   = document.getElementById('inv-footer-count');
    if (countEl) countEl.textContent =
      total + ' producto' + (total !== 1 ? 's' : '') +
      (total > POR_PAGINA ? ` — mostrando ${mostrando}` : '');

    const pagInfo = document.getElementById('inv-pag-info');
    if (pagInfo) pagInfo.textContent = `Pág. ${paginaActual} de ${paginas}`;

    const btnPrev = document.getElementById('inv-btn-prev');
    const btnNext = document.getElementById('inv-btn-next');
    if (btnPrev) btnPrev.disabled = paginaActual <= 1;
    if (btnNext) btnNext.disabled = paginaActual >= paginas;
  }

  invAplicarFiltros();

  /* ── Modal nuevo producto ── */
  window.invAbrirModal  = function() { document.getElementById('inv-modal-overlay').classList.add('open'); };
  window.invCerrarModal = function() { document.getElementById('inv-modal-overlay').classList.remove('open'); invLimpiarModal(); };
  window.invCerrarOverlay = function(e) { if (e.target === document.getElementById('inv-modal-overlay')) invCerrarModal(); };

  function invLimpiarModal() {
    ['inv-m-nombre', 'inv-m-precio', 'inv-m-stock', 'inv-m-stock-min'].forEach(id => {
      const el = document.getElementById(id); if (el) el.value = '';
    });
    const sel = document.getElementById('inv-m-categoria');
    if (sel) sel.selectedIndex = 0;
  }

  const INV_ICONOS = {
    'Consumibles y Limpieza': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
    'Herramientas y Kits':    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>',
    'Almacenamiento':         '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg>',
    'Memoria RAM':            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="2" y1="10" x2="22" y2="10"/></svg>',
  };
  const INV_CAT_CLASS = {
    'Consumibles y Limpieza': 'inv-cat--consumibles',
    'Herramientas y Kits':    'inv-cat--herramientas',
    'Almacenamiento':         'inv-cat--almacenamiento',
    'Memoria RAM':            'inv-cat--ram',
  };

  window.invGuardarNuevo = function() {
    const nombre    = document.getElementById('inv-m-nombre')?.value.trim()    || '';
    const cat       = document.getElementById('inv-m-categoria')?.value         || '';
    const precio    = parseFloat(document.getElementById('inv-m-precio')?.value);
    const stock     = parseInt(document.getElementById('inv-m-stock')?.value)   || 0;
    const stockMin  = parseInt(document.getElementById('inv-m-stock-min')?.value) || 0;

    if (!nombre)                  { alert('Ingresa el nombre del producto.'); return; }
    if (!cat)                     { alert('Selecciona una categoría.'); return; }
    if (isNaN(precio)||precio<=0) { alert('Ingresa un precio válido.'); return; }

    const saveBtn = document.querySelector('.inv-btn-save');
    if (saveBtn) { saveBtn.disabled = true; saveBtn.textContent = 'Guardando…'; }

    fetch('inventario.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ accion: 'nuevo_producto', nombre, categoria: cat, precio, stock, stockMin })
    })
      .then(r => r.json())
      .then(data => {
        if (!data.success) { alert(data.message || 'No se pudo guardar el producto.'); return; }

        const realId   = data.id;
        const catClass = INV_CAT_CLASS[cat] || '';
        const stClass  = stock >= 20 ? 'inv-stock--ok' : stock >= 10 ? 'inv-stock--low' : 'inv-stock--min';
        const icono    = INV_ICONOS[cat]    || '';

        const tr = document.createElement('tr');
        tr.dataset.cat      = cat;
        tr.dataset.nombre   = nombre.toLowerCase();
        tr.dataset.precio   = precio;
        tr.dataset.stockMin = stockMin;
        tr.innerHTML = `
          <td><div class="inv-prod-cell">
            <div class="inv-prod-icon">${icono}</div>
            <div>
              <div class="inv-prod-name">${nombre}</div>
              <div class="inv-prod-id">#${String(realId).padStart(4,'0')}</div>
            </div>
          </div></td>
          <td><span class="inv-cat-badge ${catClass}">${cat}</span></td>
          <td><span class="inv-precio">S/ ${precio.toFixed(2)}</span></td>
          <td><span class="inv-stock-badge ${stClass}" id="inv-stock-label-${realId}">${stock} uds.</span></td>
          <td><div class="inv-qty-control">
            <button class="inv-qty-btn" onclick="invCambiarQty(${realId},-1)">−</button>
            <span class="inv-qty-num" id="inv-qty-${realId}">${stock}</span>
            <button class="inv-qty-btn" onclick="invCambiarQty(${realId},1)">+</button>
          </div></td>
          <td><button class="inv-btn-update" onclick="invGuardarCambio(${realId}, event)">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            Actualizar
          </button></td>`;

        document.getElementById('inv-tbody').appendChild(tr);
        invActualizarKPIs();
        invCerrarModal();
        paginaActual = 1;
        invAplicarFiltros();
      })
      .catch(() => alert('Error de conexión. Inténtalo de nuevo.'))
      .finally(() => { if (saveBtn) { saveBtn.disabled = false; saveBtn.textContent = 'Guardar producto'; } });
  };

  function invActualizarKPIs() {
    const filas = Array.from(document.querySelectorAll('#inv-tbody tr[data-nombre]'));
    let unidades = 0, stockBajo = 0, valor = 0;
    filas.forEach(tr => {
      const qty      = parseInt(tr.querySelector('.inv-qty-num')?.textContent) || 0;
      const precio   = parseFloat(tr.dataset.precio)   || 0;
      const stockMin = parseInt(tr.dataset.stockMin)   || 0;
      unidades += qty;
      valor    += qty * precio;
      if (qty <= stockMin) stockBajo++;
    });
    const fmt = n => Math.round(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    const elU = document.getElementById('kpi-unidades');
    const elS = document.getElementById('kpi-stock-bajo');
    const elV = document.getElementById('kpi-valor');
    if (elU) elU.textContent = fmt(unidades);
    if (elS) elS.textContent = stockBajo;
    if (elV) elV.textContent = 'S/ ' + fmt(valor);
  }
})();

/* ══════════════════════════════════════════
   VENTAS.PHP — gráfico de barras y filtros
   ══════════════════════════════════════════ */

(function initVentas() {
  if (!document.getElementById('vt-bars-area')) return;

  const MONTHS_ES = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];

  const _mesesRaw = Array.isArray(window._vtVentasPorMes) ? window._vtVentasPorMes : [];
  const DATA_MES = _mesesRaw.map(r => ({
    label: MONTHS_ES[r.mo - 1],
    year:  r.yr,
    val:   r.val,
  }));
  const DATA_QUINCENA = DATA_MES.flatMap((m) => [
    {label: m.label+' 1ª', year: m.year, val: Math.round(m.val * 0.45)},
    {label: m.label+' 2ª', year: m.year, val: Math.round(m.val * 0.55)},
  ]);
  const _anioAgg = {};
  _mesesRaw.forEach(r => { _anioAgg[r.yr] = (_anioAgg[r.yr] || 0) + r.val; });
  const DATA_ANIO = Object.entries(_anioAgg)
    .map(([yr, val]) => ({label: String(yr), year: Number(yr), val}))
    .sort((a, b) => a.year - b.year);

  const _now       = new Date();
  const curMesIdx  = DATA_MES.findIndex(d => d.year === _now.getFullYear() && d.label === MONTHS_ES[_now.getMonth()]);
  const curAnioIdx = Math.max(0, DATA_ANIO.length - 1);
  const VISIBLE    = 8;
  const CHART_H    = 200;

  let vtPeriod     = 'mes';
  let vtOffset     = 0;
  let vtDataset    = DATA_MES;
  let vtCurrentIdx = Math.max(0, curMesIdx);

  function vtFmtVal(v) {
    if (v >= 1000000) return 'S/ ' + (v/1000000).toFixed(1).replace('.0','') + ' millón';
    if (v >= 1000)    return 'S/ ' + (v/1000).toFixed(v % 1000 === 0 ? 0 : 1) + ' mil';
    return 'S/ ' + v;
  }
  function vtFmtTick(v) {
    if (v >= 1000000) return (v/1000000).toFixed(0) + 'M';
    if (v >= 1000)    return (v/1000).toFixed(0) + ' mil';
    return v === 0 ? '0' : String(v);
  }

  window.vtSetPeriod = function(p, el) {
    vtPeriod = p;
    document.querySelectorAll('.vt-period-btn').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
    if (p === 'mes')           { vtDataset = DATA_MES;       vtCurrentIdx = Math.max(0, curMesIdx); }
    else if (p === 'quincena') { vtDataset = DATA_QUINCENA;  vtCurrentIdx = Math.max(0, curMesIdx * 2 + 1); }
    else                       { vtDataset = DATA_ANIO;      vtCurrentIdx = curAnioIdx; }
    vtOffset = Math.max(0, vtCurrentIdx - VISIBLE + 1);
    vtRenderChart();
  };

  window.vtNavChart = function(dir) {
    vtOffset = Math.max(0, Math.min(vtDataset.length - VISIBLE, vtOffset + dir * VISIBLE));
    vtRenderChart();
  };

  function vtRenderChart() {
    const barsEl  = document.getElementById('vt-bars-area');
    const yAxisEl = document.getElementById('vt-y-axis');
    if (!barsEl || !yAxisEl) return;

    const slice  = vtDataset.slice(vtOffset, vtOffset + VISIBLE);
    const maxVal = Math.max(...vtDataset.map(d => d.val), 1);

    const btnPrev = document.getElementById('vt-btn-prev');
    const btnNext = document.getElementById('vt-btn-next');
    if (btnPrev) btnPrev.style.opacity = vtOffset > 0 ? '1' : '0.25';
    if (btnNext) btnNext.style.opacity = (vtOffset + VISIBLE < vtDataset.length) ? '1' : '0.25';

    const first = slice[0], last = slice[slice.length - 1];
    const rangeEl = document.getElementById('vt-chart-range-label');
    if (rangeEl) rangeEl.textContent = vtPeriod === 'año'
      ? `${first.label} — ${last.label}`
      : `${first.label} ${first.year} — ${last.label} ${last.year}`;

    /* Y-axis: 5 ticks redondeados */
    const rawStep = maxVal / 4;
    const mag     = Math.pow(10, Math.floor(Math.log10(rawStep)));
    const step    = Math.ceil(rawStep / mag) * mag;
    const yMax    = step * 4;
    const ticks   = [yMax, yMax * 0.75, yMax * 0.5, yMax * 0.25, 0];
    yAxisEl.style.height = (CHART_H + 26) + 'px';
    yAxisEl.innerHTML    = ticks.map(t => `<span class="vt-y-tick">${vtFmtTick(t)}</span>`).join('');

    /* Barras */
    barsEl.style.height = CHART_H + 'px';
    barsEl.innerHTML = slice.map((d, i) => {
      const gIdx    = vtOffset + i;
      const isCur   = gIdx === vtCurrentIdx;
      const fillH   = Math.max(4, Math.round((d.val / yMax) * CHART_H));
      const fillCls = isCur ? 'vt-bar-fill--current' : 'vt-bar-fill--normal';
      const lblCls  = isCur ? 'vt-bar-label--current' : '';
      const t1      = vtPeriod === 'año' ? d.label : `${d.label} ${d.year}`;
      const t2      = vtFmtVal(d.val);
      return `
        <div class="vt-bar-col${isCur ? ' vt-bar-active' : ''}" data-val="${d.val}">
          <div class="vt-bar-bubble">${t1}<br><strong>${t2}</strong></div>
          <div class="vt-bar-track" style="height:${CHART_H}px;">
            <div class="vt-bar-fill ${fillCls}" style="height:0" data-h="${fillH}px"></div>
          </div>
          <span class="vt-bar-label ${lblCls}">${d.label}</span>
        </div>`;
    }).join('');

    requestAnimationFrame(() => {
      barsEl.querySelectorAll('.vt-bar-fill').forEach(b => { b.style.height = b.dataset.h; });
    });
  }

  vtOffset = Math.max(0, vtCurrentIdx - VISIBLE + 1);
  vtRenderChart();

  /* ── Filtros y búsqueda ── */
  let vtFiltroTipo = 'Todos';
  let vtBusqueda   = '';

  window.vtFiltrar = function(val, el) {
    vtFiltroTipo = val;
    document.querySelectorAll('.inv-filter-tab').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
    vtAplicarFiltros();
  };

  window.vtBuscar = function(q) { vtBusqueda = q.toLowerCase().trim(); vtAplicarFiltros(); };

  function vtAplicarFiltros() {
    let visible = 0;
    document.querySelectorAll('#vt-tbody tr').forEach(row => {
      const tipo   = row.dataset.tipo    || '';
      const search = row.dataset.search  || '';
      const show   = (vtFiltroTipo === 'Todos' || tipo === vtFiltroTipo) &&
                     (!vtBusqueda || search.includes(vtBusqueda));
      row.style.display = show ? '' : 'none';
      if (show) visible++;
    });
    const countEl = document.getElementById('vt-footer-count');
    if (countEl) countEl.textContent = visible + ' venta' + (visible !== 1 ? 's' : '');
  }
})();

























/* ════════════════════════════════════════════════════════════
   MULTI-STEP FORMS — registro.php & registro_staff.php
   Añadir al final de script.js (o incluir como bloque separado)
   ════════════════════════════════════════════════════════════ */

(function initMultiStep() {

  /* ── Campos que se validan en cada paso ── */
  const STEP_FIELDS = {
    // registro.php (cliente)
    'reg-form': {
      1: ['nombres', 'apellidos', 'dni', 'telefono', 'correo'],
      2: ['password', 'confirm', 'pregunta1', 'respuesta1', 'pregunta2', 'respuesta2', 'pregunta3', 'respuesta3'],
    },
  };

  /* ─────────────────────────────────────────
     msNext(formId, currentStep)
     Valida el paso actual y avanza al siguiente
  ───────────────────────────────────────────*/
  window.msNext = function(formId, currentStep) {
    const form       = document.getElementById(formId);
    const nextStep   = currentStep + 1;
    const panelCurr  = document.getElementById('ms-panel-' + currentStep);
    const panelNext  = document.getElementById('ms-panel-' + nextStep);

    if (!form || !panelCurr || !panelNext) return;

    // ── Validación de campos del paso actual ──
    const fieldsToCheck = (STEP_FIELDS[formId] && STEP_FIELDS[formId][currentStep])
      ? STEP_FIELDS[formId][currentStep]
      : [];

    // Recoge todos los inputs/selects requeridos dentro del panel actual
    const requiredEls = panelCurr.querySelectorAll('input[required], select[required]');
    let firstInvalid  = null;

    requiredEls.forEach(el => {
      const val = el.value.trim();
      if (!val) {
        el.classList.add('ms-field--error');
        if (!firstInvalid) firstInvalid = el;
        el.addEventListener('input', function onInput() {
          el.classList.remove('ms-field--error');
          el.removeEventListener('input', onInput);
        }, { once: true });
      } else {
        el.classList.remove('ms-field--error');
      }
    });

    if (firstInvalid) {
      firstInvalid.focus();
      msShakePanel(panelCurr);
      return;
    }

    // ── Validación específica del paso 1 (cliente): correo ──
    if (currentStep === 1) {
      const correoEl = document.getElementById('correo');
      if (correoEl) {
        const emailVal = correoEl.value.trim();
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailVal)) {
          correoEl.classList.add('ms-field--error');
          correoEl.focus();
          msShakePanel(panelCurr);
          return;
        }
      }

      // DNI solo 8 dígitos
      const dniEl = document.getElementById('dni');
      if (dniEl && !/^\d{8}$/.test(dniEl.value.trim())) {
        dniEl.classList.add('ms-field--error');
        dniEl.focus();
        msShakePanel(panelCurr);
        return;
      }

      // Teléfono solo 9 dígitos
      const telEl = document.getElementById('telefono');
      if (telEl && !/^\d{9}$/.test(telEl.value.trim())) {
        telEl.classList.add('ms-field--error');
        telEl.focus();
        msShakePanel(panelCurr);
        return;
      }

      // RUC opcional: si tiene algo, debe ser 11 dígitos
      const rucEl = document.getElementById('ruc');
      if (rucEl && rucEl.value.trim() && !/^\d{11}$/.test(rucEl.value.trim())) {
        rucEl.classList.add('ms-field--error');
        rucEl.focus();
        msShakePanel(panelCurr);
        return;
      }
    }

    // ── Validación específica paso 1 (staff): email corporativo & DNI ──
    if (currentStep === 1) {
      const emailEl = document.getElementById('email');
      if (emailEl) {
        if (!emailEl.value.trim().endsWith('@moralestechs.com')) {
          emailEl.classList.add('ms-field--error');
          emailEl.focus();
          msShakePanel(panelCurr);
          return;
        }
      }
    }

    // ── Avanzar ──
    panelCurr.classList.add('ms-panel--hidden');
    panelNext.classList.remove('ms-panel--hidden');

    msUpdateStepper(nextStep);
    window.scrollTo({ top: 0, behavior: 'smooth' });

    // Foco en el primer campo del siguiente panel
    const firstInput = panelNext.querySelector('input:not([type=hidden]), select');
    if (firstInput) setTimeout(() => firstInput.focus(), 80);
  };

  /* ─────────────────────────────────────────
     msBack(targetStep)
     Regresa al paso anterior sin validar
  ───────────────────────────────────────────*/
  window.msBack = function(targetStep) {
    const nextStep   = targetStep + 1;
    const panelPrev  = document.getElementById('ms-panel-' + targetStep);
    const panelCurr  = document.getElementById('ms-panel-' + nextStep);

    if (!panelPrev || !panelCurr) return;

    panelCurr.classList.add('ms-panel--hidden');
    panelPrev.classList.remove('ms-panel--hidden');

    msUpdateStepper(targetStep);
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  /* ─────────────────────────────────────────
     msUpdateStepper(activeStep)
     Actualiza los dots y conectores visuales
  ───────────────────────────────────────────*/
  function msUpdateStepper(activeStep) {
    const totalSteps = document.querySelectorAll('[id^="ms-dot-"]').length;

    for (let i = 1; i <= totalSteps; i++) {
      const dot       = document.getElementById('ms-dot-' + i);
      const connector = document.getElementById('ms-connector-' + i);
      if (!dot) continue;

      dot.classList.remove('ms-step--active', 'ms-step--done');

      if (i < activeStep)       dot.classList.add('ms-step--done');
      else if (i === activeStep) dot.classList.add('ms-step--active');

      if (connector) {
        connector.classList.toggle('ms-connector--done', i < activeStep);
      }
    }
  }

  /* ─────────────────────────────────────────
     msShakePanel(panel)
     Sacudida visual de error
  ───────────────────────────────────────────*/
  function msShakePanel(panel) {
    panel.style.animation = 'none';
    panel.offsetHeight; // reflow
    panel.style.animation = 'ms-shake .35s ease';
    setTimeout(() => panel.style.animation = '', 400);
  }

  /* ─────────────────────────────────────────
     Resalte visual de campos con error (CSS)
  ───────────────────────────────────────────*/
  (function injectErrorStyle() {
    if (document.getElementById('ms-error-style')) return;
    const style = document.createElement('style');
    style.id = 'ms-error-style';
    style.textContent = `
      .ms-field--error {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 3px rgba(239,68,68,.18) !important;
      }
      @keyframes ms-shake {
        0%,100% { transform: translateX(0); }
        20%      { transform: translateX(-6px); }
        40%      { transform: translateX(6px); }
        60%      { transform: translateX(-4px); }
        80%      { transform: translateX(4px); }
      }
    `;
    document.head.appendChild(style);
  })();

  /* ─────────────────────────────────────────
     Restaurar paso desde PHP (tras error POST)
  ───────────────────────────────────────────*/
  function restoreStepFromServer() {
    const startStep = typeof MS_START_STEP !== 'undefined' ? MS_START_STEP : 1;
    if (startStep <= 1) return;

    for (let i = 1; i < startStep; i++) {
      const panel = document.getElementById('ms-panel-' + i);
      if (panel) panel.classList.add('ms-panel--hidden');
    }
    const targetPanel = document.getElementById('ms-panel-' + startStep);
    if (targetPanel) targetPanel.classList.remove('ms-panel--hidden');

    msUpdateStepper(startStep);
  }

  // Ejecutar al cargar
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', restoreStepFromServer);
  } else {
    restoreStepFromServer();
  }

})();

/* ════════════════════════════════════════════════════════════
   VALIDACIÓN DE DOMINIO CORPORATIVO (registro_staff.php)
   (ya existía parcialmente — se mantiene compatible)
   ════════════════════════════════════════════════════════════ */
window.adminCheckDomain = function(input) {
  const hint = document.getElementById('domain-hint');
  if (!hint) return;
  const val = input.value.trim();
  if (!val) { hint.style.display = 'none'; return; }
  const isOk = val.endsWith('@moralestechs.com');
  hint.style.display = 'flex';
  hint.style.color   = isOk ? '#22c55e' : '#f87171';
  hint.querySelector('svg').style.stroke = isOk ? '#22c55e' : '#f87171';
};

/* ════════════════════════════════════════════════════════════
   FUERZA DE CONTRASEÑA — versión admin (registro_staff.php)
   ════════════════════════════════════════════════════════════ */
window.adminUpdateStrength = function(val) {
  const wrap  = document.getElementById('admin-pw-strength');
  const label = document.getElementById('admin-pw-strength-label');
  const bars  = document.querySelectorAll('.admin-pw-strength__bar');
  if (!wrap || !bars.length) return;

  wrap.style.display = val.length ? 'flex' : 'none';

  let score = 0;
  if (val.length >= 8)                                   score++;
  if (val.length >= 12)                                  score++;
  if (/[A-Z]/.test(val) && /[a-z]/.test(val))          score++;
  if (/[0-9]/.test(val) || /[^A-Za-z0-9]/.test(val))  score++;

  const colors = ['#ef4444','#f97316','#eab308','#22c55e'];
  const labels = ['Débil','Regular','Buena','Fuerte'];

  bars.forEach((bar, i) => {
    bar.style.background = i < score ? colors[score - 1] : 'rgba(0,0,0,.12)';
  });
  if (label) {
    label.textContent = val.length ? labels[Math.max(0, score - 1)] : '';
    label.style.color = colors[Math.max(0, score - 1)];
  }

  window.adminCheckMatch();
};

window.adminCheckMatch = function() {
  const pw  = document.getElementById('password');
  const pw2 = document.getElementById('password2');
  if (!pw || !pw2) return;
  // Feedback visual sutil en el campo de confirmación
  if (!pw2.value) { pw2.style.borderColor = ''; return; }
  pw2.style.borderColor = pw.value === pw2.value ? '#22c55e' : '#ef4444';
};

/* ════════════════════════════════════════════════════════════
   TOGGLES DE CONTRASEÑA — registro_staff.php
   ════════════════════════════════════════════════════════════ */
(function initStaffPasswordToggles() {
  setupPasswordToggle('pw-toggle-reg1', 'password',  'eye-show-reg1', 'eye-hide-reg1');
  setupPasswordToggle('pw-toggle-reg2', 'password2', 'eye-show-reg2', 'eye-hide-reg2');
})();

/* ════════════════════════════════════════════════════════════
   RECUPERAR CONTRASEÑA — clientes (recuperar_contrasena.php)
   Toggles de contraseña para paso 3
   ════════════════════════════════════════════════════════════ */
(function initRecuperarPage() {
  // IDs de toggles en el paso 3 del formulario de clientes
  setupPasswordToggle('pw-toggle-1', 'password', 'eye1-show', 'eye1-hide');
  setupPasswordToggle('pw-toggle-2', 'confirm',  'eye2-show', 'eye2-hide');
})();


/* ════════════════════════════════════════════════════════════
   RECUPERAR CONTRASEÑA — staff (recuperar_contra_staff.php)
   Toggles de contraseña para paso 3 (variante admin)
   ════════════════════════════════════════════════════════════ */
(function initRecuperarStaffPage() {
  setupPasswordToggle('pw-toggle-rec1', 'password',  'eye-show-rec1', 'eye-hide-rec1');
  setupPasswordToggle('pw-toggle-rec2', 'password2', 'eye-show-rec2', 'eye-hide-rec2');
})();


/* ══════════════════════════════════════════
   COTIZACIÓN PDF — nuevo_ticket_cliente.php
   Genera y descarga la cotización con jsPDF
   ══════════════════════════════════════════ */
// Estado global que enviarSolicitud() llena al recibir éxito del servidor
window._pdfData = null;

/* Sobreescribir enviarSolicitud para capturar datos del servidor */
(function patchEnviarSolicitud() {
  const _original = window.enviarSolicitud;
  window.enviarSolicitud = function () {
    const tipo     = document.getElementById('tipo_dispositivo').value;
    const esLaptop = tipo === 'Laptop';

    const marca  = (esLaptop ? document.getElementById('marca')?.value  : '') || '';
    const modelo = (esLaptop ? document.getElementById('modelo')?.value : '') || '';
    const serie  = (esLaptop ? document.getElementById('serie')?.value  : '') || '';
    const so     = (esLaptop
      ? document.getElementById('so-laptop')?.value
      : document.getElementById('so-pc')?.value) || '';
    const observaciones = document.getElementById('observaciones')?.value || '';

    // Capturar servicios antes de enviar
    const base = document.querySelector('input[name="srv_base"]:checked');
    const serviciosLocal = [];
    if (base) serviciosLocal.push({ nombre: base.dataset.nombre, precio: parseFloat(base.value) });
    document.querySelectorAll('#add-panel input[type="checkbox"]:checked').forEach(cb => {
      serviciosLocal.push({ nombre: cb.dataset.nombre, precio: parseFloat(cb.value) });
    });

    // Fecha de emisión
    const hoy = new Date();
    const fecha = hoy.toLocaleDateString('es-PE', { year: 'numeric', month: 'long', day: 'numeric' });

    // ✅ Fecha de vencimiento: emisión + 7 días, mismo formato
    const vencimiento = new Date(hoy);
    vencimiento.setDate(vencimiento.getDate() + 7);
    const fechaVencimiento = vencimiento.toLocaleDateString('es-PE', { year: 'numeric', month: 'long', day: 'numeric' });

    // Snapshot para el PDF
    window._pdfData = {
      tipo, marca, modelo, serie, so, observaciones,
      servicios: serviciosLocal,
      cliente: typeof CLIENTE_NOMBRE !== 'undefined' ? CLIENTE_NOMBRE : '',
      email:   typeof CLIENTE_EMAIL  !== 'undefined' ? CLIENTE_EMAIL  : '',
      dni:     typeof CLIENTE_DNI    !== 'undefined' ? CLIENTE_DNI    : '',
      tel:     typeof CLIENTE_TEL    !== 'undefined' ? CLIENTE_TEL    : '',
      ruc:     typeof CLIENTE_RUC    !== 'undefined' ? CLIENTE_RUC    : '',
      fecha,
      fechaVencimiento,
      codigo:   null,
      subtotal: null,
      igv:      null,
      total:    null,
    };

    // Enviar al servidor
    const serviciosPayload = serviciosLocal.map(s => ({ nombre: s.nombre, precio: s.precio }));
    const btn = document.querySelector('.btn-wizard-send');
    const textoOriginal = btn ? btn.innerHTML : '';
    if (btn) { btn.disabled = true; btn.style.opacity = '.6'; btn.textContent = 'Enviando…'; }

    function restaurarBoton() {
      if (btn) { btn.disabled = false; btn.style.opacity = ''; btn.innerHTML = textoOriginal; }
    }

    fetch('nuevo_ticket_cliente.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ tipo, marca, modelo, serie, so, observaciones, servicios: serviciosPayload })
    })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          if (window._pdfData) {
            window._pdfData.codigo   = data.codigo;
            window._pdfData.subtotal = data.subtotal;
            window._pdfData.igv      = data.igv;
            window._pdfData.total    = data.total;
          }
          const modal = document.getElementById('modal-success');
          if (modal) {
            modal.style.opacity       = '1';
            modal.style.pointerEvents = 'all';
            modal.classList.add('show');
          }
        } else {
          alert(data.message || 'No se pudo enviar la solicitud. Inténtalo de nuevo.');
          restaurarBoton();
        }
      })
      .catch(() => {
        alert('Ocurrió un error de conexión. Inténtalo de nuevo.');
        restaurarBoton();
      });
  };
})();

/* =============================================
   PDF COTIZACIÓN
   ============================================= */
window.generarPDF = function () {
    const d = window._pdfData;
    if (!d) return alert("No hay datos.");

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    const azulTecnologico = [23, 70, 234];
    const azulProfundo    = [0, 0, 25];

    // Márgenes: todo alineado a derecha usa x=195 (margen real de la hoja)
    // Solo los precios de la tabla usan x=190 para dar respiro visual
    const xDerecha  = 195;  // margen derecho estándar (header, fechas, footer, totales)
    const xPrecios  = 190;  // columna de montos en la tabla de servicios
    const xLabel    = 130;  // etiquetas de subtotal / IGV / TOTAL
    const aRight    = { align: "right" };

    let y = 20;

    // ===== HEADER =====
    doc.addImage('img/logo-horizontal-color.png', 'PNG', 15, y, 40, 15);

    doc.setFontSize(9);
    doc.setTextColor(...azulProfundo);
    doc.setFont("helvetica", "bold");
    doc.text("MORALES TECH SOLUTIONS ADVANCED S.A.C.", xDerecha, y + 5,  aRight);
    doc.setFont("helvetica", "normal");
    doc.text("Urb. Jardines de Casablanca F-06, Ica",  xDerecha, y + 10, aRight);
    doc.text("(51) 903-208-170",                        xDerecha, y + 15, aRight);
    doc.text("RUC: 20613424238",                        xDerecha, y + 20, aRight);

    y += 35;

    // ===== TITULO =====
    doc.setFont("helvetica", "bold");
    doc.setFontSize(18);
    doc.setTextColor(...azulTecnologico);
    doc.text("COTIZACIÓN DE SERVICIO", 15, y);
    y += 8;

    doc.setDrawColor(200);
    doc.line(15, y, 195, y);
    y += 8;

    // ===== TICKET / FECHA =====
    doc.setTextColor(...azulProfundo);
    doc.setFontSize(10);
    doc.setFont("helvetica", "bold");
    doc.text("N° TICKET", 15, y);
    doc.text("FECHA", xDerecha, y, aRight);
    y += 5;
    doc.setFont("helvetica", "normal");
    doc.text(d.codigo || "-", 15, y);
    doc.text(d.fecha, xDerecha, y, aRight);
    y += 12;

    // ===== CLIENTE / EQUIPO =====
    doc.setFont("helvetica", "bold");
    doc.text("CLIENTE", 15, y);

    const esLaptop = d.tipo === 'Laptop';
    const tieneEquipo = esLaptop ? (d.marca || d.modelo) : d.tipo;
    if (tieneEquipo) doc.text("EQUIPO", xDerecha, y, aRight);
    y += 6;

    doc.setFont("helvetica", "normal");

    // Fila 1: nombre cliente | (Laptop: marca) / (PC: tipo)
    if (d.cliente) doc.text(d.cliente, 15, y);
    if (esLaptop) {
        if (d.marca) doc.text(d.marca, xDerecha, y, aRight);
    } else {
        if (d.tipo) doc.text(d.tipo, xDerecha, y, aRight);
    }
    y += 6;

    // Fila 2: DNI | (Laptop: modelo) / (PC: SO)
    if (d.dni) doc.text("DNI: " + d.dni, 15, y);
    if (esLaptop) {
        if (d.modelo) doc.text(d.modelo, xDerecha, y, aRight);
    } else {
        if (d.so) doc.text(d.so, xDerecha, y, aRight);
    }
    y += 6;

    // Email (solo si existe)
    if (d.email) {
        doc.text("Correo: " + d.email, 15, y);
        y += 6;
    }

    // Teléfono (solo si existe)
    if (d.tel) {
        doc.text("Tel: " + d.tel, 15, y);
        y += 6;
    }

    // RUC del cliente (OPCIONAL)
    if (d.ruc) {
        doc.text("RUC: " + d.ruc, 15, y);
        y += 6;
    }

    y += 4;
    doc.line(15, y, 195, y);
    y += 10;

    // ===== SERVICIOS =====
    doc.setFont("helvetica", "bold");
    doc.text("DESCRIPCIÓN DE SERVICIOS", 15, y);
    y += 6;

    doc.setFillColor(...azulTecnologico);
    doc.roundedRect(15, y, 180, 8, 4, 4, 'F');
    doc.setTextColor(255, 255, 255);
    doc.text("SERVICIO", 20, y + 5.5);
    doc.text("MONTO", xPrecios, y + 5.5, aRight);
    y += 12;

    doc.setTextColor(...azulProfundo);
    doc.setFont("helvetica", "normal");
    let subtotal = 0;
    d.servicios.forEach(s => {
        doc.text(s.nombre, 20, y);
        const precio = parseFloat(s.precio) || 0;
        doc.text(`S/. ${precio.toFixed(2)}`, xPrecios, y, aRight);
        subtotal += precio;
        y += 7;
    });
    y += 5;

    // ===== TOTALES =====
    doc.setFontSize(10);
    doc.setFont("helvetica", "normal");
    doc.text("Subtotal", xLabel, y);
    doc.text(`S/. ${subtotal.toFixed(2)}`, xPrecios, y, aRight);
    y += 6;

    const igv = subtotal * 0.18;
    doc.text("IGV (18%)", xLabel, y);
    doc.text(`S/. ${igv.toFixed(2)}`, xPrecios, y, aRight);
    y += 8;

    doc.setDrawColor(200);
    doc.line(xLabel, y, 195, y);
    y += 7;

    doc.setFont("helvetica", "bold");
    doc.setFontSize(13);
    doc.setTextColor(...azulProfundo);
    doc.text("TOTAL:", xLabel, y);
    doc.setTextColor(...azulTecnologico);
    doc.text(`S/. ${(subtotal + igv).toFixed(2)}`, xPrecios, y, aRight);

    // ===== FOOTER =====
    y = 260;
    doc.setTextColor(...azulProfundo);
    doc.setFontSize(9);
    doc.setFont("helvetica", "bold");
    doc.text("Medios de pago", 15, y);
    doc.text("Fecha de vencimiento", xDerecha, y, aRight);
    y += 6;

    doc.setFont("helvetica", "normal");
    doc.text("Cuenta: 38098136788029", 15, y);
    // ✅ Fecha de vencimiento calculada: emisión + 7 días
    doc.text(d.fechaVencimiento, xDerecha, y, aRight);
    y += 5;
    doc.text("CCI: 00238019813678802941", 15, y);
    y += 5;
    doc.text("Yape: 922 893 416", 15, y);
    y += 5;
    doc.text("Titular: César Raúl Morales Ticona", 15, y);

    doc.save(`Cotizacion_${d.codigo}.pdf`);
};

/* ══════════════════════════════════════════════════════════
   NUEVA_VENTA.PHP — Tipo de venta, ticket, cliente, productos,
   resumen, registro de venta y boleta PDF
   ══════════════════════════════════════════════════════════ */
(function initNuevaVenta() {
  if (!document.getElementById('nv-bloque-productos')) return; // no estamos en nueva_venta.php

  const tickets   = Array.isArray(window.NV_TICKETS)   ? window.NV_TICKETS   : [];
  const catalogo   = Array.isArray(window.NV_PRODUCTOS) ? window.NV_PRODUCTOS : [];

  let nvTipoVenta   = 'ticket';   // 'ticket' | 'producto'
  let nvMetodoPago  = 'Yape';
  let nvTicketSel   = null;       // objeto del ticket elegido (con dataset)
  let nvClienteDni  = { idCliente: 0, nombres: '', apellidos: '', ruc: '' };
  let nvProdRows    = [];         // [{ uid, idComponente, cantidad }]
  let nvUidSeq      = 1;
  let nvUltimaVenta = null;       // datos devueltos por el servidor, usados luego en el PDF

  function escapeHtml(str) {
    return String(str ?? '').replace(/[&<>"']/g, c => ({
      '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
    }[c]));
  }

  function fmt(n) { return (Number(n) || 0).toFixed(2); }

  /* ── 1. Tipo de venta ── */
  window.nvSelTipo = function(tipo, el) {
    nvTipoVenta = tipo;
    document.querySelectorAll('.nv-vtype-opt').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');

    const bloqueTicket      = document.getElementById('nv-bloque-ticket');
    const bloqueDispositivo = document.getElementById('nv-bloque-dispositivo');
    const bloqueCliente     = document.getElementById('nv-bloque-cliente');
    const prodTitle = document.getElementById('nv-prod-card-title');
    const prodSub   = document.getElementById('nv-prod-card-sub');

    if (tipo === 'ticket') {
      bloqueTicket.classList.remove('nv-hidden');
      bloqueCliente.classList.add('nv-hidden');
      if (!nvTicketSel) bloqueDispositivo.classList.add('nv-hidden');
      prodTitle.textContent = 'Productos adicionales';
      prodSub.textContent   = 'Añade repuestos o materiales usados en el servicio';
    } else {
      bloqueTicket.classList.add('nv-hidden');
      bloqueDispositivo.classList.add('nv-hidden');
      bloqueCliente.classList.remove('nv-hidden');
      prodTitle.textContent = 'Productos de la venta';
      prodSub.textContent   = 'Selecciona los productos que el cliente está comprando';
    }
    nvUpdateQuote();
  };

  /* ── 2A. Selección de ticket ── */
  window.nvOnTicketChange = function() {
    const sel = document.getElementById('nv-sel-ticket');
    const opt = sel.options[sel.selectedIndex];
    const bloqueDispositivo = document.getElementById('nv-bloque-dispositivo');

    if (!opt || !opt.value) {
      nvTicketSel = null;
      bloqueDispositivo.classList.add('nv-hidden');
      nvUpdateQuote();
      return;
    }

    const ticketCompleto = tickets.find(t => t.idTicket === Number(opt.value));

    nvTicketSel = {
      idTicket: Number(opt.value),
      idEquipo: Number(opt.dataset.idequipo) || 0,
      codigo:   opt.dataset.codigo,
      nombres:  opt.dataset.nombres,
      apellidos:opt.dataset.apellidos,
      dni:      opt.dataset.dni,
      ruc:      opt.dataset.ruc,
      servicio: opt.dataset.servicio,
      servicios: (ticketCompleto && Array.isArray(ticketCompleto.servicios)) ? ticketCompleto.servicios : [],
      subtotal: parseFloat(opt.dataset.subtotal) || 0,
      tipo:     opt.dataset.tipo,
      marca:    opt.dataset.marca,
      modelo:   opt.dataset.modelo,
      serie:    opt.dataset.serie,
      so:       opt.dataset.so,
    };

    document.getElementById('nv-di-ticket').textContent   = nvTicketSel.codigo;
    document.getElementById('nv-di-cliente').textContent  = `${nvTicketSel.nombres} ${nvTicketSel.apellidos}`.trim();
    document.getElementById('nv-di-servicio').textContent = nvTicketSel.servicio || '—';
    document.getElementById('nv-di-subtotal').textContent = 'S/ ' + fmt(nvTicketSel.subtotal);

    nvPintarVistaEquipo();

    // Siempre arrancamos en modo vista al cambiar de ticket
    document.getElementById('nv-equipo-view').classList.remove('nv-hidden');
    document.getElementById('nv-equipo-edit').classList.add('nv-hidden');
    document.getElementById('nv-btn-editar').classList.remove('nv-hidden');
    document.getElementById('nv-btn-guardar').classList.add('nv-hidden');
    document.getElementById('nv-btn-cancelar').classList.add('nv-hidden');

    bloqueDispositivo.classList.remove('nv-hidden');
    nvUpdateQuote();
  };

  /* Pinta el modo VISTA de Detalles del Equipo según nvTicketSel actual */
  function nvPintarVistaEquipo() {
    if (!nvTicketSel) return;
    const esLaptop = nvTicketSel.tipo === 'Laptop';

    document.querySelectorAll('#nv-equipo-view .nv-field-laptop-only').forEach(el => {
      el.classList.toggle('nv-visible', esLaptop);
    });

    document.getElementById('nv-view-marca').textContent  = nvTicketSel.marca  || '—';
    document.getElementById('nv-view-modelo').textContent = nvTicketSel.modelo || '—';
    document.getElementById('nv-view-serie').textContent  = nvTicketSel.serie  || '—';
    document.getElementById('nv-view-so').textContent     = nvTicketSel.so     || '—';
    document.getElementById('nv-equipo-type-text').textContent = nvTicketSel.tipo || '—';
  }

  /* ── 2A-EXTRA. Edición de Detalles del Equipo ── */
  window.nvEquipoEditar = function() {
    if (!nvTicketSel) return;
    const esLaptop = nvTicketSel.tipo === 'Laptop';

    document.getElementById('nv-edit-marca').value  = nvTicketSel.marca  || '';
    document.getElementById('nv-edit-modelo').value = nvTicketSel.modelo || '';
    document.getElementById('nv-edit-serie').value  = nvTicketSel.serie  || '';
    document.getElementById('nv-edit-so').value     = nvTicketSel.so     || '';

    document.querySelectorAll('#nv-equipo-edit .nv-field-laptop-only').forEach(el => {
      el.classList.toggle('nv-form-visible', esLaptop);
    });

    document.getElementById('nv-equipo-view').classList.add('nv-hidden');
    document.getElementById('nv-equipo-edit').classList.remove('nv-hidden');
    document.getElementById('nv-btn-editar').classList.add('nv-hidden');
    document.getElementById('nv-btn-guardar').classList.remove('nv-hidden');
    document.getElementById('nv-btn-cancelar').classList.remove('nv-hidden');
  };

  window.nvEquipoCancelar = function() {
    document.getElementById('nv-equipo-view').classList.remove('nv-hidden');
    document.getElementById('nv-equipo-edit').classList.add('nv-hidden');
    document.getElementById('nv-btn-editar').classList.remove('nv-hidden');
    document.getElementById('nv-btn-guardar').classList.add('nv-hidden');
    document.getElementById('nv-btn-cancelar').classList.add('nv-hidden');
  };

  window.nvEquipoGuardar = function() {
    if (!nvTicketSel || !nvTicketSel.idEquipo) {
      alert('No se pudo identificar el equipo a actualizar.');
      return;
    }
    const esLaptop = nvTicketSel.tipo === 'Laptop';

    const payload = {
      idEquipo: nvTicketSel.idEquipo,
      tipo:     nvTicketSel.tipo,
      marca:    esLaptop ? document.getElementById('nv-edit-marca').value.trim()  : '',
      modelo:   esLaptop ? document.getElementById('nv-edit-modelo').value.trim() : '',
      serie:    esLaptop ? document.getElementById('nv-edit-serie').value.trim()  : '',
      so:       document.getElementById('nv-edit-so').value.trim(),
    };

    const btnGuardar = document.getElementById('nv-btn-guardar');
    btnGuardar.disabled = true;

    fetch('nueva_venta.php?action=actualizar_equipo', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
    })
      .then(r => r.json())
      .then(data => {
        btnGuardar.disabled = false;
        if (!data.success) {
          alert('No se pudo guardar los cambios del equipo. Inténtalo de nuevo.');
          return;
        }
        // Actualizar estado local y volver a modo vista
        nvTicketSel.marca  = payload.marca;
        nvTicketSel.modelo = payload.modelo;
        nvTicketSel.serie  = payload.serie;
        nvTicketSel.so     = payload.so;

        nvPintarVistaEquipo();
        window.nvEquipoCancelar();
      })
      .catch(() => {
        btnGuardar.disabled = false;
        alert('Ocurrió un error de conexión al guardar el equipo.');
      });
  };

  /* ── 2B. Autocompletado por DNI (Venta por producto) ── */
  (function initDniAutocomplete() {
    const dniInput  = document.getElementById('nv-cli-dni');
    const statusEl  = document.getElementById('nv-cli-dni-status');
    if (!dniInput) return;

    let debounceTimer = null;

    function setStatus(html) { if (statusEl) statusEl.innerHTML = html; }

    dniInput.addEventListener('input', function() {
      this.value = this.value.replace(/\D/g, '');
      clearTimeout(debounceTimer);
      setStatus('');

      if (this.value.length !== 8) {
        nvClienteDni = { idCliente: 0, nombres: '', apellidos: '', ruc: '' };
        return;
      }

      debounceTimer = setTimeout(() => {
        setStatus('<span style="opacity:.5;font-size:11px;">Buscando…</span>');
        fetch(`nueva_venta.php?action=buscar_dni&dni=${this.value}`)
          .then(r => r.json())
          .then(data => {
            if (data.found) {
              nvClienteDni = {
                idCliente: data.idCliente,
                nombres:   data.nombres || '',
                apellidos: data.apellidos || '',
                ruc:       data.ruc || '',
              };
              document.getElementById('nv-cli-nombres').value   = data.nombres || '';
              document.getElementById('nv-cli-apellidos').value = data.apellidos || '';
              if (data.ruc) document.getElementById('nv-cli-ruc').value = data.ruc;
              setStatus('<span style="color:#1aa15a;font-size:11px;">✓ Cliente encontrado</span>');
            } else {
              nvClienteDni = { idCliente: 0, nombres: '', apellidos: '', ruc: '' };
              setStatus('<span style="opacity:.5;font-size:11px;">Cliente nuevo</span>');
            }
            nvUpdateQuote();
          })
          .catch(() => setStatus(''));
      }, 350);
    });
  })();

  /* ── 3. Productos ── */
  function nvProductosDisponibles(excluirUid) {
    const usados = new Set(nvProdRows.filter(r => r.uid !== excluirUid).map(r => r.idComponente));
    return catalogo.filter(p => !usados.has(p.id));
  }

  function nvRenderProdList() {
    const cont = document.getElementById('nv-prod-list');
    cont.innerHTML = nvProdRows.map(row => {
      const opciones = nvProductosDisponibles(row.uid).map(p => {
        const sel = p.id === row.idComponente ? 'selected' : '';
        return `<option value="${p.id}" data-precio="${p.precio}" data-stock="${p.stock}" ${sel}>${escapeHtml(p.nombre)}</option>`;
      }).join('');

      const prod   = catalogo.find(p => p.id === row.idComponente);
      const precio = prod ? prod.precio * row.cantidad : 0;

      return `
        <div class="nv-prod-row" data-uid="${row.uid}">
          <select onchange="nvCambiarProducto(${row.uid}, this.value)">
            <option value="">Selecciona un producto…</option>
            ${opciones}
          </select>
          <div class="nv-qty-ctrl">
            <button type="button" class="nv-qty-btn" onclick="nvCambiarCantidad(${row.uid}, -1)">−</button>
            <span class="nv-qty-num">${row.cantidad}</span>
            <button type="button" class="nv-qty-btn" onclick="nvCambiarCantidad(${row.uid}, 1)">+</button>
          </div>
          <span class="nv-prod-precio">S/ ${fmt(precio)}</span>
          <button type="button" class="nv-prod-remove" onclick="nvQuitarProd(${row.uid})">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>`;
    }).join('');
  }

  window.nvAgregarProd = function() {
    nvProdRows.push({ uid: nvUidSeq++, idComponente: 0, cantidad: 1 });
    nvRenderProdList();
    nvUpdateQuote();
  };

  window.nvQuitarProd = function(uid) {
    nvProdRows = nvProdRows.filter(r => r.uid !== uid);
    nvRenderProdList();
    nvUpdateQuote();
  };

  window.nvCambiarProducto = function(uid, idComponenteStr) {
    const row = nvProdRows.find(r => r.uid === uid);
    if (!row) return;
    row.idComponente = Number(idComponenteStr) || 0;
    nvRenderProdList();
    nvUpdateQuote();
  };

  window.nvCambiarCantidad = function(uid, delta) {
    const row = nvProdRows.find(r => r.uid === uid);
    if (!row) return;
    const prod = catalogo.find(p => p.id === row.idComponente);
    const maxStock = prod ? prod.stock : 99;
    row.cantidad = Math.min(maxStock, Math.max(1, row.cantidad + delta));
    nvRenderProdList();
    nvUpdateQuote();
  };

  /* ── 4. Método de pago ── */
  window.nvSelMetodo = function(metodo, el) {
    nvMetodoPago = metodo;
    document.querySelectorAll('.nv-metodo-opt').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');
  };

  /* ── 5. Resumen / cotización en vivo ── */
  function nvGetProductItems() {
    return nvProdRows
      .map(row => {
        const prod = catalogo.find(p => p.id === row.idComponente);
        if (!prod) return null;
        return { nombre: prod.nombre, cantidad: row.cantidad, precio: prod.precio, total: prod.precio * row.cantidad };
      })
      .filter(Boolean);
  }

  window.nvUpdateQuote = function() {
    const items = [];

    if (nvTipoVenta === 'ticket' && nvTicketSel) {
      if (Array.isArray(nvTicketSel.servicios) && nvTicketSel.servicios.length) {
        nvTicketSel.servicios.forEach(s => {
          items.push({ nombre: s.nombre, cantidad: 1, precio: s.precio, total: s.precio });
        });
      } else {
        items.push({ nombre: nvTicketSel.servicio || 'Servicio técnico', cantidad: 1, precio: nvTicketSel.subtotal, total: nvTicketSel.subtotal });
      }
    }
    items.push(...nvGetProductItems());

    const empty   = document.getElementById('nv-q-empty');
    const details = document.getElementById('nv-q-details');
    const clientBox = document.getElementById('nv-q-client');
    const clientName = document.getElementById('nv-q-client-name');

    // Nombre del cliente en el resumen
    let nombreMostrar = '';
    if (nvTipoVenta === 'ticket' && nvTicketSel) {
      nombreMostrar = `${nvTicketSel.nombres} ${nvTicketSel.apellidos}`.trim();
    } else if (nvTipoVenta === 'producto') {
      const n = document.getElementById('nv-cli-nombres')?.value || '';
      const a = document.getElementById('nv-cli-apellidos')?.value || '';
      nombreMostrar = `${n} ${a}`.trim();
    }
    if (nombreMostrar) {
      clientName.textContent = nombreMostrar;
      clientBox.classList.remove('nv-hidden');
    } else {
      clientBox.classList.add('nv-hidden');
    }

    if (!items.length) {
      empty.classList.remove('nv-hidden');
      details.classList.add('nv-hidden');
      return;
    }

    empty.classList.add('nv-hidden');
    details.classList.remove('nv-hidden');

    const itemsHtml = items.map(it => `
      <div class="nv-quote-item">
        <span class="nv-quote-item__name">${escapeHtml(it.nombre)}${it.cantidad > 1 ? ` ×${it.cantidad}` : ''}</span>
        <span class="nv-quote-item__price">S/ ${fmt(it.total)}</span>
      </div>`).join('');
    document.getElementById('nv-q-items').innerHTML = itemsHtml;

    const subtotal = items.reduce((acc, it) => acc + it.total, 0);
    const igv      = subtotal * 0.18;
    const total     = subtotal + igv;

    document.getElementById('nv-q-subtotal').textContent = fmt(subtotal);
    document.getElementById('nv-q-igv').textContent      = fmt(igv);
    document.getElementById('nv-q-total').textContent    = fmt(total);
  };

  /* ── 6. Registrar venta ── */
  window.nvGuardarVenta = function() {
    const items = [];
    if (nvTipoVenta === 'ticket' && nvTicketSel) {
      items.push({ nombre: nvTicketSel.servicio || 'Servicio técnico', cantidad: 1, precio: nvTicketSel.subtotal, total: nvTicketSel.subtotal });
    }
    const productItems = nvGetProductItems();
    items.push(...productItems);

    if (nvTipoVenta === 'ticket' && !nvTicketSel) {
      alert('Selecciona un ticket para continuar.');
      return;
    }
    if (nvTipoVenta === 'producto') {
      const nombres   = document.getElementById('nv-cli-nombres').value.trim();
      const apellidos = document.getElementById('nv-cli-apellidos').value.trim();
      const dni       = document.getElementById('nv-cli-dni').value.trim();
      if (!nombres || !apellidos || dni.length !== 8) {
        alert('Completa nombres, apellidos y un DNI de 8 dígitos.');
        return;
      }
      if (!productItems.length) {
        alert('Añade al menos un producto a la venta.');
        return;
      }
    }

    const payload = {
      tipoVenta:  nvTipoVenta,
      metodoPago: nvMetodoPago,
      productos: nvProdRows
        .filter(r => r.idComponente > 0)
        .map(r => ({ idComponente: r.idComponente, cantidad: r.cantidad })),
    };

    if (nvTipoVenta === 'ticket') {
      payload.idTicket = nvTicketSel.idTicket;
    } else {
      payload.nombres   = document.getElementById('nv-cli-nombres').value.trim();
      payload.apellidos = document.getElementById('nv-cli-apellidos').value.trim();
      payload.dni       = document.getElementById('nv-cli-dni').value.trim();
      payload.ruc       = document.getElementById('nv-cli-ruc').value.trim();
    }

    const btn = document.getElementById('nv-btn-registrar');
    const textoOriginal = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'Registrando…';

    fetch('nueva_venta.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
    })
      .then(r => r.json())
      .then(data => {
        if (!data.success) {
          alert(data.message || 'No se pudo registrar la venta.');
          btn.disabled = false;
          btn.textContent = textoOriginal;
          return;
        }

        nvUltimaVenta = data;

        document.getElementById('nv-modal-amount').textContent = 'S/ ' + fmt(data.total);
        document.getElementById('nv-modal-method').textContent = data.metodoPago;
        document.getElementById('nv-modal-success').classList.add('show');
      })
      .catch(() => {
        alert('Ocurrió un error de conexión. Inténtalo de nuevo.');
        btn.disabled = false;
        btn.textContent = textoOriginal;
      });
  };

  /* ── 7. Boleta de venta en PDF ── */
  window.nvGenerarPDF = function() {
    if (!nvUltimaVenta) { alert('No hay datos de la venta.'); return; }
    const d = nvUltimaVenta;

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    const azulTecnologico = [23, 70, 234];
    const azulProfundo    = [0, 0, 25];

    const xDerecha = 195;
    const xPrecios = 190;
    const xLabel   = 130;
    const aRight   = { align: 'right' };

    let y = 20;

    // ===== HEADER =====
    doc.addImage('img/logo-horizontal-color.png', 'PNG', 15, y, 40, 15);

    doc.setFontSize(9);
    doc.setTextColor(...azulProfundo);
    doc.setFont('helvetica', 'bold');
    doc.text('MORALES TECH SOLUTIONS ADVANCED S.A.C.', xDerecha, y + 5, aRight);
    doc.setFont('helvetica', 'normal');
    doc.text('Urb. Jardines de Casablanca F-06, Ica', xDerecha, y + 10, aRight);
    doc.text('(51) 903-208-170', xDerecha, y + 15, aRight);
    doc.text('RUC: 20613424238', xDerecha, y + 20, aRight);

    y += 35;

    // ===== TITULO =====
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(18);
    doc.setTextColor(...azulTecnologico);
    doc.text('BOLETA DE VENTA', 15, y);
    y += 8;

    doc.setDrawColor(200);
    doc.line(15, y, 195, y);
    y += 8;

    // ===== N° VENTA / FECHA =====
    const hoy = new Date();
    const fecha = hoy.toLocaleDateString('es-PE', { year: 'numeric', month: 'long', day: 'numeric' });
    const numeroVenta = 'V-' + String(d.idVenta).padStart(6, '0');

    doc.setTextColor(...azulProfundo);
    doc.setFontSize(10);
    doc.setFont('helvetica', 'bold');
    doc.text('N° BOLETA', 15, y);
    doc.text('FECHA', xDerecha, y, aRight);
    y += 5;
    doc.setFont('helvetica', 'normal');
    doc.text(numeroVenta, 15, y);
    doc.text(fecha, xDerecha, y, aRight);
    y += 12;

    // ===== CLIENTE / MÉTODO DE PAGO =====
    doc.setFont('helvetica', 'bold');
    doc.text('CLIENTE', 15, y);
    doc.text('MÉTODO DE PAGO', xDerecha, y, aRight);
    y += 6;

    doc.setFont('helvetica', 'normal');
    if (d.cliente) doc.text(d.cliente, 15, y);
    if (d.metodoPago) doc.text(d.metodoPago, xDerecha, y, aRight);
    y += 6;

    if (d.dni) { doc.text('DNI: ' + d.dni, 15, y); y += 6; }
    if (d.ruc) { doc.text('RUC: ' + d.ruc, 15, y); y += 6; }

    y += 4;
    doc.line(15, y, 195, y);
    y += 10;

    // ===== ITEMS =====
    doc.setFont('helvetica', 'bold');
    doc.text('DESCRIPCIÓN', 15, y);
    y += 6;

    doc.setFillColor(...azulTecnologico);
    doc.roundedRect(15, y, 180, 8, 4, 4, 'F');
    doc.setTextColor(255, 255, 255);
    doc.text('ITEM', 20, y + 5.5);
    doc.text('MONTO', xPrecios, y + 5.5, aRight);
    y += 12;

    doc.setTextColor(...azulProfundo);
    doc.setFont('helvetica', 'normal');
    const items = Array.isArray(d.items) ? d.items : [];
    items.forEach(it => {
      const cantidad = Number(it.cantidad) || 1;
      const precio   = Number(it.precio) || 0;
      const totalLinea = precio * cantidad;
      const etiqueta = cantidad > 1 ? `${it.nombre} ×${cantidad}` : it.nombre;
      doc.text(etiqueta, 20, y);
      doc.text(`S/ ${totalLinea.toFixed(2)}`, xPrecios, y, aRight);
      y += 7;
    });
    y += 5;

    // ===== TOTALES =====
    doc.setFontSize(10);
    doc.setFont('helvetica', 'normal');
    doc.text('Subtotal', xLabel, y);
    doc.text(`S/ ${Number(d.subtotal).toFixed(2)}`, xPrecios, y, aRight);
    y += 6;

    doc.text('IGV (18%)', xLabel, y);
    doc.text(`S/ ${Number(d.igv).toFixed(2)}`, xPrecios, y, aRight);
    y += 8;

    doc.setDrawColor(200);
    doc.line(xLabel, y, 195, y);
    y += 7;

    doc.setFont('helvetica', 'bold');
    doc.setFontSize(13);
    doc.setTextColor(...azulProfundo);
    doc.text('TOTAL:', xLabel, y);
    doc.setTextColor(...azulTecnologico);
    doc.text(`S/ ${Number(d.total).toFixed(2)}`, xPrecios, y, aRight);

    // ===== FOOTER =====
    y = 270;
    doc.setTextColor(...azulProfundo);
    doc.setFontSize(9);
    doc.setFont('helvetica', 'normal');
    doc.text('Gracias por confiar en Morales Tech Solutions Advanced.', 15, y);

    doc.save(`Boleta_${numeroVenta}.pdf`);
  };

  /* ── Inicialización ── */
  window.nvUpdateQuote();
})();
