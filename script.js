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

  /* Datos de demo */
  const TICKETS = {
    'MT-8842': {
      device: 'Laptop HP Pavilion',
      service: 'Mantenimiento correctivo',
      status: 1,
      statusLabel: 'Recibido',
      statusClass: 'recibido',
      updateText: 'Tu equipo acaba de ser recibido y registrado en nuestro sistema. Un técnico ha sido asignado y comenzará el diagnóstico en breve.',
      eta: 'Inicio de diagnóstico: dentro de 2–4 horas',
      tecnico: 'Ángel Morales',
      fecha: '20/05/2026',
    },
    'MT-8843': {
      device: 'PC Escritorio',
      service: 'Repotenciación',
      status: 2,
      statusLabel: 'En diagnóstico',
      statusClass: 'diagnostico',
      updateText: 'Tu equipo está siendo analizado por nuestro técnico. Estamos identificando los componentes a mejorar y calculando el alcance del servicio de repotenciación.',
      eta: 'Diagnóstico estimado: 4–6 horas',
      tecnico: 'Ángel Morales',
      fecha: '19/05/2026',
    },
    'MT-8844': {
      device: 'Laptop Apple MacBook',
      service: 'Diagnóstico técnico',
      status: 3,
      statusLabel: 'En reparación',
      statusClass: 'reparacion',
      updateText: 'Tu equipo completó el diagnóstico satisfactoriamente. Actualmente se encuentra en el área de reparación con nuestro técnico asignado, quien está trabajando en el reemplazo de componentes identificados.',
      eta: 'Tiempo estimado: 24–48 horas',
      tecnico: 'Ángel Morales',
      fecha: '18/05/2026',
    },
  };

  const PROGRESS = { 1: 12, 2: 40, 3: 72, 4: 100 };

  function normalize(raw) {
    return raw.trim().toUpperCase().replace(/^MT-?/i, 'MT-');
  }

  function renderTicket(key) {
    const data = TICKETS[key];
    if (!data) return false;

    document.getElementById('rcTicketId').textContent  = 'Ticket #' + key;
    document.getElementById('rcDevice').textContent    = data.device + ' · ' + data.service;

    const badge = document.getElementById('rcStatusBadge');
    badge.textContent = data.statusLabel;
    badge.className   = 'status-badge ' + data.statusClass;

    setTimeout(() => {
      const fill = document.getElementById('rcBarFill');
      if (fill) fill.style.width = PROGRESS[data.status] + '%';
    }, 100);

    for (let i = 1; i <= 4; i++) {
      const el = document.getElementById('step' + i);
      if (!el) continue;
      el.className = 'rcp-step';
      if (i < data.status)  el.classList.add('done');
      if (i === data.status) el.classList.add('active');
    }

    document.getElementById('rcUpdateText').textContent = data.updateText;
    const etaEl = document.getElementById('rcEta');
    if (etaEl) {
      etaEl.innerHTML =
        `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:12px;height:12px"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>${data.eta}`;
    }

    document.getElementById('rcdFecha').textContent    = data.fecha;
    document.getElementById('rcdServicio').textContent = data.service;
    document.getElementById('rcdEstado').textContent   = data.statusLabel;
    return true;
  }

  function doSearch() {
    const raw   = ticketInput.value;
    const key   = normalize(raw);
    const empty = document.getElementById('resultEmpty');
    const card  = document.getElementById('resultCard');
    const fill  = document.getElementById('rcBarFill');

    if (fill) fill.style.width = '0%';

    if (renderTicket(key)) {
      empty.style.display = 'none';
      card.classList.add('visible');
      card.scrollIntoView({ behavior: 'smooth', block: 'start' });
    } else {
      card.classList.remove('visible');
      empty.style.display = '';
      const title = empty.querySelector('.result-empty-title');
      const text  = empty.querySelector('.result-empty-text');
      if (title) title.textContent = 'Ticket no encontrado';
      if (text)  text.innerHTML =
        `No encontramos un ticket con el código <strong style="color:var(--txt-main)">${raw || '—'}</strong>. Verifica el código e inténtalo de nuevo, o <a href="https://wa.me/51903208170" target="_blank" style="color:#8db4ff;font-weight:600">contáctanos por WhatsApp</a>.`;
    }
  }

  btnConsultar.addEventListener('click', doSearch);
  ticketInput.addEventListener('keydown', e => { if (e.key === 'Enter') doSearch(); });

  document.querySelectorAll('.qt-chip').forEach(chip => {
    chip.addEventListener('click', () => {
      ticketInput.value = chip.dataset.ticket;
      doSearch();
    });
  });
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

  window.abrirModal = function(id, equipo, so, servicio, adicionales, estado, fecha, total, obs) {
    document.getElementById('m-id').textContent     = '#' + id;
    document.getElementById('m-equipo').textContent = equipo;
    document.getElementById('m-estado').textContent = estado;
    document.getElementById('m-tipo').textContent   = equipo;
    document.getElementById('m-so').textContent     = so || '—';
    document.getElementById('m-fecha').textContent  = fecha;
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

/* ── Wizard (nuevo_ticket_cliente) ── */
(function initWizard() {
  if (!document.getElementById('step-1')) return;

  let currentStep = 1;
  const TOTAL     = 3;
  const progress  = [33, 66, 100];

  window.goStep = function(n) {
    if (n > currentStep + 1 || n < 1) return;
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

  function validateStep(n) {
    if (n === 1 && !document.getElementById('tipo_dispositivo').value) {
      alert('Por favor selecciona el tipo de dispositivo.');
      return false;
    }
    if (n === 2 && !document.querySelector('input[name="srv_base"]:checked')) {
      alert('Por favor selecciona al menos un servicio principal.');
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

  /* Enviar solicitud */
  window.enviarSolicitud = function() {
    const modal = document.getElementById('modal-success');
    if (modal) {
      modal.style.opacity      = '1';
      modal.style.pointerEvents = 'all';
      modal.classList.add('show');
    }
  };
})();

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
  };
})();

/* ══════════════════════════════════════════
   NUEVO_TICKET.PHP — wizard staff (4 pasos)
   ══════════════════════════════════════════ */

(function initNuevoTicket() {
  if (!document.getElementById('step-1')) return;

  let currentStep = 1;
  const TOTAL    = 4;
  const PROGRESS = [25, 50, 75, 100];

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
      const dni = document.getElementById('ntk-dni')?.value.trim() || '';
      const nom = document.getElementById('ntk-nombre-cliente')?.value.trim() || '';
      const tel = document.getElementById('ntk-telefono')?.value.trim() || '';
      if (!dni || dni.length !== 8) { alert('El DNI debe tener 8 dígitos.'); return false; }
      if (!nom)                      { alert('Ingresa el nombre del cliente.'); return false; }
      if (!tel || tel.length !== 9)  { alert('El teléfono debe tener 9 dígitos.'); return false; }
    }
    if (n === 2 && !document.getElementById('ntk-tipo-dispositivo')?.value) {
      alert('Selecciona el tipo de dispositivo.'); return false;
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

  /* ── Resumen paso 4 ── */
  function ntkBuildSummary() {
    const nombre = document.getElementById('ntk-nombre-cliente')?.value || '—';
    const dni    = document.getElementById('ntk-dni')?.value             || '—';
    const tel    = document.getElementById('ntk-telefono')?.value        || '—';
    const correo = document.getElementById('ntk-correo')?.value          || '';
    const tipo   = document.getElementById('ntk-tipo-dispositivo')?.value|| '—';
    const marca  = document.getElementById('ntk-marca')?.value           || '';
    const obs    = document.getElementById('ntk-observaciones')?.value   || '';

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
    const modal = document.getElementById('ntk-modal-success');
    if (modal) { modal.style.opacity = '1'; modal.style.pointerEvents = 'all'; modal.classList.add('show'); }
  };

  /* ── Solo números en campos del wizard ── */
  ['ntk-dni', 'ntk-ruc', 'ntk-telefono'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('input', function() { this.value = this.value.replace(/\D/g, ''); });
  });
})();

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
    btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:13px;height:13px"><polyline points="20 6 9 17 4 12"/></svg> Guardado';
    btn.style.background = 'linear-gradient(135deg,#1a7a4a,#2ecc71)';
    setTimeout(() => { btn.innerHTML = orig; btn.style.background = ''; }, 1800);
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
    ['inv-m-nombre', 'inv-m-precio', 'inv-m-stock'].forEach(id => {
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
    const nombre = document.getElementById('inv-m-nombre')?.value.trim()   || '';
    const cat    = document.getElementById('inv-m-categoria')?.value        || '';
    const precio = parseFloat(document.getElementById('inv-m-precio')?.value);
    const stock  = parseInt(document.getElementById('inv-m-stock')?.value);

    if (!nombre)                  { alert('Ingresa el nombre del producto.'); return; }
    if (!cat)                     { alert('Selecciona una categoría.'); return; }
    if (isNaN(precio)||precio<=0) { alert('Ingresa un precio válido.'); return; }
    if (isNaN(stock) ||stock < 0) { alert('Ingresa un stock válido.'); return; }

    const nuevoId   = 'new-' + Date.now();
    const catClass  = INV_CAT_CLASS[cat]  || '';
    const stClass   = stock >= 20 ? 'inv-stock--ok' : stock >= 10 ? 'inv-stock--low' : 'inv-stock--min';
    const icono     = INV_ICONOS[cat]     || '';

    const tr = document.createElement('tr');
    tr.dataset.cat    = cat;
    tr.dataset.nombre = nombre.toLowerCase();
    tr.innerHTML = `
      <td><div class="inv-prod-cell">
        <div class="inv-prod-icon">${icono}</div>
        <div>
          <div class="inv-prod-name">${nombre}</div>
          <div class="inv-prod-id">Nuevo</div>
        </div>
      </div></td>
      <td><span class="inv-cat-badge ${catClass}">${cat}</span></td>
      <td><span class="inv-precio">S/ ${precio.toFixed(2)}</span></td>
      <td><span class="inv-stock-badge ${stClass}" id="inv-stock-label-${nuevoId}">${stock} uds.</span></td>
      <td><div class="inv-qty-control">
        <button class="inv-qty-btn" onclick="invCambiarQty('${nuevoId}',-1)">−</button>
        <span class="inv-qty-num" id="inv-qty-${nuevoId}">${stock}</span>
        <button class="inv-qty-btn" onclick="invCambiarQty('${nuevoId}',1)">+</button>
      </div></td>
      <td><button class="inv-btn-update" onclick="invGuardarCambio('${nuevoId}', event)">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        Actualizar
      </button></td>`;

    document.getElementById('inv-tbody').appendChild(tr);
    invCerrarModal();
    paginaActual = 1;
    invAplicarFiltros();
  };
})();

/* ══════════════════════════════════════════
   VENTAS.PHP — gráfico de barras y filtros
   ══════════════════════════════════════════ */

(function initVentas() {
  if (!document.getElementById('vt-bars-area')) return;

  const MONTHS_ES = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];

  const DATA_MES = [
    {label:'Ene',year:2025,val:18400},{label:'Feb',year:2025,val:22100},
    {label:'Mar',year:2025,val:15800},{label:'Abr',year:2025,val:30200},
    {label:'May',year:2025,val:27500},{label:'Jun',year:2025,val:19300},
    {label:'Jul',year:2025,val:24100},{label:'Ago',year:2025,val:31000},
    {label:'Sep',year:2025,val:20700},{label:'Oct',year:2025,val:28400},
    {label:'Nov',year:2025,val:23900},{label:'Dic',year:2025,val:35200},
  ];
  const DATA_QUINCENA = DATA_MES.flatMap((m, i) => [
    {label: MONTHS_ES[i]+' 1ª', year: m.year, val: Math.round(m.val * 0.45)},
    {label: MONTHS_ES[i]+' 2ª', year: m.year, val: Math.round(m.val * 0.55)},
  ]);
  const DATA_ANIO = [
    {label:'2022',year:2022,val:185000},{label:'2023',year:2023,val:243000},
    {label:'2024',year:2024,val:310000},{label:'2025',year:2025,val:98000},
  ];

  const TODAY_IDX = 4; // Mayo
  const VISIBLE   = 8;
  const CHART_H   = 200;

  let vtPeriod     = 'mes';
  let vtOffset     = 0;
  let vtDataset    = DATA_MES;
  let vtCurrentIdx = TODAY_IDX;

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
    if (p === 'mes')           { vtDataset = DATA_MES;       vtCurrentIdx = TODAY_IDX; }
    else if (p === 'quincena') { vtDataset = DATA_QUINCENA;  vtCurrentIdx = TODAY_IDX * 2 + 1; }
    else                       { vtDataset = DATA_ANIO;      vtCurrentIdx = DATA_ANIO.length - 1; }
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

/* ══════════════════════════════════════════
   NUEVA_VENTA.PHP — formulario de venta
   ══════════════════════════════════════════ */

(function initNuevaVenta() {
  if (!document.getElementById('nv-bloque-ticket')) return;

  let nvTipoVenta   = 'ticket';
  let nvMetodo      = 'Yape';
  let nvTicketData  = null;
  let nvProdCounter = 0;

  /* ── Selector tipo ── */
  window.nvSelTipo = function(tipo, el) {
    nvTipoVenta = tipo;
    document.querySelectorAll('.nv-vtype-opt').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');
    el.blur();

    const bT = document.getElementById('nv-bloque-ticket');
    const bC = document.getElementById('nv-bloque-cliente');
    const pTitle = document.getElementById('nv-prod-card-title');
    const pSub   = document.getElementById('nv-prod-card-sub');

    if (tipo === 'ticket') {
      bT.classList.remove('nv-hidden');
      bC.classList.add('nv-hidden');
      if (pTitle) pTitle.textContent = 'Productos adicionales';
      if (pSub)   pSub.textContent   = 'Añade repuestos o materiales usados en el servicio';
    } else {
      bT.classList.add('nv-hidden');
      bC.classList.remove('nv-hidden');
      if (pTitle) pTitle.textContent = 'Productos';
      if (pSub)   pSub.textContent   = 'Selecciona los productos que desea el cliente';
      nvTicketData = null;
      const info = document.getElementById('nv-ticket-info');
      if (info) info.classList.remove('visible');
      const sel = document.getElementById('nv-sel-ticket');
      if (sel) sel.selectedIndex = 0;
    }
    nvUpdateQuote();
  };

  /* ── Cambio de ticket ── */
  window.nvOnTicketChange = function() {
    const sel  = document.getElementById('nv-sel-ticket');
    const opt  = sel?.options[sel.selectedIndex];
    const info = document.getElementById('nv-ticket-info');
    if (!sel || !sel.value) {
      if (info) info.classList.remove('visible');
      nvTicketData = null; nvUpdateQuote(); return;
    }
    nvTicketData = {
      cliente:  opt.dataset.cliente,
      servicio: opt.dataset.servicio,
      subtotal: parseFloat(opt.dataset.subtotal),
    };
    const tText  = document.getElementById('nv-ticket-info-text');
    const tPrice = document.getElementById('nv-ticket-info-price');
    if (tText)  tText.textContent  = nvTicketData.cliente + ' · ' + nvTicketData.servicio;
    if (tPrice) tPrice.textContent = 'S/ ' + nvTicketData.subtotal.toFixed(2);
    if (info)   info.classList.add('visible');
    nvUpdateQuote();
  };

  /* ── Productos ── */
  window.nvAgregarProd = function() {
    const PRODS = typeof NV_PRODUCTOS !== 'undefined' ? NV_PRODUCTOS : [];
    const list  = document.getElementById('nv-prod-list');
    if (!list) return;
    const id   = ++nvProdCounter;
    const opts = PRODS.map(p => `<option value="${p.id}" data-precio="${p.precio}">${p.nombre}</option>`).join('');
    const row  = document.createElement('div');
    row.className = 'nv-prod-row';
    row.id = 'nv-prow-' + id;
    row.innerHTML = `
      <select id="nv-psel-${id}" onchange="nvOnProdChange(${id})">
        <option value="">Seleccionar producto…</option>${opts}
      </select>
      <div class="nv-qty-ctrl">
        <button class="nv-qty-btn" onclick="nvCambiarQty(${id},-1)">−</button>
        <span class="nv-qty-num" id="nv-pqty-${id}">1</span>
        <button class="nv-qty-btn" onclick="nvCambiarQty(${id},1)">+</button>
      </div>
      <span class="nv-prod-precio" id="nv-pprecio-${id}">S/ —</span>
      <button class="nv-prod-remove" onclick="nvEliminarProd(${id})">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>`;
    list.appendChild(row);
  };

  window.nvOnProdChange = function(id) {
    const PRODS = typeof NV_PRODUCTOS !== 'undefined' ? NV_PRODUCTOS : [];
    const sel   = document.getElementById('nv-psel-' + id);
    const precio = sel?.options[sel.selectedIndex]?.dataset?.precio;
    const qty    = parseInt(document.getElementById('nv-pqty-' + id)?.textContent || 1);
    const etiq   = document.getElementById('nv-pprecio-' + id);
    if (etiq) etiq.textContent = precio ? 'S/ ' + (parseFloat(precio) * qty).toFixed(2) : 'S/ —';
    nvUpdateQuote();
  };

  window.nvCambiarQty = function(id, delta) {
    const el = document.getElementById('nv-pqty-' + id);
    if (!el) return;
    el.textContent = Math.max(1, parseInt(el.textContent) + delta);
    nvOnProdChange(id);
  };

  window.nvEliminarProd = function(id) {
    document.getElementById('nv-prow-' + id)?.remove();
    nvUpdateQuote();
  };

  /* ── Método de pago ── */
  window.nvSelMetodo = function(m, el) {
    nvMetodo = m;
    document.querySelectorAll('.nv-metodo-opt').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');
    el.blur();
  };

  /* ── Actualizar resumen ── */
  window.nvUpdateQuote = function() {
    const PRODS  = typeof NV_PRODUCTOS !== 'undefined' ? NV_PRODUCTOS : [];
    const items  = [];

    if (nvTipoVenta === 'ticket' && nvTicketData)
      items.push({ name: nvTicketData.servicio, price: nvTicketData.subtotal });

    document.querySelectorAll('[id^="nv-psel-"]').forEach(sel => {
      if (!sel.value) return;
      const prod = PRODS.find(p => p.id == sel.value);
      if (!prod) return;
      const qty = parseInt(document.getElementById(sel.id.replace('nv-psel-','nv-pqty-'))?.textContent || 1);
      items.push({ name: prod.nombre + (qty > 1 ? ` ×${qty}` : ''), price: prod.precio * qty });
    });

    const clientName = nvTipoVenta === 'ticket' && nvTicketData
      ? nvTicketData.cliente
      : (document.getElementById('nv-cli-nombre')?.value?.trim() || '');

    const qClient = document.getElementById('nv-q-client');
    if (qClient) {
      if (clientName) { document.getElementById('nv-q-client-name').textContent = clientName; qClient.classList.remove('nv-hidden'); }
      else qClient.classList.add('nv-hidden');
    }

    const qEmpty   = document.getElementById('nv-q-empty');
    const qDetails = document.getElementById('nv-q-details');
    if (items.length === 0) {
      if (qEmpty)   qEmpty.classList.remove('nv-hidden');
      if (qDetails) qDetails.classList.add('nv-hidden');
      return;
    }
    if (qEmpty)   qEmpty.classList.add('nv-hidden');
    if (qDetails) qDetails.classList.remove('nv-hidden');

    const itemsEl = document.getElementById('nv-q-items');
    if (itemsEl) itemsEl.innerHTML = items.map(i =>
      `<div class="nv-quote-item">
         <span class="nv-quote-item__name">${i.name}</span>
         <span class="nv-quote-item__price">S/ ${i.price.toFixed(2)}</span>
       </div>`
    ).join('');

    const subtotal = items.reduce((a, i) => a + i.price, 0);
    const igv      = subtotal * 0.18;
    const total    = subtotal + igv;

    const sub  = document.getElementById('nv-q-subtotal');
    const igvEl = document.getElementById('nv-q-igv');
    const totEl = document.getElementById('nv-q-total');
    if (sub)   sub.textContent  = subtotal.toFixed(2);
    if (igvEl) igvEl.textContent = igv.toFixed(2);
    if (totEl) totEl.textContent = total.toFixed(2);
  };

  /* ── Guardar ── */
  window.nvGuardarVenta = function() {
    if (nvTipoVenta === 'ticket') {
      if (!nvTicketData) { alert('Selecciona un ticket.'); return; }
    } else {
      const dni    = document.getElementById('nv-cli-dni')?.value.trim()    || '';
      const nombre = document.getElementById('nv-cli-nombre')?.value.trim() || '';
      if (!dni || dni.length !== 8) { alert('El DNI debe tener 8 dígitos.'); return; }
      if (!nombre) { alert('Ingresa el nombre del cliente.'); return; }
    }
    const totEl = document.getElementById('nv-q-total');
    const total = parseFloat(totEl?.textContent || '0');
    if (!total || total <= 0) { alert('Agrega al menos un producto o servicio.'); return; }

    const amountEl = document.getElementById('nv-modal-amount');
    const methodEl = document.getElementById('nv-modal-method');
    if (amountEl) amountEl.textContent = 'S/ ' + total.toFixed(2);
    if (methodEl) methodEl.textContent = nvMetodo;

    const modal = document.getElementById('nv-modal-success');
    if (modal) { modal.style.opacity = '1'; modal.style.pointerEvents = 'all'; modal.classList.add('show'); }
  };

  /* Escuchar cambios en nombre cliente directo */
  const cliNombre = document.getElementById('nv-cli-nombre');
  if (cliNombre) cliNombre.addEventListener('input', nvUpdateQuote);
})();
